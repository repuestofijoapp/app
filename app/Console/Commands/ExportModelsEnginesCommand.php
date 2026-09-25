<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Make;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ExportModelsEnginesCommand extends Command
{
    protected $signature = 'export:verify 
                            {--marca= : Filtrar por una marca en específico}
                            {--umbral=2 : Umbral para la distancia Levenshtein}
                            {--solo-con-alertas : Exportar únicamente las filas que tengan una posible alerta}
                            {--incluir-motores=true : Incluir la exportación de la tabla de motores}';

    protected $description = 'Exporta Marcas, Modelos y Motores a CSV para revisión — 100% solo lectura';

    public function handle()
    {
        $marcaFilter = $this->option('marca');
        $umbral = (int) $this->option('umbral');
        $soloConAlertas = $this->option('solo-con-alertas');
        $incluirMotores = $this->option('incluir-motores') !== 'false';

        $this->info("═══════════════════════════════════════════");
        $this->info("  EXPORTADOR DE VERIFICACIÓN — SOLO LECTURA");
        $this->info("═══════════════════════════════════════════");

        // Preparar directorio
        $exportPath = storage_path('app/exports');
        if (!File::exists($exportPath)) {
            File::makeDirectory($exportPath, 0755, true);
        }

        $query = Make::query();
        if ($marcaFilter) {
            $query->where('name', 'like', "%{$marcaFilter}%");
        }
        $brands = $query->orderBy('name')->get();

        if ($brands->isEmpty()) {
            $this->error("No se encontró la marca: {$marcaFilter}");
            return 1;
        }

        $totalMarcas = $brands->count();
        $totalModelosExp = 0;
        $totalMotoresExp = 0;
        $alertasModelos = ['DUPLICADO_NORMALIZADO' => 0, 'MODELO_PARTIDO' => 0, 'POSIBLE_DUPLICADO' => 0];
        $alertasMotores = ['DUPLICADO_NORMALIZADO' => 0, 'MOTOR_PARTIDO' => 0, 'POSIBLE_DUPLICADO' => 0];
        $rutas = [];

        // Registro de modelos con alerta (para cruce en Parte B)
        $modelosConAlerta = [];

        // Pre-cargar conteo de productos por modelo y motor
        // Products almacenan compatible_model_ids y compatible_engine_ids como JSON arrays
        $allProducts = Product::select('id', 'compatible_model_ids', 'compatible_engine_ids')->get();

        // Crear índices rápidos
        $productsPerModel = [];
        $productsPerEngine = [];
        foreach ($allProducts as $p) {
            $modelIds = $p->compatible_model_ids;
            if (is_string($modelIds)) $modelIds = json_decode($modelIds, true);
            if (is_array($modelIds)) {
                foreach ($modelIds as $mid) {
                    $productsPerModel[(int)$mid] = ($productsPerModel[(int)$mid] ?? 0) + 1;
                }
            }

            $engineIds = $p->compatible_engine_ids;
            if (is_string($engineIds)) $engineIds = json_decode($engineIds, true);
            if (is_array($engineIds)) {
                foreach ($engineIds as $eid) {
                    $productsPerEngine[(int)$eid] = ($productsPerEngine[(int)$eid] ?? 0) + 1;
                }
            }
        }

        $bar = $this->output->createProgressBar($totalMarcas);
        $bar->setFormat(' %current%/%max% [%bar%] %message%');

        foreach ($brands as $brand) {
            $bar->setMessage("Procesando: {$brand->name}");
            $bar->advance();

            $fecha = date('Y-m-d');

            // ═══════════════ PARTE A: MODELOS ═══════════════
            $modelos = CarModel::where('make_id', $brand->id)->get();
            $filasModelos = [];

            // Pre-cálculo de datos normalizados
            $modelData = [];
            foreach ($modelos as $m) {
                $normalizado = mb_strtoupper(preg_replace('/[\s\-\_]+/', '', $m->name));
                $modelData[] = [
                    'id' => $m->id,
                    'original' => $m->name,
                    'normalizado' => $normalizado,
                ];
            }

            foreach ($modelData as $i => $m1) {
                $alerta = '';
                $relacionado = '';

                foreach ($modelData as $j => $m2) {
                    if ($i === $j) continue;

                    // 1. DUPLICADO_NORMALIZADO
                    if ($m1['normalizado'] === $m2['normalizado']) {
                        $alerta = 'DUPLICADO_NORMALIZADO';
                        $relacionado = $m2['original'] . " (id:{$m2['id']})";
                        break;
                    }

                    // 2. MODELO_PARTIDO — m1 es substring de m2 (ej: "SPRINTER" dentro de "SPRINTER TRUENO")
                    if (strlen($m1['normalizado']) >= 3 && strlen($m2['normalizado']) > strlen($m1['normalizado'])) {
                        if (str_contains($m2['normalizado'], $m1['normalizado'])) {
                            $alerta = 'MODELO_PARTIDO';
                            $relacionado = $m2['original'] . " (id:{$m2['id']})";
                            break;
                        }
                    }

                    // 3. POSIBLE_DUPLICADO — Levenshtein
                    if (strlen($m1['normalizado']) >= 3 && strlen($m2['normalizado']) >= 3) {
                        $lev = levenshtein($m1['normalizado'], $m2['normalizado']);
                        if ($lev > 0 && $lev <= $umbral) {
                            $alerta = 'POSIBLE_DUPLICADO';
                            $relacionado = $m2['original'] . " (id:{$m2['id']})";
                            break;
                        }
                    }
                }

                if ($alerta) {
                    $alertasModelos[$alerta]++;
                    $modelosConAlerta[$m1['id']] = $alerta;
                }

                if ($soloConAlertas && empty($alerta)) {
                    continue;
                }

                $cantidadProductos = $productsPerModel[$m1['id']] ?? 0;

                $filasModelos[] = [
                    $m1['id'],
                    $brand->name,
                    $m1['original'],
                    $cantidadProductos,
                    $alerta,
                    $relacionado,
                    '', // revision_ia
                    '', // modelo_correcto_sugerido
                    '', // fuente
                    ''  // accion
                ];
            }

            if (count($filasModelos) > 0) {
                $safeName = preg_replace('/[^A-Za-z0-9_]/', '_', $brand->name);
                $rutaModelos = $exportPath . "/modelos_{$safeName}_{$fecha}.csv";
                $this->writeCsv($rutaModelos, [
                    'id_modelo', 'marca', 'modelo_guardado', 'cantidad_productos_asociados',
                    'posible_alerta', 'modelo_relacionado',
                    'revision_ia', 'modelo_correcto_sugerido', 'fuente', 'accion'
                ], $filasModelos);
                $rutas[] = $rutaModelos;
                $totalModelosExp += count($filasModelos);
            }

            // ═══════════════ PARTE B: MOTORES ═══════════════
            if ($incluirMotores) {
                // Engine pertenece a CarModel, que pertenece a Make
                $motores = Engine::whereHas('carModel', function ($q) use ($brand) {
                    $q->where('make_id', $brand->id);
                })->with('carModel')->get();

                $filasMotores = [];
                $engineData = [];

                foreach ($motores as $e) {
                    $code = $e->engine_code ?? $e->name ?? '';
                    $normalizado = mb_strtoupper(preg_replace('/[\s\-]+/', '', $code));
                    $engineData[] = [
                        'id' => $e->id,
                        'original' => $code,
                        'normalizado' => $normalizado,
                        'displacement' => $e->displacement,
                        'fuel_type' => $e->fuel_type,
                        'power' => $e->engine_power,
                        'model_name' => $e->carModel->name ?? '(sin modelo)',
                        'model_id' => $e->car_model_id,
                    ];
                }

                foreach ($engineData as $i => $e1) {
                    $alerta = '';
                    $relacionado = '';

                    foreach ($engineData as $j => $e2) {
                        if ($i === $j) continue;

                        if ($e1['normalizado'] === $e2['normalizado'] && $e1['normalizado'] !== '') {
                            $alerta = 'DUPLICADO_NORMALIZADO';
                            $relacionado = $e2['original'] . " (id:{$e2['id']}, modelo:{$e2['model_name']})";
                            break;
                        }
                        if (strlen($e1['normalizado']) >= 2 && strlen($e2['normalizado']) > strlen($e1['normalizado'])) {
                            if (str_contains($e2['normalizado'], $e1['normalizado'])) {
                                $alerta = 'MOTOR_PARTIDO';
                                $relacionado = $e2['original'] . " (id:{$e2['id']}, modelo:{$e2['model_name']})";
                                break;
                            }
                        }
                        if (strlen($e1['normalizado']) >= 2 && strlen($e2['normalizado']) >= 2) {
                            $lev = levenshtein($e1['normalizado'], $e2['normalizado']);
                            if ($lev > 0 && $lev <= $umbral) {
                                $alerta = 'POSIBLE_DUPLICADO';
                                $relacionado = $e2['original'] . " (id:{$e2['id']}, modelo:{$e2['model_name']})";
                                break;
                            }
                        }
                    }

                    if ($alerta) {
                        $alertasMotores[$alerta]++;
                    }

                    if ($soloConAlertas && empty($alerta)) {
                        continue;
                    }

                    // Coherencia cruzada
                    $modeloConAlerta = isset($modelosConAlerta[$e1['model_id']]) ? 'Sí (' . $modelosConAlerta[$e1['model_id']] . ')' : 'No';

                    $cantidadProductos = $productsPerEngine[$e1['id']] ?? 0;

                    $filasMotores[] = [
                        $e1['id'],
                        $e1['original'],
                        $e1['displacement'] ?? '',
                        $e1['fuel_type'] ?? '',
                        $e1['power'] ?? '',
                        $brand->name,
                        $e1['model_name'],
                        $cantidadProductos,
                        $alerta,
                        $relacionado,
                        $modeloConAlerta,
                        '', // revision_ia
                        '', // motor_correcto_sugerido
                        '', // fuente
                        ''  // accion
                    ];
                }

                if (count($filasMotores) > 0) {
                    $safeName = preg_replace('/[^A-Za-z0-9_]/', '_', $brand->name);
                    $rutaMotores = $exportPath . "/motores_{$safeName}_{$fecha}.csv";
                    $this->writeCsv($rutaMotores, [
                        'id_motor', 'codigo_motor_guardado', 'cilindrada', 'combustible', 'potencia',
                        'marca', 'modelo_vinculado',
                        'cantidad_productos_asociados', 'posible_alerta', 'motor_relacionado',
                        'modelo_padre_con_alerta',
                        'revision_ia', 'motor_correcto_sugerido', 'fuente', 'accion'
                    ], $filasMotores);
                    $rutas[] = $rutaMotores;
                    $totalMotoresExp += count($filasMotores);
                }
            }
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("═══════════════ RESUMEN ═══════════════");
        $this->line("Marcas procesadas:        {$totalMarcas}");
        $this->line("Total Modelos exportados: {$totalModelosExp}");
        $this->line("Total Motores exportados: {$totalMotoresExp}");
        $this->newLine();

        $this->warn("─── ALERTAS EN MODELOS ───");
        foreach ($alertasModelos as $k => $v) {
            $this->line("  {$k}: {$v}");
        }
        $this->warn("─── ALERTAS EN MOTORES ───");
        foreach ($alertasMotores as $k => $v) {
            $this->line("  {$k}: {$v}");
        }

        $this->newLine();
        $this->info("Archivos generados:");
        foreach ($rutas as $r) {
            $this->line("  → {$r}");
        }

        return 0;
    }

    private function writeCsv(string $path, array $headers, array $rows): void
    {
        $file = fopen($path, 'w');
        // BOM para que Excel lea UTF-8 correctamente
        fputs($file, "\xEF\xBB\xBF");
        fputcsv($file, $headers);
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }
}
