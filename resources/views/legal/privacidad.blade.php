<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad | RepuestoFijo</title>
    <style>
        body.in-iframe .site-header,
        body.in-iframe .legal-footer {
            display: none !important;
        }

        body.in-iframe .legal-wrap {
            padding-top: 28px;
        }
    </style>
    <meta name="description"
        content="Política de Privacidad de RepuestoFijo — Conoce cómo recopilamos, usamos y protegemos tus datos personales conforme a la Ley N° 29733 del Perú.">
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

        .legal-hero {
            background: linear-gradient(135deg, var(--dark) 0%, #1d3a4a 100%);
            color: #fff;
            padding: 52px 24px 44px;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(190, 60, 59, .25);
            border: 1px solid rgba(190, 60, 59, .5);
            color: #f87171;
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
            color: rgba(255, 255, 255, .65);
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
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 999px;
            padding: 5px 14px;
            font-size: .8rem;
            color: rgba(255, 255, 255, .7);
        }

        .meta-pill i {
            color: var(--red);
            font-size: .75rem;
        }

        .legal-wrap {
            max-width: 820px;
            margin: 0 auto;
            padding: 28px 24px 60px;
            flex: 1;
        }

        .intro-p {
            color: #475569;
            line-height: 1.75;
            margin-bottom: 36px;
            font-size: 1rem;
        }

        .toc {
            background: var(--soft);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px 28px;
            margin-bottom: 48px;
        }

        .toc-title {
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 14px;
        }

        .toc ol {
            padding-left: 18px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 24px;
        }

        .toc li {
            font-size: .9rem;
        }

        .toc a {
            color: var(--dark);
            text-decoration: none;
            transition: color .2s;
        }

        .toc a:hover {
            color: var(--red);
        }

        .legal-section {
            margin-bottom: 48px;
            scroll-margin-top: 80px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid var(--border);
        }

        .section-num {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--red);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
        }

        .legal-section p {
            color: #475569;
            line-height: 1.75;
            margin-bottom: 14px;
        }

        .legal-section p:last-child {
            margin-bottom: 0;
        }

        .subsection-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 10px;
            display: block;
        }

        .data-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 14px;
        }

        .data-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #475569;
            font-size: .95rem;
            line-height: 1.5;
        }

        .data-list li::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--red);
            margin-top: 6px;
            flex-shrink: 0;
        }

        .info-box {
            background: var(--soft);
            border-left: 3px solid var(--red);
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .info-box strong {
            display: block;
            color: var(--dark);
            margin-bottom: 4px;
            font-size: .9rem;
        }

        .info-box span {
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.5;
            display: block;
        }

        .info-box a {
            color: var(--red);
            text-decoration: none;
            font-weight: 600;
        }

        .warn-box {
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0;
            display: flex;
            gap: 12px;
        }

        .warn-box i {
            color: #F97316;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .warn-box p {
            color: #92400E;
            font-size: .9rem;
            line-height: 1.5;
            margin: 0;
        }

        .rights-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 16px;
        }

        .right-card {
            background: var(--soft);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
        }

        .right-card .ri {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(190, 60, 59, .1);
            color: var(--red);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            margin-bottom: 8px;
        }

        .right-card strong {
            display: block;
            font-size: .9rem;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .right-card span {
            font-size: .83rem;
            color: var(--muted);
            line-height: 1.45;
        }

        .link-red {
            color: var(--red);
            font-weight: 600;
            text-decoration: none;
        }

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
            .toc ol {
                grid-template-columns: 1fr;
            }

            .rights-grid {
                grid-template-columns: 1fr;
            }

            .legal-wrap {
                padding: 32px 16px 60px;
            }
        }

        /* ── Estilos dentro de un popup/iframe ── */
        body.in-iframe {
            background: #fff;
        }

        body.in-iframe .site-header,
        body.in-iframe .legal-footer {
            display: none !important;
        }

        body.in-iframe .legal-wrap {
            padding: 18px 16px 40px !important;
            max-width: 100% !important;
        }
    </style>
</head>

<body>

    <header class="site-header">
        <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo"></a>
        <a href="{{ route('home') }}" class="header-back"><i class="fas fa-arrow-left"></i> Volver a la tienda</a>
    </header>

    <div class="legal-wrap">

        <p class="intro-p">
            Al registrarte en <strong style="color:var(--dark)">RepuestoFijo</strong> y utilizar nuestros servicios,
            aceptas los términos descritos en esta Política de Privacidad. Te recomendamos leerla con atención. Si
            tienes alguna duda, puedes contactarnos en cualquier momento.
        </p>

        <div class="toc">
            <div class="toc-title"><i class="fas fa-list-ul" style="margin-right:6px;color:var(--red)"></i>Índice de
                contenidos</div>
            <ol>
                <li><a href="#sec1">¿Quiénes somos?</a></li>
                <li><a href="#sec2">¿Qué datos recopilamos?</a></li>
                <li><a href="#sec3">¿Para qué usamos tus datos?</a></li>
                <li><a href="#sec4">Base legal del tratamiento</a></li>
                <li><a href="#sec5">¿Con quién compartimos tus datos?</a></li>
                <li><a href="#sec6">Transferencia internacional de datos</a></li>
                <li><a href="#sec7">Período de retención</a></li>
                <li><a href="#sec8">¿Cómo protegemos tus datos?</a></li>
                <li><a href="#sec9">Tus derechos sobre tus datos</a></li>
                <li><a href="#sec10">Cookies y tecnologías de seguimiento</a></li>
                <li><a href="#sec11">Menores de edad</a></li>
                <li><a href="#sec12">Cambios en esta política</a></li>
            </ol>
        </div>

        <!-- 1. QUIÉNES SOMOS -->
        <div class="legal-section" id="sec1">
            <div class="section-header">
                <div class="section-num">1</div>
                <h2 class="section-title">¿Quiénes somos?</h2>
            </div>
            <p>RepuestoFijo es una plataforma digital peruana que conecta mecánicos y talleres automotrices con
                proveedores de repuestos para pedidos urgentes y entregas rápidas. Operamos a través de nuestra
                aplicación web y gestionamos los pedidos mediante un sistema automatizado.</p>
            <div class="info-box">
                <strong>Responsable del tratamiento de datos</strong>
                <span>Repuesto Fijo (RUC: 10421922557)</span>
                <span>Domicilio fiscal: Calle Filadelfia 2453, San Martín de Porres, Lima, Perú</span>
                <span>Correo de privacidad: <a
                        href="mailto:privacidad@repuestofijo.com">privacidad@repuestofijo.com</a></span>
            </div>
        </div>

        <!-- 2. QUÉ DATOS -->
        <div class="legal-section" id="sec2">
            <div class="section-header">
                <div class="section-num">2</div>
                <h2 class="section-title">¿Qué datos recopilamos?</h2>
            </div>
            <span class="subsection-label">2.1 Datos que obtenemos al registrarte con Google</span>
            <p>Cuando inicias sesión con tu cuenta de Google, recibimos automáticamente la siguiente información con tu
                autorización expresa:</p>
            <ul class="data-list">
                <li>Nombre completo</li>
                <li>Dirección de correo electrónico</li>
                <li>Foto de perfil de Google</li>
                <li>Identificador único de Google (Google ID)</li>
                <li>Idioma preferido del navegador</li>
            </ul>
            <span class="subsection-label" style="margin-top:20px">2.2 Datos que recopilamos durante el uso de la
                plataforma</span>
            <ul class="data-list">
                <li><strong>Datos del perfil:</strong> nombre del taller, distrito, tipo de vehículos que atiendes.</li>
                <li><strong>Historial de pedidos:</strong> productos solicitados, cantidades, fechas y estados de cada
                    pedido.</li>
                <li><strong>Dirección de entrega:</strong> distrito y dirección donde recibes tus pedidos.</li>
                <li><strong>Comportamiento en la plataforma:</strong> productos buscados, productos no encontrados, hora
                    de actividad.</li>
                <li><strong>Datos de pago:</strong> gestionados de forma segura por Culqi. No almacenamos datos de
                    tarjetas.</li>
                <li><strong>Incidencias reportadas:</strong> cualquier problema que registres sobre un pedido.</li>
                <li><strong>Registro de consentimiento:</strong> fecha, hora y versión de la política aceptada al
                    momento de tu registro.</li>
            </ul>
        </div>

        <!-- 3. PARA QUÉ USAMOS -->
        <div class="legal-section" id="sec3">
            <div class="section-header">
                <div class="section-num">3</div>
                <h2 class="section-title">¿Para qué usamos tus datos?</h2>
            </div>
            <p>Utilizamos la información recopilada exclusivamente para los siguientes fines:</p>
            <ul class="data-list">
                <li>Gestionar tu registro y acceso a la plataforma.</li>
                <li>Procesar y coordinar tus pedidos de repuestos.</li>
                <li>Coordinar la entrega mediante motorizado a tu dirección.</li>
                <!-- <li>Enviarte notificaciones sobre el estado de tus pedidos (vía WhatsApp o correo).</li> -->
                <li>Mejorar la experiencia de uso de la plataforma.</li>
                <li>Avisarte cuando un producto buscado y no encontrado esté disponible.</li>
                <!-- <li>Gestionar el sistema de créditos y beneficios por fidelidad.</li> -->
                <li>Detectar y prevenir usos fraudulentos del sistema.</li>
                <li>Cumplir con obligaciones legales y fiscales en Perú.</li>
            </ul>
        </div>

        <!-- 4. BASE LEGAL -->
        <div class="legal-section" id="sec4">
            <div class="section-header">
                <div class="section-num">4</div>
                <h2 class="section-title">Base legal del tratamiento</h2>
            </div>
            <p>El tratamiento de tus datos personales se sustenta en las siguientes bases legales conforme al artículo
                13° de la Ley N° 29733:</p>
            <ul class="data-list">
                <li><strong>Consentimiento informado:</strong> otorgado al aceptar esta Política de Privacidad y los
                    Términos y Condiciones al momento del registro.</li>
                <li><strong>Ejecución del contrato:</strong> necesario para procesar tus pedidos, coordinar entregas y
                    gestionar pagos.</li>
                <li><strong>Interés legítimo:</strong> mejora del servicio, seguridad de la plataforma y prevención del
                    fraude.</li>
                <li><strong>Obligación legal:</strong> cumplimiento de normas tributarias y fiscales vigentes en el
                    Perú.</li>
            </ul>
        </div>

        <!-- 5. CON QUIÉN COMPARTIMOS -->
        <div class="legal-section" id="sec5">
            <div class="section-header">
                <div class="section-num">5</div>
                <h2 class="section-title">¿Con quién compartimos tus datos?</h2>
            </div>
            <p>RepuestoFijo <strong>NO vende ni cede tus datos personales a terceros con fines comerciales.</strong>
                Únicamente compartimos información en los siguientes casos:</p>
            <ul class="data-list">
                <li><strong>Con proveedores de repuestos:</strong> únicamente el detalle del pedido (productos,
                    cantidades y distrito). Nunca compartimos tu nombre ni datos de contacto directo.</li>
                <li><strong>Con el servicio de motorizado:</strong> nombre, distrito y dirección de entrega,
                    exclusivamente para coordinar la entrega.</li>
                <li><strong>Con Culqi (pasarela de pagos):</strong> para procesar el cobro de forma segura bajo estándar
                    PCI DSS.</li>
                <li><strong>Con autoridades competentes:</strong> si la ley peruana lo exige o ante una orden judicial.
                </li>
            </ul>
        </div>

        <!-- 6. TRANSFERENCIA INTERNACIONAL -->
        <div class="legal-section" id="sec6">
            <div class="section-header">
                <div class="section-num">6</div>
                <h2 class="section-title">Transferencia internacional de datos</h2>
            </div>
            <p>Algunos de nuestros proveedores tecnológicos operan fuera del territorio peruano, por lo que ciertos
                datos pueden ser procesados en servidores ubicados en el extranjero:</p>
            <ul class="data-list">
                <li><strong>Google LLC (EE. UU.):</strong> utilizado para autenticación OAuth y almacenamiento de fotos
                    de perfil. Google cumple con el marco de privacidad UE-EE.UU. y estándares equivalentes.</li>
                <li><strong>Culqi:</strong> pasarela de pagos con operaciones en Perú y cumplimiento PCI DSS nivel 1.
                </li>
            </ul>
            <div class="warn-box">
                <i class="fas fa-info-circle"></i>
                <p>Estas transferencias se realizan con proveedores que garantizan un nivel de protección equivalente al
                    exigido por la legislación peruana vigente.</p>
            </div>
        </div>

        <!-- 7. RETENCIÓN -->
        <div class="legal-section" id="sec7">
            <div class="section-header">
                <div class="section-num">7</div>
                <h2 class="section-title">Período de retención de datos</h2>
            </div>
            <p>Conservamos tus datos personales durante el tiempo que mantengas una cuenta activa y por el período
                adicional que exijan las obligaciones legales o fiscales aplicables.</p>
            <ul class="data-list">
                <li><strong>Datos de cuenta:</strong> durante la vigencia de tu cuenta y hasta 2 años después de su
                    eliminación.</li>
                <li><strong>Historial de pedidos:</strong> hasta 5 años por obligaciones tributarias (SUNAT).</li>
                <li><strong>Registros de consentimiento:</strong> de forma indefinida como respaldo legal.</li>
                <li><strong>Datos de comportamiento y búsqueda:</strong> máximo 12 meses desde su recopilación.</li>
            </ul>
            <p style="margin-top:14px">Si solicitas la eliminación de tu cuenta, procederemos a borrar o anonimizar tus
                datos en un plazo máximo de <strong>30 días hábiles</strong>, salvo obligación legal de conservarlos.
            </p>
        </div>

        <!-- 8. PROTECCIÓN -->
        <div class="legal-section" id="sec8">
            <div class="section-header">
                <div class="section-num">8</div>
                <h2 class="section-title">¿Cómo protegemos tus datos?</h2>
            </div>
            <ul class="data-list">
                <li>Conexiones cifradas mediante protocolo HTTPS en toda la plataforma.</li>
                <li>Acceso restringido a los datos únicamente al personal autorizado.</li>
                <li>Datos de pago gestionados exclusivamente por Culqi bajo estándares PCI DSS.</li>
                <li>Contraseñas y tokens de acceso almacenados de forma cifrada (hashing).</li>
                <li>Revisiones periódicas de seguridad y control de accesos.</li>
            </ul>
            <div class="info-box" style="margin-top:20px">
                <strong>Notificación de incidentes de seguridad</strong>
                <span>En caso de una brecha de seguridad que pueda afectar tus derechos, te notificaremos en el menor
                    tiempo posible y lo comunicaremos a la ANPD conforme a la normativa vigente.</span>
            </div>
        </div>

        <!-- 9. DERECHOS -->
        <div class="legal-section" id="sec9">
            <div class="section-header">
                <div class="section-num">9</div>
                <h2 class="section-title">Tus derechos sobre tus datos</h2>
            </div>
            <p>De acuerdo con la Ley N° 29733 de Protección de Datos Personales del Perú, tienes los siguientes
                derechos:</p>
            <div class="rights-grid">
                <div class="right-card">
                    <div class="ri"><i class="fas fa-eye"></i></div>
                    <strong>Acceso</strong>
                    <span>Solicitar qué datos tuyos tenemos almacenados.</span>
                </div>
                <div class="right-card">
                    <div class="ri"><i class="fas fa-pen"></i></div>
                    <strong>Rectificación</strong>
                    <span>Corregir datos incorrectos o desactualizados.</span>
                </div>
                <div class="right-card">
                    <div class="ri"><i class="fas fa-trash"></i></div>
                    <strong>Cancelación</strong>
                    <span>Solicitar la eliminación de tus datos personales.</span>
                </div>
                <div class="right-card">
                    <div class="ri"><i class="fas fa-ban"></i></div>
                    <strong>Oposición</strong>
                    <span>Oponerte al tratamiento de tus datos para determinados fines.</span>
                </div>
            </div>
            <p style="margin-top:20px">Para ejercer cualquiera de estos derechos, escríbenos a: <a
                    href="mailto:privacidad@repuestofijo.com" class="link-red">privacidad@repuestofijo.com</a>.
                Responderemos en un plazo máximo de <strong>10 días hábiles</strong>.</p>
            <div class="info-box" style="margin-top:20px">
                <strong>Autoridad supervisora — ANPD</strong>
                <span>Si consideras que el tratamiento de tus datos no es conforme a la ley, puedes presentar una
                    reclamación ante la <strong>Autoridad Nacional de Protección de Datos Personales (ANPD)</strong> del
                    Ministerio de Justicia del Perú: <a href="https://www.gob.pe/anpd" target="_blank"
                        rel="noopener">https://www.gob.pe/anpd</a></span>
            </div>
        </div>

        <!-- 10. COOKIES -->
        <div class="legal-section" id="sec10">
            <div class="section-header">
                <div class="section-num">10</div>
                <h2 class="section-title">Cookies y tecnologías de seguimiento</h2>
            </div>
            <p>RepuestoFijo utiliza únicamente <strong>cookies técnicas esenciales</strong> para el funcionamiento de la
                plataforma, como mantener tu sesión iniciada y recordar tu lista de reparación. <strong>No utilizamos
                    cookies de publicidad ni de seguimiento de terceros.</strong></p>
        </div>

        <!-- 11. MENORES -->
        <div class="legal-section" id="sec11">
            <div class="section-header">
                <div class="section-num">11</div>
                <h2 class="section-title">Menores de edad</h2>
            </div>
            <p>RepuestoFijo está destinado exclusivamente a <strong>personas mayores de 18 años</strong>. Al aceptar
                esta Política de Privacidad y los Términos y Condiciones, el usuario declara expresamente ser mayor de
                edad.</p>
            <p>No recopilamos intencionalmente datos de menores de edad. Si tenemos conocimiento de que un menor ha
                proporcionado sus datos sin el consentimiento de un tutor legal, procederemos a eliminar dicha
                información de inmediato.</p>
        </div>

        <!-- 12. CAMBIOS -->
        <div class="legal-section" id="sec12">
            <div class="section-header">
                <div class="section-num">12</div>
                <h2 class="section-title">Cambios en esta política</h2>
            </div>
            <p>Podemos actualizar esta Política de Privacidad ocasionalmente para reflejar cambios en nuestras prácticas
                o en la legislación vigente. Cuando realicemos cambios importantes, te notificaremos por correo
                electrónico y/o mediante un aviso visible en la plataforma.</p>
            <p>La versión vigente siempre estará disponible en esta página con su fecha de actualización. El uso
                continuado del servicio tras la publicación de cambios implica la aceptación de la nueva versión.</p>
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

    <script>if (window.self !== window.top) { document.body.classList.add('in-iframe'); }</script>

</body>

</html>