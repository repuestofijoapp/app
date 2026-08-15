<div class="main-container"
    x-on:process-payment.window="$wire.processCulqiPayment($event.detail.token, $event.detail.email)">
    {{-- PRIVACY POLICY MODAL (Global) --}}
    @if($showPrivacyModal)
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="background: rgba(0,0,0,0.9); z-index: 999999; backdrop-filter: blur(10px);"
            wire:key="privacy-modal-global-top">
            <div class="col-11 col-md-8 col-lg-6" style="z-index: 1000000;">
                <div class="card bg-white shadow-2xl border-0 rounded-4 overflow-hidden d-flex flex-column"
                    style="font-family: 'Montserrat', sans-serif; height: auto; max-height: 85vh;">
                    <div class="p-4 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold text-dark mb-0">Política de Privacidad</h4>
                        <button type="button" class="btn-close" wire:click="closePrivacyModal"></button>
                    </div>
                    <div class="p-4 p-md-5 flex-grow-1 overflow-auto custom-scrollbar text-dark"
                        style="font-size: 0.95rem; line-height: 1.6; max-height: calc(85vh - 160px);">
                        <div class="text-center mb-5">
                            <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo" class="mb-3"
                                style="max-height: 40px; filter: grayscale(1); opacity: 0.8;">
                            <div class="d-block mt-2">
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-1">Versión
                                    1.0</span>
                            </div>
                        </div>

                        <p class="mb-4">Al registrarte en <strong>RepuestoFijo</strong> y utilizar nuestros servicios,
                            aceptas los términos descritos en esta Política de Privacidad. Te recomendamos leerla con
                            atención. Si tienes alguna duda, puedes contactarnos en cualquier momento.</p>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">1. ¿Quiénes somos?</h5>
                        <p>RepuestoFijo es una plataforma digital peruana que conecta mecánicos y talleres automotrices con
                            proveedores de repuestos para pedidos urgentes y entregas rápidas. Operamos a través de nuestra
                            aplicación web y gestionamos los pedidos mediante un sistema automatizado.</p>
                        <div class="p-3 bg-light rounded-3 mb-4">
                            <strong>Responsable del tratamiento de datos:</strong><br>
                            RepuestoFijo Perú<br>
                            <strong>Correo de contacto:</strong> <a href="mailto:privacidad@repuestofijo.com"
                                class="text-danger">privacidad@repuestofijo.com</a>
                        </div>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">2. ¿Qué datos recopilamos?</h5>
                        <h6 class="fw-bold small text-muted text-uppercase mb-2">2.1 Datos que obtenemos al registrarte con
                            Google</h6>
                        <p>Cuando inicias sesión con tu cuenta de Google, recibimos automáticamente la siguiente información
                            con tu autorización:</p>
                        <ul class="ps-3 mb-4">
                            <li>Nombre completo</li>
                            <li>Dirección de correo electrónico</li>
                            <li>Foto de perfil de Google</li>
                            <li>Identificador único de Google (Google ID)</li>
                            <li>Idioma preferido del navegador</li>
                        </ul>

                        <h6 class="fw-bold small text-muted text-uppercase mb-2">2.2 Datos que recopilamos durante el uso de
                            la plataforma</h6>
                        <p>Adicionalmente, durante tu interacción con RepuestoFijo recopilamos:</p>
                        <ul class="ps-3 mb-4">
                            <li><strong>Datos del perfil:</strong> nombre del taller, distrito, tipo de vehículos que
                                atiendes.</li>
                            <li><strong>Historial de pedidos:</strong> productos solicitados, cantidades, fechas y estados
                                de cada pedido.</li>
                            <li><strong>Dirección de entrega:</strong> distrito y dirección donde recibes tus pedidos.</li>
                            <li><strong>Comportamiento en la app:</strong> productos buscados, productos no encontrados,
                                hora de actividad.</li>
                            <li><strong>Datos de pago:</strong> gestionados de forma segura por nuestra pasarela de pagos
                                (Culqi). No almacenamos datos de tarjetas.</li>
                            <li><strong>Incidencias reportadas:</strong> cualquier problema que registres sobre un pedido.
                            </li>
                        </ul>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">3. ¿Para qué usamos tus datos?</h5>
                        <p>Utilizamos la información recopilada exclusivamente para los siguientes fines:</p>
                        <ul class="ps-3 mb-4">
                            <li>Gestionar tu registro y acceso a la plataforma.</li>
                            <li>Procesar y coordinar tus pedidos de repuestos.</li>
                            <li>Coordinar la entrega mediante motorizado a tu dirección.</li>
                            <li>Enviarte notificaciones sobre el estado de tus pedidos.</li>
                            <li>Mejorar la experiencia de uso de la plataforma.</li>
                            <li>Avisarte cuando un producto que buscaste y no encontraste esté disponible.</li>
                            <li>Gestionar el sistema de créditos and beneficios por fidelidad.</li>
                            <li>Detectar y prevenir usos fraudulentos del sistema.</li>
                            <li>Cumplir con obligaciones legales and fiscales en Perú.</li>
                        </ul>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">4. ¿Con quién compartimos tus datos?</h5>
                        <p>RepuestoFijo <strong>NO vende ni cede</strong> tus datos personales a terceros con fines
                            comerciales. Únicamente compartimos información en los siguientes casos:</p>
                        <ul class="ps-3 mb-4">
                            <li><strong>Con proveedores de repuestos:</strong> únicamente el detalle del pedido (productos,
                                cantidades y distrito de entrega). Nunca compartimos tu nombre completo ni datos de contacto
                                directo.</li>
                            <li><strong>Con el servicio de motorizado:</strong> nombre, distrito and dirección de entrega,
                                exclusivamente para coordinar la entrega de tu pedido.</li>
                            <li><strong>Con Culqi (pasarela de pagos):</strong> para procesar el cobro de forma segura.</li>
                            <li><strong>Con authorities competentes:</strong> si la ley peruana lo exige.</li>
                        </ul>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">5. ¿Cómo protegemos tus datos?</h5>
                        <p>Implementamos medidas técnicas and organizativas para proteger tu información personal:</p>
                        <ul class="ps-3 mb-4">
                            <li>Conexiones cifradas mediante protocolo HTTPS en toda la plataforma.</li>
                            <li>Acceso restringido a los datos únicamente al personal autorizado.</li>
                            <li>Datos de pago gestionados exclusivamente por Culqi bajo estándares PCI DSS.</li>
                            <li>Contraseñas y tokens de acceso almacenados de forma cifrada.</li>
                        </ul>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">6. Tus derechos sobre tus datos</h5>
                        <p>De acuerdo con la Ley N° 29733 de Protección de Datos Personales del Perú, tienes los siguientes
                            derechos:</p>
                        <ul class="ps-3 mb-4">
                            <li><strong>Acceso:</strong> solicitar qué datos tuyos tenemos almacenados.</li>
                            <li><strong>Rectificación:</strong> corregir datos incorrectos o desactualizados.</li>
                            <li><strong>Cancelación:</strong> solicitar la eliminación de tus datos personales.</li>
                            <li><strong>Oposición:</strong> oponerte al tratamiento de tus datos para determinados fines.
                            </li>
                        </ul>
                        <p>Para ejercer cualquiera de estos derechos, escríbenos a: <a
                                href="mailto:privacidad@repuestofijo.pe"
                                class="text-danger fw-bold">privacidad@repuestofijo.pe</a>. Responderemos tu solicitud en un
                            plazo máximo de 10 días hábiles.</p>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">7. Cookies and tecnologías de seguimiento</h5>
                        <p>RepuestoFijo utiliza cookies técnicas esenciales para el funcionamiento de la plataforma, como
                            mantener tu sesión iniciada. No utilizamos cookies de publicidad ni de seguimiento de terceros.
                        </p>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">8. Menores de edad</h5>
                        <p>RepuestoFijo está destinado exclusivamente a personas mayores de 18 años. No recopilamos
                            intencionalmente datos de menores de edad.</p>

                        <h5 class="fw-bold mt-4 mb-3" style="color: #BE3C3B;">9. Cambios en esta política</h5>
                        <p>Podemos actualizar esta Política de Privacidad ocasionalmente. Cuando realicemos cambios
                            importantes te notificaremos por correo electrónico.</p>

                        <div class="mt-5 pt-4 border-top text-center text-muted small">
                            Última actualización: marzo 2026 — Versión 1.0<br>
                            RepuestoFijo — Perú
                        </div>
                    </div>
                    <div class="p-4 border-top bg-light text-center">
                        <button type="button" class="btn btn-dark px-5 py-2 rounded-pill fw-bold"
                            wire:click="acceptPrivacyPolicy">
                            He leído y acepto los términos
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    {{-- 1. MAIN NAVIGATION HEADER --}}
    <header class="bg-header-custom py-2 border-bottom border-secondary border-opacity-25 shadow"
        style="position: fixed; top: 0; left: 0; right: 0; z-index: 1020;">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 gap-md-3">
            <div class="logo text-center text-md-start">
                <a href="#" wire:click.prevent="resetToHome" class="text-decoration-none d-block">
                    <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo" class="img-fluid logo-main"
                        style="width: auto; height: auto; max-width: 180px; max-height: 45px; object-fit: contain; cursor: pointer;">
                </a>
            </div>

            {{-- Search OEM Field (Header) --}}
            <div class="input-group overflow-hidden shadow-sm w-100 @if($viewState === 'repair_summary') d-none d-md-flex @else d-flex @endif"
                style="max-width: 600px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
                <input type="text" class="form-control form-control-custom border-0"
                    placeholder="Busca por código OEM..." wire:model="oemSearch"
                    wire:keydown.enter="performSearch('oem')"
                    style="height: 48px !important; font-size: 1.1rem; line-height: 1; padding: 0 20px;">

                {{-- Mobile Search Button (RED) --}}
                <button class="btn btn-danger d-md-none d-flex align-items-center justify-content-center"
                    wire:click="performSearch('oem')" style="width: 50px; height: 48px !important;">
                    <i class="fas fa-search"></i>
                </button>

                {{-- Desktop Search Button (BLUE/Original) --}}
                <button
                    class="btn btn-primary-custom px-4 fw-bold d-none d-md-flex align-items-center justify-content-center"
                    wire:click="performSearch('oem')" style="height: 48px !important; letter-spacing: 1px;">
                    <span>BUSCAR</span>
                </button>
            </div>

            <div class="d-flex align-items-center gap-3">
                {{-- User Session & Cart Grouped --}}
                <div class="d-flex align-items-center gap-2">
                    @auth
                        <button wire:click="toggleOrders"
                            class="btn p-0 border-0 shadow-none hover-scale-sm transition-all d-none d-md-block">
                            @if(auth()->user()->profile_photo_path)
                                <img src="{{ auth()->user()->profile_photo_path }}" alt="User"
                                    class="rounded-circle border border-white border-opacity-50 shadow-sm"
                                    style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center border border-white border-opacity-25"
                                    style="width: 45px; height: 45px;">
                                    <i class="fas fa-user text-white opacity-75 fs-5"></i>
                                </div>
                            @endif
                        </button>
                    @endauth

                    {{-- Cart Indicator --}}
                    <div class="cart-indicator d-none d-md-flex align-items-center bg-white bg-opacity-10 rounded-pill p-1 pe-4 border border-secondary border-opacity-25 cursor-pointer hover-bg-white-10 transition-all"
                        wire:click="toggleRepairSummary" style="height: 48px;">
                        <div class="position-relative me-2">
                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                style="width: 38px; height: 38px;">
                                <span class="text-white fw-bold" style="font-size: 0.9rem;">
                                    {{ array_sum(array_column($repairList, 'qty')) }}
                                </span>
                            </div>
                        </div>
                        <div class="text-start">
                            <div class="small text-white-50"
                                style="font-size: 0.65rem; line-height: 1; text-transform: uppercase; letter-spacing: 0.5px;">
                                Mi</div>
                            <div class="fw-bold text-white" style="line-height: 1.1; font-size: 0.85rem;">reparación
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- 3. INTERMEDIATE SECTION --}}
    <div class="bg-section-custom" style="padding-top: 130px; padding-bottom: 40px;">
        <div class="container pt-5 pt-md-0">
            {{-- ALERTS --}}

            {{-- SEARCH + BANNER --}}
            @if(!$showLeadForm && in_array($viewState, ['default', 'categories', 'product_list', 'initial', '', null]))
                <div class="row g-4">
                    {{-- LEFT: PLATE SEARCH & FILTERS --}}
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="card card-custom h-100 p-4 shadow-sm border-0"
                            style="background-color: #132530; overflow: visible;">
                            @if(\App\Models\SystemSetting::getBool('enable_plate_search', true))
                                <span class="badge px-3 py-1 rounded-pill"
                                    style="background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; font-size:0.72rem;">
                                    ⭐ Acceso Pro — por tiempo limitado
                                </span>
                                <h6 class="fw-medium mb-3 text-white">Buscar modelo de auto por placa</h6>

                                {{-- Plate Input (Peru Style) - RE-DESIGNED --}}
                                <div class="input-group plate-search-group mb-4 shadow-sm">
                                    <span class="input-group-text peru-badge border-0 px-2 py-0">
                                        <span class="peru-text">PERU</span>
                                    </span>
                                    <input type="text" class="form-control peru-plate-input text-center fw-bold fs-4 border-0"
                                        placeholder="ABC123" wire:model="plateSearch"
                                        wire:keydown.enter="performSearch('plate')" maxlength="7"
                                        style="text-transform: uppercase; background: #fff !important; color: #333 !important;">
                                    <button class="btn btn-danger btn-buscar-plate px-4 fw-bold border-0"
                                        wire:click="performSearch('plate')">
                                        BUSCAR
                                    </button>
                                </div>
                            @endif

                            <h6
                                class="fw-medium mb-3 text-white {{ \App\Models\SystemSetting::getBool('enable_plate_search', true) ? 'mt-2' : '' }} d-flex align-items-center justify-content-between">
                                Seleccione su modelo de auto
                                <i class="fas fa-sync-alt text-white ms-2 cursor-pointer btn-reload-manual"
                                    wire:click="clearManualSearch" title="Limpiar búsqueda"></i>
                            </h6>

                            {{-- MARCAS RECONOCIDAS (con color) + otras --}}
                            <div class="mb-3 position-relative" x-data="{ open: false }" @click.outside="open = false">
                                <div class="filter-step @if(!$selectedBrand) step-active @else step-done @endif">1</div>
                                {{-- Brand button --}}
                                <button type="button"
                                    class="btn w-100 text-start ps-5 py-2 pe-4 bg-white border-0 position-relative d-flex align-items-center justify-content-between"
                                    style="height: auto; min-height: 45px; border-radius: 4px;" @click="open = !open">
                                    <span class="text-wrap me-2" style="font-size: 0.9rem; color: #333;">
                                        @if($selectedBrand)
                                            {{ strtoupper($selectedBrand) }}
                                        @else
                                            <span class="text-muted">Elija una marca</span>
                                        @endif
                                    </span>
                                    <i class="fas fa-chevron-down small text-muted"></i>
                                </button>
                                {{-- Dropdown panel --}}
                                <div x-show="open" x-cloak x-transition
                                    class="position-absolute w-100 bg-white shadow-lg border-0"
                                    style="max-height: 380px; overflow-y: auto; border-radius: 4px; top: 100%; left: 0; z-index: 9999;">

                                    @if(!empty($priorityBrands))
                                        <div class="px-3 py-2 bg-light border-bottom"
                                            style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; color: #6c757d;">
                                            MARCAS RECONOCIDAS
                                        </div>
                                        @foreach($priorityBrands as $brand)
                                            @php $hasStock = in_array($brand, $brandsWithProducts); @endphp
                                            <button type="button"
                                                class="w-100 text-start border-0 border-bottom border-light d-flex align-items-center gap-2 px-3 py-2"
                                                style="background: #fff; font-size: 0.9rem; color: {{ $hasStock ? '#1e293b' : '#9ca3af' }}; cursor: {{ $hasStock ? 'pointer' : 'default' }};"
                                                {{ !$hasStock ? 'disabled' : '' }} @click="open = false" @if($hasStock)
                                                wire:click="selectBrand('{{ $brand }}')" @endif
                                                title="{{ $hasStock ? 'Tiene productos en stock' : 'Sin productos disponibles aún' }}"
                                                onmouseover="{{ $hasStock ? "this.style.background='#f0f6ff'" : '' }}"
                                                onmouseout="{{ $hasStock ? "this.style.background='#fff'" : '' }}">
                                                <span
                                                    style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: {{ $hasStock ? '#2563EB' : '#DC2626' }}; flex-shrink: 0;"></span>
                                                <span class="fw-medium">{{ strtoupper($brand) }}</span>
                                                @if(!$hasStock)
                                                    <span style="font-size: 0.68rem; color: #DC2626; margin-left: auto;">sin
                                                        stock</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    @endif

                                    @if(!empty($alphabeticalBrands))
                                        <div class="px-3 py-2 bg-light border-bottom border-top"
                                            style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.5px; color: #6c757d;">
                                            OTRAS MARCAS
                                        </div>
                                        @foreach($alphabeticalBrands as $brand)
                                            <button
                                                class="w-100 text-start border-0 border-bottom border-light d-flex align-items-center gap-2 px-3 py-2"
                                                type="button" style="background: #fff; font-size: 0.9rem; color: #1e293b;"
                                                @click="open = false" wire:click="selectBrand('{{ $brand }}')"
                                                onmouseover="this.style.background='#f0f6ff'"
                                                onmouseout="this.style.background='#fff'">
                                                <span
                                                    style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #2563EB; flex-shrink: 0;"></span>
                                                <span>{{ strtoupper($brand) }}</span>
                                            </button>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- MODELO --}}
                            <div class="mb-3 position-relative" x-data="{ open: false }" @click.outside="open = false">
                                <div
                                    class="filter-step @if($selectedBrand && !$selectedModel) step-active @elseif($selectedModel) step-done @else step-inactive @endif">
                                    2</div>
                                <button type="button"
                                    class="btn w-100 text-start ps-5 py-2 pe-4 bg-white border-0 position-relative d-flex align-items-center justify-content-between"
                                    style="height: auto; min-height: 45px; border-radius: 4px; {{ empty($selectedBrand) ? 'opacity:.5; cursor:not-allowed;' : '' }}"
                                    @click="if($wire.selectedBrand) open = !open">
                                    <span class="text-wrap me-2" style="font-size: 0.9rem; color: #333;">
                                        @if($selectedModel)
                                            @php $selMod = collect($models)->firstWhere('id', $selectedModel); @endphp
                                            {{ strtoupper($selMod['label'] ?? 'Modelo seleccionado') }}
                                        @else
                                            <span class="text-muted">Elija un modelo</span>
                                        @endif
                                    </span>
                                    <i class="fas fa-chevron-down small text-muted"></i>
                                </button>
                                <div x-show="open" x-transition class="position-absolute w-100 bg-white shadow-lg"
                                    style="max-height: 300px; overflow-y: auto; border-radius: 4px; top: 100%; left: 0; z-index: 9999;">
                                    @foreach($models as $model)
                                        <button class="dropdown-item w-100 text-start" type="button" @click="open = false"
                                            wire:click="selectModel('{{ $model['id'] }}')">
                                            <div class="text-wrap px-3 py-2" style="font-size: 0.9rem; color: #111;">
                                                {{ strtoupper($model['label']) }}
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- MOTOR --}}
                            <div class="mb-4 position-relative" x-data="{ open: false }" @click.outside="open = false">
                                <div
                                    class="filter-step @if($selectedModel && !$selectedEngine) step-active @elseif($selectedEngine) step-done @else step-inactive @endif">
                                    3</div>
                                <button type="button"
                                    class="btn w-100 text-start ps-5 py-2 pe-4 bg-white border-0 position-relative d-flex align-items-center justify-content-between"
                                    style="height: auto; min-height: 45px; border-radius: 4px; {{ empty($selectedModel) ? 'opacity:.5; cursor:not-allowed;' : '' }}"
                                    @click="if($wire.selectedModel) open = !open">
                                    <span class="text-wrap me-2" style="font-size: 0.9rem; color: #333;">
                                        @if($selectedEngine)
                                            @php $selEng = collect($engines)->firstWhere('id', $selectedEngine); @endphp
                                            {{ $selEng['label'] ?? 'Tipo de motor seleccionado' }}
                                        @else
                                            <span class="text-muted">Elija un tipo de motor</span>
                                        @endif
                                    </span>
                                    <i class="fas fa-chevron-down small text-muted"></i>
                                </button>
                                <div x-show="open" x-transition
                                    class="position-absolute w-100 bg-white shadow-lg border-0 py-0"
                                    style="max-height: 300px; overflow-y: auto; border-radius: 4px; top: 100%; left: 0; z-index: 9999;">
                                    @foreach($engines as $engine)
                                        <button class="dropdown-item w-100 text-start" type="button" @click="open = false"
                                            wire:click="selectEngine('{{ $engine['id'] }}')">
                                            <div class="fw-medium px-3 py-2"
                                                style="font-size: 0.9rem; white-space: normal; color: #111;">
                                                {{ $engine['label'] }}
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <button class="btn btn-danger w-100 py-2 shadow-sm fw-medium" wire:click="performManualSearch">
                                BUSCAR
                            </button>
                        </div>
                    </div>

                    {{-- RIGHT: BANNER SLIDER (dinámico) --}}
                    <div class="col-lg-8">
                        @php $bannerSlides = \App\Models\BannerSlide::getActive(); @endphp

                        @if($bannerSlides->isEmpty())
                            {{-- Fallback: cuadro rojo original si no hay slides --}}
                            <div
                                class="promo-content d-flex align-items-center h-100 bg-orange p-4 p-md-5 text-white rounded shadow-sm position-relative overflow-hidden">
                                <div class="z-1">
                                    <div class="bg-dark bg-opacity-25 d-inline-block p-2 rounded mb-3">
                                        <i class="fas fa-bullhorn fa-2x"></i>
                                    </div>
                                    <h1 class="display-5 display-md-4 fw-medium mb-2">¡Consigue un cupón de S/.50.00</h1>
                                    <p class="lead mb-4 fw-medium">al suscribirte a nuestra newsletter!</p>
                                    <button class="btn btn-dark btn-lg fw-medium px-4 px-md-5 py-2 py-md-3 shadow"
                                        style="border-radius: 3px;">SUSCRÍBETE AHORA</button>
                                </div>
                                <div
                                    class="position-absolute end-0 bottom-0 opacity-25 translate-middle-x mb-5 me-5 d-none d-md-block">
                                    <i class="fas fa-envelope fa-10x"></i>
                                </div>
                            </div>
                        @else
                            {{-- Slider de banners --}}
                            <div id="repuestoBannerSlider" class="carousel slide h-100" data-bs-ride="carousel"
                                data-bs-interval="5000">


                                {{-- Slides --}}
                                <div class="carousel-inner rounded shadow-sm" style="height:100%; min-height:220px;">
                                    @foreach($bannerSlides as $i => $slide)
                                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }} h-100">
                                            <img src="{{ Storage::url($slide->image_path) }}" class="d-block w-100 h-100"
                                                style="object-fit:cover; object-position:center;"
                                                alt="{{ $slide->title ?? 'Banner promocional' }}">

                                            {{-- Overlay con texto (si tiene título) --}}
                                            @if($slide->title || $slide->button_text)
                                                <div class="carousel-caption d-flex flex-column justify-content-end text-start"
                                                    style="background: linear-gradient(to top, rgba(0,0,0,0.6) 0%, transparent 100%); inset:0; border-radius: inherit; padding: 2rem 2.5rem;">
                                                    @if($slide->title)
                                                        <h4 class="fw-bold text-white mb-1 drop-shadow"
                                                            style="text-shadow:0 2px 8px rgba(0,0,0,0.6);">
                                                            {{ $slide->title }}
                                                        </h4>
                                                    @endif
                                                    @if($slide->subtitle)
                                                        <p class="text-white mb-2 small" style="text-shadow:0 1px 4px rgba(0,0,0,0.5);">
                                                            {{ $slide->subtitle }}
                                                        </p>
                                                    @endif
                                                    @if($slide->button_text)
                                                        <div>
                                                            <a href="{{ $slide->button_url ?: '#' }}"
                                                                class="btn btn-danger btn-sm fw-bold px-3" style="border-radius:4px;">
                                                                {{ $slide->button_text }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Controles prev/next si hay más de 1 --}}
                                @if($bannerSlides->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#repuestoBannerSlider"
                                        data-bs-slide="prev" style="width:40px;">
                                        <span class="carousel-control-prev-icon"
                                            style="filter:drop-shadow(0 1px 3px rgba(0,0,0,0.5));"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#repuestoBannerSlider"
                                        data-bs-slide="next" style="width:40px;">
                                        <span class="carousel-control-next-icon"
                                            style="filter:drop-shadow(0 1px 3px rgba(0,0,0,0.5));"></span>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>
            @endif

            {{-- ── NOVEDADES EN REPUESTOFIJO (Slider de productos destacados) ── --}}
            @if($viewState === 'initial')
                @php $featuredProductsList = \App\Models\FeaturedProduct::getActive(); @endphp

                @if($featuredProductsList->count() > 0)
                    <div class="mt-5 mb-5 w-100">
                        <h4 class="text-center mb-4"
                            style="color: #4B5563; font-family: 'Syne', sans-serif; letter-spacing: 1px;">
                            NOVEDADES
                        </h4>

                        <div class="position-relative px-4">
                            {{-- Left Arrow --}}
                            <button
                                class="btn btn-light shadow-sm position-absolute start-0 top-50 translate-middle-y z-1 rounded-0 d-flex align-items-center justify-content-center"
                                onclick="document.getElementById('featured-products-container').scrollBy({left: -260, behavior: 'smooth'})"
                                style="width: 40px; height: 40px; border: 1px solid #dee2e6; background-color: white;">
                                <i class="fas fa-chevron-left" style="color: #6c757d;"></i>
                            </button>

                            {{-- Scroll Container --}}
                            <style>
                                .hide-scrollbar::-webkit-scrollbar {
                                    display: none;
                                }

                                .hide-scrollbar {
                                    -ms-overflow-style: none;
                                    scrollbar-width: none;
                                }
                            </style>
                            <div id="featured-products-container" class="d-flex gap-4 overflow-x-auto hide-scrollbar"
                                style="scroll-snap-type: x mandatory; padding: 10px 0; scroll-behavior: smooth;">
                                @foreach($featuredProductsList as $fp)
                                    <div class="card border-0 flex-shrink-0"
                                        style="width: 240px; scroll-snap-align: start; background-color: #F8F9FB;">
                                        <div class="card-body text-center p-4 d-flex flex-column h-100">

                                            <div style="height: 140px;"
                                                class="mb-3 d-flex align-items-center justify-content-center">
                                                <img src="{{ $fp->product->image_path ? Storage::url($fp->product->image_path) : 'https://via.placeholder.com/150?text=Sin+Imagen' }}"
                                                    class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                            </div>

                                            <div class="position-relative mb-3">
                                                <hr class="border-secondary opacity-25 m-0">
                                                <div class="position-absolute top-50 start-50 translate-middle"
                                                    style="width: 6px; height: 6px; background-color: #f97316; border-radius: 50%;">
                                                </div>
                                            </div>

                                            <h6 class="mb-3 text-secondary px-1"
                                                style="font-size: 0.85rem; min-height: 3.6rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $fp->product->name }}
                                            </h6>

                                            <div class="mt-auto pt-2">
                                                <button wire:click="searchFeaturedProduct({{ $fp->product->id }})"
                                                    class="btn w-100 fw-bold py-2 text-white shadow-sm"
                                                    style="background-color: #132530; font-size: 0.85rem; border-radius: 3px;">
                                                    Descubrir ahora
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Right Arrow --}}
                            <button
                                class="btn btn-light shadow-sm position-absolute end-0 top-50 translate-middle-y z-1 rounded-0 d-flex align-items-center justify-content-center"
                                onclick="document.getElementById('featured-products-container').scrollBy({left: 260, behavior: 'smooth'})"
                                style="width: 40px; height: 40px; border: 1px solid #dee2e6; background-color: white;">
                                <i class="fas fa-chevron-right" style="color: #6c757d;"></i>
                            </button>
                        </div>
                    </div>
                @endif
            @endif

            {{-- UNIVERSAL BREADCRUMB BANNER --}}
            @if(in_array($viewState, ['vehicle_found', 'subcategories', 'products_list']) && ($vehicle || $selectedEngineObj || $searchType === 'oem'))
                <style>
                    /* DESKTOP BREADCRUMB */
                    .breadcrumb-chevron-container {
                        display: flex;
                        align-items: center;
                        width: 100%;
                    }

                    .chevron-step {
                        height: 48px;
                        padding: 0 22px;
                        font-size: 14px;
                        font-weight: 500;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        margin-right: -12px;
                        cursor: pointer;
                        transition: opacity 0.2s;
                        position: relative;
                    }

                    .chevron-step:hover {
                        opacity: 0.9;
                    }

                    .chevron-step-red {
                        background-color: #C0392B;
                        color: #FCEBEB;
                    }

                    .chevron-step-navy {
                        background-color: #0b3955ff;
                        color: #ffffff;
                    }

                    .chevron-step-first {
                        clip-path: polygon(0 0, calc(100% - 14px) 0, 100% 50%, calc(100% - 14px) 100%, 0 100%);
                        border-radius: 8px 0 0 8px;
                    }

                    .chevron-step-middle {
                        clip-path: polygon(0 0, calc(100% - 14px) 0, 100% 50%, calc(100% - 14px) 100%, 0 100%, 14px 50%);
                    }

                    .chevron-step-last {
                        clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 14px 50%);
                        border-radius: 0 8px 8px 0;
                        margin-right: 0;
                    }

                    .chevron-step-single {
                        clip-path: none;
                        border-radius: 8px;
                        margin-right: 0;
                    }

                    .chevron-step-red.chevron-step-middle,
                    .chevron-step-red.chevron-step-last,
                    .chevron-step-navy.chevron-step-middle,
                    .chevron-step-navy.chevron-step-last {
                        padding-left: 20px;
                    }

                    .chevron-text {
                        max-width: 180px;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }

                    /* MOBILE CHIPS */
                    .mobile-chips-wrapper {
                        display: flex;
                        overflow-x: auto;
                        align-items: center;
                        gap: 8px;
                        padding-bottom: 4px;
                    }

                    .mobile-chips-wrapper::-webkit-scrollbar {
                        height: 0px;
                    }

                    .mobile-chip {
                        border-radius: 20px;
                        padding: 7px 12px;
                        font-size: 12px;
                        font-weight: 500;
                        display: flex;
                        align-items: center;
                        gap: 5px;
                        flex-shrink: 0;
                        cursor: pointer;
                    }

                    .mobile-chip-red {
                        background-color: #C0392B;
                        color: #FCEBEB;
                        border: none !important;
                    }

                    .mobile-chip-navy {
                        background-color: #0b3955ff;
                        color: #ffffff;
                        border: none !important;
                    }

                    .chip-separator {
                        font-size: 12px;
                        color: #999;
                    }

                    /* DETAIL CARD */
                    .rf-detail-card {
                        border-radius: 12px;
                        padding: 14px 18px;
                        display: flex;
                        gap: 14px;
                        flex-wrap: wrap;
                        background-color: #F8F9FA;
                        align-items: center;
                        border: 1px solid #E9ECEF;
                    }

                    /* STICKY SCROLL INTEGRATION */
                    .rf-sticky-banner .rf-detail-card-desktop {
                        transition: border-radius 0.3s ease, box-shadow 0.3s ease, margin-bottom 0.3s ease, padding 0.3s ease;
                    }

                    .rf-sticky-banner .rf-breadcrumb-row {
                        transition: margin-bottom 0.3s ease;
                    }

                    .rf-sticky-banner .rf-mobile-card {
                        transition: border-radius 0.3s ease, box-shadow 0.3s ease, margin-bottom 0.3s ease;
                    }

                    .rf-sticky-banner.is-scrolled .rf-detail-card-desktop {
                        border-radius: 0 !important;
                        box-shadow: none !important;
                        margin-bottom: 0 !important;
                        padding-top: 8px !important;
                        padding-bottom: 8px !important;
                        border: none !important;
                        border-bottom: 1px solid #dee2e6 !important;
                    }

                    .rf-sticky-banner.is-scrolled .rf-breadcrumb-row {
                        margin-bottom: 0 !important;
                    }

                    .rf-sticky-banner.is-scrolled .rf-mobile-card {
                        border-radius: 0 !important;
                        box-shadow: none !important;
                        margin-bottom: 0 !important;
                        border-left: none !important;
                        border-right: none !important;
                        border-top: none !important;
                    }
                </style>

                <div x-data="{ isScrolled: false }" x-init="
                            const checkScroll = () => { isScrolled = window.scrollY > 20; };
                            window.addEventListener('scroll', checkScroll, { passive: true });
                            checkScroll();
                        " :class="{ 'is-scrolled': isScrolled }" class="rf-sticky-banner position-sticky z-3 mb-4"
                    style="top: 70px; transition: top 0.3s ease;">

                    @php
                        $carImage = null;
                        $brandName = $vehicle ? $vehicle->brand : ($selectedEngineObj['brand'] ?? null);
                        $modelName = $vehicle ? $vehicle->model : ($selectedEngineObj['model'] ?? null);
                        if ($brandName && $modelName) {
                            $cm = \App\Models\CarModel::where('name', $modelName)->whereHas('make', function ($q) use ($brandName) {
                                $q->where('name', $brandName);
                            })->first();
                            if ($cm && $cm->image) {
                                $carImage = asset('images/cars/' . $cm->image);
                            } else {
                                $carImage = asset('images/cars/Car_hide.webp');
                            }
                        }
                    @endphp

                    @if($searchType === 'oem')
                        <div class="d-flex bg-white rounded shadow-sm mb-3 align-items-center"
                            style="padding: 12px 18px; border: 1px solid #E9ECEF;">
                            <div>
                                <h5 class="mb-0 fw-medium text-dark">
                                    Resultados para: <span class="fw-bold text-danger text-uppercase">{{ $oemSearch }}</span>
                                </h5>
                            </div>
                            <div class="ms-auto fw-medium" style="font-size: 13px; color: #C0392B;">
                                {{ isset($products) ? (method_exists($products, 'total') ? $products->total() : $products->count()) : 0 }}
                                producto(s) encontrado(s)
                            </div>
                        </div>
                    @else
                        @php
                            $steps = [];

                            if ($vehicle || $selectedEngineObj) {
                                $steps[] = [
                                    'label' => 'Vehículo',
                                    'icon' => 'fas fa-car',
                                    'action' => "\$set('viewState', 'vehicle_found')",
                                ];
                            }

                            if (in_array($viewState, ['subcategories', 'products_list'])) {
                                $steps[] = [
                                    'label' => $selectedCategory->name ?? 'Categorías',
                                    'icon' => 'fas fa-cogs',
                                    'action' => "\$set('viewState', 'subcategories')",
                                ];
                            }

                            if ($viewState === 'products_list') {
                                $steps[] = [
                                    'label' => $selectedSubcategory->name ?? 'Subcategoría',
                                    'icon' => 'fas fa-list',
                                    'action' => null,
                                ];
                            }

                            $totalSteps = count($steps);
                        @endphp

                        {{-- DESKTOP DETAIL CARD --}}
                        <div class="rf-detail-card-desktop d-none d-md-flex bg-white rounded shadow-sm mb-3 align-items-center"
                            style="padding: 12px 18px; border: 1px solid #E9ECEF;">
                            <div style="width: 65px; height: 50px; border-radius: 8px; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; background-color: #fff;"
                                class="flex-shrink-0 me-3">
                                <img src="{{ $carImage ?? asset('images/cars/Car_hide.webp') }}" alt="Auto"
                                    style="width: 95%; height: 95%; object-fit: contain;">
                            </div>

                            <div class="d-flex align-items-center gap-4">
                                <div class="fw-medium text-dark text-uppercase" style="font-size: 14px;">
                                    {{ $brandName }} {{ $modelName }}
                                </div>

                                <div class="d-flex gap-3 text-secondary" style="font-size: 13px;">
                                    @if($vehicle && $searchType === 'plate')
                                        <span>Placa: <strong class="text-dark">{{ $vehicle->plate }}</strong></span>
                                    @endif
                                    @if($vehicle && $vehicle->engine_code)
                                        <span>Motor: <strong class="text-dark">{{ $vehicle->engine_code }}</strong></span>
                                    @elseif($selectedEngineObj && $selectedEngineObj['engine_code'])
                                        <span>Motor: <strong
                                                class="text-dark">{{ $selectedEngineObj['engine_code'] }}</strong></span>
                                    @endif
                                    @if($vehicle && $vehicle->body_type && $searchType === 'plate')
                                        <span>Carrocería: <strong class="text-dark">{{ $vehicle->body_type }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            <div class="ms-auto fw-medium" style="font-size: 13px; color: #C0392B;">
                                {{ isset($products) ? (method_exists($products, 'total') ? $products->total() : $products->count()) : 0 }}
                                producto(s) encontrado(s)
                            </div>
                        </div>

                        {{-- DESKTOP BREADCRUMB --}}
                        <div class="rf-breadcrumb-row d-none d-md-flex breadcrumb-chevron-container mb-3">
                            @foreach($steps as $index => $step)
                                @php
                                    $isFirst = $index === 0;
                                    $isLast = $index === ($totalSteps - 1);
                                    $isSingle = $totalSteps === 1;
                                    if ($isSingle) {
                                        $shapeClass = 'chevron-step-first';
                                    } else {
                                        $shapeClass = $isFirst ? 'chevron-step-first' : ($isLast ? 'chevron-step-last' : 'chevron-step-middle');
                                    }

                                    $isSubcategory = $isLast && $viewState === 'products_list';
                                    $stateClass = $isSubcategory ? 'chevron-step-red' : 'chevron-step-navy';
                                    $zIndex = 10 - $index;
                                @endphp
                                <div class="chevron-step {{ $shapeClass }} {{ $stateClass }}" style="z-index: {{ $zIndex }};"
                                    @if($step['action']) wire:click="{{ $step['action'] }}" @endif>
                                    <i class="{{ $step['icon'] }}"></i>
                                    <span class="chevron-text">{{ $step['label'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- MOBILE COMBINED CARD (DETAILS + CHIPS) --}}
                        <div class="rf-mobile-card d-md-none bg-white p-3 rounded shadow-sm border border-light mb-3"
                            style="transition: border-radius 0.3s ease, box-shadow 0.3s ease, margin 0.3s ease;">
                            <div class="d-flex gap-2 align-items-center mb-1">
                                <div style="width: 55px; height: 42px; border-radius: 6px; border: 1px solid #eee; background-color: #fff; display: flex; align-items: center; justify-content: center;"
                                    class="flex-shrink-0">
                                    <img src="{{ $carImage ?? asset('images/cars/Car_hide.webp') }}" alt="Auto"
                                        style="width: 95%; height: 95%; object-fit: contain;">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-dark fw-medium text-uppercase" style="font-size: 13px;">
                                        {{ $brandName }} {{ $modelName }}
                                        @if($vehicle && $vehicle->engine_code)
                                            - {{ $vehicle->engine_code }}
                                        @elseif($selectedEngineObj && $selectedEngineObj['engine_code'])
                                            - {{ $selectedEngineObj['engine_code'] }}
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size: 11px; margin-top: 1px;">
                                        @if($vehicle && $searchType === 'plate')
                                            Placa: {{ $vehicle->plate }}
                                            @if($vehicle->body_type) | Carrocería: {{ $vehicle->body_type }} @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="text-end fw-medium mb-3" style="font-size: 12px; color: #C0392B;">
                                {{ isset($products) ? (method_exists($products, 'total') ? $products->total() : $products->count()) : 0 }}
                                producto(s) encontrado(s)
                            </div>

                            <div class="mobile-chips-wrapper py-2 px-2 rounded"
                                style="background-color: #F8F9FA; border: 1px solid #E9ECEF;">
                                @foreach($steps as $index => $step)
                                    @php
                                        $isLast = $index === ($totalSteps - 1);
                                        $isSubcategory = $isLast && $viewState === 'products_list';
                                        $stateClass = $isSubcategory ? 'mobile-chip-red' : 'mobile-chip-navy';
                                    @endphp
                                    <div class="mobile-chip {{ $stateClass }}" @if($step['action'])
                                    wire:click="{{ $step['action'] }}" @endif>
                                        <i class="{{ $step['icon'] }}"></i>
                                        <span>{{ $step['label'] }}</span>
                                    </div>
                                    @if(!$isLast)
                                        <i class="fas fa-chevron-right chip-separator mx-1" style="font-size: 10px;"></i>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    @endif
                </div>
            @endif
            {{-- 4. INLINE RESULTS --}}
            @if($viewState === 'vehicle_found' && $vehicle)
                <div class="py-4">


                    <h5 class="fw-medium text-dark mb-4 border-bottom pb-2">CATEGORÍAS DISPONIBLES</h5>

                    <div class="row g-3">
                        @foreach($filteredCategories as $category)
                            <div class="col-6 col-md-3">
                                <div class="card bg-white p-3 p-md-4 text-center category-card border border-light shadow-sm h-100"
                                    wire:click="selectCategory({{ $category->id }})">
                                    <div class="mb-3 d-flex align-items-center justify-content-center" style="height: 80px;">
                                        @if($category->image_url)
                                            <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}"
                                                class="img-fluid" style="max-height: 80px; width: auto; object-fit: contain;">
                                        @else
                                            <i class="{{ $category->icon ?? 'fas fa-cogs' }} fa-2x text-primary-custom"></i>
                                        @endif
                                    </div>
                                    <h6 class="mb-0 fw-medium text-dark small">{{ $category->name }}</h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 4.2 SUBCATEGORIES --}}
            @if($viewState === 'subcategories' && $selectedCategory)
                <div class="py-4">
                    <div class="row g-3">
                        @foreach($selectedCategory->children as $sub)
                            <div class="col-6 col-md-3">
                                <div class="card bg-white p-4 text-center category-card border border-light shadow-sm h-100"
                                    wire:click="selectSubcategory({{ $sub->id }})">
                                    <div class="mb-3 text-primary-custom">
                                        <i class="fas fa-arrow-right fa-lg"></i>
                                    </div>
                                    <h6 class="mb-0 fw-medium text-dark">{{ $sub->name }}</h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 4.3 PRODUCTS LIST --}}
            @if($viewState === 'products_list')
                <div class="py-4">
                    <div class="row g-4">
                        @foreach($products as $product)
                            @php
                                $activeOversizes = $product->oversizes->where('is_active', true)->sortBy('oversize');
                                $hasMultipleOversizes = $activeOversizes->count() > 1;
                            @endphp
                            <div class="col-12" x-data="{ selectedOversize: '' }"
                                wire:key="prod-card-{{ $product->id }}-{{ $activeOversizes->pluck('oversize')->implode('-') }}">
                                <div class="card bg-white border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                                    <div class="d-flex flex-column flex-md-row align-items-center gap-0 product-card-inner">

                                        {{-- Imagen --}}
                                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 p-4"
                                            style="width: 220px; cursor: pointer;" wire:click="openDetails({{ $product->id }})">
                                            <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}"
                                                class="img-fluid hover-scale-sm"
                                                style="max-height: 200px; max-width: 200px; object-fit: contain; transition: all 0.2s ease;">
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-grow-1 p-4 ps-md-2" style="min-width: 0;">

                                            {{-- Fila 1: Título + Código --}}
                                            <h3 class="fw-bold text-dark mb-1 hover-text-danger"
                                                style="font-size: 1.25rem; line-height: 1.3; cursor: pointer; transition: color 0.2s ease;"
                                                wire:click="openDetails({{ $product->id }})">
                                                {{ $product->name }}
                                            </h3>
                                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2 mb-3"
                                                style="font-size: 0.88rem;">
                                                <div>
                                                    <span class="text-muted">Código:</span>
                                                    <span class="fw-bold text-primary">{{ $product->supplier_code }}</span>
                                                </div>
                                                @if($product->oem_code || !empty($product->additional_oem_codes))
                                                    <div class="d-none d-md-block text-muted px-1">|</div>
                                                    <div>
                                                        <span class="text-muted">C&oacute;digo Original:</span>
                                                        @php
                                                            $allOems = array_filter(array_unique(array_merge(
                                                                array_filter(array_map("trim", explode(",", $product->oem_code ?? ""))),
                                                                $product->additional_oem_codes ?? []
                                                            )));
                                                        @endphp
                                                        <span class="fw-bold text-primary">{{ implode(", ", $allOems) }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Fila 2: Specs arriba, Entrega+Botones abajo en móvil --}}
                                            <div class="d-flex flex-column gap-3">

                                                {{-- Specs: MEDIDA + DIÁMETRO/PISTÓN + COMBUSTIBLE --}}
                                                <div class="d-flex gap-2 flex-wrap">
                                                    @if($activeOversizes->count() > 0)
                                                        {{-- Unified Oversize UI: always show standard measures and a select
                                                        dropdown --}}
                                                        <div
                                                            class="d-flex flex-wrap align-items-start justify-content-between gap-3 bg-light rounded-3 px-3 py-2 flex-fill">
                                                            <div>
                                                                <div class="text-muted fw-bold mb-1"
                                                                    style="font-size: 0.6rem; letter-spacing: 1px; text-transform: uppercase;">
                                                                    MEDIDAS DISPONIBLES</div>
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    @php
                                                                        $isPiston = !empty($product->specs['pin']);
                                                                        $stdOversizes = $isPiston
                                                                            ? ['STD', '050', '075', '100', '150']
                                                                            : ['STD', '025', '050', '075', '100', '125', '150'];

                                                                        $activeOversizeNames = $activeOversizes->pluck('oversize')->toArray();
                                                                        // Si el producto tiene sobremedidas raras (ej. 200), las agregamos al array
                                                                        foreach ($activeOversizeNames as $aov) {
                                                                            if (!in_array($aov, $stdOversizes)) {
                                                                                $stdOversizes[] = $aov;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    @foreach($stdOversizes as $std)
                                                                        @if(in_array($std, $activeOversizeNames))
                                                                            <span class="badge rounded-pill"
                                                                                style="background:#2563eb; color:#fff; font-size:0.7rem;">{{ $std }}</span>
                                                                        @else
                                                                            <span class="badge rounded-pill"
                                                                                style="background:#e2e8f0; color:#94a3b8; font-size:0.7rem;">{{ $std }}</span>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column gap-0 align-items-start">
                                                                <span class="text-muted fw-bold mb-1"
                                                                    style="font-size: 0.6rem; letter-spacing: 1px; text-transform: uppercase;">ELEGIR
                                                                    MEDIDA:</span>
                                                                <select x-model="selectedOversize"
                                                                    class="form-select form-select-sm fw-bold py-0"
                                                                    style="max-width: 120px; border-radius: 8px; font-size: 0.82rem; height: 26px;">
                                                                    <option value="" disabled selected>Elegir...</option>
                                                                    @foreach($activeOversizes as $ov)
                                                                        <option value="{{ $ov->oversize }}">{{ $ov->oversize }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if(!empty($product->specs['pin']))
                                                        {{-- PISTÓN: bloque visual estructurado --}}
                                                        <div class="bg-light rounded-3 px-3 py-2 flex-fill"
                                                            style="min-width: 160px;">
                                                            <div class="text-muted fw-bold mb-1"
                                                                style="font-size: 0.6rem; letter-spacing: 1px;">ESPECIFICACIONES
                                                                PISTÓN</div>
                                                            <div style="font-size: 0.78rem; line-height: 1.6;">
                                                                @if(!empty($product->specs['bore']))
                                                                    <span class="fw-bold text-primary">Ø {{ $product->specs['bore'] }}
                                                                        mm</span>
                                                                    @if(!empty($product->specs['cylinders']))
                                                                        <span class="text-muted"> · {{ $product->specs['cylinders'] }}
                                                                            CYL</span>
                                                                    @endif
                                                                    <br>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @elseif(!empty($product->specs['raw']))
                                                        {{-- ANILLOS / METALES: texto plano --}}
                                                        <div class="bg-light rounded-3 px-3 py-2 text-center flex-fill">
                                                            <div class="text-muted fw-bold mb-1"
                                                                style="font-size: 0.6rem; letter-spacing: 1px;">DIÁMETRO</div>
                                                            <div class="fw-bold" style="font-size: 0.95rem;">
                                                                <span
                                                                    class="text-primary">{{ is_array($product->specs['raw']) ? collect($product->specs['raw'])->first() : explode(' ', $product->specs['raw'])[0] }}</span>
                                                                <span class="text-dark" style="font-size: 0.82rem;">
                                                                    {{ is_array($product->specs['raw']) ? implode(' ', array_slice($product->specs['raw'], 1)) : implode(' ', array_slice(explode(' ', $product->specs['raw']), 1)) }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($product->fuel_type)
                                                        @php
                                                            $fuelIcon = match ($product->fuel_type) {
                                                                'DIESEL' => '🛢️',
                                                                'GAS' => '💨',
                                                                'HIBRIDO' => '🔋',
                                                                default => '⛽',
                                                            };
                                                        @endphp
                                                        <div class="rounded-3 px-3 py-2 text-center flex-fill"
                                                            style="background: #fff9e6; border: 1px solid #fde68a;">
                                                            <div class="fw-bold mb-1"
                                                                style="font-size: 0.6rem; letter-spacing: 1px; color: #92400e;">
                                                                MOTOR</div>
                                                            <div class="fw-bold" style="font-size: 0.85rem; color: #b45309;">
                                                                {{ $fuelIcon }} {{ $product->fuel_type }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Entrega hoy --}}
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <span
                                                        style="display:inline-block; width:8px; height:8px; background:#22c55e; border-radius:50%;"></span>
                                                    <span class="text-dark small">Entrega hoy</span>
                                                </div>

                                                {{-- Botones --}}
                                                <div class="d-flex gap-2 w-100">
                                                    <button wire:click="openDetails({{ $product->id }})"
                                                        class="btn btn-outline-secondary fw-semibold d-flex align-items-center justify-content-center gap-1 flex-grow-1"
                                                        style="border-radius: 10px; padding: 8px 12px; border-width: 1.5px; white-space: nowrap; font-size: 0.82rem; width: 50%;">
                                                        <i class="fas fa-list-check"></i>
                                                        <span>Ver detalles</span>
                                                    </button>
                                                    @if($activeOversizes->count() > 0)
                                                        <button
                                                            @click="if(!selectedOversize) { alert('Por favor, elige una medida.'); } else { $wire.addToRepairWithOversize({{ $product->id }}, selectedOversize) }"
                                                            class="btn fw-bold d-flex align-items-center justify-content-center gap-1 flex-grow-1"
                                                            style="background: #e63946; color: #fff; border-radius: 10px; padding: 8px 14px; border: none; font-size: 0.82rem; white-space: nowrap; width: 50%;">
                                                            <i class="fas fa-plus"></i>
                                                            <span>Agregar medida</span>
                                                        </button>
                                                    @else
                                                        <button
                                                            class="btn fw-bold d-flex align-items-center justify-content-center gap-1 flex-grow-1"
                                                            wire:click="addToRepair({{ $product->id }})"
                                                            style="background: #e63946; color: #fff; border-radius: 10px; padding: 8px 14px; border: none; font-size: 0.82rem; white-space: nowrap; width: 50%;">
                                                            <i class="fas fa-plus"></i>
                                                            <span>Agregar a reparación</span>
                                                        </button>
                                                    @endif
                                                </div>

                                            </div>{{-- /Fila 2 --}}

                                        </div>{{-- /Info --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 d-flex justify-content-center custom-pagination">
                        {{ $products->links() }}
                    </div>

                    @if($products->isEmpty())
                        <div class="text-center py-5 bg-white rounded shadow-sm">
                            <i class="fas fa-search fa-3x text-light mb-3"></i>
                            <p class="text-muted">No se encontraron productos en esta categoría para tu vehículo.</p>
                            <button class="btn btn-primary-custom mt-2" wire:click="goBack">Ver otras categorías</button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- oem_found now uses the same products_list layout --}}

            @if($viewState === 'repair_summary')
                <div class="mt-4 p-0 border-0">
                    @if($isSearching)
                        {{-- ZETTABOT PREMIUM EXPERIENCE --}}
                        <div class="zbot-screen-container" wire:poll.3s="checkZbotResponses"
                            x-data="zettaBotAnimation(@js($this->getZbotProviders()))" x-init="startSequence()"
                            x-on:zbot-updated.window="updateFromLivewire($event.detail[0])">
                            <div class="zbot-card shadow-none border-0">
                                <div class="zbot-layout">
                                    {{-- LEFT SIDE: Information and Products --}}
                                    <div class="zbot-panel-left p-4 border-end border-light">
                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="zbot-icon-circle bg-danger text-white d-flex align-items-center justify-content-center shadow-sm"
                                                style="width: 48px; height: 48px; border-radius: 12px; font-size: 24px;">🔧
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0 text-dark" x-text="headerStatus"></h5>
                                                <p class="text-muted small mb-0">ZettaBot · Pedido #{{ $lastOrderId ?? '...' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="text-muted x-small fw-bold text-uppercase mb-2">Detalles del Pedido
                                            </div>
                                            <div class="zbot-product-list scrollbar-hide"
                                                style="max-height: 250px; overflow-y: auto;">
                                                @foreach($repairList as $item)
                                                    <div
                                                        class="product-item-lite p-2 border-bottom border-light d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="bg-primary rounded-circle" style="width: 6px; height: 6px;">
                                                            </div>
                                                            <span class="text-dark small">{{ $item['product']['name'] }}</span>
                                                        </div>
                                                        <span class="text-muted small">x{{ $item['qty'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Timer row in desktop view --}}
                                        <div
                                            class="d-none d-md-flex align-items-center justify-content-center gap-2 py-3 bg-light rounded-3 mb-3">
                                            <div class="timer-dot-animate"></div>
                                            <span class="text-muted small">Tiempo de respuesta:</span>
                                            <span class="fw-bold text-danger" x-text="timerCount">9:00</span>
                                        </div>
                                    </div>

                                    {{-- RIGHT SIDE: BOT Animation and Status --}}
                                    <div
                                        class="zbot-panel-right p-4 d-flex flex-column align-items-center justify-content-center bg-light bg-opacity-10">
                                        {{-- Bot Avatar --}}
                                        <div class="zbot-avatar-container mb-4">
                                            <div class="zbot-avatar-bg mx-auto">
                                                <div class="zbot-avatar-inner">🤖</div>
                                            </div>
                                            <div class="mt-3 text-center">
                                                <div class="fw-bold text-primary text-uppercase letter-spacing-2 small">ZettaBot
                                                </div>
                                                <div class="text-muted small italic-pulse" x-text="zbotStatus"></div>
                                            </div>
                                        </div>

                                        {{-- Providers Grid --}}
                                        <div class="row g-2 w-100 mb-4 justify-content-center">
                                            <template x-for="(prov, id) in providers" :key="id">
                                                <div class="col-4">
                                                    <div class="provider-card-v2 p-2 border border-light shadow-sm rounded-3 text-center h-100 d-flex flex-column align-items-center gap-1"
                                                        :class="prov.state === 'asking' ? 'border-danger shadow-danger-l' : (prov.state === 'confirmed' ? 'border-success shadow-success-l' : (prov.state === 'denied' ? 'opacity-50' : ''))"
                                                        style="transition: all 0.5s ease;">
                                                        <div class="position-relative">
                                                            <span class="fs-4" x-text="prov.icon"></span>
                                                            <div class="status-dot position-absolute top-0 end-0"
                                                                :class="prov.state === 'asking' ? 'bg-danger pulse-dot' : (prov.state === 'confirmed' ? 'bg-success' : 'bg-muted')">
                                                            </div>
                                                        </div>
                                                        <div class="fw-bold x-small text-dark" x-text="prov.name"></div>
                                                        <div class="badge x-small px-1"
                                                            :class="prov.state === 'confirmed' ? 'bg-success-soft text-success' : (prov.state === 'denied' ? 'bg-danger-soft text-danger' : 'bg-secondary bg-opacity-10 text-muted')"
                                                            x-text="prov.result"></div>
                                                        {{-- Precio del proveedor oculto al cliente: solo visible en dashboard
                                                        admin --}}
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        {{-- Progress Bar --}}
                                        <div class="w-100 mb-4 px-md-4">
                                            <div class="progress bg-secondary bg-opacity-10 overflow-hidden"
                                                style="height: 6px; border-radius: 10px;">
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    :style="'width: ' + progress + '%; transition: width 1s ease;'"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2 x-small text-muted">
                                                <span x-text="progressLabel"></span>
                                                <span x-text="progress + '%' "></span>
                                            </div>
                                        </div>

                                        {{-- Price Summary (UX Premium) --}}
                                        <div class="w-100 pt-3" x-show="priceSummaryVisible" x-cloak>
                                            @php
                                                // Build a keyed map: repairListId (pid) => confirmed item data
                                                $confirmedItemsRaw = $this->getConfirmedItems();
                                                $confirmedByPid = [];
                                                foreach ($confirmedItemsRaw as $ci) {
                                                    if (isset($ci['repair_list_id'])) {
                                                        $confirmedByPid[$ci['repair_list_id']] = $ci;
                                                    }
                                                }
                                            @endphp
                                            @if(count($confirmedByPid) > 0)
                                                @php
                                                    $unconfirmedItems = [];
                                                    foreach ($this->repairList as $pid => $rItem) {
                                                        $match = $confirmedByPid[$pid] ?? null;
                                                        if (!$match || $match['qty'] <= 0) {
                                                            $unconfirmedItems[] = $rItem;
                                                        }
                                                    }
                                                    $totalItems = count($this->repairList);
                                                    $confirmedCount = $totalItems - count($unconfirmedItems);
                                                    $unconfirmedCount = count($unconfirmedItems);
                                                    $hasPartial = $unconfirmedCount > 0;
                                                @endphp

                                                {{-- === STATUS BANNER === --}}
                                                @if($hasPartial)
                                                    <div class="rounded-3 p-3 mb-3 text-start d-flex align-items-start gap-3"
                                                        style="background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%); border: 1.5px solid #f59e0b;">
                                                        <div style="font-size: 26px; line-height:1;">⚠️</div>
                                                        <div>
                                                            <div class="fw-bold" style="color:#92400e; font-size:13px;">Stock
                                                                parcialmente disponible</div>
                                                            <div class="mt-1" style="color:#a16207; font-size:12px; line-height:1.5;">
                                                                Encontramos <strong>{{ $confirmedCount }} de {{ $totalItems }}</strong>
                                                                repuesto(s) en stock.
                                                                Puedes pagar los que están disponibles ahora y contactarnos para los
                                                                restantes.
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="rounded-3 p-3 mb-3 text-start d-flex align-items-start gap-3"
                                                        style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1.5px solid #22c55e;">
                                                        <div style="font-size: 26px; line-height:1;">✅</div>
                                                        <div>
                                                            <div class="fw-bold" style="color:#14532d; font-size:13px;">¡Todo en stock y
                                                                listo!</div>
                                                            <div class="mt-1" style="color:#166534; font-size:12px; line-height:1.5;">
                                                                Los <strong>{{ $confirmedCount }}</strong> repuesto(s) solicitados están
                                                                confirmados y disponibles para entrega.
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                {{-- === CARRITO EDITABLE POST-CONFIRMACIÓN === --}}
                                                <div class="rounded-3 border overflow-hidden mb-3" style="border-color:#e5e7eb;">
                                                    <div class="px-3 py-2 d-flex align-items-center justify-content-between"
                                                        style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                                        <span class="fw-bold text-dark"
                                                            style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">
                                                            🛒 Tu pedido
                                                        </span>
                                                    </div>
                                                    <div class="px-3 py-2 bg-white">
                                                        @php $totalCalculated = 0; @endphp
                                                        @foreach($this->repairList as $pid => $rItem)
                                                            @php
                                                                $oem = strtoupper(trim($rItem['product']['supplier_code'] ?? $rItem['product']['oem_code'] ?? ''));
                                                                $match = $confirmedByPid[$pid] ?? null;

                                                                // Raw confirmed qty from provider (before user overrides)
                                                                $rawConfirmedQty = $match ? $match['qty'] : 0;
                                                                $requestedQty = $rItem['qty'];
                                                                // Detect if client has excluded this item using the product ID as key
                                                                $isExcluded = ($confirmedOverrides[$pid]['excluded'] ?? false);
                                                                // Effective qty: 0 if excluded, user override if set, otherwise provider qty
                                                                $effectiveQty = $isExcluded
                                                                    ? 0
                                                                    : (isset($confirmedOverrides[$pid]['qty'])
                                                                        ? (int) $confirmedOverrides[$pid]['qty']
                                                                        : $rawConfirmedQty);
                                                                $confirmedQty = $rawConfirmedQty; // for badge logic
                                                                $isFull = $confirmedQty >= $requestedQty;
                                                                $isPartial = $confirmedQty < $requestedQty && $confirmedQty > 0;
                                                                $isNone = $confirmedQty == 0;
                                                                $unitPrice = $match ? $match['price'] : 0;
                                                                $effectiveSubtotal = $unitPrice * $effectiveQty;
                                                                $totalCalculated += $effectiveSubtotal;

                                                                // Oversize label from product model
                                                                $productModel = \App\Models\Product::find($rItem['product']['id']);
                                                                $oversizeLabel = $productModel ? $productModel->getOversizeLabel() : null;
                                                              @endphp
                                                            <div class="py-3 {{ !$loop->first ? 'border-top border-light-subtle' : '' }}"
                                                                style="{{ $isExcluded ? 'opacity:0.45;' : '' }} transition: opacity .2s;">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between gap-3 flex-wrap">

                                                                    {{-- Producto info --}}
                                                                    <div class="flex-grow-1" style="min-width: 250px;">
                                                                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                                                            <span class="fw-bold text-dark fs-6"
                                                                                style="{{ $isExcluded ? 'text-decoration:line-through;' : '' }}">{{ $oem }}</span>

                                                                            @if($oversizeLabel)
                                                                                <span class="badge rounded-pill px-2"
                                                                                    style="background:rgba(59,130,246,.15);color:#1d4ed8;font-size:10px;font-weight:700;">{{ $oversizeLabel }}</span>
                                                                            @endif

                                                                            @if($isFull && !$isExcluded)
                                                                                <span class="badge rounded-pill px-2"
                                                                                    style="background:#dcfce7;color:#15803d;font-size:10px;">✅
                                                                                    Disponible</span>
                                                                            @elseif($isPartial && !$isExcluded)
                                                                                <span class="badge rounded-pill px-2"
                                                                                    style="background:#fff7ed;color:#c2410c;font-size:10px;">⚠️
                                                                                    Parcial</span>
                                                                            @elseif($isNone)
                                                                                <span class="badge rounded-pill px-2"
                                                                                    style="background:#fee2e2;color:#991b1b;font-size:10px;">❌
                                                                                    Sin stock</span>
                                                                            @endif
                                                                            @if($isExcluded)
                                                                                <span class="badge rounded-pill px-2"
                                                                                    style="background:#f3f4f6;color:#6b7280;font-size:10px;">Quitado</span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="text-secondary small text-truncate"
                                                                            style="max-width: 450px;">{{ $rItem['product']['name'] }}
                                                                        </div>
                                                                        @if($confirmedQty > 0 && !$isExcluded)
                                                                            <div class="text-muted small">S/
                                                                                {{ number_format($unitPrice, 2) }} c/u
                                                                            </div>
                                                                        @endif
                                                                    </div>

                                                                    {{-- Controles y precio --}}
                                                                    <div class="d-flex align-items-center gap-3">
                                                                        {{-- Controles de cantidad (solo si hay stock y no excluido)
                                                                        --}}
                                                                        @if($confirmedQty > 0 && !$isExcluded)
                                                                            <div class="d-flex align-items-center gap-1"
                                                                                style="background:#f3f4f6; border-radius:20px; padding:3px 8px;">
                                                                                <button type="button"
                                                                                    wire:click="updateConfirmedQty('{{ $pid }}', -1)"
                                                                                    class="btn btn-sm p-0 border-0 text-muted"
                                                                                    style="width:24px;height:24px;line-height:1;font-size:16px;border-radius:50%;background:none;"
                                                                                    {{ $effectiveQty <= 1 ? 'disabled' : '' }}>−</button>
                                                                                <span class="fw-bold text-dark"
                                                                                    style="min-width:24px;text-align:center;font-size:14px;">{{ $effectiveQty }}</span>
                                                                                <button type="button"
                                                                                    wire:click="updateConfirmedQty('{{ $pid }}', 1)"
                                                                                    class="btn btn-sm p-0 border-0 text-muted"
                                                                                    style="width:24px;height:24px;line-height:1;font-size:16px;border-radius:50%;background:none;"
                                                                                    {{ $effectiveQty >= $confirmedQty ? 'disabled' : '' }}>+</button>
                                                                            </div>
                                                                        @endif

                                                                        {{-- Subtotal --}}
                                                                        @if($confirmedQty > 0 && !$isExcluded)
                                                                            <div class="fw-bold text-dark text-end fs-6"
                                                                                style="min-width:90px;">
                                                                                S/ {{ number_format($effectiveSubtotal, 2) }}
                                                                            </div>
                                                                        @elseif($isNone)
                                                                            <div class="text-muted text-end small" style="min-width:90px;">—
                                                                            </div>
                                                                        @endif

                                                                        {{-- Botón Quitar / Restaurar --}}
                                                                        @if($confirmedQty > 0)
                                                                            @if($isExcluded)
                                                                                <button type="button"
                                                                                    wire:click="restoreConfirmedItem('{{ $pid }}')"
                                                                                    class="btn btn-sm rounded-pill px-3 py-1 border-0"
                                                                                    style="font-size:11px; background:#dcfce7; color:#15803d; white-space:nowrap;">
                                                                                    ↩ Restaurar
                                                                                </button>
                                                                            @else
                                                                                <button type="button"
                                                                                    wire:click="removeConfirmedItem('{{ $pid }}')"
                                                                                    class="btn btn-sm rounded-pill px-3 py-1 border-0"
                                                                                    style="font-size:11px; background:#fee2e2; color:#991b1b; white-space:nowrap;">
                                                                                    × Quitar
                                                                                </button>
                                                                            @endif
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                {{-- === TOTALS === --}}
                                                @php
                                                    $deliveryCost = $this->estimatedDeliveryCost ?? 0;
                                                    $finalTotal = $totalCalculated + ($this->deliveryType !== 'pickup' ? $deliveryCost : 0);
                                                    $subtotalGravado = round($finalTotal / 1.18, 2);
                                                    $igvAmount = round($finalTotal - $subtotalGravado, 2);
                                                 @endphp
                                                <div class="rounded-3 p-3 mb-3"
                                                    style="background:#f9fafb; border:1.5px solid #e5e7eb;">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted" style="font-size:13px;">Subtotal repuestos (Incluye
                                                            IGV)</span>
                                                        <span class="fw-bold text-dark" style="font-size:13px;">S/
                                                            {{ number_format($totalCalculated, 2) }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted" style="font-size:13px;">Comisión de servicio</span>
                                                        <div class="text-end">
                                                            <span class="text-muted"
                                                                style="text-decoration: line-through; font-size:12px; margin-right: 5px;">S/
                                                                15.00</span>
                                                            <span class="text-success fw-bold" style="font-size:13px;">S/
                                                                0.00</span>
                                                            <span class="d-block text-success"
                                                                style="font-size:10px; margin-top:-2px;">¡No cobramos comisión por
                                                                ahora!</span>
                                                        </div>
                                                    </div>
                                                    @if($this->deliveryType !== 'pickup')
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span class="text-muted" style="font-size:13px;">Costo de envío</span>
                                                            <span class="text-dark" style="font-size:13px;">S/
                                                                {{ number_format($deliveryCost, 2) }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="d-flex justify-content-between align-items-center pt-2"
                                                        style="border-top:1.5px solid #e5e7eb;">
                                                        <span class="fw-bold text-dark" style="font-size:15px;">Total a pagar</span>
                                                        <span class="fw-bold" style="font-size:20px; color:#dc2626;">S/
                                                            {{ number_format($finalTotal, 2) }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light"
                                                        style="font-size: 11px; opacity: 0.85;">
                                                        <span class="text-muted">Op. Gravada: S/
                                                            {{ number_format($subtotalGravado, 2) }}</span>
                                                        <span class="text-muted">IGV (18%): S/
                                                            {{ number_format($igvAmount, 2) }}</span>
                                                    </div>
                                                </div>

                                                {{-- === TRUST SIGNALS === --}}
                                                <div class="d-flex justify-content-center gap-4 mb-3 flex-wrap">
                                                    <div class="d-flex align-items-center gap-1 text-muted" style="font-size:11px;">
                                                        <i class="fas fa-shield-alt text-success"></i>
                                                        <span>Pago seguro SSL</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1 text-muted" style="font-size:11px;">
                                                        <i class="fas fa-undo text-primary"></i>
                                                        <span>Garantía de entrega</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1 text-muted" style="font-size:11px;">
                                                        <i class="fab fa-cc-visa text-dark"></i>
                                                        <span>Visa / Mastercard</span>
                                                    </div>
                                                </div>

                                                {{-- === CTA BUTTON === --}}
                                                @php $allExcluded = $totalCalculated <= 0; @endphp
                                                @if($allExcluded)
                                                    <div class="rounded-3 p-3 mb-3 text-center"
                                                        style="background:#fef9c3; border:1.5px solid #fbbf24;">
                                                        <span style="font-size:13px; color:#92400e;">⚠️ Has quitado todos los productos.
                                                            Restaura al menos uno para continuar.</span>
                                                    </div>
                                                @endif
                                                <button type="button"
                                                    class="btn w-100 fw-bold rounded-3 shadow-sm position-relative overflow-hidden"
                                                    style="padding: 14px 24px; font-size:15px; background: {{ $allExcluded ? 'linear-gradient(135deg,#9ca3af,#6b7280)' : 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)' }}; color:white; border:none; transition: all .2s ease; {{ $allExcluded ? 'cursor:not-allowed;opacity:.7;' : '' }}"
                                                    onclick="{{ $allExcluded ? '' : "
                                                    initiateCulqiPayment({$finalTotal}, '".(auth()->user()->email ?? '
                                                    cliente@repuestofijo.com')."', '{$lastOrderId}' )" }}" {{ $allExcluded ? 'disabled' : '' }}
                                                    onmouseover="{{ $allExcluded ? '' : "this.style.opacity='0.92';this.style.transform='translateY(-1px)';" }}"
                                                    onmouseout="{{ $allExcluded ? '' : "this.style.opacity='1';this.style.transform='translateY(0)';" }}">
                                                    <i class="fas fa-lock me-2" style="font-size:13px;"></i>
                                                    @if($allExcluded)
                                                        Carrito vacío
                                                    @else
                                                        PAGAR S/ {{ number_format($finalTotal, 2) }} →
                                                    @endif
                                                </button>
                                                <p class="text-center text-muted mt-2 mb-0" style="font-size:11px;">
                                                    Al confirmar aceptas nuestros <a href="#" class="text-muted">Términos de
                                                        Servicio</a>.
                                                </p>

                                            @else
                                                {{-- === NO STOCK STATE === --}}
                                                <div class="rounded-3 p-3 mb-3 text-center"
                                                    style="background:#f9fafb; border: 1.5px dashed #d1d5db;">
                                                    <div style="font-size:36px; margin-bottom:8px;">😕</div>
                                                    <div class="fw-bold text-dark mb-1" style="font-size:14px;">Sin stock disponible
                                                        en este momento</div>
                                                    <div class="text-muted" style="font-size:12px;">
                                                        Ningún proveedor confirmó stock para los repuestos solicitados. Puedes
                                                        volver a buscar o contactarnos.
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <div class="text-muted x-small fw-bold text-uppercase mb-2 text-center">
                                                        PRODUCTOS QUE TE PUEDEN INTERESAR</div>
                                                    <div class="row g-2">
                                                        @foreach($this->getRecommendedProducts() as $rec)
                                                            <div class="col-6">
                                                                <div class="card p-2 border-light shadow-sm text-center h-100 cursor-pointer hover-scale"
                                                                    wire:click="trackProductView({{ $rec->id }}); openDetails({{ $rec->id }})">
                                                                    <img src="{{ $rec->image_url }}" class="img-fluid mb-2"
                                                                        style="height: 60px; object-fit: contain;">
                                                                    <div class="x-small fw-bold text-dark text-truncate">
                                                                        {{ $rec->name }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <button type="button"
                                                    class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow hover-scale"
                                                    wire:click="resetToHome">
                                                    VOLVER A BUSCAR 🔍
                                                </button>
                                            @endif

                                            @if(collect($this->getZbotProviders())->contains('result', '⚠ No responde'))
                                                <div class="text-center mt-3 x-small text-muted">
                                                    <i class="fas fa-info-circle me-1"></i> Estamos trabajando para mejorar los
                                                    tiempos de respuesta de nuestros proveedores.
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-4 text-center">
                                            <button class="btn btn-link text-muted x-small text-decoration-none"
                                                wire:click="cancelSearch">
                                                <i class="fas fa-times me-1"></i> Cancelar búsqueda
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(count($repairList) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-light">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th class="border-0">Producto</th>
                                        <th class="text-center border-0">Cantidad</th>
                                        <th class="text-end border-0">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($repairList as $id => $item)
                                        <tr>
                                            <td class="border-0">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item['product']['image_url'] ?? 'https://via.placeholder.com/50' }}"
                                                        class="rounded me-3 border p-1"
                                                        style="width: 50px; height: 50px; object-fit: contain;">
                                                    <div>
                                                        <div class="fw-medium text-dark small">{{ $item['product']['name'] }}</div>
                                                        <div class="text-muted x-small">Código Original:
                                                            {{ $item['product']['oem_code'] }}
                                                        </div>
                                                        <div class="mt-1">
                                                            @php
                                                                $rawPrice = floatval($item['product']['price'] ?? 0);
                                                                $providerDirect = isset($item['product']['provider_id']) && $item['product']['provider_id']
                                                                    ? optional(\App\Models\Provider::find($item['product']['provider_id']))->requires_zbot === false
                                                                    : false;
                                                                $showPrice = $rawPrice > 0 && $providerDirect;
                                                                $clientPrice = $showPrice ? round($rawPrice * 1.18, 2) : null;
                                                            @endphp
                                                            @if($showPrice)
                                                                <span
                                                                    class="badge bg-success-subtle text-success border border-success-subtle"
                                                                    style="font-size: 0.72rem;">S/ {{ number_format($clientPrice, 2) }}
                                                                    c/IGV</span>
                                                            @else
                                                                <span class="badge bg-light text-muted border border-light-subtle"
                                                                    style="font-size: 0.72rem;">Por confirmar</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center border-0">
                                                <div class="input-group input-group-sm d-inline-flex border rounded overflow-hidden"
                                                    style="width: 100px;">
                                                    <button class="btn btn-light border-0"
                                                        wire:click="updateQuantity({{ $id }}, {{ $item['qty'] - 1 }})">-</button>
                                                    <input type="text" class="form-control text-center border-0 fw-medium"
                                                        value="{{ $item['qty'] }}" readonly>
                                                    <button class="btn btn-light border-0"
                                                        wire:click="updateQuantity({{ $id }}, {{ $item['qty'] + 1 }})">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end border-0">
                                                <button class="btn btn-link text-danger p-1"
                                                    onclick="confirmRemoval({{ $id }}, '{{ $item['product']['name'] }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4 border-top pt-4">
                                <button
                                    class="btn btn-primary-custom w-100 py-3 fw-bold shadow d-flex align-items-center justify-content-center gap-2"
                                    wire:click="openDeliveryModal">
                                    @if($this->isOrderFullyPrePriced())
                                        <i class="fas fa-credit-card fs-5"></i>
                                        <span>PROCEDER AL PAGO</span>
                                    @else
                                        <i class="fab fa-whatsapp fs-5"></i>
                                        <span>CONSULTAR DISPONIBILIDAD</span>
                                    @endif
                                    <i class="fas fa-arrow-right small opacity-75"></i>
                                </button>

                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-light mb-3"></i>
                            <p class="text-muted">Tu lista de reparación está vacía.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- 5. RECENTLY VIEWED (ALWAYS WHITE) --}}
    @if(!empty($recentlyViewed))
        <div class="container py-4">
            <h4 class="fw-medium mb-5 text-center text-dark" style="letter-spacing: 1px;">ARTÍCULOS VISTOS RECIENTEMENTE
            </h4>
            <div class="row g-4 justify-content-center">
                @foreach($recentlyViewed as $product)
                    @if($product)
                        <div class="col-11 col-sm-6 col-md-3">
                            <div class="card border-light shadow-sm h-100 overflow-hidden category-card bg-white p-2 hover-scale"
                                style="transition: all 0.2s ease;">
                                <div class="p-3 text-center cursor-pointer" style="height: 180px;"
                                    wire:click="openDetails({{ $product->id }})">
                                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/150' }}"
                                        class="img-fluid h-100 w-100 object-fit-contain hover-scale-sm"
                                        style="transition: all 0.2s ease;">
                                </div>
                                <div class="card-body p-3 text-start">
                                    <div class="text-muted small mb-1">Código Original: {{ $product->oem_code }}</div>
                                    <h6 class="fw-medium mb-2 text-dark text-truncate cursor-pointer hover-text-danger"
                                        style="transition: color 0.2s ease;" wire:click="openDetails({{ $product->id }})">
                                        {{ $product->name }}
                                    </h6>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <button class="btn btn-outline-danger btn-sm px-3" style="border-radius: 3px;"
                                            wire:click="openDetails({{ $product->id }})">Detalles</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <style>
        .min-vh-75 {
            min-height: 75vh;
        }

        /* Custom Pagination Styling */
        .custom-pagination .pagination {
            gap: 5px;
        }

        .custom-pagination .page-item .page-link {
            border-radius: 8px !important;
            border: 1px solid #dee2e6;
            color: #132530 !important;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }

        .custom-pagination .page-item.active .page-link {
            background-color: #BE3C3B !important;
            border-color: #BE3C3B !important;
            color: white !important;
            box-shadow: 0 4px 6px rgba(190, 60, 59, 0.2);
        }

        .custom-pagination .page-item .page-link:hover {
            background-color: #f8f9fa;
            border-color: #BE3C3B;
            color: #BE3C3B !important;
        }

        .bg-orange {
            background-color: #BE3C3B !important;
        }

        .filter-step {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            z-index: 5;
            transition: all 0.3s ease;
        }

        .step-active {
            background-color: #BE3C3B !important;
            /* Rojo del sitio */
            color: white !important;
            border: 2px solid #BE3C3B;
            box-shadow: 0 0 5px rgba(190, 60, 59, 0.5);
        }

        .step-done {
            background-color: #BE3C3B !important;
            /* Rojo del sitio */
            color: white !important;
            border: 2px solid #BE3C3B;
        }

        .step-inactive {
            background-color: #e9ecef !important;
            color: #adb5bd !important;
            border: 1px solid #dee2e6;
        }

        .peru-plate-container {
            border: 1px solid #dee2e6;
            background: #fff;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .peru-plate-container,
        .peru-plate-container input,
        .peru-plate-container div,
        .input-group,
        .input-group .form-control,
        .input-group .btn {
            border-radius: 3px !important;
        }

        .peru-plate-input {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            color: #000 !important;
            letter-spacing: 1px;
        }

        .x-small {
            font-size: 0.65rem;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Override body and page background to white for the public search */
        body {
            background: #f8f9fa !important;
            background-image: none !important;
            background-attachment: unset !important;
        }

        .main-content {
            background: #f8f9fa !important;
            padding: 0 !important;
        }

        /* --- ZETTABOT PREMIUM STYLES (LIGHT THEME) --- */
        .zbot-screen-container {
            --bg: #ffffff;
            --surface: #ffffff;
            --surface2: #f8f9fa;
            --border: #e2e8f0;
            --danger: #BE3C3B;
            --success: #00D68F;
            --text: #132530;
            --muted: #64748b;
            padding: 0;
        }

        .zbot-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            font-family: 'DM Sans', sans-serif;
            margin: 0 auto;
            max-width: 100%;
        }

        .zbot-layout {
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 992px) {
            .zbot-card {
                max-width: 100%;
            }

            .zbot-layout {
                display: grid !important;
                grid-template-columns: 350px 1fr !important;
            }
        }

        .zbot-icon-circle {
            background: linear-gradient(135deg, #BE3C3B, #e53e3e);
        }

        .zbot-avatar-bg {
            width: 72px;
            height: 72px;
            background: #f8f9fa;
            border: 2px solid rgba(190, 60, 59, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            animation: botPulse 2.5s infinite;
        }

        @keyframes botPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(190, 60, 59, 0.4), 0 0 10px rgba(190, 60, 59, 0.1);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(190, 60, 59, 0), 0 0 25px rgba(190, 60, 59, 0.2);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(190, 60, 59, 0), 0 0 10px rgba(190, 60, 59, 0.1);
            }
        }

        .italic-pulse {
            font-style: italic;
            animation: statusCycle 1.8s infinite;
        }

        @keyframes statusCycle {

            0%,
            100% {
                opacity: 0.9;
            }

            50% {
                opacity: 0.4;
            }
        }

        .provider-card-v2 {
            background: #ffffff;
        }

        .shadow-danger-l {
            box-shadow: 0 0 15px rgba(190, 60, 59, 0.2);
        }

        .shadow-success-l {
            box-shadow: 0 0 15px rgba(0, 214, 143, 0.2);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }

        .pulse-dot {
            animation: blink 0.8s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.3;
                transform: scale(0.8);
            }
        }

        .timer-dot-animate {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #BE3C3B;
            animation: blink 1s infinite;
        }

        .letter-spacing-2 {
            letter-spacing: 2px;
        }

        .bg-success-soft {
            background: rgba(0, 214, 143, 0.15);
        }

        .bg-danger-soft {
            background: rgba(190, 60, 59, 0.15);
        }

        .fade-slide-up {
            animation: fadeSlideUp 0.6s ease-out;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hover-scale {
            transition: transform 0.2s;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }

        [x-cloak] {
            display: none !important;
        }

        .zbot-product-list::-webkit-scrollbar {
            width: 4px;
        }

        .zbot-product-list::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .main-container {
            background: #f8f9fa;
            min-height: 100vh;
        }

        /* Fixed header height approx 62px – push content below it */
        .main-container>*:not(header) {
            /* compensated by the .bg-section-custom padding-top below */
        }

        .bg-section-custom {
            padding-top: calc(62px + 1rem) !important;
        }

        /* Custom Header Background */
        .bg-header-custom {
            background-color: #132530 !important;
        }

        /* Force Montserrat in everything inside this component EXCEPT icons */
        /* *:not(i):not([class*="fa-"]) {
            font-family: 'Montserrat', sans-serif !important;
        } */

        /* Ensure SweetAlert toasts are always on top of EVERYTHING */
        .swal2-container {
            z-index: 9999 !important;
        }

        /* Spacing for mobile fixed bottom bar */
        @media (max-width: 767.98px) {
            .main-container {
                padding-bottom: 80px;
            }

            .logo-main {
                max-height: 22px !important;
            }

            .mx-n2 {
                margin-left: -0.5rem !important;
                margin-right: -0.5rem !important;
            }
        }

        @media (min-width: 768px) {
            .logo-main {
                max-height: 50px !important;
            }
        }

        .plate-search-group {
            background: #fff;
            border-radius: 5px !important;
            border: 2px solid #fff;
            overflow: hidden;
            height: 50px;
        }

        .peru-badge {
            background-color: #0d6efd !important;
            /* Azul intenso */
            display: flex !important;
            align-items: center;
            justify-content: center;
            min-width: 35px;
            border-radius: 0 !important;
        }

        .peru-text {
            color: white;
            font-size: 0.55rem;
            font-weight: 800;
            writing-mode: vertical-rl;
            letter-spacing: 1px;
            transform: rotate(180deg);
        }

        .peru-plate-input {
            letter-spacing: 2px;
            padding: 0;
        }

        .peru-plate-input:focus {
            box-shadow: none !important;
        }

        .btn-buscar-plate {
            background-color: #BE3C3B !important;
            border-radius: 0 3px 3px 0 !important;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .btn-reload-manual {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .btn-reload-manual:hover {
            transform: rotate(180deg);
        }

        optgroup {
            font-weight: 700;
            color: #132530;
            background: #f8f9fa;
        }

        option {
            font-weight: 400;
            color: #333;
            background: #fff;
        }

        .custom-dropdown .dropdown-toggle:disabled {
            background-color: #e9ecef !important;
            opacity: 0.7;
            cursor: not-allowed;
        }

        .custom-dropdown .dropdown-toggle::after {
            display: none;
        }

        .custom-dropdown .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .custom-dropdown .dropdown-item:active {
            background-color: #BE3C3B;
            color: white !important;
        }

        .custom-dropdown .dropdown-item:active * {
            color: white !important;
        }

        .text-wrap {
            white-space: normal !important;
        }

        .btn-confirm-custom {
            background-color: #132530 !important;
            color: white !important;
            border: none !important;
            padding: 10px 25px !important;
            border-radius: 4px !important;
            font-weight: 500 !important;
        }

        .btn-confirm-custom:hover {
            background-color: #1c3646 !important;
            color: white !important;
        }
    </style>

    <script>
        function confirmRemoval(productId, productName) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-confirm-custom ms-2",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "¿Estás seguro?",
                text: `¿Deseas quitar "${productName}" de tu lista de reparación?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, quitarlo",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.removeFromRepair(productId);
                    swalWithBootstrapButtons.fire({
                        title: "¡Eliminado!",
                        text: "El repuesto ha sido quitado de tu lista.",
                        icon: "success",
                        timer: 1500,
                        showConfirmButton: false

                    });
                }
            });
        }
    </script>

    {{-- 5.2 VERIFICATION MODAL OVERLAY --}}
    @if($showLeadForm)
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="background: rgba(0,0,0,0.8); z-index: 1050; backdrop-filter: blur(5px);">
            <div class="col-11 col-md-5">
                <div class="card bg-white p-4 p-md-5 shadow-lg border-0 text-center rounded-4 position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        wire:click="$set('showLeadForm', false)"></button>

                    <div class="mb-4">
                        <div class="text-white d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm"
                            style="width: 70px; height: 70px; background: @if(auth()->check() && auth()->user()->hasVerifiedPhone()) #00c853 @else #132530 @endif;">
                            <i class="fab fa-whatsapp fa-2x"></i>
                        </div>
                    </div>

                    @if(auth()->check() && auth()->user()->hasVerifiedPhone())
                        {{-- ✅ NÚMERO YA VINCULADO --}}
                        <h3 class="fw-bold text-dark mb-2">WhatsApp vinculado</h3>
                        <p class="text-muted mb-4 small">
                            Tu número de WhatsApp ya está verificado y asociado a tu cuenta.
                            No necesitas volver a hacerlo.
                        </p>

                        <div class="bg-light rounded-3 p-3 mb-4 text-start d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white"
                                style="width:38px; height:38px; flex-shrink:0;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <div class="small text-muted mb-0">Número verificado</div>
                                <div class="fw-bold text-dark">
                                    +{{ auth()->user()->phone }}
                                </div>
                                <div class="x-small text-muted">
                                    Vinculado {{ auth()->user()->phone_verified_at?->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-danger w-100 py-3 fw-bold rounded-3 mb-3"
                            wire:click="$set('showLeadForm', false)">
                            <i class="fab fa-whatsapp me-2"></i> Continuar con este número
                        </button>

                        <a href="#" class="x-small text-muted text-decoration-underline" wire:click.prevent="clearVerification">
                            Desvincular este número y usar otro
                        </a>

                    @else
                        {{-- 🔐 FLUJO NORMAL DE VERIFICACIÓN --}}
                        <h3 class="fw-bold text-dark mb-2">Verifica tu WhatsApp</h3>
                        <p class="text-muted mb-4 small">
                            Confirma tu número para recibir disponibilidad y precios.
                            @auth <span class="fw-medium text-success">Solo te pediremos esto una vez.</span> @endauth
                        </p>

                        <div class="text-start">
                            <label class="small fw-bold text-muted mb-1">Número de Celular</label>
                            <div class="input-group mb-4 shadow-sm border rounded overflow-hidden">
                                <span class="input-group-text bg-light border-0">
                                    <span class="small fw-bold text-primary">+51</span>
                                </span>
                                <input type="text" class="form-control border-0 py-3"
                                    placeholder="987 654 321 (sin código de país)" wire:model="phone" maxlength="9"
                                    inputmode="numeric">
                                <button class="btn btn-primary-custom px-4" wire:click="sendVerificationCode"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="sendVerificationCode">Enviar</span>
                                    <span wire:loading wire:target="sendVerificationCode">
                                        <i class="fas fa-circle-notch fa-spin"></i>
                                    </span>
                                </button>
                            </div>

                            <label class="small fw-bold text-muted mb-1">Código de 4 dígitos</label>
                            <div class="input-group mb-4 shadow-sm border rounded overflow-hidden">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-key"></i></span>
                                <input type="text" class="form-control border-0 py-3 text-center fw-bold fs-5"
                                    placeholder="0 0 0 0" wire:model="verificationCode" maxlength="4" inputmode="numeric">
                                <button class="btn btn-danger px-4" wire:click="verifyCode" wire:loading.attr="disabled"
                                    wire:target="verifyCode">
                                    <span wire:loading.remove wire:target="verifyCode">Verificar</span>
                                    <span wire:loading wire:target="verifyCode">
                                        <i class="fas fa-circle-notch fa-spin"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="x-small text-muted">
                            <i class="fas fa-shield-alt me-1 text-success"></i>
                            Solo recibirás mensajes relacionados a tu pedido. Sin spam.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- ── DELIVERY ADDRESS MODAL ── --}}
    @if($showDeliveryModal)
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-end align-items-md-center justify-content-center"
            style="background: rgba(0,0,0,0.75); z-index: 1060; backdrop-filter: blur(6px);">
            <div class="col-12 col-md-6 col-lg-5" style="max-height: 95vh; overflow-y: auto;">
                <div class="card border-0 shadow-lg rounded-top-4 rounded-md-4" style="background: #111827;">

                    {{-- Handle (mobile) --}}
                    <div class="text-center pt-3 d-md-none">
                        <div
                            style="width:40px; height:4px; background:rgba(255,255,255,0.15); border-radius:10px; margin:0 auto;">
                        </div>
                    </div>

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between px-4 pt-4 pb-3"
                        style="border-bottom: 1px solid rgba(255,255,255,0.07);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center rounded-3"
                                style="width:42px;height:42px;background:linear-gradient(135deg,#BE3C3B,#e05555);">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">¿A dónde enviamos?</h5>
                                <p class="mb-0 text-white" style="font-size:0.75rem;">Antes de consultar a los
                                    proveedores</p>
                            </div>
                        </div>
                        <button wire:click="$set('showDeliveryModal', false)" class="btn p-1"
                            style="color:rgba(255,255,255,0.4);font-size:1.2rem;">&times;</button>
                    </div>

                    {{-- Type Toggle --}}
                    <div class="px-4 pt-4">
                        <div class="d-flex gap-2 p-1 rounded-3" style="background:rgba(255,255,255,0.05);">
                            <button wire:click="$set('deliveryType','lima')"
                                class="btn flex-1 py-2 fw-bold rounded-2 {{ $deliveryType === 'lima' ? 'text-white' : 'text-white' }}"
                                style="font-size:0.85rem; {{ $deliveryType === 'lima' ? 'background:#BE3C3B;' : 'background:transparent;' }}">
                                <i class="fas fa-motorcycle me-1"></i> Lima
                            </button>
                            <button wire:click="$set('deliveryType','province')"
                                class="btn flex-1 py-2 fw-bold rounded-2 {{ $deliveryType === 'province' ? 'text-white' : 'text-white' }}"
                                style="font-size:0.85rem; {{ $deliveryType === 'province' ? 'background:#BE3C3B;' : 'background:transparent;' }}">
                                <i class="fas fa-bus me-1"></i> Provincia
                            </button>
                        </div>
                    </div>

                    {{-- ★ DIRECCIONES GUARDADAS ──────────────────────── --}}
                    @if(auth()->check() && count($savedAddresses) > 0)
                        <div class="px-4 pt-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-white"
                                    style="font-size:0.72rem; letter-spacing:.5px; text-transform:uppercase;">
                                    <i class="fas fa-history me-1" style="color:#BE3C3B;"></i> Mis direcciones
                                </span>
                                <span class="text-white-50" style="font-size:0.7rem;">{{ count($savedAddresses) }}/5</span>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($savedAddresses as $idx => $addr)
                                            <button wire:click="loadSavedAddress({{ $idx }})"
                                                class="btn text-start p-2 rounded-3 position-relative"
                                                style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1);
                                                                                                                                                                               max-width: 100%; transition: all .2s;
                                                                                                                                                                               {{ ($deliveryType === ($addr['type'] ?? '') &&
                                    ($deliveryAddress === ($addr['address'] ?? '') || $deliveryAgency === ($addr['agency'] ?? '')))
                                    ? 'border-color:#BE3C3B!important; background:rgba(190,60,59,0.12)!important;' : '' }}"
                                                onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                                                onmouseout="this.style.background='rgba(255,255,255,0.06)'">

                                                {{-- Badge "Última usada" solo en el primero --}}
                                                @if($idx === 0)
                                                    <span class="badge position-absolute top-0 end-0 mt-1 me-1"
                                                        style="background:#BE3C3B; font-size:0.6rem; padding:2px 6px;">
                                                        Reciente
                                                    </span>
                                                @endif

                                                <div class="d-flex align-items-center gap-2 pe-3">
                                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0 rounded-2"
                                                        style="width:28px; height:28px; background:rgba(190,60,59,0.2);">
                                                        @if(($addr['type'] ?? '') === 'lima')
                                                            <i class="fas fa-motorcycle" style="font-size:0.7rem; color:#BE3C3B;"></i>
                                                        @else
                                                            <i class="fas fa-bus" style="font-size:0.7rem; color:#BE3C3B;"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="text-white fw-bold"
                                                            style="font-size:0.78rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;">
                                                            {{ $addr['label'] ?? ($addr['district'] ?? $addr['city'] ?? '—') }}
                                                        </div>
                                                        <div class="text-white-50" style="font-size:0.68rem;">
                                                            @if(($addr['type'] ?? '') === 'lima')
                                                                {{ $addr['address'] ?? '' }}
                                                            @else
                                                                {{ $addr['agency'] ?? '' }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </button>
                                @endforeach
                            </div>

                            <div class="my-3 d-flex align-items-center gap-2">
                                <div style="flex:1; height:1px; background:rgba(255,255,255,0.07);"></div>
                                <span class="text-white-50" style="font-size:0.72rem;">o ingresa una nueva</span>
                                <div style="flex:1; height:1px; background:rgba(255,255,255,0.07);"></div>
                            </div>
                        </div>
                    @endif

                    {{-- LIMA FIELDS --}}
                    @if($deliveryType === 'lima')
                        <div class="px-4 pt-3 pb-2">
                            <div class="mb-3">
                                <label class="text-white fw-bold mb-1"
                                    style="font-size:0.75rem; letter-spacing:.5px;">DIRECCIÓN</label>
                                <div class="input-group rounded-3 overflow-hidden"
                                    style="background:#1A2235; border:1px solid rgba(255,255,255,0.08);">
                                    <span class="input-group-text border-0" style="background:transparent; color:#6B7A99;"><i
                                            class="fas fa-home"></i></span>
                                    <input type="text" wire:model="deliveryAddress"
                                        class="form-control border-0 py-3 text-white"
                                        style="background:transparent; font-size:0.9rem;"
                                        placeholder="Av. Las Flores 123, interior 4B">
                                </div>
                                @error('deliveryAddress') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="text-white fw-bold mb-1"
                                    style="font-size:0.75rem; letter-spacing:.5px;">DISTRITO</label>
                                <div class="position-relative rounded-3 overflow-hidden"
                                    style="background:#1A2235; border:1px solid rgba(255,255,255,0.08);">
                                    <i class="fas fa-map-pin position-absolute"
                                        style="left:14px; top:50%; transform:translateY(-50%); color:#6B7A99; z-index:1;"></i>
                                    <select wire:model.live="deliveryDistrict" class="form-select border-0 py-3 text-white ps-5"
                                        style="background:transparent; font-size:0.9rem; appearance:auto;">
                                        <option value="" style="background:#1A2235;">— Selecciona tu distrito —</option>
                                        @foreach($limaZones as $zoneName => $zone)
                                            <optgroup label="Zona {{ $zoneName }} (S/. {{ $zone['cost'] }} aprox.)"
                                                style="background:#0d1117; color:#6B7A99;">
                                                @foreach($zone['districts'] as $dist)
                                                    <option value="{{ $dist }}" style="background:#1A2235; color:#fff;">{{ $dist }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                @error('deliveryDistrict') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    @endif

                    {{-- PROVINCE FIELDS --}}
                    @if($deliveryType === 'province')
                        <div class="px-4 pt-3 pb-2">
                            <div class="p-3 rounded-3 mb-3"
                                style="background:rgba(245,158,11,0.08); border:1px solid rgba(245,158,11,0.2);">
                                <p class="mb-0 text-warning" style="font-size:0.78rem;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    El motorizado lleva tu pedido hasta la agencia en Lima.
                                    El flete desde Lima a tu ciudad <strong>lo cobra la agencia al momento de
                                        recoger</strong>.
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="text-white fw-bold mb-1" style="font-size:0.75rem; letter-spacing:.5px;">AGENCIA
                                    DE TRANSPORTE</label>
                                <div class="position-relative rounded-3 overflow-hidden"
                                    style="background:#1A2235; border:1px solid rgba(255,255,255,0.08);">
                                    <i class="fas fa-truck position-absolute"
                                        style="left:14px; top:50%; transform:translateY(-50%); color:#6B7A99; z-index:1;"></i>
                                    <select wire:model.live="deliveryAgency" class="form-select border-0 py-3 text-white ps-5"
                                        style="background:transparent; font-size:0.9rem; appearance:auto;">
                                        <option value="" style="background:#1A2235;">— Elige la agencia —</option>
                                        @foreach($shippingAgencies as $agency => $cost)
                                            <option value="{{ $agency }}" style="background:#1A2235; color:#fff;">{{ $agency }}
                                                (costo a agencia: S/. {{ $cost }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('deliveryAgency') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="text-white fw-bold mb-1" style="font-size:0.75rem; letter-spacing:.5px;">CIUDAD /
                                    DEPARTAMENTO DE DESTINO</label>
                                <div class="input-group rounded-3 overflow-hidden"
                                    style="background:#1A2235; border:1px solid rgba(255,255,255,0.08);">
                                    <span class="input-group-text border-0" style="background:transparent; color:#6B7A99;"><i
                                            class="fas fa-city"></i></span>
                                    <input type="text" wire:model="deliveryCity" class="form-control border-0 py-3 text-white"
                                        style="background:transparent; font-size:0.9rem;"
                                        placeholder="Ej: Arequipa, Cusco, Trujillo...">
                                </div>
                                @error('deliveryCity') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            {{-- Divider --}}
                            <div class="mb-3 mt-4" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1rem;">
                                <div class="text-white fw-bold mb-3" style="font-size:0.8rem; letter-spacing:.5px;">
                                    <i class="fas fa-user-check me-2 text-primary-custom"></i>DATOS DEL DESTINATARIO
                                </div>

                                {{-- Nombre --}}
                                <div class="mb-3">
                                    <label class="text-white fw-bold mb-1"
                                        style="font-size:0.75rem; letter-spacing:.5px;">NOMBRE COMPLETO *</label>
                                    <div class="input-group rounded-3 overflow-hidden"
                                        style="background:#1A2235; border:1px solid rgba(255,255,255,0.08);">
                                        <span class="input-group-text border-0"
                                            style="background:transparent; color:#6B7A99;"><i class="fas fa-user"></i></span>
                                        <input type="text" wire:model="recipientName"
                                            class="form-control border-0 py-2 text-white"
                                            style="background:transparent; font-size:0.9rem;" placeholder="Juan Pérez Quispe">
                                    </div>
                                    @error('recipientName') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- DNI / RUC --}}
                                <div class="mb-3">
                                    <label class="text-white fw-bold mb-1" style="font-size:0.75rem; letter-spacing:.5px;">DNI o
                                        RUC *</label>
                                    <div class="input-group rounded-3 overflow-hidden"
                                        style="background:#1A2235; border:1px solid rgba(255,255,255,0.08);">
                                        <span class="input-group-text border-0"
                                            style="background:transparent; color:#6B7A99;"><i class="fas fa-id-card"></i></span>
                                        <input type="text" wire:model="recipientDni"
                                            class="form-control border-0 py-2 text-white"
                                            style="background:transparent; font-size:0.9rem;"
                                            placeholder="12345678 (DNI) o 20123456789 (RUC)" maxlength="11">
                                    </div>
                                    @error('recipientDni') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Celular destinatario --}}
                                <div class="mb-3">
                                    <label class="text-white fw-bold mb-1"
                                        style="font-size:0.75rem; letter-spacing:.5px;">CELULAR DEL DESTINATARIO *</label>
                                    <div class="input-group rounded-3 overflow-hidden"
                                        style="background:#1A2235; border:1px solid rgba(255,255,255,0.08);">
                                        <span class="input-group-text border-0"
                                            style="background:transparent; color:#6B7A99;"><i
                                                class="fas fa-mobile-alt"></i></span>
                                        <input type="text" wire:model="recipientPhone"
                                            class="form-control border-0 py-2 text-white"
                                            style="background:transparent; font-size:0.9rem;" placeholder="987 654 321"
                                            maxlength="9">
                                    </div>
                                    @error('recipientPhone') <small class="text-danger">{{ $message }}</small> @enderror
                                    <small class="text-white" style="font-size:0.7rem;">La contraseña de recojo se enviará a
                                        este número por WhatsApp.</small>
                                </div>

                                {{-- Referencia domicilio (opcional) --}}
                                <div class="mb-2">
                                    <label class="text-white fw-bold mb-1"
                                        style="font-size:0.75rem; letter-spacing:.5px;">REFERENCIA DE DOMICILIO <span
                                            style="opacity:.5;">(opcional)</span></label>
                                    <div class="input-group rounded-3 overflow-hidden"
                                        style="background:#1A2235; border:1px solid rgba(255,255,255,0.08);">
                                        <span class="input-group-text border-0"
                                            style="background:transparent; color:#6B7A99;"><i
                                                class="fas fa-map-signs"></i></span>
                                        <input type="text" wire:model="recipientAddress"
                                            class="form-control border-0 py-2 text-white"
                                            style="background:transparent; font-size:0.9rem;"
                                            placeholder="Cerca al mercado, casa color verde...">
                                    </div>
                                </div>
                            </div>

                            {{-- Pickup password info box --}}
                            <div class="p-3 rounded-3 mb-1"
                                style="background:rgba(59,130,246,0.08); border:1px solid rgba(59,130,246,0.2);">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-lock text-primary mt-1" style="font-size:0.85rem;"></i>
                                    <p class="mb-0 text-white" style="font-size:0.78rem;">
                                        <strong>Contraseña de recojo:</strong> Se genera automáticamente al confirmar el
                                        pago y se enviará al celular del destinatario por WhatsApp. El motorizado entregará
                                        esta contraseña a la agencia al momento del depósito.
                                    </p>
                                </div>
                            </div>

                        </div>
                    @endif

                    {{-- Cost Preview --}}
                    @if($estimatedDeliveryCost > 0)
                        <div class="mx-4 mb-3 p-3 rounded-3 d-flex justify-content-between align-items-center"
                            style="background:rgba(0,214,143,0.08); border:1px solid rgba(0,214,143,0.2);">
                            <div>
                                <div class="text-white" style="font-size:0.72rem; letter-spacing:.5px;">COSTO ESTIMADO DE
                                    ENVÍO</div>
                                <div class="fw-bold text-success" style="font-size:1.1rem;">S/.
                                    {{ $estimatedDeliveryCost }}.00
                                </div>
                            </div>
                            <i class="fas fa-check-circle text-success fa-2x opacity-50"></i>
                        </div>
                    @endif

                    {{-- Cost breakdown note --}}
                    <div class="mx-4 mb-4 p-3 rounded-3"
                        style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06);">
                        <div class="text-white" style="font-size:0.72rem;">
                            <div class="d-flex justify-content-between py-1"><span>Productos (confirmación
                                    pendiente)</span><span>Variable</span></div>
                            <div class="d-flex justify-content-between py-1 align-items-center">
                                <span>Comisión de servicio</span>
                                <div class="text-end">
                                    <span style="text-decoration: line-through; opacity: 0.6; margin-right: 5px;">S/.
                                        15.00</span>
                                    <span class="text-success fw-bold">S/. 0.00</span>
                                </div>
                            </div>
                            <div class="text-end text-success mb-1" style="font-size: 0.65rem; margin-top: -2px;">
                                ¡No cobramos esta comisión por el momento!
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span>Envío</span><span>{{ $estimatedDeliveryCost > 0 ? 'S/. ' . $estimatedDeliveryCost . '.00' : 'Según destino' }}</span>
                            </div>
                            <div class="d-flex justify-content-between pt-2 mt-1 fw-bold text-white"
                                style="border-top:1px solid rgba(255,255,255,0.06);">
                                <span>Total estimado</span>
                                <span>Productos +
                                    {{ $estimatedDeliveryCost > 0 ? 'S/. ' . $estimatedDeliveryCost . '.00' : 'Envío según destino' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="px-4 pb-4">
                        <button wire:click="confirmDeliveryAndProceed" class="btn w-100 py-3 fw-bold text-white rounded-3"
                            style="background: linear-gradient(135deg, #BE3C3B, #e05555); font-size:0.95rem; letter-spacing:.3px;">
                            <i class="fab fa-whatsapp me-2"></i>
                            Consultar disponibilidad
                        </button>
                        <button wire:click="$set('showDeliveryModal',false)" class="btn w-100 mt-2 text-white"
                            style="font-size:0.85rem;">
                            Cancelar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- 5.3 SUCCESS POPUP --}}
    @if($showSuccessPopup)
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="background: rgba(0,0,0,0.8); z-index: 2100; backdrop-filter: blur(5px);">
            <div class="col-11 col-md-5">
                <div class="card bg-white p-4 p-md-5 shadow-lg border-0 text-center rounded-4">
                    <div class="mb-4">
                        <div class="bg-success text-white d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm"
                            style="width: 80px; height: 80px;">
                            <i class="fas fa-check fa-3x"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">¡Solicitud Enviada!</h2>
                    <p class="text-dark mb-4">Hemos recibido tu lista de repuestos. Un asesor de
                        <strong>ZettaBot</strong> se pondrá en contacto contigo por WhatsApp a la brevedad con la
                        disponibilidad y precios finales.
                    </p>

                    @if(session()->has('secret_key'))
                        <div
                            class="alert alert-warning border-warning border-opacity-25 bg-warning bg-opacity-10 mb-4 text-start">
                            <div class="fw-bold text-dark mb-1"><i class="fas fa-lock me-2"></i>Clave Secreta de Envío:</div>
                            <div class="fs-4 fw-bold text-danger text-center letter-spacing-2">{{ session('secret_key') }}</div>
                            <div class="x-small text-muted mt-2">Guárdate esta clave, la necesitarás para el seguimiento y
                                recojo de tu pedido en provincia.</div>
                        </div>
                    @endif

                    <button class="btn btn-primary-custom btn-lg w-100 py-3 fw-bold" wire:click="closeSuccessPopup">
                        ENTENDIDO
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ── MODAL: FICHA TÉCNICA DETALLADA ──────────────────────────── --}}
    @if($showDetailsModal && $selectedProductForDetails)
        @php $p = $selectedProductForDetails; @endphp
        <div wire:click.self="closeDetails"
            style="position:fixed; inset:0; background:rgba(0,0,0,0.65); z-index:9999; display:flex; align-items:center; justify-content:center; padding:1rem;">
            <div
                style="background: linear-gradient(135deg, #0b1528 0%, #080f1d 100%); border: 2px solid #38bdf8; color: #fff; border-radius:16px; max-width:640px; width:100%; max-height:90vh; overflow-y:auto; box-shadow:0 25px 60px rgba(0,0,0,0.35); position: relative;">
                <button wire:click="closeDetails"
                    style="position: absolute; top: 16px; right: 16px; background: transparent; border: none; color: #94a3b8; font-size: 1.25rem; cursor: pointer; transition: color 0.2s; z-index: 10;"
                    onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">
                    <i class="fas fa-times"></i>
                </button>

                {{-- Body --}}
                <div class="p-4 pt-5">

                    {{-- SPECS TÉCNICOS (Pro) --}}
                    <div class="mt-4" style="color:#e2e8f0;">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                            <span class="fw-bold" style="color:#f8fafc;">📐 Especificaciones Técnicas</span>
                            <span class="badge px-2 py-1 rounded-pill"
                                style="background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; font-size:0.68rem; white-space:nowrap;">
                                ⭐ Acceso Pro - por tiempo limitado
                            </span>
                        </div>
                        @php
                            $specs = is_array($p->specs) ? $p->specs : (json_decode($p->specs, true) ?? []);
                        @endphp
                        <div class="row g-2">
                            @if(!empty($selectedProductDisplacements))
                                <div class="col-12 col-md-6">
                                    <div class="small text-muted mb-0" style="color:#94a3b8 !important; font-size:0.7rem;">
                                        Cilindrada</div>
                                    <div class="fw-bold" style="color:#f8fafc; word-break: break-word;">
                                        {{ implode(' / ', $selectedProductDisplacements) }}cc
                                    </div>
                                </div>
                            @endif

                            @if(isset($specs['length']) || isset($specs['bore']) || isset($specs['pin']))
                                {{-- Bloque específico para PISTÓN (Adaptado a tema oscuro fluido) --}}

                                @if(!empty($specs['bore']))
                                    <div class="col-12 col-md-6 mt-2">
                                        <div class="small text-muted mb-0" style="color:#94a3b8 !important; font-size:0.7rem;">
                                            Especificaciones Pistón</div>
                                        <div class="fw-bold" style="color:#f8fafc; word-break: break-word;">
                                            Ø {{ $specs['bore'] }} mm
                                            @if(!empty($specs['cylinders']))
                                                <span style="color:#94a3b8; font-weight:normal;"> · {{ $specs['cylinders'] }} CYL</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($specs['length']))
                                    <div class="col-12 col-md-6 mt-2">
                                        <div class="small text-muted mb-0" style="color:#94a3b8 !important; font-size:0.7rem;">
                                            Length</div>
                                        <div class="fw-bold" style="color:#f8fafc; word-break: break-word;">{{ $specs['length'] }}
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($specs['comp_height']))
                                    <div class="col-12 col-md-6 mt-2">
                                        <div class="small text-muted mb-0" style="color:#94a3b8 !important; font-size:0.7rem;">Comp.
                                            Height</div>
                                        <div class="fw-bold" style="color:#f8fafc; word-break: break-word;">
                                            {{ $specs['comp_height'] }}
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($specs['height']) && is_array($specs['height']) && count($specs['height']) > 0)
                                    <div class="col-12 col-md-6 mt-2">
                                        <div class="small text-muted mb-0" style="color:#94a3b8 !important; font-size:0.7rem;">
                                            Height</div>
                                        <div class="fw-bold" style="color:#f8fafc; word-break: break-word;">
                                            {{ $specs['height'][0] }}{{ isset($specs['height'][1]) ? ' / ' . $specs['height'][1] : '' }}
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($specs['pin']))
                                    <div class="col-12 col-md-6 mt-2">
                                        <div class="small text-muted mb-0" style="color:#94a3b8 !important; font-size:0.7rem;">PIN
                                        </div>
                                        <div class="fw-bold" style="color:#f8fafc; word-break: break-word;">
                                            {{ str_replace('X', ' × ', $specs['pin']) }}
                                            @if(!empty($specs['circlip_required']))
                                                &nbsp;<span class="badge text-bg-warning ms-1"
                                                    style="font-size:0.55rem; padding:2px 5px; vertical-align: text-top;">circlip
                                                    ✓</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @else
                                {{-- Bloque genérico para ANILLOS / METALES --}}
                                @foreach([
                                        'raw' => 'Medidas (raw)',
                                        'radial' => 'Ancho radial (a1)',
                                        'shape' => 'Forma',
                                        'ancho' => 'Ancho',
                                        'other' => 'Notas',
                                    ] as $key => $label)
                                    @if(!empty($specs[$key]))
                                        <div class="col-12 col-md-6 mt-2">
                                            <div class="small text-muted mb-0" style="color:#94a3b8 !important; font-size:0.7rem;">
                                                {{ $label }}
                                            </div>
                                            <div class="fw-bold" style="color:#f8fafc; word-break: break-word;">
                                                {{ is_array($specs[$key]) ? implode(' / ', $specs[$key]) : $specs[$key] }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        @if(empty(array_filter($specs)))
                            <p class="text-muted small mb-0" style="color:#64748b !important;">Specs técnicos no disponibles
                                para este producto.</p>
                        @endif
                    </div>

                    {{-- Compatibilidad --}}
                    @if(!empty($p->compatible_engines) || !empty($p->compatible_vehicles))
                        <div class="mt-3">
                            <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3 mt-4">
                                <span class="fw-bold" style="color:#f8fafc;">🔧 Compatibilidad</span>
                            </div>
                            @php
                                $engines = is_array($p->compatible_engines) ? $p->compatible_engines : (json_decode($p->compatible_engines, true) ?? []);
                                $vehiclesRaw = is_array($p->compatible_vehicles)
                                    ? $p->compatible_vehicles
                                    : (is_string($p->compatible_vehicles) ? array_map('trim', explode(',', $p->compatible_vehicles)) : []);
                                $vehicles = array_filter($vehiclesRaw);
                            @endphp

                            {{-- Motor badges --}}
                            @if(!empty($engines))
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    @foreach($engines as $eng)
                                        <span class="badge rounded-pill px-3 py-1"
                                            style="background:#1e3a5f; color:#93c5fd; font-size:0.78rem; white-space:nowrap;">
                                            Motor: {{ trim($eng) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Modelos de vehículo — cada modelo en su propio badge --}}
                            @if(!empty($vehicles))
                                <div class="d-flex flex-wrap gap-1" style="max-width:100%;">
                                    @foreach($vehicles as $veh)
                                        @if(trim($veh))
                                            <span class="badge rounded-pill px-2 py-1"
                                                style="background:#14532d; color:#86efac; font-size:0.75rem; white-space:nowrap; max-width:100%; overflow:hidden; text-overflow:ellipsis;">
                                                {{ trim($veh) }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- LOGIN MODAL --}}
    @if($showLoginModal)
        <div class="modal fade show d-block"
            style="background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 2000;">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                <div class="modal-content border-0 shadow-lg"
                    style="border-radius: 20px; overflow: hidden; background-color: #132530;">
                    <div class="modal-header border-0 p-4 pb-0 justify-content-end">
                        <button type="button" class="btn-close btn-close-white" wire:click="closeLoginModal"></button>
                    </div>
                    <div class="modal-body p-5 text-center">
                        <div class="mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo" class="img-fluid mb-4"
                                style="max-height: 50px;">
                            <h3 class="text-white fw-bold mb-2">Identifícate para continuar</h3>

                            @if($loginBlockMessage)
                                <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger small my-4 py-3"
                                    style="border-radius: 12px;">
                                    <i class="fas fa-lock fa-2x mb-2 d-block"></i>
                                    <span class="fw-bold d-block mb-1">CUENTA BLOQUEADA</span>
                                    {!! str_replace('[EMAIL]', '<a href="mailto:gestion@repuestofijo.com" class="text-danger fw-bold text-decoration-underline">gestion@repuestofijo.com</a>', e($loginBlockMessage)) !!}
                                </div>
                            @endif

                            <p class="text-white text-opacity-50 small px-3">
                                @if($loginBlockMessage)
                                    {{-- No mostramos el mensaje estándar si hay bloqueo --}}
                                @elseif(count($repairList) > 0)
                                    Tienes <strong class="text-warning">{{ count($repairList) }} producto(s)</strong> en tu
                                    lista.
                                    Al iniciar sesión, continuarás desde aquí.
                                @else
                                    Accede en segundos y encuentra el repuesto que necesitas.
                                @endif
                            </p>
                        </div>

                        {{-- Botón Google — guarda estado antes de redirigir --}}
                        <button wire:click="loginWithGoogle"
                            class="btn w-100 py-3 fw-bold rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-3 mb-3 hover-scale border-0"
                            style="background-color: white; color: #333;" wire:loading.attr="disabled"
                            wire:target="loginWithGoogle">
                            <span wire:loading.remove wire:target="loginWithGoogle">
                                <img src="https://www.google.com/images/branding/googleg/1x/googleg_standard_color_128dp.png"
                                    alt="Google" width="20" style="width: 20px; height: 20px;">
                                Continuar con Google
                            </span>
                            <span wire:loading wire:target="loginWithGoogle">
                                <i class="fas fa-circle-notch fa-spin me-2"></i> Iniciando sesión con Google...
                            </span>
                        </button>

                        @if(count($repairList) > 0)
                            <div
                                class="d-flex align-items-center justify-content-center gap-2 mb-3 small text-white text-opacity-50">
                                <i class="fas fa-shield-alt text-success"></i>
                                Tu lista de reparación se guardará automáticamente
                            </div>
                        @endif

                        <div class="mt-4 pt-3 border-top border-white border-opacity-10">
                            <p class="x-small text-white text-opacity-40 mb-0">
                                Al continuar, aceptas nuestra
                                <a href="javascript:void(0)" wire:click="openPrivacyModal" class="fw-bold hover-underline"
                                    style="color: #BE3C3B;">
                                    <span wire:loading.remove wire:target="openPrivacyModal">Política de Privacidad</span>
                                    <span wire:loading wire:target="openPrivacyModal"><i
                                            class="fas fa-circle-notch fa-spin"></i> Cargando...</span>
                                </a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- USER PANEL DRAWER (Side Panel) --}}
    {{-- USER PANEL DRAWER (Side Panel) --}}
    @if($showOrdersDrawer)
        <div class="fixed-top h-100 w-100 overflow-hidden"
            style="z-index: 1050; background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);">
            <div class="bg-white h-100 ms-auto shadow-lg d-flex flex-column"
                style="max-width: 450px; width: 90%; animation: slideInInRight 0.3s ease-out;">

                {{-- Drawer Header (Dark Blue) --}}
                <div class="p-4 bg-header-custom d-flex justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3 overflow-hidden">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ auth()->user()->profile_photo_path }}" alt="User"
                                class="rounded-circle border border-white border-opacity-25"
                                style="width: 45px; height: 45px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center border border-white border-opacity-25"
                                style="width: 45px; height: 45px;">
                                <i class="fas fa-user text-white fs-5 opacity-75"></i>
                            </div>
                        @endif
                        <div class="overflow-hidden">
                            <div class="text-white fw-bold text-truncate" style="font-family: 'Syne', sans-serif;">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="text-white text-opacity-50 x-small text-truncate" style="font-size: 0.7rem;">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-link text-white text-decoration-none p-0 opacity-75 hover-opacity-100"
                        wire:click="toggleOrders">
                        <i class="fas fa-times fs-4"></i>
                    </button>
                </div>

                {{-- Drawer Content --}}
                <div class="flex-grow-1 overflow-auto bg-white custom-scrollbar">
                    @if($drawerSubState === 'menu')
                        {{-- Main Menu --}}
                        <div class="list-group list-group-flush">
                            <button wire:click="showOrders"
                                class="list-group-item list-group-item-action border-0 px-4 d-flex align-items-center justify-content-between user-drawer-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <span>Mis pedidos</span>
                                </div>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </button>

                            <button wire:click="showAddresses"
                                class="list-group-item list-group-item-action border-0 px-4 d-flex align-items-center justify-content-between user-drawer-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <span>Direcciones</span>
                                </div>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </button>

                            <button wire:click="showProfile"
                                class="list-group-item list-group-item-action border-0 px-4 d-flex align-items-center justify-content-between user-drawer-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <span>Mis datos</span>
                                </div>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </button>

                            <button
                                class="list-group-item list-group-item-action border-0 px-4 d-flex align-items-center justify-content-between user-drawer-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <span>Lista de deseos</span>
                                </div>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </button>

                            <button
                                class="list-group-item list-group-item-action border-0 px-4 d-flex align-items-center justify-content-between user-drawer-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle">
                                        <i class="fas fa-piggy-bank"></i>
                                    </div>
                                    <span>Mis bonificaciones</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted small">S/ 0.00</span>
                                    <i class="fas fa-chevron-right text-muted small"></i>
                                </div>
                            </button>
                        </div>

                        <div class="mt-auto p-4 border-top">
                            <button wire:click="logout"
                                class="btn btn-dark w-100 py-3 rounded-pill d-flex align-items-center justify-content-center gap-3 fw-bold transition-all hover-scale shadow-sm">
                                <span>Cerrar Sesión</span>
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </div>

                    @elseif($drawerSubState === 'orders')
                        {{-- Orders List Subview --}}
                        <div class="p-4 pt-2">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <button class="btn btn-link text-decoration-none text-muted p-0 d-flex align-items-center gap-2"
                                    wire:click="showMenu">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </button>
                                <span class="text-dark fw-bold">Mis pedidos</span>
                            </div>

                            @forelse($this->orders as $order)
                                <div class="card border border-light mb-3 shadow-sm hover-scale transition-all"
                                    style="border-radius: 12px;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <div class="small text-muted" style="font-size: 0.75rem;">Pedido #{{ $order->id }}
                                                </div>
                                                <div class="text-dark fw-bold">{{ $order->created_at->format('d M, Y') }}</div>
                                            </div>
                                            @php
                                                $statusConfig = [
                                                    'pendiente' => ['label' => 'Pendiente', 'class' => 'bg-secondary text-white'],
                                                    'pagado' => ['label' => 'Pagado', 'class' => 'bg-success text-white'],
                                                    'en_preparacion' => ['label' => 'En preparación', 'class' => 'bg-warning text-dark'],
                                                    'en_camino' => ['label' => 'En camino', 'class' => 'bg-info text-white'],
                                                    'entregado' => ['label' => 'Entregado', 'class' => 'bg-success text-white'],
                                                    'cancelado' => ['label' => 'Cancelado', 'class' => 'bg-danger text-white'],
                                                ];
                                                $orderStatusValue = is_object($order->status) ? $order->status->value : $order->status;
                                                $s = $statusConfig[$orderStatusValue] ?? ['label' => 'Status', 'class' => 'bg-light text-dark'];
                                            @endphp
                                            <div class="text-end">
                                                <span class="badge {{ $s['class'] }} mb-2"
                                                    style="font-size: 0.75rem; border-radius: 6px; padding: 0.5em 1em;">{{ $s['label'] }}</span>
                                                <div class="mt-1">
                                                    <button wire:click.stop="reportIncident({{ $order->id }})"
                                                        class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-3 py-1 shadow-none"
                                                        style="font-size: 0.65rem; border-width: 1.5px;">
                                                        <i class="fas fa-exclamation-triangle me-1" style="font-size: 0.6rem;"></i>
                                                        Reportar un problema
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-light">
                                            <div class="text-muted small">Total: <span class="text-dark fw-bold">S/
                                                    {{ number_format($order->total, 2) }}</span></div>
                                            <div class="d-flex gap-2">
                                                <button
                                                    class="btn btn-light border p-2 rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px; color: #132530;" title="Ver detalle"
                                                    wire:click="viewUserOrderDetail({{ $order->id }})">
                                                    <i class="fas fa-eye small"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                        style="width: 80px; height: 80px;">
                                        <i class="fas fa-box-open text-muted text-opacity-20 fs-1"></i>
                                    </div>
                                    <h5 class="text-dark">Sin pedidos aún</h5>
                                    <p class="text-muted small">Tus compras aparecerán aquí.</p>
                                </div>
                            @endforelse
                        </div>

                    @elseif($drawerSubState === 'addresses')
                        {{-- Addresses Subview --}}
                        <div class="p-4 pt-2">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <button class="btn btn-link text-decoration-none text-muted p-0 d-flex align-items-center gap-2"
                                    wire:click="showMenu">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </button>
                                <span class="text-dark fw-bold">Direcciones</span>
                            </div>

                            @php $addresses = auth()->user()->getSavedAddresses(); @endphp

                            @if(count($addresses) < 3)
                                <button wire:click="addNewAddress"
                                    class="btn btn-danger w-100 mb-4 py-2 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
                                    style="background-color: #BE3C3B; border: none;">
                                    <i class="fas fa-plus"></i>
                                    <span>Agregar dirección</span>
                                </button>
                            @endif
                            @forelse($addresses as $idx => $addr)
                                <div class="card border border-light mb-3 shadow-sm" style="border-radius: 12px;">
                                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width: 40px; height: 40px;">
                                                <i
                                                    class="fas {{ $addr['type'] === 'lima' ? 'fa-home' : 'fa-truck' }} text-primary"></i>
                                            </div>
                                            <div class="overflow-hidden flex-grow-1">
                                                <div class="text-dark fw-bold text-truncate" title="{{ $addr['label'] }}">
                                                    {{ $addr['label'] }}
                                                </div>
                                                <div class="text-muted small text-truncate">
                                                    @if($addr['type'] === 'lima')
                                                        {{ $addr['address'] }}
                                                    @else
                                                        {{ $addr['city'] }} · {{ $addr['agency'] ?? '' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button wire:click="editAddress({{ $idx }})"
                                                class="btn btn-light border p-2 rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="fas fa-pen small text-primary"></i>
                                            </button>
                                            <button wire:click="deleteAddress({{ $idx }})"
                                                class="btn btn-light border p-2 rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;"
                                                onclick="confirm('¿Estás seguro de eliminar esta dirección?') || event.stopImmediatePropagation()">
                                                <i class="fas fa-trash small text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                                        style="width: 80px; height: 80px;">
                                        <i class="fas fa-map-marked-alt text-muted text-opacity-20 fs-1"></i>
                                    </div>
                                    <h5 class="text-dark">No tienes direcciones</h5>
                                    <p class="text-muted small">Agrega una dirección en tu próxima compra.</p>
                                </div>
                            @endforelse

                            <div class="mt-4 text-center pb-4">
                                @if(count($addresses) < 3)
                                    <div class="bg-light rounded-pill d-inline-block px-3 py-1">
                                        <span class="text-muted small">Te {{ (3 - count($addresses)) == 1 ? 'queda' : 'quedan' }}
                                            <span class="text-dark fw-bold">{{ 3 - count($addresses) }}</span>
                                            {{ (3 - count($addresses)) == 1 ? 'espacio' : 'espacios' }} disponibles</span>
                                    </div>
                                @else
                                    <div
                                        class="bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-pill d-inline-block px-4 py-2">
                                        <span class="text-danger small fw-bold"><i class="fas fa-crown me-1"></i> Sube a Pro para
                                            añadir más direcciones</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    @elseif($drawerSubState === 'profile')
                        {{-- Profile Subview --}}
                        <div class="p-4 pt-2">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <button class="btn btn-link text-decoration-none text-muted p-0 d-flex align-items-center gap-2"
                                    wire:click="showMenu">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </button>
                            </div>

                            <div class="text-center mb-4 pb-4 border-bottom">
                                @if(auth()->user()->profile_photo_path)
                                    <img src="{{ auth()->user()->profile_photo_path }}" alt="User"
                                        class="rounded-circle border border-warning shadow-sm mb-3"
                                        style="width: 100px; height: 100px; object-fit: cover; border-width: 3px !important; padding: 4px;">
                                @else
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center shadow-sm mb-3"
                                        style="width: 100px; height: 100px;">
                                        <i class="fas fa-user text-muted fs-1"></i>
                                    </div>
                                @endif
                                <h4 class="fw-bold text-dark mb-1">{{ auth()->user()->name }}</h4>
                                <div
                                    class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-medium border border-danger border-opacity-25 ripple-bg-danger">
                                    {{ auth()->user()->getRoleLabel() }}
                                </div>
                            </div>

                            <div class="vstack gap-4">
                                <div class="d-flex align-items-start gap-3 overflow-hidden">
                                    <div class="text-muted flex-shrink-0" style="width: 24px;"><i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="small text-muted mb-1">Correo electrónico</div>
                                        <div class="text-dark fw-bold text-truncate">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start gap-3">
                                    <div class="text-muted" style="width: 24px;"><i class="fas fa-phone"></i></div>
                                    <div>
                                        <div class="small text-muted mb-1">Celular</div>
                                        <div class="text-dark fw-bold">{{ auth()->user()->phone ?? 'No registrado' }}</div>
                                    </div>
                                </div>


                                <div class="d-flex align-items-start gap-3">
                                    <div class="text-muted" style="width: 24px;"><i class="fas fa-calendar-alt"></i></div>
                                    <div>
                                        <div class="small text-muted mb-1">Miembro desde</div>
                                        <div class="text-dark fw-bold">{{ auth()->user()->created_at->format('d/m/Y - H:i') }}
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->ruc_dni || auth()->user()->business_name)
                                    <div class="pt-2 border-top border-light mt-2">
                                        <div class="extra-small text-muted fw-bold text-uppercase ls-1 mb-3"
                                            style="font-size: 0.65rem;">Datos Fiscales</div>

                                        @if(auth()->user()->ruc_dni)
                                            <div class="d-flex align-items-start gap-3 mb-3">
                                                <div class="text-muted" style="width: 24px;"><i class="fas fa-id-card"></i></div>
                                                <div>
                                                    <div class="small text-muted mb-1">{{ auth()->user()->getDocumentLabel() }}</div>
                                                    <div class="text-dark fw-bold">{{ auth()->user()->ruc_dni }}</div>
                                                </div>
                                            </div>
                                        @endif

                                        @if(auth()->user()->business_name)
                                            <div class="d-flex align-items-start gap-3 overflow-hidden">
                                                <div class="text-muted flex-shrink-0" style="width: 24px;"><i
                                                        class="fas fa-building"></i></div>
                                                <div class="overflow-hidden">
                                                    <div class="small text-muted mb-1">Razón Social</div>
                                                    <div class="text-dark fw-bold text-truncate"
                                                        title="{{ auth()->user()->business_name }}">{{ auth()->user()->business_name }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                    @endif
                </div>
            </div>
        </div>
        <style>
            @keyframes slideInInRight {
                from {
                    transform: translateX(100%);
                }

                to {
                    transform: translateX(0);
                }
            }

            .custom-scrollbar::-webkit-scrollbar {
                width: 5px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.1);
                border-radius: 10px;
            }

            /* Neutral Drawer Menu Style */
            .user-drawer-item {
                background-color: #ffffff !important;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
                color: #1a2e3a !important;
                transition: all 0.2s ease !important;
                font-size: 0.95rem;
                font-weight: 500;
                height: 60px;
            }

            .user-drawer-item:hover {
                background-color: #BE3C3B !important;
                color: #ffffff !important;
            }

            .user-drawer-item i {
                color: #1a2e3a;
                width: 20px;
                transition: color 0.2s ease;
            }

            .user-drawer-item .icon-circle {
                width: 36px;
                height: 36px;
                background: #f1f5f9;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }

            .user-drawer-item:hover .icon-circle {
                background: rgba(255, 255, 255, 0.2) !important;
            }

            .user-drawer-item:hover i,
            .user-drawer-item:hover .text-muted,
            .user-drawer-item:hover span {
                color: #ffffff !important;
            }
        </style>
    @endif

    {{-- ADDRESS EDIT MODAL --}}
    @if($showAddressEditModal)
        <div class="modal fade show d-block"
            style="background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 2200;">
            <div class="modal-dialog modal-dialog-centered px-3" style="max-width: 450px;">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background-color: #1a1e2e;">
                    <div class="modal-header border-0 p-4 pb-0 justify-content-end">
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="$set('showAddressEditModal', false)"></button>
                    </div>
                    <div class="modal-body p-4 pt-2">
                        <h4 class="fw-bold text-white mb-4" style="font-family: 'Syne', sans-serif;">
                            {{ $editingAddressIndex === -1 ? 'Nueva Dirección' : 'Editar Dirección' }}
                        </h4>

                        <div class="vstack gap-3 text-start">
                            <div>
                                <label class="text-white-50 small mb-1">Nombre/Etiqueta (Opcional)</label>
                                <input type="text" wire:model="addressEditData.label"
                                    class="form-control bg-white bg-opacity-10 border-white border-opacity-10 text-white rounded-3 shadow-none px-3"
                                    style="background-color: rgba(255,255,255,0.1) !important; color: white !important;"
                                    placeholder="Ej: Trabajo, Mi Casa">
                            </div>

                            @if(($addressEditData['type'] ?? 'lima') === 'lima')
                                <div>
                                    <label class="text-white-50 small mb-1">Dirección exacta</label>
                                    <input type="text" wire:model="addressEditData.address"
                                        class="form-control bg-white bg-opacity-10 border-white border-opacity-10 text-white rounded-3 shadow-none px-3"
                                        style="background-color: rgba(255,255,255,0.1) !important; color: white !important;">
                                </div>
                                <div>
                                    <label class="text-white-50 small mb-1">Distrito</label>
                                    <select wire:model="addressEditData.district"
                                        class="form-select bg-white bg-opacity-10 border-white border-opacity-10 text-white rounded-3 shadow-none px-3 py-2"
                                        style="background-color: rgba(255,255,255,0.1) !important; color: white !important;">
                                        <option value="" class="text-dark">Selecciona distrito</option>
                                        @foreach($limaZones as $zone => $data)
                                            @foreach($data['districts'] as $dist)
                                                <option value="{{ $dist }}" class="text-dark">{{ $dist }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div>
                                    <label class="text-white-50 small mb-1">Ciudad/Provincia</label>
                                    <input type="text" wire:model="addressEditData.city"
                                        class="form-control bg-white bg-opacity-10 border-white border-opacity-10 text-white rounded-3 shadow-none px-3"
                                        style="background-color: rgba(255,255,255,0.1) !important; color: white !important;">
                                </div>
                                <div>
                                    <label class="text-white-50 small mb-1">Agencia de Envío</label>
                                    <select wire:model="addressEditData.agency"
                                        class="form-select bg-white bg-opacity-10 border-white border-opacity-10 text-white rounded-3 shadow-none px-3 py-2"
                                        style="background-color: rgba(255,255,255,0.1) !important; color: white !important;">
                                        <option value="" class="text-dark">Selecciona agencia</option>
                                        @foreach($shippingAgencies as $name => $cost)
                                            <option value="{{ $name }}" class="text-dark">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-top border-white border-opacity-10 d-flex gap-2">
                            <button class="btn btn-outline-light border-opacity-25 w-100 rounded-pill py-2"
                                wire:click="$set('showAddressEditModal', false)">Cancelar</button>
                            <button class="btn btn-danger w-100 rounded-pill py-2 fw-bold" wire:click="saveEditedAddress"
                                style="background-color: #BE3C3B; border-color: #BE3C3B;">
                                {{ $editingAddressIndex === -1 ? 'Agregar' : 'Guardar' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <style>
        .hover-bg-white:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            transform: translateY(-2px);
        }
    </style>


    {{-- 6. MOBILE BOTTOM NAVIGATION --}}
    <div class="fixed-bottom bg-red d-flex justify-content-around align-items-center py-2 shadow-lg border-top border-white border-opacity-10 d-md-none"
        style="z-index: 1030; height: 65px; background-color: #BE3C3B !important;">
        <button class="btn border-0 text-white d-flex flex-column align-items-center" wire:click="resetToHome">
            <i class="fas fa-th-large fs-4 mb-1"></i>
            <span style="font-size: 0.65rem;">Inicio</span>
        </button>

        <button class="btn border-0 text-white d-flex flex-column align-items-center position-relative"
            wire:click="toggleRepairSummary">
            <i class="fas fa-box fs-4 mb-1"></i>
            <span style="font-size: 0.65rem;">Mi Reparación</span>
            @if(count($repairList) > 0)
                <span
                    class="position-absolute top-0 start-50 translate-middle-x mt-1 ms-3 badge rounded-pill bg-white text-danger border border-danger"
                    style="font-size: 0.6rem;">
                    {{ array_sum(array_column($repairList, 'qty')) }}
                </span>
            @endif
        </button>

        <button class="btn border-0 text-white d-flex flex-column align-items-center opacity-75" @auth
        wire:click="toggleOrders" @else wire:click="$set('showLoginModal', true)" @endauth>
            @auth
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ auth()->user()->profile_photo_path }}" alt="User"
                        class="rounded-circle border border-white border-opacity-50 mb-1"
                        style="width: 26px; height: 26px; object-fit: cover;">
                @else
                    <i class="fas fa-user-circle fs-4 mb-1"></i>
                @endif
            @else
                <i class="fas fa-user fs-4 mb-1"></i>
            @endauth
            <span style="font-size: 0.65rem;">{{ Auth::check() ? 'Cuenta' : 'Ingresar' }}</span>
        </button>
    </div>
    <script>
        function zettaBotAnimation(initialProviders) {
            let providersObj = {};
            initialProviders.forEach(p => {
                providersObj[p.id] = p;
            });

            return {
                timerCount: '9:00',
                countdown: 540,
                progress: 0,
                statusLabel: 'Iniciando consulta...',
                zbotStatus: 'Consultando proveedores...',
                headerStatus: 'Consultando disponibilidad...',
                priceSummaryVisible: false,
                btnPagarVisible: false,
                providers: providersObj,
                timerInterval: null,
                updateFromLivewire(detail) {
                    if (!detail || !detail.providers) return;

                    detail.providers.forEach(p => {
                        if (this.providers[p.id]) {
                            this.providers[p.id].state = p.state;
                            this.providers[p.id].result = p.result;
                            // Nota: zbot_price (costo del proveedor) NO se expone aquí al cliente
                        }
                    });

                    this.calculateProgress();
                },
                calculateProgress() {
                    const keys = Object.keys(this.providers);
                    if (keys.length === 0) return;

                    const processed = keys.filter(k => this.providers[k].state === 'confirmed' || this.providers[k].state === 'denied');
                    const baseProgress = (processed.length / keys.length) * 100;

                    this.progress = Math.max(this.progress, baseProgress);

                    // We show the summary ONLY when all ZettaBot dependent providers are processed
                    const zbotKeys = keys.filter(k => this.providers[k].depends_on_zbot);
                    const allZbotProcessed = zbotKeys.every(k => this.providers[k].state === 'confirmed' || this.providers[k].state === 'denied');

                    if (allZbotProcessed && keys.length > 0) {
                        this.priceSummaryVisible = true;
                        this.headerStatus = 'Respuestas de proveedores recibidas';
                        this.zbotStatus = '¡Disponibilidad confirmada!';
                        this.statusLabel = 'Consulta finalizada con éxito';
                    }

                    if (processed.length === keys.length && keys.length > 0) {
                        this.finishSequence();
                    } else if (processed.length > 0) {
                        if (this.priceSummaryVisible) {
                            this.headerStatus = 'Respuestas de proveedores recibidas';
                            this.zbotStatus = '¡Disponibilidad confirmada!';
                            this.statusLabel = 'Consulta finalizada con éxito';
                        } else {
                            this.zbotStatus = processed.length + ' respuesta' + (processed.length > 1 ? 's' : '') + ' recibida' + (processed.length > 1 ? 's' : '') + '...';
                        }
                    }
                },
                startSequence() {
                    this.reset();
                    this.calculateProgress(); // Check status immediately
                    this.startTimer();
                    this.statusLabel = 'Consultando disponibilidad via WhatsApp...';
                },
                finishSequence() {
                    this.progress = 100;
                    this.statusLabel = 'Consulta finalizada con éxito';
                    this.zbotStatus = '¡Todo listo! Revisa el resumen';
                    this.headerStatus = 'Disponibilidad confirmada';
                    this.priceSummaryVisible = true;
                    clearInterval(this.timerInterval);
                },
                updateProvider(id, state, result, progressValue = null, label = null) {
                    if (this.providers[id]) {
                        this.providers[id].state = state;
                        this.providers[id].result = result;
                    }
                    if (progressValue) this.progress = progressValue;
                    if (label) this.statusLabel = label;
                },
                startTimer() {
                    this.timerInterval = setInterval(() => {
                        this.countdown--;
                        const m = Math.floor(this.countdown / 60);
                        const s = this.countdown % 60;
                        this.timerCount = m + ':' + String(s).padStart(2, '0');
                        if (this.countdown <= 0) clearInterval(this.timerInterval);
                    }, 1000);
                },
                reset() {
                    this.countdown = 540;
                    this.progress = 0;
                    this.priceSummaryVisible = false;
                    Object.keys(this.providers).forEach(key => {
                        const p = this.providers[key];
                        if (p.depends_on_zbot === false) {
                            // Keep its state and results as pre-confirmed
                        } else {
                            p.state = 'waiting';
                            p.result = 'Esperando';
                        }
                    });
                }
            }
        }
        window.addEventListener('open-url', event => {
            window.open(event.detail.url, '_blank');
        });
    </script>
    <style>
        .hover-scale-sm:hover {
            transform: scale(1.05);
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #BE3C3B;
        }
    </style>
    {{-- INCIDENT MODAL (Moved to end for top-level rendering) --}}
    @if($showIncidentModal)
        <div class="modal fade show d-block"
            style="background: rgba(0,0,0,0.85); backdrop-filter: blur(10px); z-index: 10000; position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 450px; z-index: 10001;">
                <div class="modal-content border-0 shadow-lg text-white"
                    style="border-radius: 20px; background-color: #1a1e2e; border: 1px solid rgba(255,255,255,0.05);">
                    <div class="p-2 d-flex justify-content-center">
                        <div style="width: 40px; height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px;"></div>
                    </div>
                    <div class="modal-body p-4 pt-2">
                        <div class="mb-4">
                            <h3 class="fw-bold mb-1" style="font-family: 'Syne', sans-serif;">¿Qué pasó con tu pedido?</h3>
                            <p class="text-white text-opacity-50 small">Pedido #{{ $selectedOrderIdForIncident }} · Lo
                                resolvemos en el día</p>
                        </div>

                        <div class="d-grid gap-3">
                            <button wire:click="sendIncident('wrong_product')"
                                class="btn p-3 text-start hover-bg-white transition-all rounded-4 d-flex align-items-center gap-3 border-0"
                                style="background-color: rgba(255,255,255,0.05);">
                                <div class="fs-2" style="filter: drop-shadow(0 0 8px rgba(255,107,43,0.2));">📦</div>
                                <div>
                                    <div class="fw-bold text-white mb-0" style="font-size: 0.95rem;">No era el producto que
                                        pedí</div>
                                    <div class="text-white text-opacity-50" style="font-size: 0.8rem;">Llegó un producto
                                        diferente al solicitado</div>
                                </div>
                            </button>

                            <button wire:click="sendIncident('wrong_quantity')"
                                class="btn p-3 text-start hover-bg-white transition-all rounded-4 d-flex align-items-center gap-3 border-0"
                                style="background-color: rgba(255,255,255,0.05);">
                                <div class="fs-2" style="filter: drop-shadow(0 0 8px rgba(59,130,246,0.2));">🔢</div>
                                <div>
                                    <div class="fw-bold text-white mb-0" style="font-size: 0.95rem;">Me llegó menos cantidad
                                    </div>
                                    <div class="text-white text-opacity-50" style="font-size: 0.8rem;">La cantidad recibida
                                        no coincide con el pedido</div>
                                </div>
                            </button>

                            <button wire:click="sendIncident('not_received')"
                                class="btn p-3 text-start hover-bg-white transition-all rounded-4 d-flex align-items-center gap-3 border-0"
                                style="background-color: rgba(255,255,255,0.05);">
                                <div class="fs-2" style="filter: drop-shadow(0 0 8px rgba(255,59,92,0.2));">🚫</div>
                                <div>
                                    <div class="fw-bold text-white mb-0" style="font-size: 0.95rem;">No llegó mi pedido
                                    </div>
                                    <div class="text-white text-opacity-50" style="font-size: 0.8rem;">El motorizado no se
                                        presentó o llegó al lugar incorrecto</div>
                                </div>
                            </button>

                            <button wire:click="sendIncident('other')"
                                class="btn p-3 text-start hover-bg-white transition-all rounded-4 d-flex align-items-center gap-3 border-0"
                                style="background-color: rgba(255,255,255,0.05);">
                                <div class="fs-2" style="filter: drop-shadow(0 0 8px rgba(255,255,255,0.1));">💬</div>
                                <div>
                                    <div class="fw-bold text-white mb-0" style="font-size: 0.95rem;">Otro problema</div>
                                    <div class="text-white text-opacity-50" style="font-size: 0.8rem;">Describir situación
                                        al equipo de soporte</div>
                                </div>
                            </button>
                        </div>

                        <button type="button" class="btn btn-outline-light border-0 w-100 mt-4 rounded-pill py-3 fw-bold"
                            style="background: rgba(255,255,255,0.05);" wire:click="$set('showIncidentModal', false)">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- USER ORDER DETAIL MODAL --}}
    @if($showUserOrderDetailModal && $userSelectedOrder)
        <div class="modal fade show d-block"
            style="background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 2500; position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;" id="print-area-invoice">
                    <div class="modal-header bg-light border-0 py-3 px-4" style="border-radius: 20px 20px 0 0;">
                        <div>
                            <h5 class="modal-title fw-bold text-dark">Detalle del Pedido #{{ $userSelectedOrder->id }}</h5>
                            <div class="small text-muted">{{ $userSelectedOrder->created_at->format('d M, Y - H:i') }}</div>
                        </div>
                        <button type="button" class="btn-close shadow-none" wire:click="closeUserOrderDetail"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-3"><i
                                        class="fas fa-info-circle me-2 text-primary"></i>Resumen</h6>
                                <div class="bg-light p-3 rounded-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Estado:</span>
                                        <span
                                            class="badge bg-primary rounded-pill">{{ ucfirst($userSelectedOrder->status) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total:</span>
                                        <span class="fw-bold text-dark">S/
                                            {{ number_format($userSelectedOrder->total, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Método de pago:</span>
                                        <span
                                            class="text-dark">{{ $userSelectedOrder->metodo_pago === 'Culqi' ? 'Tarjeta (Crédito/Débito)' : ($userSelectedOrder->metodo_pago ?? 'No especificado') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-truck me-2 text-primary"></i>Entrega
                                </h6>
                                <div class="bg-light p-3 rounded-3">
                                    <div class="text-dark fw-bold mb-1">{{ $userSelectedOrder->tipo_envio ?? 'Entrega' }}
                                    </div>
                                    <div class="small text-muted mb-1"><strong>Dirección:</strong>
                                        {{ $userSelectedOrder->direccion ?? 'No especificada' }}
                                    </div>
                                    <div class="small text-muted"><strong>Distrito/Ciudad:</strong>
                                        {{ $userSelectedOrder->distrito ?? 'No especificado' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-box-open me-2 text-primary"></i>Productos</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="small text-muted fw-bold">Producto</th>
                                        <th class="small text-muted fw-bold text-center">Cant.</th>
                                        <th class="small text-muted fw-bold text-end">Precio</th>
                                        <th class="small text-muted fw-bold text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userSelectedOrder->items as $item)
                                        <tr class="border-bottom border-light">
                                            <td>
                                                <div class="fw-bold text-dark">
                                                    {{ $item->product ? $item->product->name : 'Producto #' . $item->product_id }}
                                                </div>
                                                <div class="extra-small text-muted d-flex align-items-center gap-1 flex-wrap">
                                                    <span>OEM:
                                                        {{ $item->product ? ($item->product->oem_code ?? $item->product->supplier_code) : 'N/A' }}</span>
                                                    @if($item->product && $item->product->oversize)
                                                        <span class="badge rounded-pill px-2 py-0"
                                                            style="background:#eff6ff;color:#1d4ed8;font-size:9px;font-weight:700;border:1px solid #bfdbfe;">{{ $item->product->getOversizeLabel() }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center text-dark">{{ $item->cantidad }}</td>
                                            <td class="text-end text-dark">S/ {{ number_format($item->precio_unitario, 2) }}
                                            </td>
                                            <td class="text-end fw-bold text-dark">S/ {{ number_format($item->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end text-muted pt-3" style="font-size: 0.9rem;">Subtotal
                                            productos:</td>
                                        <td class="text-end text-dark pt-3" style="font-size: 0.9rem; font-weight: 500;">S/
                                            {{ number_format($userSelectedOrder->subtotal, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end text-muted py-1" style="font-size: 0.9rem;">Comisión
                                            de servicio:</td>
                                        <td class="text-end py-1" style="font-size: 0.9rem; font-weight: 500;">
                                            <span class="text-muted"
                                                style="text-decoration: line-through; font-size: 0.8rem; margin-right: 5px;">S/
                                                15.00</span>
                                            <span class="text-success fw-bold">S/ 0.00</span>
                                            <span class="d-block text-success"
                                                style="font-size: 0.7rem; font-weight: 400; margin-top:-2px;">¡No cobramos
                                                comisión por ahora!</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end text-muted py-1" style="font-size: 0.9rem;">Costo de
                                            envío:</td>
                                        <td class="text-end text-dark py-1" style="font-size: 0.9rem; font-weight: 500;">S/
                                            {{ number_format($userSelectedOrder->costo_envio, 2) }}
                                        </td>
                                    </tr>
                                    @php
                                        $orderTotal = $userSelectedOrder->total;
                                        $subtotalGravado = round($orderTotal / 1.18, 2);
                                        $igvAmount = round($orderTotal - $subtotalGravado, 2);
                                    @endphp
                                    <tr>
                                        <td colspan="3" class="text-end text-muted py-1" style="font-size: 0.85rem;">Op.
                                            Gravada (Base):</td>
                                        <td class="text-end text-dark py-1" style="font-size: 0.85rem;">S/
                                            {{ number_format($subtotalGravado, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end text-muted py-1" style="font-size: 0.85rem;">IGV
                                            (18%):</td>
                                        <td class="text-end text-dark py-1" style="font-size: 0.85rem;">S/
                                            {{ number_format($igvAmount, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold pt-2 border-top border-light">Total a pagar:
                                        </td>
                                        <td class="text-end fw-bold text-primary pt-2 fs-5 border-top border-light">S/
                                            {{ number_format($userSelectedOrder->total, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-between align-items-center">
                        <div>
                            @if(!empty($userSelectedOrder->invoice_url) && $userSelectedOrder->invoice_url !== '#')
                                <a href="{{ $userSelectedOrder->invoice_url }}" target="_blank"
                                    class="btn btn-success rounded-pill px-4 fw-bold">
                                    <i class="fas fa-external-link-alt me-2"></i>Ver PDF Oficial SUNAT (Con QR)
                                </a>
                            @else
                                <button type="button" class="btn btn-outline-primary rounded-pill px-4 fw-bold"
                                    wire:click="downloadInvoice({{ $userSelectedOrder->id }})"
                                    @if($userSelectedOrder->status === 'cancelado') disabled
                                    style="opacity: 0.5; cursor: not-allowed;" @endif>
                                    <i class="fas fa-spinner fa-spin me-2" wire:loading wire:target="downloadInvoice"></i>
                                    <i class="fas fa-file-pdf me-2" wire:loading.remove wire:target="downloadInvoice"></i>
                                    <span>Generar Comprobante SUNAT (PDF)</span>
                                </button>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4"
                                wire:click="closeUserOrderDetail">Cerrar</button>
                            <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold"
                                wire:click="reportIncident({{ $userSelectedOrder->id }})"
                                @if($userSelectedOrder->status === 'cancelado') disabled
                                style="opacity: 0.5; cursor: not-allowed;" @endif>
                                <i class="fas fa-exclamation-triangle me-2"></i>Reportar problema
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print-specific styles so only the invoice modal prints beautifully as a real physical invoice sheet -->
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #print-area-invoice,
                #print-area-invoice * {
                    visibility: visible;
                }

                #print-area-invoice {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    background: #fff !important;
                    color: #000 !important;
                    padding: 20px !important;
                }

                .modal-footer,
                .btn-close {
                    display: none !important;
                }

                .modal-dialog {
                    max-width: 100% !important;
                    margin: 0 !important;
                }

                .modal-content {
                    border: none !important;
                    box-shadow: none !important;
                }

                .bg-light {
                    background-color: #f8f9fa !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }
        </style>

        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('print-invoice', () => {
                    setTimeout(() => {
                        window.print();
                    }, 500);
                });
            });
        </script>
    @endif


</div>

</div>