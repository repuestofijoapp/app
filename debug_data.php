<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$user = App\Models\User::first();
$provider = App\Models\Provider::first();
$products = App\Models\Product::limit(2)->get();

echo "USER_ID: " . ($user->id ?? 'NONE') . "\n";
echo "PROVIDER_ID: " . ($provider->id ?? 'NONE') . "\n";
foreach ($products as $p) {
    echo "PRODUCT_ID: " . $p->id . " | PROVIDER: " . $p->provider_id . "\n";
}
