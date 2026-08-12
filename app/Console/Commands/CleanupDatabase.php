<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CleanupDatabase extends Command
{
    protected $signature = 'db:cleanup';
    protected $description = 'Limpieza completa de la base de datos';

    public function handle()
    {
        // PASO A: Eliminar vehicles de prueba
        $this->info("\n--- PASO A: Eliminar vehicles de prueba ---");
        $fakeVehicles = DB::table('vehicles')
            ->where('vin', 'LIKE', 'VIN%')
            ->orWhereNull('vin')
            ->get(['id', 'plate', 'vin']);
        $this->info("Encontrados: {$fakeVehicles->count()}");
        foreach ($fakeVehicles as $v) {
            $this->line("  Eliminando: [{$v->id}] {$v->plate} | {$v->vin}");
        }
        $deleted = DB::table('vehicles')
            ->where('vin', 'LIKE', 'VIN%')
            ->orWhereNull('vin')
            ->delete();
        $this->info("✅ Eliminados: $deleted vehicles. Quedan: " . DB::table('vehicles')->count());

        // PASO B: Limpiar version_no, start_year, end_year
        $this->info("\n--- PASO B: Limpiar campos innecesarios de car_models ---");
        $cleared = DB::table('car_models')->update(['version_no' => null, 'start_year' => null, 'end_year' => null]);
        $this->info("✅ Limpiados $cleared registros");

        // PASO C: Separar modelos con nombre múltiple
        $this->info("\n--- PASO C: Separar modelos múltiples ---");
        $multiModels = CarModel::with('make')->where('name', 'LIKE', '%,%')->get();
        $this->info("Encontrados: {$multiModels->count()}");

        foreach ($multiModels as $multi) {
            $names = array_map('trim', explode(',', $multi->name));
            $this->line("  Separando: [{$multi->make->name}] {$multi->name}");
            $newIds = [];
            foreach ($names as $name) {
                $name = strtoupper(trim($name));
                if (!$name)
                    continue;
                $model = CarModel::firstOrCreate(['make_id' => $multi->make_id, 'name' => $name]);
                $newIds[] = $model->id;
                $this->line("    → '$name' ID:{$model->id} " . ($model->wasRecentlyCreated ? '(nuevo)' : '(existía)'));
            }
            // Update products
            Product::whereJsonContains('compatible_model_ids', $multi->id)->each(function ($p) use ($multi, $newIds) {
                $ids = array_values(array_unique(array_merge(
                    array_filter($p->compatible_model_ids ?? [], fn($id) => $id != $multi->id),
                    $newIds
                )));
                $p->update(['compatible_model_ids' => $ids]);
                $this->line("    Producto ID:{$p->id} actualizado");
            });
            // Move engines
            $engCount = Engine::where('car_model_id', $multi->id)->count();
            if ($engCount && !empty($newIds)) {
                Engine::where('car_model_id', $multi->id)->update(['car_model_id' => $newIds[0]]);
                $this->line("    $engCount motores → ID:{$newIds[0]}");
            }
            $multi->delete();
            $this->line("    ✅ Eliminado registro múltiple");
        }

        // PASO D: fuel_type = GASOLINA donde NULL
        $this->info("\n--- PASO D: Set fuel_type = GASOLINA ---");
        $upd = DB::table('engines')->where('id', '>', 227)
            ->where(fn($q) => $q->whereNull('fuel_type')->orWhere('fuel_type', ''))
            ->update(['fuel_type' => 'GASOLINA']);
        $this->info("✅ $upd engines actualizados");

        // PASO E: Eliminar car_models huérfanos
        $this->info("\n--- PASO E: Eliminar car_models huérfanos ---");
        $allModelIds = DB::table('products')
            ->whereNotNull('compatible_model_ids')
            ->where('compatible_model_ids', '!=', 'null')
            ->pluck('compatible_model_ids')
            ->flatMap(fn($j) => json_decode($j, true) ?? [])
            ->unique()->values()->toArray();
        $this->info("Modelos referenciados en productos: " . count($allModelIds));

        $orphans = CarModel::with('make')->whereNotIn('id', count($allModelIds) ? $allModelIds : [0])->get();
        $this->info("Huérfanos: {$orphans->count()}");
        $dM = $dE = 0;
        foreach ($orphans as $o) {
            $eng = Engine::where('car_model_id', $o->id)->count();
            $this->line("  [{$o->make->name}] {$o->name} — $eng motores");
            Engine::where('car_model_id', $o->id)->delete();
            $o->delete();
            $dM++;
            $dE += $eng;
        }
        $this->info("✅ $dM modelos y $dE motores eliminados");

        // PASO F: Eliminar motores duplicados (mismo car_model_id + engine_code)
        $this->info("\n--- PASO F: Eliminar motores duplicados ---");
        $dupEngines = DB::table('engines')
            ->select('car_model_id', 'engine_code', DB::raw('COUNT(*) as cnt'))
            ->groupBy('car_model_id', 'engine_code')
            ->having('cnt', '>', 1)
            ->get();
        $this->info("Grupos duplicados encontrados: {$dupEngines->count()}");
        $deletedEngines = 0;
        foreach ($dupEngines as $dup) {
            $engines = Engine::where('car_model_id', $dup->car_model_id)
                ->where('engine_code', $dup->engine_code)
                ->orderBy('id')
                ->get();
            $keep = $engines->first();
            foreach ($engines as $index => $engine) {
                if ($index === 0) continue;
                $this->line("  Eliminando Engine ID:{$engine->id} ({$engine->engine_code}) → conservando ID:{$keep->id}");
                // Redirigir referencias en productos
                DB::table('products')
                    ->whereJsonContains('compatible_engine_ids', $engine->id)
                    ->get()
                    ->each(function ($product) use ($engine, $keep) {
                        $ids = json_decode($product->compatible_engine_ids, true) ?? [];
                        $ids = array_map(fn($id) => $id == $engine->id ? $keep->id : $id, $ids);
                        $ids = array_values(array_unique($ids));
                        DB::table('products')->where('id', $product->id)->update([
                            'compatible_engine_ids' => json_encode($ids)
                        ]);
                    });
                $engine->delete();
                $deletedEngines++;
            }
        }
        $this->info("✅ $deletedEngines motores duplicados eliminados");

        $this->info("\n=== RESUMEN FINAL ===");
        $this->table(['Tabla', 'Registros'], [
            ['vehicles', DB::table('vehicles')->count()],
            ['makes', DB::table('makes')->count()],
            ['car_models', DB::table('car_models')->count()],
            ['engines', DB::table('engines')->count()],
            ['products', DB::table('products')->count()],
        ]);
    }
}
