<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Engine;
use App\Models\CarModel;
use App\Models\Make;
use App\Models\ProductCompatibility;
use Illuminate\Support\Facades\DB;

class PopulateCompatibilitiesCommand extends Command
{
    protected $signature = 'compatibilities:populate {--fresh : Truncate product_compatibilities before populating}';
    protected $description = 'Populate product_compatibilities pivot table from existing products with cross-validation';

    public function handle()
    {
        $this->info('Starting population of product_compatibilities...');

        if ($this->option('fresh')) {
            $this->warn('Truncating product_compatibilities table...');
            ProductCompatibility::truncate();
        }

        $start = microtime(true);

        // Preload caches
        $this->line('Preloading engines, models, and makes...');
        $allEngines = Engine::all()->keyBy('id');
        $enginesByModel = Engine::all()->groupBy('car_model_id');
        $allModels = CarModel::all()->keyBy('id');
        $allMakes = Make::all()->keyBy('id');
        $makesByName = Make::all()->keyBy(fn($m) => strtoupper(trim($m->name)));

        $records = [];
        $skippedInvalid = 0;
        $now = now();

        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $p) {
            $engineIds = is_array($p->compatible_engine_ids) ? $p->compatible_engine_ids : json_decode($p->compatible_engine_ids ?? '[]', true);
            $modelIds = is_array($p->compatible_model_ids) ? $p->compatible_model_ids : json_decode($p->compatible_model_ids ?? '[]', true);
            $enginesList = is_array($p->compatible_engines) ? $p->compatible_engines : json_decode($p->compatible_engines ?? '[]', true);
            $upperEngines = !empty($enginesList) ? array_map('strtoupper', array_map('trim', (array)$enginesList)) : [];

            $productMakeName = strtoupper(trim($p->vehicle_make ?? ''));
            $productMake = !empty($productMakeName) ? $makesByName->get($productMakeName) : null;

            $matchedForThisProduct = 0;

            // 1. Matched by engine_id with cross-validation
            if (!empty($engineIds)) {
                foreach ($engineIds as $eId) {
                    $engine = $allEngines->get($eId);
                    if (!$engine) continue;

                    $model = $allModels->get($engine->car_model_id);
                    if (!$model) continue;

                    $engineCode = strtoupper(trim($engine->engine_code));

                    // Rule A: Engine code must match product's engine codes list (if specified)
                    if (!empty($upperEngines) && !in_array($engineCode, $upperEngines)) {
                        $skippedInvalid++;
                        continue;
                    }

                    // Rule B: Make must match (if product has a vehicle_make)
                    if ($productMake && $model->make_id && $model->make_id != $productMake->id) {
                        $skippedInvalid++;
                        continue;
                    }

                    $key = "{$p->id}_{$model->id}_{$engine->id}";
                    if (!isset($records[$key])) {
                        $records[$key] = [
                            'product_id' => $p->id,
                            'make_id' => $model->make_id,
                            'car_model_id' => $model->id,
                            'engine_id' => $engine->id,
                            'source' => 'engine_id',
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                        $matchedForThisProduct++;
                    }
                }
            }

            // 2. Matched by model_ids + engine codes
            if (!empty($modelIds)) {
                foreach ($modelIds as $mId) {
                    $model = $allModels->get($mId);
                    if (!$model) continue;

                    // Make validation
                    if ($productMake && $model->make_id && $model->make_id != $productMake->id) {
                        continue;
                    }

                    $modelEngines = $enginesByModel->get($mId, collect());
                    $foundEngine = false;

                    if (!empty($upperEngines)) {
                        foreach ($modelEngines as $me) {
                            if (in_array(strtoupper(trim($me->engine_code)), $upperEngines)) {
                                $key = "{$p->id}_{$mId}_{$me->id}";
                                if (!isset($records[$key])) {
                                    $records[$key] = [
                                        'product_id' => $p->id,
                                        'make_id' => $model->make_id,
                                        'car_model_id' => $mId,
                                        'engine_id' => $me->id,
                                        'source' => 'model_and_code',
                                        'created_at' => $now,
                                        'updated_at' => $now,
                                    ];
                                    $matchedForThisProduct++;
                                }
                                $foundEngine = true;
                            }
                        }
                    }

                    // Model only fallback if no engines matched
                    if (!$foundEngine && $matchedForThisProduct === 0) {
                        $key = "{$p->id}_{$mId}_0";
                        if (!isset($records[$key])) {
                            $records[$key] = [
                                'product_id' => $p->id,
                                'make_id' => $model->make_id,
                                'car_model_id' => $mId,
                                'engine_id' => null,
                                'source' => 'model_only',
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                            $matchedForThisProduct++;
                        }
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $totalRecords = count($records);
        $this->info("Validation complete in " . round(microtime(true) - $start, 2) . "s");
        $this->info("Generated {$totalRecords} verified relations. Discarded {$skippedInvalid} corrupted/conflicting IDs.");

        // Insert in chunks of 500
        $this->line("Inserting relations into product_compatibilities table...");
        $chunks = array_chunk(array_values($records), 500);
        foreach ($chunks as $chunk) {
            DB::table('product_compatibilities')->insertOrIgnore($chunk);
        }

        $finalCount = DB::table('product_compatibilities')->count();
        $this->info("Done! Current total rows in product_compatibilities: {$finalCount}");

        return 0;
    }
}
