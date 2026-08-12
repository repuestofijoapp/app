<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;

echo "--- CHECKING FOR DUPLICATES ---\n";
// Case insensitive name check
$names = Category::all()->pluck('name')->map(fn($n) => strtolower($n));
$counts = array_count_values($names->toArray());

foreach($counts as $name => $count) {
    if ($count > 1) {
        echo "Duplicate Found: '$name' ($count times)\n";
        $cats = Category::where('name', 'like', $name)->get();
        foreach($cats as $c) {
            echo "  ID: {$c->id} | Parent: " . ($c->parent_id ?? 'ROOT') . "\n";
        }
    }
}

echo "\n--- TOTAL CATS: " . Category::count() . " ---\n";
