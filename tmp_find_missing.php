<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\Category;

echo "--- PRODUCTS WITHOUT CATEGORY ---\n";
$noCat = Product::whereNull('category_id')->get();
echo "Count: " . $noCat->count() . "\n";
foreach($noCat->take(20) as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Brand: {$p->brand}\n";
}

echo "\n--- SEARCHING FOR 'NDC' (Brand mentioned by user) ---\n";
$ndc = Product::where('brand', 'like', '%NDC%')->orWhere('name', 'like', '%NDC%')->get();
echo "Count NDC: " . $ndc->count() . "\n";
foreach($ndc->take(20) as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Brand: {$p->brand} | Cat: " . ($p->category ? $p->category->name : 'NULL') . "\n";
}
