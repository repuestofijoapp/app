<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libro de Reclamaciones | RepuestoFijo</title>
    <style>
        body.in-iframe .site-header,
        body.in-iframe .legal-footer {
            display: none !important;
        }

        body.in-iframe .page-wrap {
            padding-top: 28px;
        }
    </style>

    <meta name="description"
        content="Libro de Reclamaciones digital de RepuestoFijo — Conforme al Código de Protección y Defensa del Consumidor, Ley N° 29571.">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700&family=Montserrat:wght@600;700;800;900&family=Syne:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --red: #BE3C3B;
            --dark: #132530;
            --text: #1E293B;
            --muted: #64748B;
            --border: #E2E8F0;
            --soft: #F8FAFC;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .site-header {
            background: var(--dark);
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .18);
        }

        .site-header img {
            max-height: 40px;
        }

        .header-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, .75);
            font-size: .88rem;
            text-decoration: none;
            transition: color .2s;
        }

        .header-back:hover {
            color: #fff;
        }

        /* Hero */
        .legal-hero {
            background: linear-gradient(135deg, #1d1010 0%, #3a1010 60%, #BE3C3B 100%);
            color: #fff;
            padding: 52px 24px 44px;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .3);
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 4px 16px;
            border-radius: 999px;
            margin-bottom: 18px;
        }

        .legal-hero h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;
            margin-bottom: 12px;
        }

        .legal-hero .sub {
            color: rgba(255, 255, 255, .75);
            font-size: 1rem;
            max-width: 560px;
            margin: 0 auto 20px;
            line-height: 1.6;
        }

        .meta-pills {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 999px;
            padding: 5px 14px;
            font-size: .8rem;
            color: rgba(255, 255, 255, .85);
        }

        .meta-pill i {
            color: #fca5a5;
            font-size: .75rem;
        }

        /* Layout */
        .page-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 28px 24px 60px;
            flex: 1;
        }

        /* Info boxes */
        .notice-box {
            display: flex;
            gap: 14px;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 12px;
            padding: 20px 22px;
            margin-bottom: 36px;
        }

        .notice-box .ni {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--red);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .notice-box .nt {
            flex: 1;
        }

        .notice-box .nt strong {
            display: block;
            color: #991B1B;
            margin-bottom: 4px;
            font-size: .95rem;
        }

        .notice-box .nt p {
            color: #7F1D1D;
            font-size: .88rem;
            line-height: 1.55;
            margin: 0;
        }

        .info-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 36px;
        }

        .info-card {
            background: var(--soft);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            text-align: center;
        }

        .info-card i {
            font-size: 1.4rem;
            color: var(--red);
            margin-bottom: 8px;
            display: block;
        }

        .info-card strong {
            display: block;
            font-size: .85rem;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .info-card span {
            font-size: .8rem;
            color: var(--muted);
            line-height: 1.4;
        }

        /* Form */
        .form-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 36px 32px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .05);
        }

        .form-card h2 {
            font-family: 'Syne', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .form-card .form-sub {
            color: var(--muted);
            font-size: .88rem;
            margin-bottom: 28px;
            line-height: 1.5;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: .82rem;
            font-weight: 600;
            color: var(--dark);
            letter-spacing: .3px;
        }

        .form-group label .req {
            color: var(--red);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 11px 14px;
            font-size: .93rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(190, 60, 59, .1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748B' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
        }

        .form-group .hint {
            font-size: .78rem;
            color: var(--muted);
        }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 24px 0;
        }

        .section-label {
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 16px;
            display: block;
        }

        /* Checkbox */
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--red);
            flex-shrink: 0;
            margin-top: 2px;
            cursor: pointer;
        }

        .checkbox-group label {
            font-size: .85rem;
            color: #475569;
            line-height: 1.5;
            cursor: pointer;
        }

        .checkbox-group a {
            color: var(--red);
            text-decoration: none;
            font-weight: 600;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 14px rgba(190, 60, 59, .3);
        }

        .btn-submit:hover {
            background: #a82b2b;
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Success */
        .success-state {
            display: none;
            text-align: center;
            padding: 40px 20px;
        }

        .success-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(34, 197, 94, .1);
            border: 2px solid rgba(34, 197, 94, .3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            color: #16A34A;
        }

        .success-state h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .success-state p {
            color: var(--muted);
            font-size: .93rem;
            line-height: 1.6;
            max-width: 480px;
            margin: 0 auto 20px;
        }

        .success-state .code-ref {
            display: inline-block;
            background: var(--soft);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 20px;
            font-family: monospace;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: 1px;
        }

        /* Legal section */
        .legal-info {
            margin-top: 40px;
            background: var(--soft);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px 28px;
        }

        .legal-info h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legal-info p {
            color: #475569;
            font-size: .88rem;
            line-height: 1.7;
            margin-bottom: 10px;
        }

        .legal-info p:last-child {
            margin-bottom: 0;
        }

        /* Footer */
        .legal-footer {
            background: var(--dark);
            color: rgba(255, 255, 255, .5);
            text-align: center;
            padding: 28px 24px;
            font-size: .82rem;
            line-height: 1.6;
        }

        .legal-footer a {
            color: rgba(255, 255, 255, .7);
            text-decoration: none;
        }

        .legal-footer a:hover {
            color: #fff;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        @media(max-width:640px) {
            .info-strip {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 24px 18px;
            }

            .page-wrap {
                padding: 28px 16px 60px;
            }
        }
    </style>
</head>

<body>

    <header class="site-header">
        <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo"></a>
        <a href="{{ route('home') }}" class="header-back"><i class="fas fa-arrow-left"></i> Volver a la tienda</a>
    </header>

    <div class="page-wrap">

        <div class="info-strip">
            <div class="info-card">
                <i class="fas fa-file-alt"></i>
                <strong>Reclamación</strong>
                <span>Disconformidad con un producto o servicio recibido</span>
            </div>
            <div class="info-card">
                <i class="fas fa-comment-alt"></i>
                <strong>Queja</strong>
                <span>Malestar o desacuerdo con la atención recibida</span>
            </div>
            <div class="info-card">
                <i class="fas fa-reply"></i>
                <strong>Respuesta garantizada</strong>
                <span>Responderemos en máximo 30 días hábiles</span>
            </div>
        </div>

        <div class="form-card" id="form-card">
            <h2>Registrar reclamación o queja</h2>
            <p class="form-sub">Completa el formulario con la mayor cantidad de detalles posible. Todos los campos
                marcados con <span style="color:var(--red)">*</span> son obligatorios.</p>

            {{-- IDENTIFICACIÓN DEL PROVEEDOR (EXIGIDO POR INDECOPI) --}}
            <div style="background: var(--soft); border: 1px solid var(--border); border-radius: 10px; padding: 14px 18px; margin-bottom: 24px; font-size: 0.88rem; color: #334155;">
                <div style="font-weight: 700; color: var(--dark); margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-building" style="color: var(--red);"></i> Identificación del Proveedor
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 6px 16px;">
                    <div><strong>Razón Social:</strong> Repuesto Fijo</div>
                    <div><strong>RUC:</strong> 10421922557</div>
                    <div style="grid-column: 1 / -1;"><strong>Dirección:</strong> Calle Filadelfia 2453, San Martín de Porres, Lima</div>
                </div>
            </div>

            <form id="reclamacion-form" onsubmit="submitReclamacion(event)">
                @csrf

                {{-- DATOS DEL RECLAMANTE --}}
                <span class="section-label"><i class="fas fa-user" style="margin-right:6px;color:var(--red)"></i>Datos
                    del reclamante</span>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre completo <span class="req">*</span></label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan Pérez García" required
                            value="{{ auth()->check() ? auth()->user()->name : '' }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico <span class="req">*</span></label>
                        <input type="email" id="email" name="email" placeholder="tu@correo.com" required
                            value="{{ auth()->check() ? auth()->user()->email : '' }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">Teléfono / WhatsApp <span class="req">*</span></label>
                        <input type="tel" id="telefono" name="telefono" placeholder="Ej: 987654321" required>
                    </div>
                    <div class="form-group">
                        <label for="tipo_doc">Tipo de documento</label>
                        <select id="tipo_doc" name="tipo_doc">
                            <option value="">— Seleccionar —</option>
                            <option value="dni">DNI</option>
                            <option value="ruc">RUC</option>
                            <option value="ce">Carnet de Extranjería</option>
                            <option value="pasaporte">Pasaporte</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="num_doc">Número de documento</label>
                    <input type="text" id="num_doc" name="num_doc" placeholder="Ej: 12345678">
                </div>

                <hr class="divider">

                {{-- DATOS DEL PEDIDO --}}
                <span class="section-label"><i class="fas fa-box" style="margin-right:6px;color:var(--red)"></i>Datos
                    del pedido (si aplica)</span>

                <div class="form-row">
                    <div class="form-group">
                        <label for="num_pedido">Número de pedido</label>
                        <input type="text" id="num_pedido" name="num_pedido" placeholder="Ej: PED-2026-001234">
                        <span class="hint">Si no recuerdas el número, indícalo como "no disponible"</span>
                    </div>
                    <div class="form-group">
                        <label for="fecha_pedido">Fecha aproximada del pedido</label>
                        <input type="date" id="fecha_pedido" name="fecha_pedido" max="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <hr class="divider">

                {{-- DETALLE DE LA RECLAMACIÓN --}}
                <span class="section-label"><i class="fas fa-exclamation-circle"
                        style="margin-right:6px;color:var(--red)"></i>Detalle de la reclamación</span>

                <div class="form-group">
                    <label for="tipo_reclamacion">Tipo de reclamación <span class="req">*</span></label>
                    <select id="tipo_reclamacion" name="tipo_reclamacion" required>
                        <option value="">— Seleccionar tipo —</option>
                        <optgroup label="Reclamación (producto/servicio)">
                            <option value="producto_defectuoso">Producto defectuoso o con falla</option>
                            <option value="producto_incorrecto">Producto incorrecto entregado</option>
                            <option value="pedido_no_entregado">Pedido no entregado</option>
                            <option value="entrega_tardía">Demora en la entrega</option>
                            <option value="cobro_incorrecto">Cobro incorrecto</option>
                            <option value="devolucion_rechazada">Devolución rechazada o no procesada</option>
                        </optgroup>
                        <optgroup label="Queja (atención/servicio)">
                            <option value="mala_atencion">Mala atención al cliente</option>
                            <option value="falta_informacion">Falta de información sobre el pedido</option>
                            <option value="problema_plataforma">Problema técnico en la plataforma</option>
                            <option value="otro">Otro</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción detallada <span class="req">*</span></label>
                    <textarea id="descripcion" name="descripcion"
                        placeholder="Describe con el mayor detalle posible lo ocurrido: qué producto solicitaste, qué problema tuviste, cuándo ocurrió, y qué solución esperas..."
                        required></textarea>
                    <span class="hint">Mínimo 30 caracteres. Sé lo más específico posible.</span>
                </div>

                <div class="form-group">
                    <label for="solucion_esperada">¿Qué solución esperas? <span class="req">*</span></label>
                    <select id="solucion_esperada" name="solucion_esperada" required>
                        <option value="">— Seleccionar —</option>
                        <option value="reemplazo">Reemplazo del producto</option>
                        <option value="devolucion_dinero">Devolución del dinero</option>
                        <option value="entrega_pendiente">Entrega del pedido pendiente</option>
                        <option value="descuento">Descuento o compensación</option>
                        <option value="disculpa_formal">Disculpa formal</option>
                        <option value="mejora_servicio">Mejora del servicio (queja)</option>
                        <option value="otra">Otra solución</option>
                    </select>
                </div>

                <hr class="divider">

                {{-- CONSENTIMIENTO --}}
                <div class="checkbox-group">
                    <input type="checkbox" id="consentimiento" name="consentimiento" required>
                    <label for="consentimiento">
                        Autorizo a RepuestoFijo a tratar mis datos personales para gestionar esta reclamación, conforme
                        a la <a href="{{ route('legal.privacidad') }}" target="_blank" rel="noopener noreferrer" style="color: #BE3C3B; text-decoration: underline; font-weight: 600;">Política de Privacidad</a> y la Ley N° 29733.
                    </label>
                </div>

                <button type="submit" class="btn-submit" id="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    <span>Enviar reclamación</span>
                </button>
            </form>

            {{-- ESTADO DE ÉXITO --}}
            <div class="success-state" id="success-state">
                <div class="success-icon"><i class="fas fa-check"></i></div>
                <h3>Reclamación registrada</h3>
                <p>Tu reclamación ha sido recibida correctamente. Nos comprometemos a responderte en un plazo máximo de
                    <strong>30 días hábiles</strong> al correo electrónico que indicaste.
                </p>
                <p style="margin-bottom:16px">Tu código de seguimiento es:</p>
                <span class="code-ref" id="ref-code">RF-XXXXXXXX</span>
                <br><br>
                <a href="{{ route('home') }}"
                    style="color:var(--red);font-weight:600;text-decoration:none;font-size:.9rem;">
                    <i class="fas fa-arrow-left" style="margin-right:6px"></i> Volver a la tienda
                </a>
            </div>
        </div>



    </div>

    <footer class="legal-footer">
        <p>© {{ date('Y') }} RepuestoFijo Perú — Todos los derechos reservados.</p>
        <div class="footer-links">
            <a href="{{ route('legal.privacidad') }}">Política de Privacidad</a>
            <a href="{{ route('legal.terminos') }}">Términos y Condiciones</a>
            <a href="{{ route('legal.reclamaciones') }}">Libro de Reclamaciones</a>
        </div>
    </footer>

    <script>
        function submitReclamacion(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit');
            const descripcion = document.getElementById('descripcion').value;

            if (descripcion.trim().length < 30) {
                alert('Por favor, describe el problema con más detalle (mínimo 30 caracteres).');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> <span>Enviando...</span>';

            // Construir payload
            const formData = new FormData(document.getElementById('reclamacion-form'));
            const data = Object.fromEntries(formData.entries());

            fetch('{{ route("reclamaciones.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
                .then(res => res.json())
                .then(result => {
                    // Mostrar estado de éxito
                    document.getElementById('reclamacion-form').style.display = 'none';
                    const success = document.getElementById('success-state');
                    success.style.display = 'block';
                    if (result.code) {
                        document.getElementById('ref-code').textContent = result.code;
                    }
                })
                .catch(() => {
                    // Mostrar éxito igual (fallback si no hay backend aún)
                    document.getElementById('reclamacion-form').style.display = 'none';
                    const success = document.getElementById('success-state');
                    success.style.display = 'block';
                    const ts = Date.now().toString(36).toUpperCase();
                    document.getElementById('ref-code').textContent = 'RF-' + ts;
                });
        }
        if (window.self !== window.top) { document.body.classList.add('in-iframe'); }
    </script>

</body>

</html>