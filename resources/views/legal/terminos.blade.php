<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones | RepuestoFijo</title>
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
        content="Términos y Condiciones de uso de RepuestoFijo — Plataforma digital peruana de repuestos automotrices.">
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

        .alert-box {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 20px 0;
            display: flex;
            gap: 12px;
        }

        .alert-box i {
            color: #EF4444;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-box p {
            color: #991B1B;
            font-size: .9rem;
            line-height: 1.5;
            margin: 0;
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

            .legal-wrap {
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

    <div class="legal-wrap">

        <p class="intro-p">
            Estos Términos y Condiciones regulan el acceso y uso de la plataforma <strong
                style="color:var(--dark)">RepuestoFijo</strong>. Al registrarte y utilizar nuestros servicios, aceptas
            expresamente estos términos en su totalidad. Si no estás de acuerdo con alguno de ellos, te pedimos que no
            utilices la plataforma.
        </p>

        <div class="toc">
            <div class="toc-title"><i class="fas fa-list-ul" style="margin-right:6px;color:var(--red)"></i>Índice de
                contenidos</div>
            <ol>
                <li><a href="#t1">Definiciones</a></li>
                <li><a href="#t2">Objeto del servicio</a></li>
                <li><a href="#t3">Registro y elegibilidad</a></li>
                <li><a href="#t4">Obligaciones del usuario</a></li>
                <li><a href="#t5">Proceso de pedidos</a></li>
                <li><a href="#t6">Precios y pagos</a></li>
                <li><a href="#t7">Devoluciones y reclamaciones</a></li>
                <li><a href="#t8">Responsabilidad y limitaciones</a></li>
                <li><a href="#t9">Propiedad intelectual</a></li>
                <li><a href="#t10">Suspensión y cancelación de cuenta</a></li>
                <li><a href="#t11">Privacidad de datos</a></li>
                <li><a href="#t12">Modificaciones del servicio</a></li>
                <li><a href="#t13">Ley aplicable y jurisdicción</a></li>
                <li><a href="#t14">Contacto</a></li>
            </ol>
        </div>

        <!-- 1. DEFINICIONES -->
        <div class="legal-section" id="t1">
            <div class="section-header">
                <div class="section-num">1</div>
                <h2 class="section-title">Definiciones</h2>
            </div>
            <p>A efectos de estos Términos y Condiciones, se entenderá por:</p>
            <ul class="data-list">
                <li><strong>Plataforma:</strong> el sitio web y aplicación web de RepuestoFijo accesible en
                    repuestofijo.com.</li>
                <li><strong>Usuario / Cliente:</strong> toda persona natural o jurídica que accede y usa la plataforma,
                    principalmente mecánicos y talleres automotrices.</li>
                <li><strong>Proveedor:</strong> empresa o persona que suministra los repuestos automotrices a través de
                    RepuestoFijo.</li>
                <li><strong>Motorizado:</strong> servicio de entrega a domicilio coordinado por RepuestoFijo.</li>
                <li><strong>Pedido:</strong> solicitud de compra de uno o más repuestos realizada por un usuario a
                    través de la plataforma.</li>
                <li><strong>RepuestoFijo / Nosotros:</strong> la empresa titular y operadora de la plataforma,
                    RepuestoFijo Perú.</li>
            </ul>
        </div>

        <!-- 2. OBJETO DEL SERVICIO -->
        <div class="legal-section" id="t2">
            <div class="section-header">
                <div class="section-num">2</div>
                <h2 class="section-title">Objeto del servicio</h2>
            </div>
            <p>RepuestoFijo es una plataforma de intermediación digital que conecta a mecánicos y talleres automotrices
                con proveedores de repuestos para pedidos urgentes con entrega rápida. RepuestoFijo actúa como
                intermediario facilitador del proceso de compra, cotización y entrega, sin ser directamente el vendedor
                de los repuestos.</p>
            <p>Nuestros servicios incluyen:</p>
            <ul class="data-list">
                <li>Búsqueda y cotización de repuestos automotrices por código OEM o nombre.</li>
                <li>Gestión del pedido y coordinación con proveedores aliados.</li>
                <li>Servicio de entrega a domicilio mediante motorizado.</li>
                <li>Notificaciones del estado del pedido en tiempo real.</li>
                <!-- <li>Sistema de créditos y beneficios por fidelidad para usuarios recurrentes.</li> -->
            </ul>
        </div>

        <!-- 3. REGISTRO Y ELEGIBILIDAD -->
        <div class="legal-section" id="t3">
            <div class="section-header">
                <div class="section-num">3</div>
                <h2 class="section-title">Registro y elegibilidad</h2>
            </div>
            <p>Para utilizar RepuestoFijo debes:</p>
            <ul class="data-list">
                <li>Ser mayor de <strong>18 años de edad</strong>. Al registrarte, declaras expresamente ser mayor de
                    edad.</li>
                <li>Disponer de una cuenta de Google activa para el proceso de autenticación.</li>
                <li>Proporcionar información veraz, completa y actualizada durante el registro y en el perfil.</li>
                <li>Ser responsable de mantener la confidencialidad de tu cuenta.</li>
            </ul>
            <div class="alert-box">
                <i class="fas fa-exclamation-triangle"></i>
                <p>RepuestoFijo se reserva el derecho de suspender o cancelar cuentas que hayan sido registradas con
                    información falsa, que correspondan a menores de edad, o que incumplan estos Términos y Condiciones.
                </p>
            </div>
        </div>

        <!-- 4. OBLIGACIONES DEL USUARIO -->
        <div class="legal-section" id="t4">
            <div class="section-header">
                <div class="section-num">4</div>
                <h2 class="section-title">Obligaciones del usuario</h2>
            </div>
            <p>Al utilizar la plataforma, el usuario se compromete a:</p>
            <ul class="data-list">
                <li>Usar la plataforma únicamente para fines lícitos y conforme a la normativa peruana vigente.</li>
                <li>No intentar vulnerar la seguridad de la plataforma, ni realizar scraping, crawling o extracción
                    automatizada de datos.</li>
                <li>No suplantar la identidad de otras personas ni crear cuentas con datos falsos.</li>
                <li>No publicar ni transmitir contenido ofensivo, fraudulento o ilegal.</li>
                <li>Verificar la información de entrega antes de confirmar un pedido.</li>
                <li>Estar disponible para recibir el pedido en la dirección y horario indicados.</li>
                <li>Reportar cualquier incidencia o reclamación a través de los canales habilitados.</li>
            </ul>
        </div>

        <!-- 5. PROCESO DE PEDIDOS -->
        <div class="legal-section" id="t5">
            <div class="section-header">
                <div class="section-num">5</div>
                <h2 class="section-title">Proceso de pedidos</h2>
            </div>
            <p>El flujo de un pedido en RepuestoFijo funciona de la siguiente manera:</p>
            <ul class="data-list">
                <li><strong>Búsqueda:</strong> el usuario busca el repuesto por código OEM, marca o descripción.</li>
                <li><strong>Solicitud:</strong> el usuario añade el repuesto a su lista de reparación y envía el pedido.
                </li>
                <li><strong>Cotización:</strong> RepuestoFijo verifica la disponibilidad con los proveedores aliados.
                </li>
                <li><strong>Confirmación:</strong> el usuario recibe la cotización y confirma el pedido con el pago.
                </li>
                <li><strong>Despacho:</strong> el proveedor prepara el pedido y el motorizado lo recoge.</li>
                <li><strong>Entrega:</strong> el motorizado entrega el pedido en la dirección indicada.</li>
            </ul>
            <div class="warn-box">
                <i class="fas fa-info-circle"></i>
                <p>RepuestoFijo no garantiza la disponibilidad de todos los repuestos solicitados. En caso de no
                    disponibilidad, se notificará al usuario y no se realizará ningún cobro.</p>
            </div>
            <p>El usuario es responsable de verificar que el repuesto recibido corresponde al solicitado antes de
                instalarlo. RepuestoFijo no se hace responsable por incompatibilidades derivadas de una búsqueda
                incorrecta por parte del usuario.</p>
        </div>

        <!-- 6. PRECIOS Y PAGOS -->
        <div class="legal-section" id="t6">
            <div class="section-header">
                <div class="section-num">6</div>
                <h2 class="section-title">Precios y pagos</h2>
            </div>
            <ul class="data-list">
                <li>Todos los precios mostrados en la plataforma incluyen IGV (18%) salvo indicación expresa en
                    contrario.</li>
                <li>Los precios están expresados en Soles peruanos (PEN / S/).</li>
                <li>El pago se procesa a través de <strong>Culqi</strong>, pasarela de pagos certificada PCI DSS.
                    RepuestoFijo no almacena datos de tarjetas bancarias.</li>
                <li>RepuestoFijo se reserva el derecho de modificar precios en cualquier momento, sin que ello afecte a
                    los pedidos ya confirmados.</li>
                <li>El precio del servicio de entrega (motorizado) será informado al usuario antes de confirmar el
                    pedido.</li>
            </ul>
            <div class="info-box">
                <strong>Comprobantes de pago</strong>
                <!-- <span>RepuestoFijo emitirá el comprobante de pago correspondiente (boleta o factura) conforme a la
                    normativa tributaria de SUNAT. Para solicitar factura, el usuario debe indicar sus datos fiscales al
                    momento del pedido.</span> -->
                <span>RepuestoFijo emitirá el comprobante de pago boleta electrónica conforme a la
                    normativa tributaria de SUNAT. </span>
            </div>
        </div>

        <!-- 7. DEVOLUCIONES Y RECLAMACIONES -->
        <div class="legal-section" id="t7">
            <div class="section-header">
                <div class="section-num">7</div>
                <h2 class="section-title">Devoluciones y reclamaciones</h2>
            </div>
            <p>Conforme al Código de Protección y Defensa del Consumidor (Ley N° 29571), el usuario tiene derecho a
                reclamar en los siguientes supuestos:</p>
            <ul class="data-list">
                <li><strong>Producto defectuoso:</strong> el repuesto presenta fallas de fabricación o no funciona según
                    sus especificaciones.</li>
                <li><strong>Producto incorrecto:</strong> se entregó un repuesto distinto al solicitado y confirmado.
                </li>
                <li><strong>Pedido no entregado:</strong> el pedido no llegó en el tiempo estimado sin justificación.
                </li>
            </ul>
            <p style="margin-top:14px"><strong>Plazo para reclamar:</strong> el usuario dispone de <strong>48
                    horas</strong> desde la recepción del pedido para reportar cualquier incidencia a través de nuestro
                portal en el area de pedidos.</p>
            <p><strong>Proceso de devolución:</strong> evaluada la reclamación, RepuestoFijo coordinará la recogida del
                producto y gestionará el reemplazo o la devolución del importe pagado en un plazo máximo de <strong>5
                    días hábiles</strong>.</p>
            <div class="alert-box">
                <i class="fas fa-exclamation-triangle"></i>
                <p>No se aceptarán devoluciones de repuestos que hayan sido instalados, modificados o que presenten
                    daños causados por el usuario.</p>
            </div>
        </div>

        <!-- 8. RESPONSABILIDAD -->
        <div class="legal-section" id="t8">
            <div class="section-header">
                <div class="section-num">8</div>
                <h2 class="section-title">Responsabilidad y limitaciones</h2>
            </div>
            <p>RepuestoFijo actúa como intermediario entre el usuario y el proveedor. En consecuencia:</p>
            <ul class="data-list">
                <li>RepuestoFijo <strong>no es responsable</strong> por defectos de fabricación de los repuestos
                    cubiertos por garantía del fabricante.</li>
                <li>RepuestoFijo <strong>no garantiza</strong> la disponibilidad ininterrumpida de la plataforma, aunque
                    se compromete a mantener el servicio operativo con la mayor continuidad posible.</li>
                <li>RepuestoFijo <strong>no se responsabiliza</strong> por daños derivados del uso incorrecto de un
                    repuesto por parte del usuario o de terceros.</li>
                <li>La responsabilidad máxima de RepuestoFijo ante cualquier reclamación estará limitada al importe
                    abonado por el pedido en cuestión.</li>
            </ul>
        </div>

        <!-- 9. PROPIEDAD INTELECTUAL -->
        <div class="legal-section" id="t9">
            <div class="section-header">
                <div class="section-num">9</div>
                <h2 class="section-title">Propiedad intelectual</h2>
            </div>
            <p>Todo el contenido de la plataforma RepuestoFijo — incluyendo el logotipo, diseño, textos, imágenes,
                código fuente y funcionalidades — es propiedad exclusiva de RepuestoFijo Perú o de sus licenciantes, y
                está protegido por las leyes de propiedad intelectual vigentes en el Perú.</p>
            <p>Queda expresamente prohibido reproducir, distribuir, modificar o explotar cualquier contenido de la
                plataforma sin autorización escrita previa de RepuestoFijo.</p>
        </div>

        <!-- 10. SUSPENSIÓN Y CANCELACIÓN -->
        <div class="legal-section" id="t10">
            <div class="section-header">
                <div class="section-num">10</div>
                <h2 class="section-title">Suspensión y cancelación de cuenta</h2>
            </div>
            <p>RepuestoFijo se reserva el derecho de suspender o cancelar la cuenta de un usuario, de forma temporal o
                permanente, en los siguientes casos:</p>
            <ul class="data-list">
                <li>Incumplimiento de estos Términos y Condiciones.</li>
                <li>Uso fraudulento o abusivo de la plataforma.</li>
                <li>Registro con datos falsos o suplantación de identidad.</li>
                <li>Comportamiento ofensivo hacia el equipo de RepuestoFijo o los proveedores.</li>
                <li>Intentos de vulnerar la seguridad de la plataforma.</li>
            </ul>
            <p style="margin-top:14px">El usuario podrá solicitar la cancelación de su cuenta en cualquier momento
                escribiendo a <a href="mailto:soporte@repuestofijo.com" class="link-red">soporte@repuestofijo.com</a>.
                La cancelación no eximirá al usuario de sus obligaciones pendientes (pedidos en curso, deudas, etc.).
            </p>
        </div>

        <!-- 11. PRIVACIDAD -->
        <div class="legal-section" id="t11">
            <div class="section-header">
                <div class="section-num">11</div>
                <h2 class="section-title">Privacidad de datos</h2>
            </div>
            <p>El tratamiento de los datos personales de los usuarios se rige por nuestra Política de Privacidad,
                conforme a la
                Ley N° 29733 de Protección de Datos Personales del Perú. Al aceptar estos Términos y Condiciones, el
                usuario acepta también dicha política.</p>
        </div>

        <!-- 12. MODIFICACIONES -->
        <div class="legal-section" id="t12">
            <div class="section-header">
                <div class="section-num">12</div>
                <h2 class="section-title">Modificaciones del servicio</h2>
            </div>
            <p>RepuestoFijo se reserva el derecho de modificar, suspender o discontinuar cualquier aspecto de la
                plataforma en cualquier momento, incluyendo estos Términos y Condiciones. Las modificaciones importantes
                serán notificadas a los usuarios por correo electrónico o mediante aviso en la plataforma con al menos
                <strong>15 días de anticipación</strong>.
            </p>
            <p>El uso continuado de la plataforma tras la publicación de cambios implica la aceptación de los nuevos
                términos.</p>
        </div>

        <!-- 13. LEY APLICABLE -->
        <div class="legal-section" id="t13">
            <div class="section-header">
                <div class="section-num">13</div>
                <h2 class="section-title">Ley aplicable y jurisdicción</h2>
            </div>
            <p>Estos Términos y Condiciones se rigen por las leyes de la República del Perú. Cualquier controversia o
                reclamación derivada del uso de la plataforma que no pueda resolverse de forma amistosa será sometida a
                la jurisdicción de los tribunales competentes de la ciudad de Lima, con renuncia expresa al fuero que
                pudiera corresponder.</p>
            <p>Sin perjuicio de lo anterior, el usuario siempre podrá recurrir al <strong>INDECOPI</strong> (Instituto
                Nacional de Defensa de la Competencia y de la Protección de la Propiedad Intelectual) para la resolución
                de conflictos de consumo.</p>
        </div>

        <!-- 14. CONTACTO -->
        <div class="legal-section" id="t14">
            <div class="section-header">
                <div class="section-num">14</div>
                <h2 class="section-title">Contacto</h2>
            </div>
            <p>Para cualquier consulta, duda o reclamación relacionada con estos Términos y Condiciones, puedes
                contactarnos a través de:</p>
            <div class="info-box">
                <strong>Repuesto Fijo</strong>
                <span>RUC: 10421922557</span>
                <span>Domicilio fiscal: Calle Filadelfia 2453, San Martín de Porres, Lima, Perú</span>
                <span>Correo de atención: <a href="mailto:soporte@repuestofijo.com">soporte@repuestofijo.com</a></span>
                <span>Correo de privacidad: <a
                        href="mailto:privacidad@repuestofijo.com">privacidad@repuestofijo.com</a></span>
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

    <script>if (window.self !== window.top) { document.body.classList.add('in-iframe'); }</script>

</body>

</html>