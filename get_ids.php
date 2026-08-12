<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$targetNames = ['Filtro de Aceite', 'Pastillas de freno', 'Bujías', 'Alternadores', 'Pistones'];
$found = Category::whereIn('name', $targetNames)->get();

foreach ($found as $c) {
    echo "ID: {$c->id} | Name: {$c->name}\n";
}
