<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ZbotQuery;

echo "--- Confirmed Queries ---\n";
$queries = ZbotQuery::where('status', 'confirmed')->get();
foreach ($queries as $q) {
    echo "ID: {$q->id} | Chat: {$q->chat_id} | Price: [{$q->price}] | Raw Step: [" . $q->getRawOriginal('current_step') . "] | Created: {$q->created_at}\n";
}
echo "--- End ---\n";
