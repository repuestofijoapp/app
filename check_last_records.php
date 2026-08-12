<?php
require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'repuestofijo',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    $lastRecords = Capsule::table('vehicles')->orderBy('id', 'desc')->limit(10)->get();
    file_put_contents('e:/xampp/htdocs/fast2/Repuestofijo/last_records.txt', json_encode($lastRecords, JSON_PRETTY_PRINT));
    echo "Done. Saved to last_records.txt\n";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
