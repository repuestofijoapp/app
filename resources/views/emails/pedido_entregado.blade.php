@extends('emails.layout', ['title' => 'Pedido Entregado - Pedido #' . $pedido->id, 'badge' => 'Entregado'])

@section('content')
    <h2 style="margin:0 0 16px 0;font-size:20px;color:#ffffff;font-weight:700;">
        ¡Pedido entregado con éxito! ✅
    </h2>

    <p style="margin:0 0 18px 0;">
        Hola, <strong>{{ $pedido->customer?->name ?? 'Cliente' }}</strong>. Tu pedido <strong>#{{ $pedido->id }}</strong> ha sido entregado en la dirección acordada.
    </p>

    <!-- Warranty notice box -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#131a29;border:1px solid #243047;border-radius:10px;margin:20px 0;padding:18px;">
        <tr>
            <td>
                <span style="font-size:11px;text-transform:uppercase;color:#10b981;font-weight:bold;display:block;margin-bottom:8px;">
                    🛡️ Garantía de Satisfacción RepuestoFijo
                </span>
                <p style="margin:0 0 10px 0;color:#ffffff;font-size:14px;line-height:1.5;">
                    Cuentas con <strong>48 horas</strong> a partir de este momento para verificar que tu repuesto esté en óptimas condiciones y sea exactamente compatible con tu vehículo.
                </p>
                <p style="margin:0;font-size:13px;color:#94a3b8;line-height:1.5;">
                    Si notas algún daño de fábrica, incompatibilidad o faltante, puedes reportarlo al instante ingresando a <strong>Mis Pedidos &rarr; «Reportar un problema»</strong> y lo atenderemos el mismo día hábil.
                </p>
            </td>
        </tr>
    </table>

    <div style="text-align:center;margin:28px 0;">
        <a href="{{ route('home') }}" style="display:inline-block;padding:12px 24px;background:#243047;border:1px solid #3b4c6b;color:#ffffff;text-decoration:none;font-weight:bold;font-size:14px;border-radius:8px;">
            Ver mis pedidos &rarr;
        </a>
    </div>

    <p style="margin:0;font-size:13px;color:#64748b;text-align:center;">
        ¡Gracias por confiar en RepuestoFijo!
    </p>
@endsection
