@extends('emails.layout', ['title' => 'Pago Confirmado - Pedido #' . $pedido->id, 'badge' => 'Pago Exitoso'])

@section('content')
    <h2 style="margin:0 0 16px 0;font-size:20px;color:#ffffff;font-weight:700;">
        ¡Gracias por tu compra, {{ $pedido->customer?->name ?? 'Cliente' }}! 🚗
    </h2>

    <p style="margin:0 0 18px 0;">
        Hemos confirmado tu pago mediante <strong>{{ $pedido->metodo_pago ?? 'Culqi' }}</strong>. Tu orden <strong>#{{ $pedido->id }}</strong> ya ha ingresado a nuestra central logística para su preparación y recojo.
    </p>

    <!-- Order summary -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#131a29;border:1px solid #243047;border-radius:10px;margin:20px 0;overflow:hidden;">
        <tr>
            <td style="padding:14px 18px;background-color:#1a2336;border-bottom:1px solid #243047;font-weight:bold;color:#ffffff;font-size:14px;">
                Resumen del Pedido #{{ $pedido->id }}
            </td>
        </tr>
        <tr>
            <td style="padding:14px 18px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    @foreach($pedido->items as $item)
                        <tr>
                            <td style="padding:8px 0;color:#e2e8f0;font-size:14px;border-bottom:1px solid #1e283d;">
                                <strong>{{ $item->quantity }}x</strong> {{ $item->product?->name ?? 'Repuesto automotriz' }}
                                @if($item->product?->sku)
                                    <span style="display:block;font-size:12px;color:#94a3b8;">SKU: {{ $item->product->sku }}</span>
                                @endif
                            </td>
                            <td align="right" style="padding:8px 0;color:#ffffff;font-weight:600;font-size:14px;border-bottom:1px solid #1e283d;">
                                S/ {{ number_format($item->price * $item->quantity, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="padding:10px 0 4px 0;color:#94a3b8;font-size:13px;">Costo de envío:</td>
                        <td align="right" style="padding:10px 0 4px 0;color:#cbd5e1;font-size:13px;">
                            S/ {{ number_format($pedido->costo_envio ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;color:#ffffff;font-weight:bold;font-size:16px;">Total Pagado:</td>
                        <td align="right" style="padding:8px 0;color:#BE3C3B;font-weight:900;font-size:18px;">
                            S/ {{ number_format($pedido->total ?? ($pedido->items->sum(fn($i) => $i->price * $i->quantity) + ($pedido->costo_envio ?? 0)), 2) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Delivery Address -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#131a29;border:1px solid #243047;border-radius:10px;margin:20px 0;padding:16px;">
        <tr>
            <td>
                <span style="font-size:11px;text-transform:uppercase;color:#94a3b8;font-weight:bold;display:block;margin-bottom:6px;">
                    Dirección de Entrega
                </span>
                <p style="margin:0;color:#ffffff;font-size:14px;">
                    {{ $pedido->direccion }}
                    @if($pedido->distrito) &bull; {{ $pedido->distrito }} @endif
                    @if($pedido->departamento) &bull; {{ $pedido->departamento }} @endif
                </p>
                @if($pedido->referencia)
                    <p style="margin:6px 0 0 0;font-size:12px;color:#94a3b8;">
                        <em>Referencia: {{ $pedido->referencia }}</em>
                    </p>
                @endif
            </td>
        </tr>
    </table>

    <p style="margin:16px 0;font-size:14px;color:#94a3b8;">
        Te notificaremos en cuanto nuestro motorizado recoja el paquete y se encuentre en camino a tu dirección.
    </p>
@endsection
