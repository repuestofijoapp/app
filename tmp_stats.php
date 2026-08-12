<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$data = DB::table('vehicles')->get(['brand', 'model', 'engine_code']);
$counts = [];
foreach ($data as $v) {
    // Clean up strings
    $brand = trim($v->brand ?: 'UNKNOWN');
    $model = trim($v->model ?: 'UNKNOWN');
    $engine = trim($v->engine_code ?: 'N/A');
    $key = "$brand $model | Engine: $engine";
    $counts[$key] = ($counts[$key] ?? 0) + 1;
}

arsort($counts);

echo "TOP VEHICLES IN TABLE:\n";
echo "======================\n";
foreach (array_slice($counts, 0, 40) as $key => $val) {
    echo "[$val] $key\n";
}
