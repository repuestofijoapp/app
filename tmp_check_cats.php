<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Category;
use App\Models\Product;

echo "--- ALL CATEGORIES ---\n";
$cats = Category::all();
foreach($cats as $c) {
    $parent = $c->parent ? $c->parent->name : 'ROOT';
    echo "ID: {$c->id} | Name: {$c->name} | Slug: {$c->slug} | Parent: {$parent}\n";
}

echo "\n--- DUPLICATE CATEGORIES BY NAME ---\n";
$duplicates = Category::select('name', \DB::raw('count(*) as total'))
    ->groupBy('name')
    ->having('total', '>', 1)
    ->get();

if ($duplicates->isEmpty()) {
    echo "No duplicates found by name.\n";
} else {
    foreach($duplicates as $d) {
        echo "Name: {$d->name} | Count: {$d->total}\n";
    }
}

echo "\n--- PRODUCTS WITH 'METAL' OR 'BIELA' IN NAME ---\n";
$products = Product::where('nombre', 'like', '%metal%')
    ->orWhere('nombre', 'like', '%biela%')
    ->get();

foreach($products as $p) {
    $catName = $p->category ? $p->category->name : 'NONE';
    echo "Product ID: {$p->id} | Name: {$p->nombre} | Category: {$catName} ({$p->category_id})\n";
}
