<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Engine;
use App\Models\ProductCompatibility;
use Illuminate\Support\Facades\DB;

class MergeEnginesCommand extends Command
{
    protected $signature = 'engine:merge 
                            {--from= : ID of duplicate engine to merge FROM (will be deleted)}
                            {--to= : ID of target engine to merge INTO (will remain)}
                            {--dry-run : Test run without applying changes}';

    protected $description = 'Merge duplicate engines into a single official engine and reassign all products';

    public function handle()
    {
        $fromId = $this->option('from');
        $toId = $this->option('to');
        $dryRun = $this->option('dry-run');

        if (!$fromId || !$toId) {
            $this->error('You must specify both --from and --to engine IDs.');
            return 1;
        }

        $fromEngine = Engine::with('carModel')->find($fromId);
        $toEngine = Engine::with('carModel')->find($toId);

        if (!$fromEngine) {
            $this->error("Source engine (ID: {$fromId}) not found.");
            return 1;
        }

        if (!$toEngine) {
            $this->error("Target engine (ID: {$toId}) not found.");
            return 1;
        }

        if ($fromEngine->id === $toEngine->id) {
            $this->error("Source and target engine cannot be the same.");
            return 1;
        }

        $this->info("Merging Engine:");
        $this->line("  [FROM] ID: {$fromEngine->id} | Code: '{$fromEngine->engine_code}' | Model: '{$fromEngine->carModel->name}' | CC: '{$fromEngine->displacement}'");
        $this->line("  [TO]   ID: {$toEngine->id} | Code: '{$toEngine->engine_code}' | Model: '{$toEngine->carModel->name}' | CC: '{$toEngine->displacement}'");

        $compatCount = ProductCompatibility::where('engine_id', $fromEngine->id)->count();
        $this->line("  Product relations linked to FROM engine: {$compatCount}");

        if ($dryRun) {
            $this->warn("[DRY-RUN] No changes were made.");
            return 0;
        }

        DB::transaction(function () use ($fromEngine, $toEngine) {
            // Reassign product_compatibilities
            ProductCompatibility::where('engine_id', $fromEngine->id)
                ->update([
                    'engine_id' => $toEngine->id,
                    'car_model_id' => $toEngine->car_model_id,
                    'make_id' => $toEngine->carModel->make_id ?? null,
                ]);

            // Delete duplicate engine
            $fromEngine->delete();
        });

        $this->info("Successfully merged engine ID {$fromId} into engine ID {$toId}!");
        return 0;
    }
}
