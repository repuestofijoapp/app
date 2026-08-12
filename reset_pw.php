<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = User::where('email', 'admin@prueba.com')->first();
if ($u) {
    $u->password = Hash::make('123456');
    $u->save();
    echo "RESET_SUCCESSFUL\n";
}
else {
    echo "USER_NOT_FOUND\n";
}
