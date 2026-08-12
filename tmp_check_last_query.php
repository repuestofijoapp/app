<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ZbotQuery;

$q = ZbotQuery::latest()->first();
echo "ID: {$q->id}\n";
echo "Status: {$q->status}\n";
echo "Confirmed JSON:\n";
print_r($q->items_confirmed_json);
echo "Items JSON:\n";
print_r($q->items_json);
