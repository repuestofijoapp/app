<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "App Time: " . now()->toDateTimeString() . "\n";
echo "Carbon Time: " . Carbon::now()->toDateTimeString() . "\n";

try {
    $dbTime = DB::select("SELECT NOW() as now")[0]->now;
    echo "DB Time: " . $dbTime . "\n";
} catch (\Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}

echo "Timezone Config: " . config('app.timezone') . "\n";
echo "PHP Timezone: " . date_default_timezone_get() . "\n";
