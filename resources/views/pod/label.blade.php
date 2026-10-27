<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Etiqueta Logística QR — Pedido #{{ $pedido->id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, sans-serif;
        }
        body {
            background: #f1f5f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .label-container {
            width: 100mm;
            min-height: 140mm;
            background: #fff;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 12px;
            color: #000;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .brand {
            font-size: 16px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }
        .order-pill {
            background: #000;
            color: #fff;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 800;
            font-size: 14px;
        }
        .qr-section {
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1.5px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .qr-img {
            width: 110px;
            height: 110px;
        }
        .destinatario-title {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 800;
            color: #64748b;
            margin-bottom: 2px;
        }
        .destinatario-name {
            font-size: 15px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 4px;
        }
        .destinatario-address {
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
        }
        .footer-note {
            font-size: 9px;
            text-align: center;
            color: #475569;
            font-weight: 600;
            border-top: 1px solid #000;
            padding-top: 6px;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .label-container {
                border: none;
                width: 100%;
                height: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div>
        <div class="no-print" style="margin-bottom: 16px; text-align: center;">
            <button onclick="doPrint()" style="padding: 10px 24px; background: #000; color: #fff; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; font-size: 14px;">
                🖨️ Imprimir Etiqueta Logística
            </button>
        </div>

        <div class="label-container">
            <div>
                {{-- Header --}}
                <div class="header">
                    <div class="brand">REPUESTO FIJO · HUB CENTRAL</div>
                    <div class="order-pill">#{{ $pedido->id }}</div>
                </div>

                {{-- QR y Destino --}}
                <div class="qr-section">
                    <img src="{{ $qrUrl }}" class="qr-img" alt="QR PoD">
                    <div style="flex: 1;">
                        <div class="destinatario-title">DESTINATARIO:</div>
                        <div class="destinatario-name">{{ $pedido->customer?->name ?? 'Cliente' }}</div>
                        <div class="destinatario-address">
                            @if($pedido->distrito)
                                <strong>{{ $pedido->distrito }}:</strong>
                            @endif
                            {{ $pedido->direccion }}
                        </div>
                        <div style="font-size: 11px; margin-top: 4px; font-weight: 700;">
                            📞 {{ $pedido->telefono_contacto ?: ($pedido->customer?->phone ?: 'No registrado') }}
                        </div>
                    </div>
                </div>

                {{-- Instrucciones de Entrega --}}
                <div style="margin-bottom: 12px; padding: 8px 10px; background: #f8fafc; border: 1px dashed #000; border-radius: 6px;">
                    <div style="font-size: 9px; font-weight: 800; text-transform: uppercase; color: #000; letter-spacing: 0.5px;">
                        📝 Instrucciones de Entrega:
                    </div>
                    <div style="font-size: 11px; font-weight: 600; color: #000; margin-top: 2px; line-height: 1.3;">
                        {{ $pedido->referencia ?: 'Sin instrucciones o referencias adicionales.' }}
                    </div>
                </div>


            </div>

            {{-- Footer instructions --}}
            <div>
                <div class="footer-note">
                    ESCANEADO OBLIGATORIO: El motorizado debe escanear este QR con la cámara de su celular al recibir el paquete y al entregarlo en destino.
                </div>
                <div style="text-align: right; font-size: 8px; color: #94a3b8; margin-top: 4px;">
                    Generado: {{ now()->format('d/m/Y H:i') }} · Hub Lima
                </div>
            </div>
        </div>
    </div>

    <script>
        function doPrint() {
            try {
                localStorage.setItem('operario_label_printed_{{ $pedido->id }}', 'true');
            } catch(e) {}
            window.print();
        }
        window.addEventListener('afterprint', () => {
            try {
                localStorage.setItem('operario_label_printed_{{ $pedido->id }}', 'true');
            } catch(e) {}
        });
    </script>
</body>
</html>
