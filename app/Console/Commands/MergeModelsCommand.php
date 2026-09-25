<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Make;
use App\Models\ProductCompatibility;
use Illuminate\Support\Facades\DB;

class MergeModelsCommand extends Command
{
    protected $signature = 'model:merge 
                            {--from= : Name or ID of duplicate model to merge FROM (will be removed)}
                            {--to= : Name or ID of target model to merge INTO (will remain)}
                            {--make= : Optional make name to filter models by brand}
                            {--dry-run : Test run without applying changes}';

    protected $description = 'Merge duplicate car models into a single official model and reassign all products and engines';

    public function handle()
    {
        $fromInput = $this->option('from');
        $toInput = $this->option('to');
        $makeName = $this->option('make');
        $dryRun = $this->option('dry-run');

        if (!$fromInput || !$toInput) {
            $this->error('You must specify both --from and --to options.');
            return 1;
        }

        $make = null;
        if ($makeName) {
            $make = Make::where('name', 'LIKE', "%{$makeName}%")->first();
            if (!$make) {
                $this->error("Make '{$makeName}' not found.");
                return 1;
            }
        }

        // Find FROM model
        $fromQuery = CarModel::query();
        if ($make) $fromQuery->where('make_id', $make->id);
        if (is_numeric($fromInput)) {
            $fromModel = $fromQuery->find($fromInput);
        } else {
            $fromModel = $fromQuery->where('name', $fromInput)->first();
        }

        if (!$fromModel) {
            $this->error("Source model (from: '{$fromInput}') not found.");
            return 1;
        }

        // Find TO model
        $toQuery = CarModel::query();
        if ($make) $toQuery->where('make_id', $make->id);
        if (is_numeric($toInput)) {
            $toModel = $toQuery->find($toInput);
        } else {
            $toModel = $toQuery->where('name', $toInput)->first();
        }

        if (!$toModel) {
            $this->error("Target model (to: '{$toInput}') not found.");
            return 1;
        }

        if ($fromModel->id === $toModel->id) {
            $this->error("Source and target models cannot be the same (ID: {$fromModel->id}).");
            return 1;
        }

        $this->info("Merging Model:");
        $this->line("  [FROM] ID: {$fromModel->id} | Name: '{$fromModel->name}' (Will be deleted)");
        $this->line("  [TO]   ID: {$toModel->id} | Name: '{$toModel->name}' (Official)");

        $fromEngines = Engine::where('car_model_id', $fromModel->id)->get();
        $toEngines = Engine::where('car_model_id', $toModel->id)->get()->keyBy(fn($e) => strtoupper(trim($e->engine_code)));

        $compatCount = ProductCompatibility::where('car_model_id', $fromModel->id)->count();

        $this->line("  Engines in FROM model: {$fromEngines->count()}");
        $this->line("  Product relations in FROM model: {$compatCount}");

        if ($dryRun) {
            $this->warn("[DRY-RUN] No changes were made.");
            return 0;
        }

        DB::transaction(function () use ($fromModel, $toModel, $fromEngines, $toEngines) {
            foreach ($fromEngines as $fe) {
                $code = strtoupper(trim($fe->engine_code));
                if ($toEngines->has($code)) {
                    // Target model already has this engine: reassign products to target engine
                    $targetEngine = $toEngines->get($code);
                    ProductCompatibility::where('engine_id', $fe->id)
                        ->update([
                            'car_model_id' => $toModel->id,
                            'engine_id' => $targetEngine->id,
                            'make_id' => $toModel->make_id,
                        ]);
                    // Delete duplicate engine
                    $fe->delete();
                } else {
                    // Target model does not have this engine: move engine to target model
                    $fe->update(['car_model_id' => $toModel->id]);
                    ProductCompatibility::where('engine_id', $fe->id)
                        ->update([
                            'car_model_id' => $toModel->id,
                            'make_id' => $toModel->make_id,
                        ]);
                }
            }

            // Reassign any remaining compatibilities (e.g. model-only)
            ProductCompatibility::where('car_model_id', $fromModel->id)
                ->update([
                    'car_model_id' => $toModel->id,
                    'make_id' => $toModel->make_id,
                ]);

            // Finally delete FROM model
            $fromModel->delete();
        });

        $this->info("Successfully merged model '{$fromModel->name}' into '{$toModel->name}'!");
        return 0;
    }
}
