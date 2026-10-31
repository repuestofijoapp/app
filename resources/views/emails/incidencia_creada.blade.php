@extends('emails.layout', ['title' => 'Reclamo Registrado - ' . $incidencia->codigo, 'badge' => 'Soporte Abierto'])

@section('content')
    <h2 style="margin:0 0 16px 0;font-size:20px;color:#ffffff;font-weight:700;">
        Hemos recibido tu reclamo [{{ $incidencia->codigo }}] 📋
    </h2>

    <p style="margin:0 0 18px 0;">
        Hola, <strong>{{ $incidencia->customer?->name ?? 'Cliente' }}</strong>. Confirmamos que tu reporte para el <strong>Pedido #{{ $incidencia->pedido_id }}</strong> ha sido registrado en nuestro sistema de atención ágil.
    </p>

    <!-- Incident Summary -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#131a29;border:1px solid #243047;border-radius:10px;margin:20px 0;padding:18px;">
        <tr>
            <td>
                <span style="font-size:11px;text-transform:uppercase;color:#f87171;font-weight:bold;display:block;margin-bottom:8px;">
                    Detalle de la Incidencia
                </span>
                <p style="margin:0 0 6px 0;color:#ffffff;font-size:14px;">
                    <strong>Código:</strong> {{ $incidencia->codigo }}
                </p>
                <p style="margin:0 0 6px 0;color:#ffffff;font-size:14px;">
                    <strong>Motivo:</strong> {{ ucfirst(str_replace('_', ' ', $incidencia->tipo)) }}
                </p>
                @if($incidencia->submotivo)
                <p style="margin:0 0 6px 0;color:#ffffff;font-size:14px;">
                    <strong>Especificación:</strong> {{ ucfirst(str_replace('_', ' ', $incidencia->submotivo)) }}
                </p>
                @endif
                <p style="margin:0 0 6px 0;color:#ffffff;font-size:14px;">
                    <strong>Teléfono verificado:</strong> +{{ $incidencia->telefono_verificado }}
                </p>
                @if($incidencia->descripcion)
                <p style="margin:8px 0 0 0;padding-top:8px;border-top:1px solid #1e283d;color:#cbd5e1;font-size:13px;font-style:italic;">
                    &ldquo;{{ $incidencia->descripcion }}&rdquo;
                </p>
                @endif
            </td>
        </tr>
    </table>

    <!-- SLA commitment -->
    <div style="background:rgba(59,130,246,0.1);border-left:4px solid #3b82f6;padding:12px 16px;border-radius:4px;margin:20px 0;font-size:13px;color:#93c5fd;">
        <strong>SLA de Atención:</strong> Nuestro equipo logístico auditará la evidencia del despacho y te responderá en el <strong>mismo día hábil</strong> (objetivo interno &lt; 2 horas).
    </div>

    <p style="margin:16px 0 0 0;font-size:13px;color:#94a3b8;">
        Recibirás una notificación por este medio y vía WhatsApp en cuanto haya una actualización sobre tu caso.
    </p>
@endsection
