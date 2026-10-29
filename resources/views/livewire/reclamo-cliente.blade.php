<div>
    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL BOTTOM SHEET / POPUP (Bootstrap 5 Dark Mode)           --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    @if ($showModal)
    <div
        class="modal fade show d-block reclamo-modal-overlay"
        tabindex="-1"
        style="background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); z-index: 10500; position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow-x: hidden;"
        wire:keydown.escape="cancel"
    >
        <div class="modal-dialog reclamo-modal-dialog">
            <div class="modal-content border-0 shadow-2xl text-white reclamo-modal-content"
                style="background: #131826; border: 1px solid rgba(255,255,255,0.08);">

                {{-- Drag Handle (Mobile aesthetic) --}}
                <div class="d-flex justify-content-center pt-2 pb-1">
                    <div style="width: 38px; height: 4px; background: rgba(255,255,255,0.25); border-radius: 2px;"></div>
                </div>

                {{-- ══════════════════════════════════════════════════════ --}}
                {{-- MODO SOLO LECTURA (incidencia existente)              --}}
                {{-- ══════════════════════════════════════════════════════ --}}
                @if ($showReadOnly && $incidenciaData)
                    @include('livewire.reclamo-cliente._readonly')

                {{-- ══════════════════════════════════════════════════════ --}}
                {{-- PANTALLA DE ÉXITO                                     --}}
                {{-- ══════════════════════════════════════════════════════ --}}
                @elseif ($showSuccess)
                    @include('livewire.reclamo-cliente._success')

                {{-- ══════════════════════════════════════════════════════ --}}
                {{-- FLUJO 3 PASOS                                         --}}
                {{-- ══════════════════════════════════════════════════════ --}}
                @else
                    {{-- Cabecera --}}
                    <div class="reclamo-modal-header border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="fw-bold mb-1 text-white reclamo-title" style="font-family: 'Syne', sans-serif;">
                                    ¿Qué pasó con tu pedido?
                                </h4>
                                @if ($pedidoData)
                                    <p class="text-white text-opacity-50 small mb-0">
                                        Pedido #{{ $pedidoData['id'] }} · Lo resolvemos en el día
                                    </p>
                                @endif
                            </div>
                            <button type="button" wire:click="cancel" class="btn-close btn-close-white shadow-none" style="opacity: 0.6;"></button>
                        </div>

                        {{-- Barra de progreso de 3 pasos --}}
                        <div class="d-flex gap-2 mt-2">
                            <div class="flex-grow-1" style="height: 3px; border-radius: 2px; background: {{ $step >= 1 ? '#ea580c' : 'rgba(255,255,255,0.1)' }}; transition: background 0.3s;"></div>
                            <div class="flex-grow-1" style="height: 3px; border-radius: 2px; background: {{ $step >= 2 ? '#ea580c' : 'rgba(255,255,255,0.1)' }}; transition: background 0.3s;"></div>
                            <div class="flex-grow-1" style="height: 3px; border-radius: 2px; background: {{ $step >= 3 ? '#ea580c' : 'rgba(255,255,255,0.1)' }}; transition: background 0.3s;"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-white text-opacity-40" style="font-size: 0.72rem;">
                                Paso {{ $tipo === 'otro' ? ($step === 1 ? 1 : 2) : $step }} de {{ $tipo === 'otro' ? 2 : 3 }}
                            </span>
                        </div>
                    </div>

                    {{-- Contenido scrollable --}}
                    <div class="reclamo-modal-body">

                        {{-- Error general --}}
                        @if ($generalError)
                            <div class="alert alert-danger py-2 px-3 rounded-3 small mb-2" style="background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.3); color: #fca5a5; font-size: 0.8rem;">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $generalError }}
                            </div>
                        @endif

                        {{-- ── PASO 1: Tipo de problema ──────────────────── --}}
                        @if ($step === 1)
                            <div class="d-grid gap-2">
                                @forelse ($eligibleTipos as $key => $tipoItem)
                                    <button
                                        type="button"
                                        wire:click="selectTipo('{{ $key }}')"
                                        class="btn text-start p-3 rounded-4 d-flex align-items-center gap-3 border-0 transition-all text-white reclamo-option-card"
                                        style="background: rgba(255,255,255,0.05); min-height: 56px;"
                                    >
                                        <span style="font-size: 1.5rem; line-height: 1;">{{ $tipoItem['icon'] }}</span>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold" style="font-size: 0.92rem;">{{ $tipoItem['label'] }}</div>
                                            <div class="text-white text-opacity-50" style="font-size: 0.78rem;">{{ $tipoItem['desc'] }}</div>
                                        </div>
                                        <i class="fas fa-chevron-right text-white text-opacity-30" style="font-size: 0.85rem;"></i>
                                    </button>
                                @empty
                                    <p class="text-white text-opacity-50 text-center py-4 mb-0">No hay opciones disponibles para este pedido en este momento.</p>
                                @endforelse
                            </div>
                        @endif

                        {{-- ── PASO 2: Submotivo ─────────────────────────── --}}
                        @if ($step === 2)
                            <div class="d-grid gap-2">
                                @forelse ($submotivoOptions as $sub)
                                    <button
                                        type="button"
                                        wire:click="selectSubmotivo('{{ $sub['key'] }}')"
                                        class="btn text-start p-3 rounded-4 d-flex align-items-center gap-3 border-0 transition-all text-white reclamo-option-card"
                                        style="background: rgba(255,255,255,0.05); min-height: 56px;"
                                    >
                                        <div class="flex-grow-1">
                                            <div class="fw-bold" style="font-size: 0.92rem;">{{ $sub['label'] }}</div>
                                            <div class="text-white text-opacity-50" style="font-size: 0.78rem;">{{ $sub['desc'] }}</div>
                                        </div>
                                        <i class="fas fa-chevron-right text-white text-opacity-30" style="font-size: 0.85rem;"></i>
                                    </button>
                                @empty
                                    <p class="text-white text-opacity-50 text-center py-4 mb-0">No hay submotivos disponibles.</p>
                                @endforelse
                            </div>
                        @endif

                        {{-- ── PASO 3: Evidencia + OTP ───────────────────── --}}
                        @if ($step === 3)
                            <div class="d-flex flex-column gap-2">
                                {{-- Instrucción contextual compacta --}}
                                <div class="p-2 px-3 rounded-3" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.25); color: #93c5fd; font-size: 0.8rem;">
                                    @switch($submotivo ?: $tipo)
                                        @case('no_era_producto')    📸 Adjunta foto del producto recibido y de su etiqueta. @break
                                        @case('menos_cantidad')     📸 Adjunta foto de todo lo que llegó en el paquete. @break
                                        @case('no_compatible')      📸 Adjunta foto de la pieza y de la placa de tu vehículo. @break
                                        @case('golpeado_abierto')   📸 Adjunta foto de la caja cerrada y del daño visible. @break
                                        @case('falla_fabrica')      📸 Adjunta foto clara del defecto de fabricación. @break
                                        @case('caja_ajena')         📸 Adjunta foto del sticker QR de la caja que recibiste. @break
                                        @case('no_llego_nada')      ℹ️ Sin foto requerida. Describe brevemente lo ocurrido en el comentario. @break
                                        @case('otro')               ℹ️ Describe detalladamente el problema. Puedes adjuntar una foto si ayuda. @break
                                        @default                     📸 Adjunta una foto como evidencia de tu reclamo. @break
                                    @endswitch
                                </div>

                                {{-- Foto (compacto) --}}
                                @if ($submotivo !== 'no_llego_nada')
                                    <div>
                                        <label class="form-label text-white text-opacity-80 small fw-bold mb-1" style="font-size: 0.8rem;">
                                            Foto de evidencia {{ $tipo !== 'otro' ? '*' : '(opcional)' }}
                                        </label>

                                        @if ($foto)
                                            <div class="position-relative mb-2">
                                                <img src="{{ $foto->temporaryUrl() }}" class="img-fluid rounded-3 w-100" style="max-height: 120px; object-fit: cover; border: 1px solid rgba(255,255,255,0.15);" alt="Evidencia">
                                                <button type="button" wire:click="$set('foto', null)" class="btn btn-dark btn-sm rounded-circle position-absolute" style="top: 6px; right: 6px; width: 24px; height: 24px; padding: 0; font-size: 0.7rem; background: rgba(0,0,0,0.7);">
                                                    ✕
                                                </button>
                                            </div>
                                        @endif

                                        <label class="w-100 p-2 px-3 rounded-3 text-center d-flex align-items-center justify-content-center gap-2"
                                            style="cursor: pointer; background: rgba(255,255,255,0.04); border: 1.5px dashed rgba(255,255,255,0.2); min-height: 48px;">
                                            <i class="fas fa-camera text-warning fs-5"></i>
                                            <div class="text-start">
                                                <span class="text-white small fw-semibold d-block" style="font-size: 0.82rem; line-height: 1.1;">{{ $foto ? 'Cambiar foto' : 'Tomar foto o subir de galería' }}</span>
                                                <span class="text-white text-opacity-40" style="font-size: 0.68rem;">JPG, PNG, WEBP · máx. 5 MB</span>
                                            </div>
                                            <input type="file" wire:model="foto" accept="image/jpeg,image/png,image/webp" capture="environment" style="display: none;">
                                        </label>
                                        @error('foto')
                                            <div class="text-danger small mt-1" style="font-size: 0.72rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                {{-- Comentario (compacto rows="2") --}}
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label text-white text-opacity-80 small fw-bold mb-0" style="font-size: 0.8rem;">
                                            Comentario {{ $tipo === 'otro' ? '*' : '(opcional)' }}
                                        </label>
                                        <span class="text-white text-opacity-40" style="font-size: 0.68rem;">
                                            {{ strlen($descripcion) }}/500
                                        </span>
                                    </div>
                                    <textarea
                                        wire:model.live="descripcion"
                                        maxlength="500"
                                        rows="2"
                                        placeholder="{{ $tipo === 'otro' ? 'Describe el problema con detalle...' : 'Información adicional para agilizar tu caso (opcional)' }}"
                                        class="form-control text-white border-0 py-2 px-3"
                                        style="background: rgba(255,255,255,0.06); border-radius: 10px; font-size: 0.84rem; min-height: 52px; max-height: 85px;"
                                    ></textarea>
                                </div>

                                {{-- Verificación WhatsApp (OTP compacto) --}}
                                <div class="p-2 px-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="text-white fw-bold mb-0" style="font-size: 0.8rem;">
                                            Código de confirmación <span class="text-warning">*</span>
                                        </label>
                                        <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30" style="font-size: 0.62rem; padding: 0.25em 0.5em;">WhatsApp</span>
                                    </div>
                                    <p class="text-white text-opacity-50 mb-2" style="font-size: 0.72rem;">
                                        Enviaremos el código al +{{ $telefonoMascarado }}
                                    </p>

                                    <div class="d-flex gap-2" x-data="otpCountdownHandler()" x-init="init()">
                                        <input
                                            type="text"
                                            wire:model.live="otp"
                                            inputmode="numeric"
                                            maxlength="4"
                                            placeholder="••••"
                                            class="form-control text-center text-white fw-bold border-0 shadow-none py-1"
                                            style="background: rgba(255,255,255,0.08); border-radius: 10px; font-size: 1.1rem; letter-spacing: 6px; max-width: 110px; height: 38px; {{ !$otpEnviado ? 'opacity: 0.5;' : '' }}"
                                            @if(!$otpEnviado) readonly @endif
                                        >
                                        <button
                                            type="button"
                                            wire:click="enviarOtp"
                                            wire:loading.attr="disabled"
                                            class="btn btn-warning fw-bold flex-grow-1 shadow-none py-1"
                                            style="font-size: 0.8rem; border-radius: 10px !important; height: 38px;"
                                            x-bind:disabled="countdown > 0"
                                            x-text="countdown > 0 ? 'Reenviar en ' + countdown + 's' : 'Enviar código'"
                                        >
                                            Enviar código
                                        </button>
                                    </div>

                                    @if ($otpError)
                                        <div class="text-danger small mt-1" style="font-size: 0.74rem;">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $otpError }}
                                        </div>
                                    @endif

                                    @if ($otpEnviado && !$otpError)
                                        <div class="text-success small mt-1" style="font-size: 0.74rem;">
                                            <i class="fas fa-check-circle me-1"></i> Código enviado por WhatsApp. Revisa tus mensajes.
                                        </div>
                                    @endif
                                </div>

                                {{-- Honeypot oculto --}}
                                <div style="display: none;" aria-hidden="true">
                                    <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
                                </div>
                            </div>
                        @endif

                    </div>{{-- /scrollable --}}

                    {{-- Barra de acciones fija (Footer) --}}
                    <div class="reclamo-modal-footer border-top d-flex gap-2" style="border-color: rgba(255,255,255,0.08) !important;">
                        @if ($step > 1)
                            <button type="button" wire:click="back" class="btn btn-outline-light border-0 rounded-pill py-2 px-3 fw-semibold" style="background: rgba(255,255,255,0.06); font-size: 0.85rem; height: 42px;">
                                ←
                            </button>
                        @endif

                        @if ($step === 3)
                            <button
                                type="button"
                                wire:click="submit"
                                wire:loading.attr="disabled"
                                @if (strlen($otp) < 4) disabled @endif
                                class="btn btn-danger fw-bold rounded-pill py-2 px-3 flex-grow-1 shadow-none"
                                style="font-size: 0.88rem; background: #dc2626; height: 42px;"
                            >
                                <span wire:loading.remove wire:target="submit">Enviar reclamo</span>
                                <span wire:loading wire:target="submit"><i class="fas fa-spinner fa-spin me-1"></i> Enviando…</span>
                            </button>
                        @endif

                        <button type="button" wire:click="cancel" class="btn btn-outline-light border-0 rounded-pill py-2 px-3 {{ $step === 3 ? '' : 'flex-grow-1' }}" style="background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); font-size: 0.85rem; height: 42px;">
                            Cancelar
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </div>
    @endif

    <style>
        /* Desktop base */
        .reclamo-modal-dialog {
            max-width: 480px;
            margin: 1.75rem auto;
        }
        .reclamo-modal-content {
            border-radius: 22px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .reclamo-modal-header {
            padding: 1rem 1.25rem 0.75rem;
            flex-shrink: 0;
        }
        .reclamo-modal-body {
            padding: 0.85rem 1.25rem;
            max-height: 65vh;
            overflow-y: auto;
            flex-grow: 1;
        }
        .reclamo-modal-footer {
            padding: 0.75rem 1.25rem 1rem;
            flex-shrink: 0;
            background: #131826;
        }
        .reclamo-title {
            font-size: 1.25rem;
        }

        /* Mobile specific bottom sheet */
        @media (max-width: 576px) {
            .reclamo-modal-overlay {
                display: flex !important;
                align-items: flex-end !important;
                padding: 0 !important;
            }
            .reclamo-modal-dialog {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
            }
            .reclamo-modal-content {
                border-radius: 20px 20px 0 0 !important;
                max-height: 90vh !important;
                max-height: 90dvh !important;
                border-bottom: none !important;
            }
            .reclamo-modal-header {
                padding: 0.65rem 1rem 0.5rem !important;
            }
            .reclamo-title {
                font-size: 1.1rem !important;
            }
            .reclamo-modal-body {
                padding: 0.6rem 1rem !important;
                max-height: calc(90dvh - 145px) !important;
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch;
            }
            .reclamo-modal-footer {
                padding: 0.6rem 1rem calc(0.6rem + env(safe-area-inset-bottom, 0px)) !important;
                position: sticky !important;
                bottom: 0 !important;
                z-index: 10 !important;
            }
        }

        .reclamo-option-card:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(234, 88, 12, 0.4) !important;
            transform: translateY(-1px);
        }
    </style>

    <script>
        function otpCountdownHandler() {
            return {
                countdown: 0,
                init() {
                    window.addEventListener('otp-countdown-start', () => {
                        this.countdown = 60;
                        const interval = setInterval(() => {
                            this.countdown--;
                            if (this.countdown <= 0) {
                                this.countdown = 0;
                                clearInterval(interval);
                            }
                        }, 1000);
                    });
                }
            }
        }
    </script>
</div>
