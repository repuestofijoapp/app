<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$cats = Category::whereNotNull('parent_id')->get();
foreach ($cats as $c) {
    echo "ID: {$c->id} | Name: {$c->name}\n";
}
