<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\Pedido::find(1000050);
if ($p) {
    $p->update([
        'invoice_url' => null,
        'invoice_xml' => null,
        'billing_type' => 'boleta'
    ]);
    echo "Pedido #1000050 limpiado para nueva emision real con direccion!\n";
} else {
    echo "Pedido no encontrado.\n";
}
