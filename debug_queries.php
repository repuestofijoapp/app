<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ZbotQuery;

$queries = ZbotQuery::latest()->limit(5)->get();
echo "Current App Time: " . now()->toDateTimeString() . "\n\n";

foreach ($queries as $q) {
    $elapsed = now()->diffInSeconds($q->created_at);
    echo "ID: {$q->id} | Status: {$q->status} | Reminders: {$q->reminders_sent} | Created: {$q->created_at} | Elapsed: {$elapsed}s\n";
}
