@extends('emails.layout', ['title' => 'Resolución de Reclamo - ' . $incidencia->codigo, 'badge' => $estadoLabel])

@section('content')
    <h2 style="margin:0 0 16px 0;font-size:20px;color:#ffffff;font-weight:700;">
        Actualización de tu Reclamo [{{ $incidencia->codigo }}]
    </h2>

    <p style="margin:0 0 18px 0;">
        Estimado(a) <strong>{{ $incidencia->customer?->name ?? 'Cliente' }}</strong>, nuestro equipo de soporte ha completado la auditoría de tu caso para el <strong>Pedido #{{ $incidencia->pedido_id }}</strong>:
    </p>

    <!-- Status badge box -->
    <div style="background-color:#131a29;border:1px solid #243047;border-radius:10px;padding:18px;margin:20px 0;">
        <span style="font-size:11px;text-transform:uppercase;color:#94a3b8;font-weight:bold;display:block;margin-bottom:6px;">
            Estado del Reclamo
        </span>
        <div style="font-size:18px;font-weight:bold;color:{{ $estadoColor }};margin-bottom:12px;">
            {{ $estadoLabel }}
        </div>

        @if(!empty($notaCliente))
        <div style="background:#0f1420;border-left:4px solid {{ $estadoColor }};padding:14px;border-radius:0 6px 6px 0;">
            <span style="font-size:11px;text-transform:uppercase;color:#94a3b8;font-weight:bold;display:block;margin-bottom:6px;">
                Resolución / Respuesta del Gestor:
            </span>
            <div style="color:#ffffff;font-size:14px;line-height:1.6;">
                {!! nl2br(e($notaCliente)) !!}
            </div>
        </div>
        @endif
    </div>

    <p style="margin:16px 0 0 0;font-size:14px;color:#cbd5e1;line-height:1.6;">
        Si tienes alguna duda o necesitas coordinar la entrega o devolución, puedes responder directamente a este correo o escribir a <a href="mailto:soporte@repuestofijo.com" style="color:#BE3C3B;text-decoration:none;font-weight:bold;">soporte@repuestofijo.com</a>.
    </p>
@endsection
