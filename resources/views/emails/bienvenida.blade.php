@extends('emails.layout', ['title' => '¡Bienvenido a RepuestoFijo!', 'badge' => 'Cuenta Activa'])

@section('content')
    <h2 style="margin:0 0 16px 0;font-size:20px;color:#ffffff;font-weight:700;">
        ¡Hola, {{ $user->name }}! 👋
    </h2>

    <p style="margin:0 0 18px 0;">
        Tu perfil ha sido completado con éxito y tu cuenta en <strong>RepuestoFijo</strong> ya está totalmente activa.
    </p>

    <!-- Info Box -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#131a29;border:1px solid #243047;border-radius:10px;margin:20px 0;padding:18px;">
        <tr>
            <td>
                <span style="font-size:11px;text-transform:uppercase;color:#94a3b8;font-weight:bold;letter-spacing:0.5px;display:block;margin-bottom:6px;">
                    Datos de tu cuenta
                </span>
                <p style="margin:0 0 6px 0;color:#ffffff;font-size:14px;">
                    <strong>Tipo de cuenta:</strong> {{ ucfirst($user->role->value ?? 'Cliente') }}
                </p>
                <p style="margin:0 0 6px 0;color:#ffffff;font-size:14px;">
                    <strong>Documento / RUC:</strong> {{ $user->ruc_dni ?? 'No registrado' }}
                </p>
                @if($user->business_name)
                <p style="margin:0;color:#ffffff;font-size:14px;">
                    <strong>Razón Social / Negocio:</strong> {{ $user->business_name }}
                </p>
                @endif
            </td>
        </tr>
    </table>

    <h3 style="font-size:16px;color:#ffffff;margin:24px 0 12px 0;">Beneficios de tu cuenta:</h3>
    <ul style="margin:0 0 24px 0;padding-left:20px;color:#cbd5e1;line-height:1.8;">
        <li>Acceso a miles de repuestos originales y alternativos garantizados.</li>
        <li>Trazabilidad logística en tiempo real (4 hitos con foto y GPS).</li>
        <li>Precios y stock sincronizados al instante.</li>
        <li>Garantía de compra y canal de resolución de problemas en el día.</li>
    </ul>

    <!-- CTA Button -->
    <div style="text-align:center;margin:30px 0;">
        <a href="{{ route('home') }}" style="display:inline-block;padding:14px 28px;background:linear-gradient(135deg, #BE3C3B 0%, #9e2a29 100%);color:#ffffff;text-decoration:none;font-weight:bold;font-size:14px;border-radius:8px;box-shadow:0 4px 14px rgba(190,60,59,0.4);">
            Explorar Catálogo de Repuestos &rarr;
        </a>
    </div>
@endsection
