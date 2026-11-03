<div class="container-fluid">
    <style>
        .config-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem;
            backdrop-filter: blur(10px);
            margin: 10px;
            flex: 1;
            min-width: 300px;
            transition: all 0.3s ease;
        }
        .config-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s;
        }
        .config-item:hover {
            border-color: var(--accent-red);
            background: rgba(214, 51, 132, 0.05);
            transform: translateY(-2px);
        }
        .label-pill {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 0.75rem;
            display: block;
        }
        .log-widget {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        .log-widget:hover {
            background: rgba(255, 255, 255, 0.02);
            border-color: rgba(255, 255, 255, 0.1);
        }
        .provider-card {
            border-radius: 16px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            flex: 1;
        }
        .provider-card.active-green {
            border-color: #22c55e;
            background: rgba(34, 197, 94, 0.07);
        }
        .provider-card.active-peru {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.07);
        }
        .provider-card.inactive {
            background: rgba(255,255,255,0.03);
            border-color: rgba(255,255,255,0.08);
        }
        .provider-card.inactive:hover {
            border-color: rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.05);
        }
    </style>

    <div class="py-6 px-3">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-1" style="font-family: 'Syne', sans-serif; letter-spacing: -0.5px;">
                    <i class="fas fa-robot mr-3 text-danger"></i>
                    ZettaBot Core
                </h1>
                <p class="text-white opacity-60 text-sm">Configuración técnica de la inteligencia logística y WhatsApp.</p>
            </div>
        </div>

        <div class="max-w-7xl">

            {{-- ── SWITCH DE PROVEEDOR ── --}}
            <div class="config-card mb-6">
                <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-3" style="font-family: 'Syne', sans-serif;">
                    <i class="fas fa-exchange-alt text-yellow-400"></i>
                    Proveedor WhatsApp Activo
                </h3>
                <p class="text-white/40 text-xs mb-6">Selecciona el servicio que envía los mensajes de ZettaBot. Solo uno puede estar activo a la vez.</p>

                <div class="flex flex-col md:flex-row gap-4 mb-4">

                    {{-- Tarjeta Green API --}}
                    <div class="provider-card {{ $activeProvider === 'green_api' ? 'active-green' : 'inactive' }}"
                         wire:click="switchProvider('green_api')">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <i class="fab fa-whatsapp text-green-400 text-xl"></i>
                                <span class="text-white font-bold text-base">Green API</span>
                            </div>
                            @if($activeProvider === 'green_api')
                                <span class="text-[10px] font-black uppercase tracking-widest text-green-400 bg-green-500/15 border border-green-500/30 px-3 py-1 rounded-full">
                                    ✓ ACTIVO
                                </span>
                            @else
                                <span class="text-[10px] font-bold uppercase tracking-widest text-white/30 bg-white/5 px-3 py-1 rounded-full">
                                    Inactivo
                                </span>
                            @endif
                        </div>
                        <div class="space-y-2 text-xs text-white/50">
                            <div class="flex justify-between">
                                <span>Instance ID</span>
                                <code class="text-white/70">{{ env('GREEN_API_ID_INSTANCE', '—') }}</code>
                            </div>
                            <div class="flex justify-between">
                                <span>Token</span>
                                <code class="text-white/70 blur-sm hover:blur-none transition-all cursor-pointer">
                                    {{ substr(env('GREEN_API_TOKEN_INSTANCE', ''), 0, 12) }}…
                                </code>
                            </div>
                            @if($activeProvider === 'green_api' && $greenStatus)
                                <div class="flex justify-between items-center pt-1">
                                    <span>Estado sesión</span>
                                    @if($greenStatus === 'online')
                                        <span class="text-green-400 font-bold">● Online</span>
                                    @elseif($greenStatus === 'offline')
                                        <span class="text-red-400 font-bold">● Offline</span>
                                    @else
                                        <span class="text-yellow-400 font-bold">● Error</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <p class="text-[10px] text-white/25 mt-3 italic">Edite GREEN_API_* en su .env para cambios.</p>
                    </div>

                    {{-- Tarjeta APIs Perú --}}
                    <div class="provider-card {{ $activeProvider === 'apis_peru' ? 'active-peru' : 'inactive' }}"
                         wire:click="switchProvider('apis_peru')">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <i class="fab fa-whatsapp text-blue-400 text-xl"></i>
                                <span class="text-white font-bold text-base">APIs Perú</span>
                                <span class="text-[9px] font-black bg-blue-500/20 text-blue-300 border border-blue-500/30 px-2 py-0.5 rounded-full">PREMIUM</span>
                            </div>
                            @if($activeProvider === 'apis_peru')
                                <span class="text-[10px] font-black uppercase tracking-widest text-blue-400 bg-blue-500/15 border border-blue-500/30 px-3 py-1 rounded-full">
                                    ✓ ACTIVO
                                </span>
                            @else
                                <span class="text-[10px] font-bold uppercase tracking-widest text-white/30 bg-white/5 px-3 py-1 rounded-full">
                                    Inactivo
                                </span>
                            @endif
                        </div>
                        <div class="space-y-2 text-xs text-white/50">
                            <div class="flex justify-between">
                                <span>Usuario</span>
                                <code class="text-white/70">{{ env('APIS_PERU_USERNAME', '—') }}</code>
                            </div>
                            <div class="flex justify-between">
                                <span>Device ID</span>
                                @if($apisPeruReady)
                                    <code class="text-white/70">{{ env('APIS_PERU_DEVICE_ID') }}</code>
                                @else
                                    <span class="text-yellow-400 font-bold">⚠ Pendiente</span>
                                @endif
                            </div>
                            <div class="flex justify-between">
                                <span>Hosting</span>
                                <span class="text-blue-300">Managed · Tunnel Online</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-white/25 mt-3 italic">Token JWT se renueva automáticamente cada 23h.</p>
                    </div>
                </div>

                {{-- Aviso de Device ID pendiente --}}
                @if(!$apisPeruReady && $activeProvider === 'apis_peru')
                    <div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/30 mt-2">
                        <p class="text-xs text-yellow-300 flex items-start gap-2">
                            <i class="fas fa-exclamation-triangle mt-0.5 flex-shrink-0"></i>
                            <span>
                                <strong>Falta el Device ID.</strong> Ve al panel de <a href="https://whatsapp.apisperu.com" target="_blank" class="underline">APIs Perú</a>,
                                crea un dispositivo y vincula tu número de WhatsApp con el código QR.
                                Luego agrega el ID al <code>.env</code> como <code>APIS_PERU_DEVICE_ID</code>.
                            </span>
                        </p>
                    </div>
                @endif

                {{-- ⚠️ WARNING Green API session --}}
                @if($showGreenApiWarning)
                    <div class="mt-4 p-4 rounded-xl bg-orange-500/15 border border-orange-500/40">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-circle text-orange-400 text-lg mt-0.5 flex-shrink-0"></i>
                            <div class="flex-1">
                                <p class="text-orange-300 font-bold text-sm mb-1">⚠️ Revisa la sesión de Green API</p>
                                <p class="text-orange-200/70 text-xs leading-relaxed">
                                    Has activado <strong>Green API</strong>. Asegúrate de que el número de WhatsApp
                                    esté <strong>vinculado y con sesión activa</strong> en el panel de Green API
                                    (escanea el QR si es necesario). Sin sesión activa, los mensajes de ZettaBot no se enviarán.
                                </p>
                                <div class="flex gap-3 mt-3">
                                    <a href="https://console.green-api.com" target="_blank"
                                       class="text-xs font-bold text-orange-300 bg-orange-500/20 border border-orange-500/30 px-3 py-1.5 rounded-lg hover:bg-orange-500/30 transition-all">
                                        <i class="fas fa-external-link-alt mr-1"></i>Ir al panel Green API
                                    </a>
                                    <button wire:click="dismissWarning"
                                            class="text-xs text-white/40 hover:text-white/70 transition-all px-3 py-1.5">
                                        Ya lo verifiqué ✓
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex flex-col md:flex-row gap-4 mb-6">
                {{-- Card: Green API Gateway --}}
                <div class="config-card">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3" style="font-family: 'Syne', sans-serif;">
                        <i class="fab fa-whatsapp text-green-500"></i>
                        Green API Gateway
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="config-item">
                            <span class="label-pill">ID Instancia (Instance Id)</span>
                            <div class="flex items-center justify-between">
                                <code class="text-white font-mono text-lg tracking-wider">{{ env('GREEN_API_ID_INSTANCE', '7103539817') }}</code>
                                <i class="fas fa-lock text-xs text-white/20"></i>
                            </div>
                        </div>
                        
                        <div class="config-item">
                            <span class="label-pill">Token de Acceso (API Token)</span>
                            <div class="flex items-center justify-between">
                                <code class="text-white font-mono blur-sm hover:blur-none transition-all cursor-pointer text-sm">
                                    {{ substr(env('GREEN_API_TOKEN_INSTANCE', '987c04ae3c...'), 0, 15) }}...
                                </code>
                                <i class="fas fa-eye text-xs text-white/20"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 rounded-xl bg-white/5 border border-white/5">
                        <p class="text-xs text-white/70 flex items-center gap-2">
                            <i class="fas fa-info-circle text-pink-500"></i>
                            <span>Edite <span class="text-pink-500 font-bold">GREEN_API_*</span> en su .env para cambios.</span>
                        </p>
                    </div>
                </div>

                {{-- Card: Algoritmo de Persistencia --}}
                <div class="config-card">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3" style="font-family: 'Syne', sans-serif;">
                        <i class="fas fa-microchip text-red-500"></i>
                        Algoritmo de Persistencia
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="config-item">
                            <span class="label-pill">Intervalo Reintento</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-bold text-white">3</span>
                                <span class="text-xs font-normal text-white/40 uppercase tracking-tighter">minutos</span>
                            </div>
                            <p class="text-[10px] mt-2 text-white/30 italic">Frecuencia de notificación.</p>
                        </div>
                        
                        <div class="config-item">
                            <span class="label-pill">Límite Intentos</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-2xl font-bold text-white">3</span>
                                <span class="text-xs font-normal text-white/40 uppercase tracking-tighter">veces</span>
                            </div>
                            <p class="text-[10px] mt-2 text-white/30 italic">Saturación máxima.</p>
                        </div>
                    </div>

                    <div class="mt-6 p-4 rounded-xl bg-red-500/5 border border-red-500/10">
                        <p class="text-xs text-white/60 flex items-center gap-3">
                            <i class="fas fa-sync-alt fa-spin text-red-500/40"></i>
                            <span>Requiere reinicio de workers para aplicar cambios en caliente.</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Card: Logs --}}
            <div class="config-card">
                <h4 class="text-sm font-bold text-white mb-8 uppercase tracking-widest opacity-40 flex items-center gap-2" style="font-family: 'Syne', sans-serif;">
                    <i class="fas fa-history text-blue-400"></i>
                    Logs de Sincronización en Tiempo Real
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="log-widget">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-1">Webhook Status</span>
                            <span class="text-base text-white font-bold">Sincronizado</span>
                        </div>
                        <div class="ml-auto">
                            <span class="text-[10px] text-green-400 font-bold bg-green-500/10 border border-green-500/20 px-3 py-1 rounded-full">OK</span>
                        </div>
                    </div>

                    <div class="log-widget">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-1">Proveedor Activo</span>
                            <span class="text-base text-white font-bold">
                                {{ $activeProvider === 'apis_peru' ? 'APIs Perú' : 'Green API' }}
                            </span>
                        </div>
                        <div class="ml-auto">
                            @if($activeProvider === 'apis_peru')
                                <span class="text-[10px] text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20 px-3 py-1 rounded-full">Managed</span>
                            @else
                                <span class="text-[10px] text-green-400 font-bold bg-green-500/10 border border-green-500/20 px-3 py-1 rounded-full">Stable</span>
                            @endif
                        </div>
                    </div>

                    <div class="log-widget">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-1">Cloud Engine</span>
                            <span class="text-base text-white font-bold">Instancia 01</span>
                        </div>
                        <div class="ml-auto">
                            <span class="text-[10px] text-blue-400 font-bold bg-blue-500/10 border border-blue-500/20 px-3 py-1 rounded-full">Online</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
