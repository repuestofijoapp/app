@extends('emails.layout', ['title' => 'Pago Confirmado - Pedido #' . $pedido->id])

@section('content')
    <h2 style="margin:0 0 16px 0;font-size:20px;color:#ffffff;font-weight:700;">
        ¡Gracias por tu compra, {{ $pedido->customer?->name ?? 'Cliente' }}!
    </h2>

    <p style="margin:0 0 18px 0;">
        Hemos confirmado tu pago. Tu pedido <strong>#{{ $pedido->id }}</strong> ya ha ingresado a nuestra central logística para su preparación y recojo.
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
                        @php
                            $qty = $item->cantidad ?? $item->quantity ?? 1;
                            $precioUnit = $item->precio_unitario ?? $item->price ?? 0;
                            $sub = $item->subtotal ?? ($precioUnit * $qty);
                            $product = $item->product;
                            $imgSrc = $product?->image_url ?? ($product?->image_path ? asset('storage/' . $product->image_path) : 'https://app.repuestofijo.com/images/logo.png');
                            $codigo = $product?->supplier_code ?? $product?->oem_code ?? $product?->sku ?? '';
                        @endphp
                        <tr>
                            <!-- Product Image -->
                            <td width="54" valign="top" style="padding:10px 12px 10px 0;border-bottom:1px solid #1e283d;">
                                <div style="width:48px;height:48px;background-color:#0f1420;border:1px solid #232d42;border-radius:6px;overflow:hidden;text-align:center;">
                                    <img src="{{ $imgSrc }}" alt="Repuesto" style="width:48px;height:48px;object-fit:cover;display:block;" onerror="this.src='https://app.repuestofijo.com/images/logo.png';" />
                                </div>
                            </td>

                            <!-- Product Data (Code + small description) -->
                            <td valign="top" style="padding:10px 8px 10px 0;border-bottom:1px solid #1e283d;">
                                <div style="color:#ffffff;font-weight:700;font-size:13px;margin-bottom:3px;">
                                    {{ $qty }}x 
                                    @if($codigo)
                                        <span style="color:#f87171;font-family:monospace;">[{{ $codigo }}]</span>
                                    @endif
                                </div>
                                <div style="font-size:12px;color:#94a3b8;line-height:1.4;">
                                    {{ $product?->name ?? 'Repuesto automotriz' }}
                                    @if(!empty($item->oversize) && $item->oversize !== 'STD')
                                        <span style="color:#fbbf24;font-weight:600;">(Medida: {{ $item->oversize }})</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Price -->
                            <td width="90" align="right" valign="top" style="padding:10px 0;border-bottom:1px solid #1e283d;white-space:nowrap;">
                                <span style="color:#ffffff;font-weight:700;font-size:14px;">
                                    S/ {{ number_format($sub, 2) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach

                    <!-- Shipping Cost -->
                    <tr>
                        <td colspan="2" style="padding:12px 0 4px 0;color:#94a3b8;font-size:13px;">
                            Costo de envío:
                        </td>
                        <td align="right" style="padding:12px 0 4px 0;color:#cbd5e1;font-size:13px;white-space:nowrap;">
                            S/ {{ number_format($pedido->costo_envio ?? 0, 2) }}
                        </td>
                    </tr>

                    <!-- Total Paid (Same line, nowrap) -->
                    <tr>
                        <td colspan="2" style="padding:10px 0 0 0;color:#ffffff;font-weight:bold;font-size:15px;">
                            Total Pagado:
                        </td>
                        <td align="right" style="padding:10px 0 0 0;color:#BE3C3B;font-weight:900;font-size:17px;white-space:nowrap;">
                            S/ {{ number_format($pedido->total ?? ($pedido->subtotal + ($pedido->costo_envio ?? 0)), 2) }}
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
                <p style="margin:0;color:#ffffff;font-size:14px;line-height:1.4;">
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
