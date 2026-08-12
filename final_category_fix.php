<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Product;

echo "--- RESTRUCTURING CATEGORIES ---\n";

$motorParent = Category::where('name', 'Motor y Componentes Internos')->first();
if (!$motorParent) { echo "Motor parent not found!"; exit; }

// Find if there's any category with 'metales' in slug already
$existingMetales = Category::where('slug', 'metales-de-motor')->first();

if ($existingMetales) {
    $existingMetales->update(['name' => 'Metales de Motor']);
    $metalesParent = $existingMetales;
} else {
    $metalesParent = Category::firstOrCreate([
        'name' => 'Metales de Motor',
        'slug' => 'metales-de-motor',
        'parent_id' => $motorParent->id
    ]);
}
echo "Main Metales Category: ID {$metalesParent->id}\n";

$subMetales = [
    'Metales de Biela',
    'Metales de Bancada',
    'Separadores de Bancada',
    'Metales de Levas',
    'Metal compensador',
    'Bocina de biela'
];

foreach ($subMetales as $name) {
    // Check if it exists with a different name/slug combination
    $sub = Category::where('name', $name)->where('parent_id', $metalesParent->id)->first();
    if (!$sub) {
        $sub = Category::create([
            'name' => $name,
            'slug' => \Str::slug($name . '-' . rand(100, 999)), // Avoid collisions
            'parent_id' => $metalesParent->id
        ]);
        // Try to clean up slug if possible
        try { $sub->update(['slug' => \Str::slug($name)]); } catch(\Exception $e) {}
    }
    echo "  Added/Found: $name (ID: {$sub->id})\n";
}

echo "--- ASSIGNING NDC PRODUCTS ---\n";
$bielaSub = Category::where('name', 'Metales de Biela')->first();
$bancadaSub = Category::where('name', 'Metales de Bancada')->first();
$twSub = Category::where('name', 'Separadores de Bancada')->first();

$count = 0;
// Biela (CB-)
$up = Product::where('brand', 'NDC')->where('supplier_code', 'LIKE', 'CB-%')->update(['category_id' => $bielaSub->id]);
$count += $up;
// Bancada (MS-)
$up = Product::where('brand', 'NDC')->where('supplier_code', 'LIKE', 'MS-%')->update(['category_id' => $bancadaSub->id]);
$count += $up;
// Axial / Separadores (TW-)
$up = Product::where('brand', 'NDC')->where('supplier_code', 'LIKE', 'TW-%')->update(['category_id' => $twSub->id]);
$count += $up;

echo "Assigned $count NDC products to specific subcategories.\n";
