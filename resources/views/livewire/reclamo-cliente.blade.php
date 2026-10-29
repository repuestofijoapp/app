<div>
    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- BOTÓN TRIGGER — condicional según estado del pedido           --}}
    {{-- Usar: <livewire:reclamo-cliente :pedido-id="$order->id" />    --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}

    {{-- ── MODAL BOTTOM SHEET ──────────────────────────────────────── --}}
    @if ($showModal)
    <div
        x-data="reclamoSheet()"
        x-init="init()"
        class="fixed inset-0 z-[9999] flex items-end sm:items-center justify-center"
        @keydown.escape.window="$wire.cancel()"
    >
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="cancel"></div>

        {{-- Sheet --}}
        <div class="relative w-full sm:max-w-md bg-[#0f172a] rounded-t-2xl sm:rounded-2xl shadow-2xl flex flex-col max-h-[90dvh] overflow-hidden">

            {{-- ── DRAG HANDLE (mobile) --}}
            <div class="flex justify-center pt-3 pb-1 sm:hidden">
                <div class="w-10 h-1 rounded-full bg-white/20"></div>
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
                <div class="px-5 pt-4 pb-3 border-b border-white/10">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-white font-bold text-lg leading-tight">¿Qué pasó con tu pedido?</h2>
                            @if ($pedidoData)
                                <p class="text-gray-400 text-xs mt-0.5">Pedido #{{ $pedidoData['id'] }} · Lo resolvemos en el día</p>
                            @endif
                        </div>
                        <button wire:click="cancel" class="text-gray-400 hover:text-white mt-0.5 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    {{-- Indicador de paso --}}
                    @if (!$showSuccess && !$showReadOnly)
                        <div class="flex gap-1.5 mt-3">
                            @foreach ([1,2,3] as $s)
                                <div class="h-1 flex-1 rounded-full transition-colors duration-300 {{ $step >= $s ? 'bg-orange-500' : 'bg-white/10' }}"></div>
                            @endforeach
                        </div>
                        <p class="text-gray-500 text-[11px] mt-1">
                            Paso {{ $tipo === 'otro' ? ($step === 1 ? 1 : 2) : $step }} de {{ $tipo === 'otro' ? 2 : 3 }}
                        </p>
                    @endif
                </div>

                {{-- Contenido scrollable --}}
                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3">

                    {{-- Error general --}}
                    @if ($generalError)
                        <div class="bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3 text-red-400 text-sm">
                            {{ $generalError }}
                        </div>
                    @endif

                    {{-- ── PASO 1: Tipo de problema ──────────────────── --}}
                    @if ($step === 1)
                        @forelse ($eligibleTipos as $key => $tipo)
                            <button
                                wire:click="selectTipo('{{ $key }}')"
                                class="w-full flex items-center gap-4 bg-white/5 hover:bg-white/10 border border-white/10 hover:border-orange-500/40 rounded-xl px-4 text-left transition-all duration-200 min-h-[56px]"
                            >
                                <span class="text-2xl flex-shrink-0">{{ $tipo['icon'] }}</span>
                                <div class="flex-1 py-3">
                                    <div class="text-white font-medium text-sm">{{ $tipo['label'] }}</div>
                                    <div class="text-gray-400 text-xs mt-0.5">{{ $tipo['desc'] }}</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @empty
                            <p class="text-gray-400 text-sm text-center py-6">No hay opciones disponibles para este pedido en este momento.</p>
                        @endforelse
                    @endif

                    {{-- ── PASO 2: Submotivo ─────────────────────────── --}}
                    @if ($step === 2)
                        @forelse ($submotivoOptions as $sub)
                            <button
                                wire:click="selectSubmotivo('{{ $sub['key'] }}')"
                                class="w-full flex items-center gap-4 bg-white/5 hover:bg-white/10 border border-white/10 hover:border-orange-500/40 rounded-xl px-4 text-left transition-all duration-200 min-h-[56px]"
                            >
                                <div class="flex-1 py-3">
                                    <div class="text-white font-medium text-sm">{{ $sub['label'] }}</div>
                                    <div class="text-gray-400 text-xs mt-0.5">{{ $sub['desc'] }}</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @empty
                            <p class="text-gray-400 text-sm text-center py-6">No hay submotivos disponibles.</p>
                        @endforelse
                    @endif

                    {{-- ── PASO 3: Evidencia + OTP ───────────────────── --}}
                    @if ($step === 3)
                        {{-- Instrucción contextual --}}
                        <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl px-4 py-3 text-blue-300 text-xs">
                            @switch($submotivo ?: $tipo)
                                @case('no_era_producto')    📸 Adjunta foto del producto recibido y de su etiqueta. @break
                                @case('menos_cantidad')     📸 Adjunta foto de todo lo que llegó en el paquete. @break
                                @case('no_compatible')      📸 Adjunta foto de la pieza y de la placa de tu vehículo. @break
                                @case('golpeado_abierto')   📸 Adjunta foto de la caja cerrada y del daño visible. @break
                                @case('falla_fabrica')      📸 Adjunta foto clara del defecto de fábrica. @break
                                @case('caja_ajena')         📸 Adjunta foto del sticker QR de la caja que recibiste. @break
                                @case('no_llego_nada')      Sin foto requerida. Describe brevemente lo ocurrido en el comentario. @break
                                @case('otro')               Describe detalladamente el problema. Puedes adjuntar una foto si ayuda. @break
                                @default                     Adjunta una foto como evidencia de tu reclamo. @break
                            @endswitch
                        </div>

                        {{-- Foto --}}
                        @if ($submotivo !== 'no_llego_nada')
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">
                                    Foto de evidencia {{ $tipo !== 'otro' ? '*' : '(opcional)' }}
                                </label>

                                @if ($foto)
                                    <div class="relative mb-2">
                                        <img src="{{ $foto->temporaryUrl() }}" class="w-full max-h-40 object-cover rounded-xl border border-white/10" alt="Vista previa">
                                        <button wire:click="$set('foto', null)" class="absolute top-2 right-2 bg-black/60 hover:bg-black/80 text-white rounded-full w-7 h-7 flex items-center justify-center text-xs">✕</button>
                                    </div>
                                @endif

                                <label class="flex items-center gap-3 bg-white/5 hover:bg-white/10 border border-dashed border-white/20 hover:border-orange-500/40 rounded-xl px-4 py-3 cursor-pointer transition-all">
                                    <svg class="w-5 h-5 text-orange-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <div>
                                        <span class="text-white text-sm">{{ $foto ? 'Cambiar foto' : 'Cámara o galería' }}</span>
                                        <span class="text-gray-500 text-xs block">JPG, PNG o WEBP · máx. 5 MB</span>
                                    </div>
                                    <input
                                        type="file"
                                        wire:model="foto"
                                        accept="image/jpeg,image/png,image/webp"
                                        capture="environment"
                                        class="hidden"
                                    >
                                </label>

                                @error('foto')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        {{-- Comentario --}}
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">
                                Comentario {{ $tipo === 'otro' ? '*' : '(opcional)' }}
                            </label>
                            <textarea
                                wire:model.live="descripcion"
                                maxlength="500"
                                rows="3"
                                placeholder="{{ $tipo === 'otro' ? 'Describe el problema con detalle...' : 'Información adicional (opcional)' }}"
                                class="w-full bg-white/5 border border-white/10 focus:border-orange-500/60 rounded-xl px-4 py-3 text-white text-sm placeholder-gray-500 resize-none focus:outline-none transition-colors"
                            ></textarea>
                            <div class="flex justify-end mt-1">
                                <span class="text-gray-500 text-xs">{{ strlen($descripcion) }}/500</span>
                            </div>
                        </div>

                        {{-- OTP Section --}}
                        <div class="bg-white/3 border border-white/10 rounded-xl p-4 space-y-3">
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-1">
                                    Código de confirmación <span class="text-orange-400">*</span>
                                </label>
                                <p class="text-gray-500 text-xs">
                                    Enviaremos el código al +{{ $telefonoMascarado }}
                                </p>
                            </div>

                            <div class="flex gap-3">
                                <input
                                    type="text"
                                    wire:model.live="otp"
                                    inputmode="numeric"
                                    maxlength="4"
                                    placeholder="Código WhatsApp"
                                    class="flex-1 bg-white/5 border border-white/10 focus:border-orange-500/60 rounded-xl px-4 py-2.5 text-white text-sm text-center tracking-[0.4em] focus:outline-none transition-colors {{ !$otpEnviado ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    @if (!$otpEnviado) readonly @endif
                                >

                                <button
                                    wire:click="enviarOtp"
                                    wire:loading.attr="disabled"
                                    @if ($otpCountdown > 0) disabled @endif
                                    x-text="countdown > 0 ? `Reenviar en ${countdown}s` : 'Enviar código'"
                                    class="flex-shrink-0 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-600 disabled:cursor-not-allowed text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors"
                                ></button>
                            </div>

                            @if ($otpError)
                                <p class="text-red-400 text-xs">{{ $otpError }}</p>
                            @endif

                            @if ($otpEnviado && !$otpError)
                                <p class="text-green-400 text-xs">✓ Código enviado por WhatsApp. Revisa tus mensajes.</p>
                            @endif
                        </div>

                        {{-- Honeypot oculto --}}
                        <div style="display:none" aria-hidden="true">
                            <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
                        </div>
                    @endif

                </div>{{-- /scrollable --}}

                {{-- Barra de acciones fija --}}
                <div class="px-5 py-4 border-t border-white/10 flex gap-3">
                    @if ($step > 1)
                        <button wire:click="back" class="flex-1 bg-white/5 hover:bg-white/10 text-gray-300 text-sm font-medium py-3 rounded-xl transition-colors">
                            ← Atrás
                        </button>
                    @endif

                    @if ($step === 3)
                        <button
                            wire:click="submit"
                            wire:loading.attr="disabled"
                            @if (strlen($otp) < 4) disabled @endif
                            class="flex-1 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-600 disabled:cursor-not-allowed text-white text-sm font-bold py-3 rounded-xl transition-colors"
                            x-bind:disabled="otp.length < 4"
                            x-data="{ otp: @entangle('otp') }"
                        >
                            <span wire:loading.remove wire:target="submit">Enviar reclamo</span>
                            <span wire:loading wire:target="submit">Enviando…</span>
                        </button>
                    @endif

                    <button wire:click="cancel" class="{{ $step === 3 ? '' : 'flex-1' }} {{ $step < 3 ? 'flex-1' : 'flex-shrink-0 px-4' }} bg-white/5 hover:bg-white/10 text-gray-400 text-sm py-3 rounded-xl transition-colors">
                        Cancelar
                    </button>
                </div>
            @endif

        </div>{{-- /sheet --}}
    </div>{{-- /overlay --}}
    @endif

    {{-- Script del countdown OTP --}}
    <script>
        function reclamoSheet() {
            return {
                countdown: 0,
                init() {
                    this.$wire.on('otp-countdown-start', () => {
                        this.countdown = 60;
                        const iv = setInterval(() => {
                            this.countdown--;
                            if (this.countdown <= 0) {
                                this.countdown = 0;
                                clearInterval(iv);
                            }
                        }, 1000);
                    });
                }
            }
        }
    </script>
</div>
