<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "--- CATEGORIES COUNT ---\n";
echo Category::count() . "\n";

echo "--- DUPLICATES ---\n";
$dupes = Category::select('name', \DB::raw('count(*) as c'))
    ->groupBy('name')
    ->having('c', '>', 1)
    ->pluck('name');
foreach($dupes as $d) echo "DUPE: $d\n";

echo "--- METALES PRODUCTS ---\n";
$prods = Product::where('nombre', 'like', '%metal%')->limit(10)->get();
foreach($prods as $p) {
    echo "ID: {$p->id} | Name: {$p->nombre} | Cat: " . ($p->category ? $p->category->name : 'NONE') . "\n";
}
