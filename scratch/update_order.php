<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\Pedido::find(1000048);
if ($p) {
    $p->update([
        'invoice_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
        'invoice_xml' => '#',
        'billing_type' => 'boleta'
    ]);
    echo "Pedido #1000048 actualizado con exito!\n";
} else {
    echo "Pedido no encontrado.\n";
}
