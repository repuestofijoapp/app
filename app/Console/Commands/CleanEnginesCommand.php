<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Engine;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CleanEnginesCommand extends Command
{
    protected $signature = 'clean:engines {--marca= : Filtrar por marca específica}';
    protected $description = 'Limpia nombres compuestos y corrige tipos de combustible mediante heurísticas locales';

    public function handle()
    {
        $marcaName = $this->option('marca');
        
        $query = Engine::with(['carModel.make']);
        
        if ($marcaName) {
            $query->whereHas('carModel.make', function($q) use ($marcaName) {
                $q->where('name', 'LIKE', "%{$marcaName}%");
            });
        }
        
        $engines = $query->get();
        $this->info("Analizando {$engines->count()} motores...");

        $stats = [
            'separados' => 0,
            'combustible_corregido' => 0,
            'fusionados' => 0
        ];

        DB::beginTransaction();
        try {
            // PASO 1: SEPARAR MOTORES COMPUESTOS (ej. "2TZ-FE, 2TZ-FZE")
            $this->info("Paso 1: Separando motores compuestos...");
            foreach ($engines as $engine) {
                $code = $engine->engine_code ?? $engine->name ?? '';
                if (strpos($code, ',') !== false || strpos($code, '/') !== false) {
                    $parts = preg_split('/[, \/]+/', $code, -1, PREG_SPLIT_NO_EMPTY);
                    if (count($parts) > 1) {
                        $this->line("Separando: {$code}");
                        
                        // El motor actual toma el primer nombre
                        $engine->engine_code = $parts[0];
                        $engine->save();
                        
                        // Crear los demás motores y clonar sus relaciones
                        for ($i = 1; $i < count($parts); $i++) {
                            $newEngine = $engine->replicate();
                            $newEngine->engine_code = $parts[$i];
                            $newEngine->save();
                            
                            // Reasignar productos que apuntaban al motor original para que apunten TAMBIÉN al nuevo
                            $products = Product::whereJsonContains('compatible_engine_ids', $engine->id)->get();
                            foreach ($products as $p) {
                                $eIds = $p->compatible_engine_ids;
                                if (is_string($eIds)) $eIds = json_decode($eIds, true);
                                if (!in_array($newEngine->id, $eIds)) {
                                    $eIds[] = $newEngine->id;
                                    $p->compatible_engine_ids = array_values(array_unique($eIds));
                                    $p->save();
                                }
                            }
                        }
                        $stats['separados']++;
                    }
                }
            }
            
            // Recargar motores después de separar
            $engines = $query->get();

            // PASO 2: CORREGIR TIPOS DE COMBUSTIBLE CON HEURÍSTICAS
            $this->info("Paso 2: Corrigiendo tipos de combustible...");
            foreach ($engines as $engine) {
                $code = mb_strtoupper($engine->engine_code ?? $engine->name ?? '');
                $marca = mb_strtoupper($engine->carModel->make->name ?? '');
                $currentFuel = mb_strtoupper($engine->fuel_type ?? '');
                
                $expectedFuel = $this->guessFuelType($code, $marca);
                
                if ($expectedFuel && $currentFuel !== $expectedFuel) {
                    $this->line("Corrigiendo Combustible [{$marca}] {$code}: {$currentFuel} -> {$expectedFuel}");
                    $engine->fuel_type = $expectedFuel;
                    $engine->save();
                    $stats['combustible_corregido']++;
                }
            }

            // PASO 3: FUSIONAR DUPLICADOS DENTRO DEL MISMO MODELO
            // (Ya que al limpiar y separar pueden quedar duplicados exactos)
            $this->info("Paso 3: Fusionando duplicados locales...");
            $engines = $query->get();
            $grouped = [];
            foreach ($engines as $e) {
                $norm = mb_strtoupper(preg_replace('/[\s\-]+/', '', $e->engine_code ?? $e->name ?? ''));
                $key = $e->car_model_id . '_' . $norm . '_' . $e->displacement . '_' . $e->fuel_type;
                $grouped[$key][] = $e;
            }

            foreach ($grouped as $key => $engineList) {
                if (count($engineList) > 1) {
                    $master = $engineList[0];
                    for ($i = 1; $i < count($engineList); $i++) {
                        $duplicate = $engineList[$i];
                        
                        // Reasignar productos
                        $products = Product::whereJsonContains('compatible_engine_ids', $duplicate->id)->get();
                        foreach ($products as $p) {
                            $eIds = $p->compatible_engine_ids;
                            if (is_string($eIds)) $eIds = json_decode($eIds, true);
                            $eIds = array_diff($eIds, [$duplicate->id]);
                            if (!in_array($master->id, $eIds)) $eIds[] = $master->id;
                            $p->compatible_engine_ids = array_values(array_unique($eIds));
                            $p->save();
                        }
                        
                        $duplicate->delete();
                        $stats['fusionados']++;
                    }
                }
            }

            DB::commit();
            $this->info("¡Proceso completado!");
            $this->info("Motores separados (nombres compuestos): {$stats['separados']}");
            $this->info("Combustibles corregidos: {$stats['combustible_corregido']}");
            $this->info("Duplicados fusionados: {$stats['fusionados']}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("ERROR: " . $e->getMessage());
        }
    }

    private function guessFuelType($code, $marca)
    {
        $code = preg_replace('/[\s\-]+/', '', $code);
        
        if ($marca === 'TOYOTA') {
            // TOYOTA DIESEL: C (1C,2C,3C), L (2L,3L,5L), B (11B,14B), KZ, KD, GD, VD, ND, AD, W, DZ
            if (preg_match('/^(1C|2C|3C|L|2L|3L|5L|B|11B|13B|14B|15B|1KZ|1KD|2KD|1GD|2GD|1VD|1ND|1AD|2AD|1W|1DZ|N04C|S05D|J|2J)/', $code)) {
                return 'DIESEL';
            }
            return 'GASOLINA'; // Por defecto, si no es diesel es gasolina (o híbrido, pero en repuestos gasolina)
        }
        
        if ($marca === 'NISSAN') {
            // NISSAN DIESEL: CD, TD, YD, ZD, QD, RD, SD, LD, BD, FD
            if (preg_match('/(CD|TD|YD|ZD|QD|RD|SD|LD|BD|FD)[0-9]+/', $code) || preg_match('/^(CD|TD|YD|ZD|QD|RD|SD|LD|BD|FD)/', $code)) {
                return 'DIESEL';
            }
            return 'GASOLINA';
        }
        
        if ($marca === 'MITSUBISHI') {
            // MITSUBISHI DIESEL: 4D5, 4M4, 4N1, S4, 6D
            if (preg_match('/^(4D5|4M4|4N1|S4|6D|4D3|4DR)/', $code)) {
                return 'DIESEL';
            }
            return 'GASOLINA';
        }

        if ($marca === 'ISUZU') {
            // ISUZU: Mayoría Diesel (4J, 4H, 4B, C, 4F, 4E)
            if (preg_match('/^(4J|4H|4B|C2|C3|4F|4E|6H|6B|6U|6W|4D)/', $code)) {
                return 'DIESEL';
            }
        }

        if ($marca === 'MAZDA') {
            // MAZDA DIESEL: R2, RF, WL, WE, S2, XA, HA, VS, TF, TM, PN, SH
            if (preg_match('/^(R2|RF|WL|WE|S2|XA|HA|VS|TF|TM|PN|SH|W9)/', $code)) {
                return 'DIESEL';
            }
            return 'GASOLINA';
        }
        
        if ($marca === 'HONDA') {
            // HONDA: Casi todos Gasolina. N es Diesel.
            if (preg_match('/^(N16A|N22A)/', $code)) {
                return 'DIESEL';
            }
            return 'GASOLINA';
        }

        return null; // Si no estamos seguros, no tocamos
    }
}
