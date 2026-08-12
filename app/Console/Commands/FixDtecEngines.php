<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Engine;

class FixDtecEngines extends Command
{
    protected $signature = 'db:fix-dtec-engines';
    protected $description = 'Duplicar motor D-TEC 1.6 a NUBIRA, ESPERO y AVEO 1.6';

    public function handle()
    {
        // Motor fuente: ID 250, D-TEC 1.6
        $source = Engine::find(250);

        if (!$source) {
            $this->error('No se encontró el motor ID 250');
            return;
        }

        $this->info("Motor fuente: code={$source->engine_code} | disp={$source->displacement} | fuel={$source->fuel_type}");

        // Modelos destino
        $targets = [
            213 => 'NUBIRA',
            211 => 'ESPERO',
            215 => 'AVEO 1.6',
        ];

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
