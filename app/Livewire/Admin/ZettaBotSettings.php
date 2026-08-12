<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ZettaBotSettings extends Component
{
    public function render()
    {
        return <<<'HTML'
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
                    justify-between;
                    transition: all 0.3s;
                }
                .log-widget:hover {
                    background: rgba(255, 255, 255, 0.02);
                    border-color: rgba(255, 255, 255, 0.1);
                }
            </style>

            <div class="py-6 px-3">
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
                    <div class="flex flex-col md:flex-row gap-4 mb-6">
                        {{-- Card 1: Green API Gateway --}}
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

                        {{-- Card 2: Algoritmo de Persistencia --}}
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

                    {{-- Card 3: Historial de Servidor --}}
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
                                    <span class="text-[10px] uppercase text-white/30 font-bold tracking-widest mb-1">API Gateway</span>
                                    <span class="text-base text-white font-bold">Conexión Directa</span>
                                </div>
                                <div class="ml-auto">
                                    <span class="text-[10px] text-green-400 font-bold bg-green-500/10 border border-green-500/20 px-3 py-1 rounded-full">Stable</span>
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
HTML;
    }
}
