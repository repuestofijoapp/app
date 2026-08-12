<?php
// Test what the actual data looks like
$prod = App\Models\Product::first();
$rawEngines = $prod->getRawOriginal('compatible_engines');
$rawVehicles = $prod->getRawOriginal('compatible_vehicles');
echo "raw compatible_engines: " . $rawEngines . PHP_EOL;
echo "raw compatible_vehicles: " . $rawVehicles . PHP_EOL;

// Test REGEXP pattern
$result = DB::select("SELECT engine_code FROM engines LIMIT 10");
echo PHP_EOL . "Engine codes in DB:";
foreach ($result as $r)
    echo " " . $r->engine_code;
echo PHP_EOL;

// Test if LIKE '\"4EA\"' works
$matchLike = DB::select("SELECT id FROM products WHERE compatible_engines LIKE '%\"4EA\"%' AND is_active = 1 LIMIT 3");
echo "Products matching LIKE '%\"4EA\"%': " . count($matchLike) . PHP_EOL;

// Test FIND_IN_SET for vehicles
$matchVehicles = DB::select("SELECT id FROM products WHERE FIND_IN_SET('MATIZ', REPLACE(compatible_vehicles, ', ', ',')) > 0 LIMIT 3");
echo "Products matching FIND_IN_SET MATIZ: " . count($matchVehicles) . PHP_EOL;

// Find which makes have products
$makes = DB::table('makes')
    ->where(function ($q) {
        $q->whereExists(function ($sub) {
            $sub->select(DB::raw(1))
                ->from('car_models')
                ->join('engines', 'engines.car_model_id', '=', 'car_models.id')
                ->join('products', function ($j) {
                    $j->whereRaw('products.compatible_engines LIKE CONCAT(\'%"\', engines.engine_code, \'"%\')')
                        ->where('products.is_active', true);
                })
                ->whereColumn('car_models.make_id', 'makes.id');
        })->orWhereExists(function ($sub) {
            $sub->select(DB::raw(1))
                ->from('car_models')
                ->join('products', function ($j) {
                    $j->whereRaw('FIND_IN_SET(car_models.name, REPLACE(products.compatible_vehicles, \', \', \',\')) > 0')
                        ->where('products.is_active', true);
                })
                ->whereColumn('car_models.make_id', 'makes.id');
        });
    })
    ->pluck('name');
echo PHP_EOL . "Makes WITH products: " . $makes->implode(', ') . PHP_EOL;
