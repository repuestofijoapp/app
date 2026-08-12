<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Engine;

class FixSparkEngine extends Command
{
    protected $signature = 'db:fix-spark-engine';
    protected $description = 'Duplicar motor S-TEC II 1.2L de SPARK a SAIL y AVEO';

    public function handle()
    {
        // Motor del SPARK (car_model_id = 182, engine ID = 253)
        $source = Engine::where('car_model_id', 182)
            ->where('engine_code', 'LIKE', '%S-TEC%')
            ->first();

        if (!$source) {
            $this->error('No se encontró motor S-TEC en SPARK (ID 182)');
            return;
        }

        $this->info("Motor fuente: code={$source->engine_code} | disp={$source->displacement} | fuel={$source->fuel_type}");

        // Modelos destino: SAIL(41), AVEO(78)
        $targets = [41 => 'SAIL', 78 => 'AVEO'];

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
