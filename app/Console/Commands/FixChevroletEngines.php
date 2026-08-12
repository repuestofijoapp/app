<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Engine;
use App\Models\CarModel;

class FixChevroletEngines extends Command
{
    protected $signature = 'db:fix-chevrolet-engines';
    protected $description = 'Duplicar motor de G6 a MALIBU/MONTANA/RELAY/RENDEZVOUS';

    public function handle()
    {
        // Motor del G6 (car_model_id = 216)
        $source = Engine::where('car_model_id', 216)->first();

        if (!$source) {
            $this->error('No se encontró motor en G6 (ID 216)');
            return;
        }

        $this->info("Motor fuente: code={$source->engine_code} | disp={$source->displacement} | fuel={$source->fuel_type}");

        // Modelos destino: MALIBU(217), MONTANA(218), RELAY(219), RENDEZVOUS(220)
        $targets = [217 => 'MALIBU', 218 => 'MONTANA', 219 => 'RELAY', 220 => 'RENDEZVOUS'];

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
