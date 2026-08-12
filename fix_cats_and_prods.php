<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "--- CREATING METALES CATEGORY ---\n";
$parent = Category::where('name', 'Motor y Componentes Internos')->first();
if (!$parent) {
    echo "Parent not found!"; exit;
}

$metales = Category::firstOrCreate([
    'name' => 'Metales de Motor (Biela, Bancada, Levas)',
    'slug' => 'metales-de-motor',
    'parent_id' => $parent->id
]);

echo "Created/Found Category ID: {$metales->id}\n";

echo "--- UPDATING NDC PRODUCTS ---\n";
// Products with brand NDC that are currently in the root 'Motor' or no category
$count = Product::where('brand', 'NDC')
    ->where(function($q) use ($parent) {
        $q->where('category_id', $parent->id)
          ->orWhereNull('category_id');
    })
    ->update(['category_id' => $metales->id]);

echo "Updated $count products to 'Metales de Motor'.\n";

echo "--- CHECKING FOR DUPLICATES (AGAIN) ---\n";
// If we find identical names, we delete the one with NO products
$cats = Category::all()->groupBy('name');
foreach($cats as $name => $group) {
    if ($group->count() > 1) {
        echo "Semantic duplicate found: $name\n";
        // Keep the one with most products
        $best = null;
        $maxProds = -1;
        foreach($group as $c) {
            $pCount = Product::where('category_id', $c->id)->count();
            if ($pCount > $maxProds) {
                $maxProds = $pCount;
                $best = $c;
            }
        }
        foreach($group as $c) {
            if ($c->id != $best->id) {
                echo "  Deleting Category ID: {$c->id} (0 or fewer products than {$best->id})\n";
                // Move any stray products just in case
                Product::where('category_id', $c->id)->update(['category_id' => $best->id]);
                $c->delete();
            }
        }
    }
}
