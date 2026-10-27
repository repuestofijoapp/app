<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>🛵 RepuestoFijo PoD · Pedido #{{ $pedido->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #0b0f19;
            --surface: #131b2e;
            --surface2: #1e293b;
            --border: rgba(255, 255, 255, 0.08);
            --red: #ef4444;
            --red-glow: rgba(239, 68, 68, 0.35);
            --emerald: #10b981;
            --emerald-glow: rgba(16, 185, 129, 0.3);
            --amber: #f59e0b;
            --text: #f8fafc;
            --muted: #94a3b8;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            padding-bottom: 40px;
        }

        /* ── HEADER FIJO (STICKY) ────────────────────────── */
        .top-bar {
            background: rgba(19, 27, 46, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }
        .top-bar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .top-bar-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--red);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .top-bar-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
        }
        .top-bar-sub {
            font-size: 0.68rem;
            color: var(--muted);
            font-weight: 500;
        }
        .top-bar-badge {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            padding: 5px 12px;
            border-radius: 99px;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        /* ── TARJETAS ────────────────────────────────────── */
        .pod-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 14px;
        }

        .card-label {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── BOTONES DE CONTACTO DIRECTO ─────────────────── */
        .contact-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-call {
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.35);
            color: #60a5fa;
        }
        .btn-call:hover, .btn-call:active {
            background: #3b82f6;
            color: #fff;
        }
        .btn-whatsapp {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #34d399;
        }
        .btn-whatsapp:hover, .btn-whatsapp:active {
            background: #10b981;
            color: #fff;
        }

        /* ── BOTON CAMARA Y CAPTURA ──────────────────────── */
        .btn-camera {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border: 2px dashed rgba(239, 68, 68, 0.5);
            border-radius: 16px;
            padding: 22px 16px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #fff;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-camera:active {
            transform: scale(0.98);
            border-color: var(--red);
            background: rgba(239, 68, 68, 0.08);
        }
        .btn-camera-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.18);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: var(--red);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        /* ── PREVIEW DE FOTO ─────────────────────────────── */
        .photo-preview-box {
            position: relative;
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid rgba(16, 185, 129, 0.4);
            margin-bottom: 12px;
            background: #000;
        }
        .photo-preview-img {
            width: 100%;
            max-height: 280px;
            object-fit: cover;
            display: block;
        }
        .photo-timestamp-badge {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #34d399;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── BOTON SUBMIT ────────────────────────────────── */
        .btn-submit-action {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            border: none;
            padding: 16px 20px;
            border-radius: 14px;
            font-weight: 800;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.05rem;
            box-shadow: 0 8px 24px var(--red-glow);
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-submit-action:active {
            transform: scale(0.98);
        }
        .btn-submit-action:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* ── INPUTS Y TEXTAREA ───────────────────────────── */
        .custom-textarea {
            background: var(--surface2) !important;
            border: 1px solid var(--border) !important;
            color: #fff !important;
            border-radius: 12px !important;
            padding: 10px 12px !important;
            font-size: 0.88rem !important;
            resize: none;
        }
        .custom-textarea:focus {
            border-color: rgba(239, 68, 68, 0.5) !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
        }
        .custom-textarea::placeholder {
            color: #64748b;
        }
    </style>
</head>
<body>

    {{-- HEADER FIJO (STICKY) --}}
    <header class="top-bar">
        <div class="top-bar-brand">
            <span class="top-bar-icon">
                <i class="fas fa-motorcycle"></i>
            </span>
            <div>
                <div class="top-bar-title">RepuestoFijo PoD</div>
                <div class="top-bar-sub">Control de Entrega y Trazabilidad</div>
            </div>
        </div>
        <span class="top-bar-badge">
            Pedido #{{ $pedido->id }}
        </span>
    </header>

    <main class="container py-3" style="max-width: 520px;">

        {{-- ALERTA DE EXITO --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-3 rounded-4 mb-3 p-3" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.35); color: #34d399;">
                <i class="fas fa-circle-check fs-4 flex-shrink-0"></i>
                <div style="font-size: 0.88rem; font-weight: 600;">{{ session('success') }}</div>
            </div>
        @endif

        {{-- ALERTA DE BLOQUEO POR GEOCERCA / UBICACION FUERA DE RANGO --}}
        @if(session('error_geocerca'))
            <div class="alert alert-danger d-flex align-items-start gap-3 rounded-4 mb-3 p-3" style="background: rgba(239, 68, 68, 0.15); border: 1.5px solid rgba(239, 68, 68, 0.45); color: #fca5a5;">
                <i class="fas fa-triangle-exclamation fs-3 flex-shrink-0 text-danger mt-1"></i>
                <div style="font-size: 0.88rem; line-height: 1.45;">
                    <strong class="text-white d-block mb-1 fs-6">⛔ Entrega Bloqueada por Ubicación</strong>
                    {{ session('error_geocerca') }}

                    @if(app()->environment('local'))
                        <div class="mt-3 pt-2 border-top border-white border-opacity-10">
                            <span class="badge bg-warning text-dark mb-1" style="font-size: 0.72rem;">🛠️ Modo Pruebas Dev</span>
                            <div class="small text-white-50 mb-2" style="font-size: 0.76rem;">Detectada prueba local fuera de Lima:</div>
                            <button type="button" class="btn btn-sm btn-outline-warning py-1 px-3 rounded-3 fw-bold" onclick="simularDestinoLocal()">
                                <i class="fas fa-location-crosshairs me-1"></i> Simular GPS en Destino (Para esta prueba)
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- TARJETA: DESTINO DE ENTREGA (DATOS DEL CLIENTE) --}}
        <div class="pod-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="card-label">
                    <i class="fas fa-location-dot text-danger"></i> Destino de Entrega
                </span>
                <span class="badge bg-danger bg-opacity-25 text-danger px-2 py-1" style="font-size: 0.72rem; font-weight: 800;">
                    {{ strtoupper($pedido->tipo_envio ?? 'ENVÍO EXPRESS') }}
                </span>
            </div>

            {{-- Nombre del destinatario --}}
            <h4 class="fw-bold text-white mb-2" style="font-size: 1.25rem; letter-spacing: -0.3px;">
                {{ $pedido->customer?->name ?? 'Cliente Destinatario' }}
            </h4>

            {{-- Botones de llamada y WhatsApp para el motorizado --}}
            @if($pedido->telefono_contacto)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $pedido->telefono_contacto);
                    if (strlen($cleanPhone) === 9 && !str_starts_with($cleanPhone, '51')) {
                        $cleanPhone = '51' . $cleanPhone;
                    }
                @endphp
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <a href="tel:{{ $pedido->telefono_contacto }}" class="contact-action-btn btn-call">
                        <i class="fas fa-phone-flip"></i> Llamar ({{ $pedido->telefono_contacto }})
                    </a>
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('¡Hola! Soy el motorizado de RepuestoFijo. Voy en camino con su pedido #' . $pedido->id) }}" target="_blank" class="contact-action-btn btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
            @endif

            {{-- Dirección completa --}}
            <div class="p-3 rounded-3" style="background: var(--surface2); border: 1px solid rgba(255, 255, 255, 0.05);">
                <div class="text-white mb-1" style="font-size: 0.92rem; line-height: 1.45;">
                    <strong class="text-white text-uppercase me-1">{{ $pedido->distrito }}:</strong>
                    {{ $pedido->direccion }}
                </div>

                {{-- Instrucciones especiales para el motorizado --}}
                @if($pedido->referencia)
                    <div class="mt-2 p-2 rounded-2" style="background: rgba(245, 158, 11, 0.12); border: 1px dashed rgba(245, 158, 11, 0.4); color: #fbbf24; font-size: 0.82rem;">
                        <i class="fas fa-comment-dots me-1 text-warning"></i>
                        <strong>Instrucciones motorizado:</strong> {{ $pedido->referencia }}
                    </div>
                @endif
            </div>

            {{-- Botón para abrir navegación en Google Maps --}}
            @php
                $mapUrl = ($pedido->lat && $pedido->lng)
                    ? "https://maps.google.com/?q={$pedido->lat},{$pedido->lng}"
                    : "https://maps.google.com/?q=" . urlencode("{$pedido->direccion}, {$pedido->distrito}, Lima, Peru");
            @endphp
            <div class="mt-3 text-end">
                <a href="{{ $mapUrl }}" target="_blank" class="btn btn-sm btn-outline-info text-decoration-none py-2 px-3 fw-bold d-inline-flex align-items-center gap-2" style="font-size: 0.8rem; border-radius: 10px; border-color: rgba(56, 189, 248, 0.4); color: #38bdf8;">
                    <i class="fas fa-map-location-dot"></i> Navegar en Google Maps
                </a>
            </div>
        </div>

        {{-- FORMULARIO DE ACCION: TOMA DE FOTO Y CONFIRMACION --}}
        @if($siguienteHito !== 'completado')
            @php
                $esEntregaCliente = ($siguienteHito === 'entrega_cliente');
                $pasoNum = $esEntregaCliente ? '4 de 4' : '3 de 4';
                $pasoTitulo = $esEntregaCliente ? 'Entrega en Destino (Cliente)' : 'Recepción de Paquete en Hub';
                $btnTitulo = $esEntregaCliente ? 'Confirmar Entrega al Cliente' : 'Confirmar Recepción de Paquete';
                $btnColor = $esEntregaCliente ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                $btnShadow = $esEntregaCliente ? '0 8px 24px rgba(16, 185, 129, 0.35)' : '0 8px 24px rgba(239, 68, 68, 0.35)';
            @endphp

            <div class="pod-card" style="border-color: {{ $esEntregaCliente ? 'rgba(16, 185, 129, 0.4)' : 'rgba(239, 68, 68, 0.4)' }}; background: linear-gradient(180deg, {{ $esEntregaCliente ? 'rgba(16, 185, 129, 0.08)' : 'rgba(239, 68, 68, 0.08)' }} 0%, var(--surface) 100%);">
                
                {{-- Encabezado del paso actual --}}
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-white border-opacity-10">
                    <span class="badge {{ $esEntregaCliente ? 'bg-success' : 'bg-danger' }} px-2 py-1" style="font-size: 0.75rem; font-weight: 800;">
                        Paso {{ $pasoNum }}
                    </span>
                    <span class="text-white fw-bold" style="font-size: 0.92rem;">
                        {{ $pasoTitulo }}
                    </span>
                </div>

                <form action="{{ route('pod.store', $pedido->delivery_token) }}" method="POST" enctype="multipart/form-data" id="podDeliveryForm">
                    @csrf
                    {{-- Datos ocultos: paso, nombre y GPS silencioso en background --}}
                    <input type="hidden" name="tipo_hito" value="{{ $siguienteHito }}">
                    <input type="hidden" name="operador_nombre" value="{{ auth()->user()?->name ?? 'Motorizado' }}">
                    <input type="hidden" name="lat" id="gps_lat" value="">
                    <input type="hidden" name="lng" id="gps_lng" value="">

                    {{-- Caja de preview de foto (se activa al tomar la foto) --}}
                    <div id="photoPreviewContainer" style="display: none;" class="photo-preview-box">
                        <img id="photoPreviewImg" class="photo-preview-img" src="" alt="Evidencia">
                        <div class="photo-timestamp-badge" id="photoTimestampBadge">
                            <i class="fas fa-clock"></i> <span id="photoTimeText">Capturada</span>
                        </div>
                    </div>

                    {{-- Botón para tomar foto con cámara nativa --}}
                    <div class="mb-3">
                        <input type="file" name="foto" id="fotoInput" accept="image/*" capture="environment" style="display:none;" required>
                        <button type="button" class="btn-camera" id="btnTriggerCamera" onclick="document.getElementById('fotoInput').click()">
                            <div class="btn-camera-icon" id="cameraIconBox">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="fw-bold" style="font-size: 1rem;" id="btnCameraText">
                                {{ $esEntregaCliente ? '📸 Tomar Foto de Entrega al Cliente' : '📸 Tomar Foto del Paquete Recibido' }}
                            </div>
                            <small class="text-white-50" style="font-size: 0.74rem;" id="btnCameraSub">
                                Se abrirá la cámara de tu móvil al presionar
                            </small>
                        </button>
                    </div>

                    {{-- Observaciones de entrega --}}
                    <div class="mb-3">
                        <label class="card-label mb-2">
                            <i class="fas fa-pen text-secondary"></i> Observaciones (Opcional)
                        </label>
                        <textarea name="observaciones" rows="2" class="form-control custom-textarea" placeholder="Ej: Recibió en portería, timbre averiado, entregado al titular..."></textarea>
                    </div>

                    {{-- Botón principal para confirmar --}}
                    <button type="submit" class="btn-submit-action" id="submitBtn" style="background: {{ $btnColor }}; box-shadow: {{ $btnShadow }};">
                        <i class="fas {{ $esEntregaCliente ? 'fa-circle-check' : 'fa-box' }}"></i>
                        <span>{{ $btnTitulo }}</span>
                    </button>
                </form>
            </div>

        @else

            {{-- VISTA CUANDO EL PEDIDO YA FUE COMPLETADO --}}
            <div class="pod-card text-center py-5" style="border-color: rgba(16, 185, 129, 0.4); background: rgba(16, 185, 129, 0.08);">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid rgba(16, 185, 129, 0.4);">
                    <i class="fas fa-check-double fa-2x"></i>
                </div>
                <h4 class="fw-bold text-white mb-2">¡Pedido Entregado con Éxito!</h4>
                <p class="text-white-50 small mb-0 px-3">
                    La entrega ha sido completada y la evidencia fotográfica fue auditada satisfactoriamente en el sistema.
                </p>
            </div>

        @endif

        {{-- PIE DE PAGINA --}}
        <div class="text-center text-white-50 extra-small mt-4" style="font-size: 0.72rem;">
            RepuestoFijo · Sistema Logístico Seguro PoD · {{ date('Y') }}
        </div>

    </main>

    <script>
        // Captura silenciosa de GPS en segundo plano (dato oculto)
        function captureSilentGPS() {
            if (!navigator.geolocation) return;
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    const latEl = document.getElementById('gps_lat');
                    const lngEl = document.getElementById('gps_lng');
                    if (latEl) latEl.value = pos.coords.latitude;
                    if (lngEl) lngEl.value = pos.coords.longitude;
                },
                function(err) {
                    console.log('GPS no disponible o no permitido.');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        }

        @if(app()->environment('local'))
        function simularDestinoLocal() {
            const targetLat = {{ $pedido->lat ?? -12.0464 }};
            const targetLng = {{ $pedido->lng ?? -77.0428 }};
            const latEl = document.getElementById('gps_lat');
            const lngEl = document.getElementById('gps_lng');
            if (latEl) latEl.value = targetLat;
            if (lngEl) lngEl.value = targetLng;

            let bypassInput = document.getElementById('bypass_test_geocerca');
            if (!bypassInput) {
                bypassInput = document.createElement('input');
                bypassInput.type = 'hidden';
                bypassInput.id = 'bypass_test_geocerca';
                bypassInput.name = 'bypass_test_geocerca';
                bypassInput.value = '1';
                document.getElementById('podDeliveryForm')?.appendChild(bypassInput);
            }
            alert('📍 Coordenadas de prueba asignadas a la ubicación de entrega (' + targetLat + ', ' + targetLng + '). Ahora toma la foto y confirma.');
        }
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            // Iniciar captura silenciosa de GPS
            captureSilentGPS();

            // Manejo de la foto y preview en tiempo real
            const fotoInput = document.getElementById('fotoInput');
            const previewContainer = document.getElementById('photoPreviewContainer');
            const previewImg = document.getElementById('photoPreviewImg');
            const timeText = document.getElementById('photoTimeText');
            const btnCameraText = document.getElementById('btnCameraText');
            const btnCameraSub = document.getElementById('btnCameraSub');
            const cameraIconBox = document.getElementById('cameraIconBox');

            if (fotoInput) {
                fotoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            previewImg.src = evt.target.result;
                            previewContainer.style.display = 'block';

                            // Fecha y hora exacta de captura
                            const now = new Date();
                            const timeStr = now.toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' }) + ' ' + 
                                            now.toLocaleTimeString('es-PE', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                            
                            timeText.textContent = 'Foto tomada: ' + timeStr;

                            // Actualizar botón para permitir retoma
                            btnCameraText.textContent = '🔄 Cambiar o Tomar Otra Foto';
                            btnCameraSub.textContent = 'Foto lista para enviar';
                            if (cameraIconBox) {
                                cameraIconBox.style.background = 'rgba(16, 185, 129, 0.2)';
                                cameraIconBox.style.borderColor = 'rgba(16, 185, 129, 0.5)';
                                cameraIconBox.style.color = '#10b981';
                                cameraIconBox.innerHTML = '<i class="fas fa-check"></i>';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Evitar envíos dobles y mostrar animación de carga
            const form = document.getElementById('podDeliveryForm');
            if (form) {
                form.addEventListener('submit', function() {
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando evidencia de entrega...';
                    }
                });
            }
        });
    </script>
</body>
</html>
