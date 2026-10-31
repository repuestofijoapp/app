<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'RepuestoFijo' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#0d111a;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#e2e8f0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#0d111a;padding:30px 10px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;background-color:#161d2d;border-radius:14px;border:1px solid #232d42;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.5);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding:28px 32px;background:linear-gradient(135deg, #131826 0%, #1c2438 100%);border-bottom:1px solid #26334d;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <div style="display:inline-block;">
                                            <span style="font-size:24px;font-weight:900;letter-spacing:-0.5px;color:#ffffff;text-transform:uppercase;">
                                                REPUESTO<span style="color:#BE3C3B;">FIJO</span>
                                            </span>
                                            <span style="display:block;font-size:11px;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;margin-top:2px;">
                                                Repuestos automotrices al instante
                                            </span>
                                        </div>
                                    </td>
                                    <td align="right">
                                        <span style="display:inline-block;padding:6px 12px;background:rgba(190,60,59,0.15);border:1px solid rgba(190,60,59,0.3);border-radius:6px;font-size:12px;font-weight:700;color:#f87171;">
                                            {{ $badge ?? 'Notificación' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding:32px 32px 24px 32px;line-height:1.6;font-size:15px;color:#cbd5e1;">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Need Help / Support Banner -->
                    <tr>
                        <td style="padding:0 32px 28px 32px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#111622;border:1px solid #1e283d;border-radius:10px;padding:16px;">
                                <tr>
                                    <td style="font-size:13px;color:#94a3b8;">
                                        ¿Dudas o consultas sobre tu pedido? Escríbenos a 
                                        <a href="mailto:soporte@repuestofijo.com" style="color:#BE3C3B;text-decoration:none;font-weight:bold;">soporte@repuestofijo.com</a>
                                        o comunícate a través de WhatsApp.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:24px 32px;background-color:#0f1420;border-top:1px solid #1e283d;text-align:center;font-size:12px;color:#64748b;">
                            <p style="margin:0 0 8px 0;font-weight:600;color:#94a3b8;">
                                RepuestoFijo &mdash; Lima, Perú
                            </p>
                            <p style="margin:0;line-height:1.4;">
                                Este es un mensaje transaccional automático enviado a tu correo registrado.<br>
                                Conforme a la Ley N° 29571 &bull; Código de Protección y Defensa del Consumidor.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
