<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Vehicle;
use App\Models\Category;

echo "--- VEHICLES ---\n";
$vehicles = Vehicle::all();
echo "Count: " . $vehicles->count() . "\n";
foreach ($vehicles as $v) {
    echo "Plate: {$v->plate} | Brand: {$v->brand} | Model: {$v->model}\n";
}

echo "\n--- SUBCATEGORIES ---\n";
$subcats = Category::whereNotNull('parent_id')->get();
echo "Count: " . $subcats->count() . "\n";
foreach ($subcats->take(10) as $c) {
    echo "ID: {$c->id} | Name: {$c->name} | Slug: {$c->slug}\n";
}
