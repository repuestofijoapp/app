<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Engine;

class FixNp960Engines extends Command
{
    protected $signature = 'db:fix-np960-engines';
    protected $description = 'Duplicar motor NP960 a LEMAN 1.5L, LANOS, ESPERO, CIELO, NUBIRA, LACETTI';

    public function handle()
    {
        // Motor fuente: ID 249, NP960
        $source = Engine::find(249);

        if (!$source) {
            $this->error('No se encontró el motor ID 249');
            return;
        }

        $this->info("Motor fuente: code={$source->engine_code} | disp={$source->displacement} | fuel={$source->fuel_type}");

        // Modelos destino (todos Daewoo, make_id=12)
        $targets = [
            209 => 'LEMAN 1.5L',
            210 => 'LANOS',
            211 => 'ESPERO',
            212 => 'CIELO',
            213 => 'NUBIRA',
            214 => 'LACETTI',
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
