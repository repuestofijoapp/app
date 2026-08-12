<?php
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Product;
use App\Models\Make;
use Illuminate\Support\Facades\DB;

$log = [];
$log[] = "=== LIMPIEZA DE BD - " . now() . " ===\n";

// ───────────────────────────────────────────────
// PASO A: Eliminar vehicles de prueba (VINs falsos)
// ───────────────────────────────────────────────
$log[] = "\n--- PASO A: Eliminar vehicles de prueba ---";
$fakeVehicles = DB::table('vehicles')
    ->where(function ($q) {
        $q->where('vin', 'LIKE', 'VIN%')   // VINs falsos tipo VIN0987654321
            ->orWhereNull('vin');
    })
    ->get(['id', 'plate', 'vin', 'brand']);

$log[] = "Encontrados: {$fakeVehicles->count()} registros de prueba";
foreach ($fakeVehicles as $v) {
    $log[] = "  Eliminando ID:{$v->id} | plate:{$v->plate} | VIN:{$v->vin} | brand:{$v->brand}";
}
$deleted = DB::table('vehicles')
    ->where(function ($q) {
        $q->where('vin', 'LIKE', 'VIN%')
            ->orWhereNull('vin');
    })
    ->delete();
$log[] = "✅ Eliminados: $deleted vehicles de prueba";
$log[] = "   Quedan: " . DB::table('vehicles')->count() . " vehicles reales";

// ───────────────────────────────────────────────
// PASO B: Limpiar version_no, start_year, end_year de car_models
// ───────────────────────────────────────────────
$log[] = "\n--- PASO B: Limpiar version_no, start_year, end_year ---";
$cleared = DB::table('car_models')->update([
    'version_no' => null,
    'start_year' => null,
    'end_year' => null,
]);
$log[] = "✅ Limpiados $cleared registros de car_models";

// ───────────────────────────────────────────────
// PASO C: Separar modelos con nombre múltiple
// ───────────────────────────────────────────────
$log[] = "\n--- PASO C: Separar modelos con nombre múltiple ---";
$multiModels = CarModel::with('make')->where('name', 'LIKE', '%,%')->get();
$log[] = "Encontrados: {$multiModels->count()} registros con nombre múltiple";

foreach ($multiModels as $multi) {
    $names = array_map('trim', explode(',', $multi->name));
    $log[] = "  Separando ID:{$multi->id} [{$multi->make->name}]: " . implode(' | ', $names);

    $newIds = [];
    foreach ($names as $name) {
        $name = strtoupper(trim($name));
        if (!$name)
            continue;
        // Create or find individual model
        $model = CarModel::firstOrCreate([
            'make_id' => $multi->make_id,
            'name' => $name,
        ]);
        $newIds[] = $model->id;
        $log[] = "    → '{$name}' → ID:{$model->id} " . ($model->wasRecentlyCreated ? '(nuevo)' : '(existía)');
    }

    // Update products that reference the multi-name model ID
    $products = Product::whereJsonContains('compatible_model_ids', $multi->id)->get();
    $log[] = "    Productos a actualizar: {$products->count()}";
    foreach ($products as $product) {
        $currentIds = $product->compatible_model_ids ?? [];
        // Remove the old multi ID and add the new individual IDs
        $updatedIds = array_values(array_unique(array_merge(
            array_filter($currentIds, fn($id) => $id != $multi->id),
            $newIds
        )));
        $product->update(['compatible_model_ids' => $updatedIds]);
        $log[] = "      Producto ID:{$product->id} actualizado";
    }

    // Move engines to the first new model (if any)
    $engines = Engine::where('car_model_id', $multi->id)->get();
    if ($engines->count() > 0 && !empty($newIds)) {
        $log[] = "    Moviendo {$engines->count()} motores → ID:{$newIds[0]}";
        Engine::where('car_model_id', $multi->id)->update(['car_model_id' => $newIds[0]]);
    }

    // Delete the old multi-name record
    $multi->delete();
    $log[] = "    ✅ Eliminado registro múltiple ID:{$multi->id}";
}

// ───────────────────────────────────────────────
// PASO D: Set fuel_type = GASOLINA en engines con NULL (ID > 227)
// ───────────────────────────────────────────────
$log[] = "\n--- PASO D: Set fuel_type = GASOLINA en nuevos engines ---";
$updated = DB::table('engines')
    ->where('id', '>', 227)
    ->whereNull('fuel_type')
    ->orWhere('fuel_type', '')
    ->update(['fuel_type' => 'GASOLINA']);
$log[] = "✅ Actualizados $updated engines con fuel_type=GASOLINA";

// ───────────────────────────────────────────────
// PASO E: Eliminar car_models huérfanos (sin productos)
// ───────────────────────────────────────────────
$log[] = "\n--- PASO E: Eliminar car_models huérfanos ---";

// Get all car_model IDs referenced in any product
$allModelIds = DB::table('products')
    ->whereNotNull('compatible_model_ids')
    ->where('compatible_model_ids', '!=', 'null')
    ->pluck('compatible_model_ids')
    ->flatMap(fn($json) => json_decode($json, true) ?? [])
    ->unique()
    ->values()
    ->toArray();

$log[] = "IDs de modelos referenciados en productos: " . count($allModelIds);

// Find orphan car_models (not referenced in any product)
$orphans = CarModel::with('make')
    ->whereNotIn('id', $allModelIds ?: [0])
    ->get();

$log[] = "Car models huérfanos a eliminar: {$orphans->count()}";
$deletedEngines = 0;
$deletedModels = 0;

foreach ($orphans as $o) {
    $eng = Engine::where('car_model_id', $o->id)->count();
    $log[] = "  [{$o->make->name}] {$o->name} (ID:{$o->id}) — {$eng} motores";
    Engine::where('car_model_id', $o->id)->delete();
    $deletedEngines += $eng;
    $o->delete();
    $deletedModels++;
}
$log[] = "✅ Eliminados: $deletedModels car_models y $deletedEngines engines huérfanos";

// ───────────────────────────────────────────────
// RESUMEN FINAL
// ───────────────────────────────────────────────
$log[] = "\n=== RESUMEN FINAL ===";
$log[] = "vehicles:   " . DB::table('vehicles')->count();
$log[] = "makes:      " . DB::table('makes')->count();
$log[] = "car_models: " . DB::table('car_models')->count();
$log[] = "engines:    " . DB::table('engines')->count();
$log[] = "products:   " . DB::table('products')->count();

foreach ($log as $line) {
    echo $line . "\n";
}
