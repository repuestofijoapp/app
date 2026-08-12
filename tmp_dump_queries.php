<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ZbotQuery;

echo "--- ZbotQuery Dump ---\n";
$queries = ZbotQuery::all();
foreach ($queries as $q) {
    echo "ID: {$q->id} | Chat: {$q->chat_id} | Step: {$q->current_step} | Status: {$q->status} | Created: {$q->created_at}\n";
}
echo "--- End ---\n";
