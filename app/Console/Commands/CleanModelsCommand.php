<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CleanModelsCommand extends Command
{
    protected $signature = 'clean:models {--marca= : Filtrar por marca específica}';
    protected $description = 'Separa modelos compuestos por comas y fusiona modelos duplicados exactos en todas las marcas';

    public function handle()
    {
        $marcaName = $this->option('marca');
        
        $query = CarModel::with(['make']);
        
        if ($marcaName) {
            $query->whereHas('make', function($q) use ($marcaName) {
                $q->where('name', 'LIKE', "%{$marcaName}%");
            });
        }
        
        $models = $query->get();
        $this->info("Analizando {$models->count()} modelos de autos...");

        $stats = [
            'separados' => 0,
            'fusionados' => 0,
            'motores_movidos' => 0,
            'productos_reasignados' => 0
        ];

        DB::beginTransaction();
        try {
            // PASO 1: SEPARAR MODELOS COMPUESTOS (ej. "ATLAS,CONDOR")
            $this->info("Paso 1: Separando modelos compuestos...");
            foreach ($models as $model) {
                $name = $model->name ?? '';
                if (strpos($name, ',') !== false || strpos($name, '/') !== false) {
                    // Ignoramos casos que son claramente un solo modelo con barra, si los hay, 
                    // pero en catálogos asiáticos suele ser agrupación (ej. 90/120)
                    $parts = preg_split('/[, \/]+/', $name, -1, PREG_SPLIT_NO_EMPTY);
                    if (count($parts) > 1 && strlen($parts[0]) > 1) {
                        // Reconstruir el nombre (algunas partes pueden ser palabras del mismo modelo,
                        // la heurística de coma es la más segura)
                        $partsByComma = explode(',', $name);
                        if (count($partsByComma) > 1) {
                            $this->line("Separando modelo: {$name}");
                            
                            $firstModelName = trim($partsByComma[0]);
                            $model->name = $firstModelName;
                            $model->save();
                            
                            for ($i = 1; $i < count($partsByComma); $i++) {
                                $newModelName = trim($partsByComma[$i]);
                                if (empty($newModelName)) continue;
                                
                                $newModel = $model->replicate();
                                $newModel->name = $newModelName;
                                $newModel->save();
                                
                                // Duplicar los motores para este nuevo modelo
                                $engines = Engine::where('car_model_id', $model->id)->get();
                                foreach ($engines as $eng) {
                                    $newEng = $eng->replicate();
                                    $newEng->car_model_id = $newModel->id;
                                    $newEng->save();
                                }
                                
                                // Reasignar productos
                                $products = Product::all(); // Forma robusta
                                foreach ($products as $p) {
                                    $mIds = $p->compatible_model_ids;
                                    if (is_string($mIds)) $mIds = json_decode($mIds, true);
                                    if (is_array($mIds) && in_array($model->id, array_map('intval', $mIds))) {
                                        $mIds[] = $newModel->id;
                                        $p->compatible_model_ids = array_values(array_unique(array_map('intval', $mIds)));
                                        $p->save();
                                    }
                                }
                            }
                            $stats['separados']++;
                        }
                    }
                }
            }

            // PASO 2: LIMPIEZA DE NOMBRES Y FUSIÓN DE DUPLICADOS EXACTOS
            $this->info("Paso 2: Fusionando duplicados locales en todas las marcas...");
            
            // Recargar modelos
            $models = $query->get();
            $grouped = [];
            
            foreach ($models as $m) {
                // Normalizar: quitar espacios dobles, convertir a mayúsculas
                $cleanName = mb_strtoupper(trim(preg_replace('/\s+/', ' ', $m->name)));
                
                // Guardar el nombre limpio si cambió
                if ($m->name !== $cleanName) {
                    $m->name = $cleanName;
                    $m->save();
                }
                
                $key = $m->make_id . '_' . $cleanName;
                $grouped[$key][] = $m;
            }

            foreach ($grouped as $key => $modelList) {
                if (count($modelList) > 1) {
                    $master = $modelList[0];
                    $this->line("Fusionando duplicados de: {$master->name}");
                    
                    for ($i = 1; $i < count($modelList); $i++) {
                        $duplicate = $modelList[$i];
                        
                        // Mover motores del duplicado al master
                        $moved = Engine::where('car_model_id', $duplicate->id)
                                       ->update(['car_model_id' => $master->id]);
                        $stats['motores_movidos'] += $moved;
                        
                        // Reasignar productos
                        $products = Product::all();
                        $prodCount = 0;
                        foreach ($products as $p) {
                            $mIds = $p->compatible_model_ids;
                            if (is_string($mIds)) $mIds = json_decode($mIds, true);
                            if (is_array($mIds)) {
                                $mIds = array_map('intval', $mIds);
                                if (in_array($duplicate->id, $mIds)) {
                                    $mIds = array_diff($mIds, [$duplicate->id]);
                                    if (!in_array($master->id, $mIds)) {
                                        $mIds[] = $master->id;
                                    }
                                    $p->compatible_model_ids = array_values(array_unique($mIds));
                                    $p->save();
                                    $prodCount++;
                                }
                            }
                        }
                        
                        $stats['productos_reasignados'] += $prodCount;
                        $duplicate->delete();
                        $stats['fusionados']++;
                    }
                }
            }

            DB::commit();
            $this->info("¡Proceso completado para Modelos!");
            $this->info("Modelos separados (nombres compuestos): {$stats['separados']}");
            $this->info("Duplicados fusionados y eliminados: {$stats['fusionados']}");
            $this->info("Motores reasignados: {$stats['motores_movidos']}");
            $this->info("Referencias en productos actualizadas: {$stats['productos_reasignados']}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("ERROR: " . $e->getMessage());
        }
    }
}
