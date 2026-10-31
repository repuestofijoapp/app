@extends('emails.layout', ['title' => 'Tu pedido está en camino - Pedido #' . $pedido->id, 'badge' => 'En Camino'])

@section('content')
    <h2 style="margin:0 0 16px 0;font-size:20px;color:#ffffff;font-weight:700;">
        ¡Tu pedido #{{ $pedido->id }} ya salió del Hub! 🛵💨
    </h2>

    <p style="margin:0 0 18px 0;">
        Hola, <strong>{{ $pedido->customer?->name ?? 'Cliente' }}</strong>. Te informamos que tu paquete ya fue verificado, embalado y entregado al motorizado de confianza.
    </p>

    <!-- Info Box -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#131a29;border:1px solid #243047;border-radius:10px;margin:20px 0;padding:18px;">
        <tr>
            <td>
                <span style="font-size:11px;text-transform:uppercase;color:#f59e0b;font-weight:bold;display:block;margin-bottom:8px;">
                    Hito 3: Despacho por motorizado confirmado
                </span>
                <p style="margin:0 0 6px 0;color:#ffffff;font-size:14px;">
                    <strong>Destino:</strong> {{ $pedido->direccion }} ({{ $pedido->distrito }})
                </p>
                <p style="margin:0;color:#94a3b8;font-size:13px;">
                    Por favor mantén activo tu teléfono de contacto: <strong>{{ $pedido->telefono_contacto ?? $pedido->customer?->phone }}</strong>.
                </p>
            </td>
        </tr>
    </table>

    <div style="background:rgba(245,158,11,0.1);border-left:4px solid #f59e0b;padding:12px 16px;border-radius:4px;margin:20px 0;font-size:13px;color:#fde68a;">
        <strong>Importante:</strong> El motorizado solicitará tu confirmación y registrará una fotografía de entrega al momento de entregarte el repuesto.
    </div>
@endsection
