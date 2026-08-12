<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

// In Laravel 11/12, the kernel handles instantiation
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$keyFromEnv = env('GEMINI_API_KEY');
$keyFromConfig = config('app.gemini_api_key');

echo "Key from env: " . substr($keyFromEnv, 0, 15) . "...\n";
echo "Key from config: " . substr($keyFromConfig, 0, 15) . "...\n";
