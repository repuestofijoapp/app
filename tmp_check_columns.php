<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$columns = \DB::getSchemaBuilder()->getColumnListing('zbot_queries');
echo "Columns: " . implode(', ', $columns) . "\n";
