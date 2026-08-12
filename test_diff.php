<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ZbotQuery;
use App\Models\Product;

$repairList = [
    15 => ['qty' => 3, 'product' => Product::find(15)->toArray()]
];

// Let's see what collect($repairList)->groupBy('product.provider_id') does to the keys!
$grouped = collect($repairList)->groupBy('product.provider_id');
foreach ($grouped as $providerId => $items) {
    echo "Provider ID: $providerId\n";
    foreach ($items as $key => $val) {
        echo "  Key: " . var_export($key, true) . " | Type: " . gettype($key) . " | Name: " . $val['product']['name'] . "\n";
    }
}
