<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ZbotQuery;
use App\Models\Provider;

echo "--- Confirmed Queries with Provider Info ---\n";
$queries = ZbotQuery::where('status', 'confirmed')->get();
foreach ($queries as $q) {
    $pName = Provider::find($q->provider_id)->name ?? 'Unknown';
    echo "ID: {$q->id} | Prov: {$pName} (#{$q->provider_id}) | Price: [{$q->price}] | Created: {$q->created_at}\n";
}
echo "--- End ---\n";
