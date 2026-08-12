<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "--- CATEGORIES LIST (ALL) ---\n";
$allCats = Category::all();
foreach($allCats as $c) {
    echo "ID: {$c->id} | Name: {$c->name} | Parent: " . ($c->parent_id ?? 'ROOT') . "\n";
}

echo "\n--- DUPLICATES BY NAME ---\n";
$dupes = Category::select('name', \DB::raw('count(*) as total'))
    ->groupBy('name')
    ->having('total', '>', 1)
    ->get();

foreach($dupes as $d) {
    echo "DUPLICATE: {$d->name} ({$d->total} times)\n";
    $ids = Category::where('name', $d->name)->pluck('id')->implode(', ');
    echo "  IDs: $ids\n";
}

echo "\n--- SEARCHING FOR BRANDS AND ENGINE PARTS ---\n";
$terms = ['metal', 'bancada', 'biela', 'anillo', 'filtro'];
foreach($terms as $term) {
    echo "Term '$term': " . Product::where('name', 'like', "%$term%")->count() . " products\n";
}
