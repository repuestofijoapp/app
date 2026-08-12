<?php
use App\Models\User;
use App\Models\Pedido;
use App\Models\Product;
use App\Models\PedidoItem;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$customer = User::first();
if (!$customer) {
    echo "No users found. Please create a user first.\n";
    exit;
}

// Order 1: LIMA
$pedidoLima = Pedido::create([
    'customer_id' => $customer->id,
    'tipo_envio' => 'Lima',
    'distrito' => 'Miraflores',
    'direccion' => 'Av. Larco 123',
    'telefono_contacto' => '987654321',
    'metodo_pago' => 'Culqi',
    'costo_envio' => 15.00,
    'subtotal' => 100.00,
    'total' => 115.00,
    'status' => 'pagado'
]);

// Add items if possible
$product = Product::first();
if ($product) {
    PedidoItem::create([
        'pedido_id' => $pedidoLima->id,
        'product_id' => $product->id,
        'provider_id' => $product->provider_id,
        'cantidad' => 1,
        'precio_unitario' => 100.00,
        'subtotal' => 100.00
    ]);
}

// Order 2: PROVINCIA
$pedidoProv = Pedido::create([
    'customer_id' => $customer->id,
    'tipo_envio' => 'Provincia',
    'distrito' => 'Cusco',
    'direccion' => 'Calle Real 456',
    'telefono_contacto' => '912345678',
    'metodo_pago' => 'Culqi',
    'clave_secreta' => '1234',
    'costo_envio' => 25.00,
    'subtotal' => 200.00,
    'total' => 225.00,
    'status' => 'pagado'
]);

if ($product) {
    PedidoItem::create([
        'pedido_id' => $pedidoProv->id,
        'product_id' => $product->id,
        'provider_id' => $product->provider_id,
        'cantidad' => 2,
        'precio_unitario' => 100.00,
        'subtotal' => 200.00
    ]);
}

echo "Created Pedido Lima: #{$pedidoLima->id}\n";
echo "Created Pedido Provincia: #{$pedidoProv->id}\n";
