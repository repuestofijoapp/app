<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$keyFromEnv = env('GEMINI_API_KEY');
$keyFromConfig = config('app.gemini_api_key');

echo "Key from env: " . $keyFromEnv . "\n";
echo "Key from config: " . $keyFromConfig . "\n";
