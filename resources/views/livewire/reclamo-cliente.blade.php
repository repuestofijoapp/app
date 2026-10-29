<div>
    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL BOTTOM SHEET / POPUP (Bootstrap 5 Dark Mode)           --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    @if ($showModal)
    <div
        class="modal fade show d-block"
        tabindex="-1"
        style="background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); z-index: 10500; position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow-y: auto;"
        wire:keydown.escape="cancel"
    >
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px; margin: 1.75rem auto;">
            <div class="modal-content border-0 shadow-2xl text-white"
                style="border-radius: 22px; background: #131826; border: 1px solid rgba(255,255,255,0.08); overflow: hidden;">

                {{-- Drag Handle (Mobile aesthetic) --}}
                <div class="d-flex justify-content-center pt-3 pb-1">
                    <div style="width: 42px; height: 4px; background: rgba(255,255,255,0.25); border-radius: 2px;"></div>
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
                    <div class="p-4 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="fw-bold mb-1 text-white" style="font-family: 'Syne', sans-serif; font-size: 1.25rem;">
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
                        <div class="d-flex gap-2 mt-3">
                            <div class="flex-grow-1" style="height: 4px; border-radius: 2px; background: {{ $step >= 1 ? '#ea580c' : 'rgba(255,255,255,0.1)' }}; transition: background 0.3s;"></div>
                            <div class="flex-grow-1" style="height: 4px; border-radius: 2px; background: {{ $step >= 2 ? '#ea580c' : 'rgba(255,255,255,0.1)' }}; transition: background 0.3s;"></div>
                            <div class="flex-grow-1" style="height: 4px; border-radius: 2px; background: {{ $step >= 3 ? '#ea580c' : 'rgba(255,255,255,0.1)' }}; transition: background 0.3s;"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-white text-opacity-40" style="font-size: 0.72rem;">
                                Paso {{ $tipo === 'otro' ? ($step === 1 ? 1 : 2) : $step }} de {{ $tipo === 'otro' ? 2 : 3 }}
                            </span>
                        </div>
                    </div>

                    {{-- Contenido scrollable --}}
                    <div class="p-4 py-3" style="max-height: 65vh; overflow-y: auto;">

                        {{-- Error general --}}
                        @if ($generalError)
                            <div class="alert alert-danger py-2 px-3 rounded-3 small mb-3" style="background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.3); color: #fca5a5;">
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
                            <div class="d-flex flex-column gap-3">
                                {{-- Instrucción contextual --}}
                                <div class="p-3 rounded-3 small" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.25); color: #93c5fd;">
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

                                {{-- Foto --}}
                                @if ($submotivo !== 'no_llego_nada')
                                    <div>
                                        <label class="form-label text-white text-opacity-80 small fw-bold mb-1">
                                            Foto de evidencia {{ $tipo !== 'otro' ? '*' : '(opcional)' }}
                                        </label>

                                        @if ($foto)
                                            <div class="position-relative mb-2">
                                                <img src="{{ $foto->temporaryUrl() }}" class="img-fluid rounded-3 w-100" style="max-height: 160px; object-fit: cover; border: 1px solid rgba(255,255,255,0.15);" alt="Evidencia">
                                                <button type="button" wire:click="$set('foto', null)" class="btn btn-dark btn-sm rounded-circle position-absolute" style="top: 8px; right: 8px; width: 28px; height: 28px; padding: 0; background: rgba(0,0,0,0.7);">
                                                    ✕
                                                </button>
                                            </div>
                                        @endif

                                        <label class="w-100 p-3 rounded-3 text-center d-flex align-items-center justify-content-center gap-2"
                                            style="cursor: pointer; background: rgba(255,255,255,0.04); border: 1.5px dashed rgba(255,255,255,0.2);">
                                            <i class="fas fa-camera text-warning fs-5"></i>
                                            <div>
                                                <span class="text-white small fw-semibold">{{ $foto ? 'Cambiar foto' : 'Tomar foto o subir de galería' }}</span>
                                                <span class="text-white text-opacity-40 d-block" style="font-size: 0.72rem;">JPG, PNG, WEBP · máx. 5 MB</span>
                                            </div>
                                            <input type="file" wire:model="foto" accept="image/jpeg,image/png,image/webp" capture="environment" style="display: none;">
                                        </label>
                                        @error('foto')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                {{-- Comentario --}}
                                <div>
                                    <label class="form-label text-white text-opacity-80 small fw-bold mb-1">
                                        Comentario {{ $tipo === 'otro' ? '*' : '(opcional)' }}
                                    </label>
                                    <textarea
                                        wire:model.live="descripcion"
                                        maxlength="500"
                                        rows="3"
                                        placeholder="{{ $tipo === 'otro' ? 'Describe el problema con detalle...' : 'Información adicional para agilizar tu caso (opcional)' }}"
                                        class="form-control text-white border-0"
                                        style="background: rgba(255,255,255,0.06); border-radius: 12px; font-size: 0.88rem;"
                                    ></textarea>
                                    <div class="text-end text-white text-opacity-40 mt-1" style="font-size: 0.72rem;">
                                        {{ strlen($descripcion) }}/500
                                    </div>
                                </div>

                                {{-- Verificación WhatsApp (OTP) --}}
                                <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <label class="text-white fw-bold small mb-0">
                                            Código de confirmación <span class="text-warning">*</span>
                                        </label>
                                        <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 small" style="font-size: 0.65rem;">WhatsApp</span>
                                    </div>
                                    <p class="text-white text-opacity-50 mb-2" style="font-size: 0.75rem;">
                                        Enviaremos el código al +{{ $telefonoMascarado }}
                                    </p>

                                    <div class="d-flex gap-2" x-data="otpCountdownHandler()" x-init="init()">
                                        <input
                                            type="text"
                                            wire:model.live="otp"
                                            inputmode="numeric"
                                            maxlength="4"
                                            placeholder="••••"
                                            class="form-control text-center text-white fw-bold border-0 shadow-none"
                                            style="background: rgba(255,255,255,0.08); border-radius: 12px; font-size: 1.15rem; letter-spacing: 8px; max-width: 130px; {{ !$otpEnviado ? 'opacity: 0.5;' : '' }}"
                                            @if(!$otpEnviado) readonly @endif
                                        >
                                        <button
                                            type="button"
                                            wire:click="enviarOtp"
                                            wire:loading.attr="disabled"
                                            class="btn btn-warning fw-bold flex-grow-1 shadow-none rounded-3"
                                            style="font-size: 0.82rem; border-radius: 12px !important;"
                                            x-bind:disabled="countdown > 0"
                                            x-text="countdown > 0 ? 'Reenviar en ' + countdown + 's' : 'Enviar código'"
                                        >
                                            Enviar código
                                        </button>
                                    </div>

                                    @if ($otpError)
                                        <div class="text-danger small mt-2" style="font-size: 0.76rem;">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $otpError }}
                                        </div>
                                    @endif

                                    @if ($otpEnviado && !$otpError)
                                        <div class="text-success small mt-2" style="font-size: 0.76rem;">
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
                    <div class="p-3 px-4 border-top d-flex gap-2" style="border-color: rgba(255,255,255,0.08) !important;">
                        @if ($step > 1)
                            <button type="button" wire:click="back" class="btn btn-outline-light border-0 rounded-pill py-2 px-3 fw-semibold" style="background: rgba(255,255,255,0.06); font-size: 0.88rem;">
                                ← Atrás
                            </button>
                        @endif

                        @if ($step === 3)
                            <button
                                type="button"
                                wire:click="submit"
                                wire:loading.attr="disabled"
                                @if (strlen($otp) < 4) disabled @endif
                                class="btn btn-danger fw-bold rounded-pill py-2 px-4 flex-grow-1 shadow-none"
                                style="font-size: 0.9rem; background: #dc2626;"
                            >
                                <span wire:loading.remove wire:target="submit">Enviar reclamo</span>
                                <span wire:loading wire:target="submit"><i class="fas fa-spinner fa-spin me-1"></i> Enviando…</span>
                            </button>
                        @endif

                        <button type="button" wire:click="cancel" class="btn btn-outline-light border-0 rounded-pill py-2 px-3 {{ $step === 3 ? '' : 'flex-grow-1' }}" style="background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); font-size: 0.88rem;">
                            Cancelar
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </div>
    @endif

    <style>
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
