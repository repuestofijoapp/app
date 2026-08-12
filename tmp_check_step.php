<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$q = \App\Models\ZbotQuery::latest()->first();
echo "Last Query ID: " . $q->id . "\n";
echo "Raw Attribute current_step: [" . $q->getRawOriginal('current_step') . "]\n";
echo "Property current_step: [" . $q->current_step . "]\n";
echo "All attributes: " . json_encode($q->toArray()) . "\n";
