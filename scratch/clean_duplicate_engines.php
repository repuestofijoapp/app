<?php
require __DIR__ . "/../bootstrap/autoload.php";
$app = require_once __DIR__ . "/../bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Engine;
use Illuminate\Support\Facades\DB;

$duplicates = Engine::select("car_model_id", "engine_code", DB::raw("COUNT(*) as count"))
    ->groupBy("car_model_id", "engine_code")
    ->having("count", ">", 1)
    ->get();

echo "Found " . $duplicates->count() . " duplicate groups.\n";

foreach ($duplicates as $dup) {
    $engines = Engine::where("car_model_id", $dup->car_model_id)
        ->where("engine_code", $dup->engine_code)
        ->get();

    $keep = $engines->first();
    foreach ($engines as $index => $engine) {
        if ($index > 0) {
            echo "Deleting Engine ID: " . $engine->id . " (Code: " . $engine->engine_code . ", ModelID: " . $engine->car_model_id . ")\n";
            DB::table("products")
                ->whereJsonContains("compatible_engine_ids", $engine->id)
                ->get()
                ->each(function ($product) use ($engine, $keep) {
                    $ids = json_decode($product->compatible_engine_ids, true);
                    $ids = array_map(fn($id) => $id == $engine->id ? $keep->id : $id, $ids);
                    $ids = array_values(array_unique($ids));
                    DB::table("products")->where("id", $product->id)->update([
                        "compatible_engine_ids" => json_encode($ids)
                    ]);
                });
            $engine->delete();
        }
    }
}
echo "Cleanup finished.\n";
