<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Provider;

echo "--- Current Providers ---\n";
foreach (Provider::all() as $p) {
    echo "ID: {$p->id} | Name: {$p->name} | Phone: {$p->whatsapp_number}\n";
}
echo "--- End ---\n";
