<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Engine;

class FixDaewooEngines extends Command
{
    protected $signature = 'db:fix-daewoo-engines';
    protected $description = 'Duplicar motor 3EA/F6B/S-TEC I 0.8L a FINO y MATIZ de Daewoo';

    public function handle()
    {
        // Motor fuente: ID 248, car_model_id=14 (TICO de Daewoo)
        $source = Engine::find(248);

        if (!$source) {
            $this->error('No se encontró el motor ID 248');
            return;
        }

        $this->info("Motor fuente: code={$source->engine_code} | disp={$source->displacement} | fuel={$source->fuel_type}");

        // Modelos destino: FINO(206), MATIZ(207) - Daewoo
        $targets = [206 => 'FINO', 207 => 'MATIZ'];

        foreach ($targets as $modelId => $modelName) {
            $exists = Engine::where('car_model_id', $modelId)
                ->where('engine_code', $source->engine_code)
                ->exists();

            if ($exists) {
                $this->line("  [$modelName] Ya existe — saltando");
            } else {
                Engine::create([
                    'car_model_id' => $modelId,
                    'engine_code' => $source->engine_code,
                    'displacement' => $source->displacement,
                    'fuel_type' => $source->fuel_type,
                    'engine_power' => $source->engine_power,
                ]);
                $this->info("  [$modelName] ✅ Motor creado");
            }
        }

        $this->info("\nTotal engines: " . Engine::count());
    }
}
