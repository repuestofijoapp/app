@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
    <style>
        .card-glass {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
        }

        .metric-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9ca3af;
            margin-bottom: 0.25rem;
        }

        .metric-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
        }

        .metric-sub {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .text-success-custom {
            color: #22c55e;
        }

        .text-danger-custom {
            color: #ff3b5c;
        }

        .text-warning-custom {
            color: #fbbf24;
        }

        .text-blue-custom {
            color: #3b82f6;
        }

        .table-custom {
            width: 100%;
            color: #d1d5db;
            font-size: 0.875rem;
        }

        .table-custom th {
            color: #9ca3af;
            font-weight: 500;
            text-align: left;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .table-custom td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            vertical-align: middle;
        }

        .badge-estado {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-alerta {
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            border: 1px solid rgba(255, 59, 92, 0.2);
        }

        /* Animation for DOT */
        .dot-active {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #22c55e;
            margin-right: 4px;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        .dot-warning {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #fbbf24;
            margin-right: 4px;
        }

        .dot-danger {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #ff3b5c;
            margin-right: 4px;
        }

        /* Brand style row for specific orders */
        .row-brand-warning {
            background: rgba(251, 191, 36, 0.05) !important;
            border: 1px solid rgba(251, 191, 36, 0.3) !important;
            color: #fbbf24 !important;
        }

        .row-brand-warning td {
            border-bottom: 1px solid rgba(251, 191, 36, 0.1) !important;
        }
    </style>

    <div class="container-fluid py-4 px-lg-4">
        <!-- Quick Stats Cards (Grid of 4) -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-glass p-3 h-100 rounded-4">
                    <div class="metric-title text-white">PEDIDOS HOY</div>
                    <div class="metric-value">{{ number_format($stats['pedidos_hoy']) }}</div>
                    <div class="metric-sub mt-2">
                        @if($stats['pedidos_growth'] > 0)
                            <span class="text-success-custom fw-medium">+{{ $stats['pedidos_growth'] }}% vs ayer</span>
                        @elseif($stats['pedidos_growth'] < 0)
                            <span class="text-danger-custom fw-medium">{{ $stats['pedidos_growth'] }}% vs ayer</span>
                        @else
                            <span class="text-success-custom fw-medium">+0% vs ayer</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-glass p-3 h-100 rounded-4">
                    <div class="metric-title text-white">EN VIVO AHORA <span class="dot-active ms-1"></span></div>
                    <div class="metric-value">{{ number_format($stats['en_vivo_ahora']) }}</div>
                    <div class="metric-sub mt-2">lo que sucede en vivo</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                @php
                    $ticketColor = 'text-success-custom';
                    $ticketSubColor = 'text-success-custom';
                    if ($stats['tickets_soporte'] >= 10) {
                        $ticketColor = 'text-danger-custom';
                        $ticketSubColor = 'text-danger-custom';
                    } elseif ($stats['tickets_soporte'] >= 5) {
                        $ticketColor = 'text-warning-custom';
                        $ticketSubColor = 'text-warning-custom';
                    }
                @endphp
                <div class="card card-glass p-3 h-100 rounded-4">
                    <div class="metric-title text-white">TICKETS SOPORTE</div>
                    <div class="metric-value {{ $ticketColor }}">{{ number_format($stats['tickets_soporte']) }}</div>
                    <div class="metric-sub mt-2 {{ $ticketSubColor }}">sin resolver</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                @php
                    $alertColor = 'text-success-custom';
                    $alertSubColor = 'text-success-custom';
                    if ($stats['alertas_seguridad'] >= 10) {
                        $alertColor = 'text-danger-custom';
                        $alertSubColor = 'text-danger-custom';
                    } elseif ($stats['alertas_seguridad'] >= 5) {
                        $alertColor = 'text-warning-custom';
                        $alertSubColor = 'text-warning-custom';
                    }
                @endphp
                <div class="card card-glass p-3 h-100 rounded-4">
                    <div class="metric-title text-white">ALERTAS SEGURIDAD</div>
                    <div class="metric-value {{ $alertColor }}">{{ number_format($stats['alertas_seguridad']) }}</div>
                    <div class="metric-sub mt-2 {{ $alertSubColor }}">últimas 24h</div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="row g-4">

            <!-- Column 1: Pedidos en vivo, Resumen del día, Alertas de seguridad -->
            <div class="col-lg-8">
                <!-- Pedidos en vivo -->
                <div class="card card-glass rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold mb-4">Pedidos en vivo</h5>
                        <div class="table-responsive">
                            <table class="table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Estado</th>
                                        <th>Proveedor</th>
                                        <th>Tiempo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedidosEnVivoList as $pedido)
                                        @php
                                            $isWarning = $pedido->status === 'por_confirmar';
                                            $rowClass = $isWarning ? 'row-brand-warning' : '';

                                            // Time format H:i:s
                                            $diff = $pedido->created_at->diff(now());
                                            $timeString = sprintf('%02d:%02d:%02d', $diff->h, $diff->i, $diff->s);
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td class="fw-bold">#{{ str_pad($pedido->id, 7, '0', STR_PAD_LEFT) }}</td>
                                            <td class="fw-medium text-uppercase">{{ $pedido->customer->name ?? 'Cliente' }}</td>
                                            <td>
                                                @if($pedido->status == 'por_confirmar')
                                                    <span class="fw-medium">Por confirmar</span>
                                                @else
                                                    <span
                                                        class="badge rounded-pill fw-medium px-3 py-1 bg-white bg-opacity-10 text-white"
                                                        style="background: {{ $pedido->status_color }}25 !important; color: {{ $pedido->status_color }} !important;">
                                                        {{ $pedido->status_label }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="fw-medium">Proveedor {{ collect(['A', 'B', 'C'])->random() }}</td>
                                            <td class="fw-medium">
                                                {{ $timeString }}
                                                @if($isWarning)
                                                    <i class="fas fa-exclamation-triangle ms-1 opacity-75"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($pedidosEnVivoList->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted border-0">No hay pedidos en vivo
                                                en este momento</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Resumen del día -->
                <div class="card card-glass rounded-4 mb-4 border-0"
                    style="border-bottom: 1px solid rgba(255,255,255,0.08) !important;">
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold mb-4">Resumen del día</h5>
                        <div class="row row-cols-2 row-cols-md-4 g-4 mb-4">
                            <div class="col">
                                <div class="text-white small mb-1">Completados</div>
                                <div class="fs-4 text-white fw-bold">{{ number_format($stats['resumen_completados']) }}
                                </div>
                            </div>
                            <div class="col">
                                <div class="text-white small mb-1">Cancelados</div>
                                <div class="fs-4 text-danger-custom fw-bold">
                                    {{ number_format($stats['resumen_cancelados']) }}</div>
                            </div>
                            <div class="col">
                                <div class="text-white small mb-1">Ticket promedio</div>
                                <div class="fs-4 text-white fw-bold">S/ {{ number_format($stats['ticket_promedio'], 0) }}
                                </div>
                            </div>
                            <div class="col">
                                <div class="text-white small mb-1">Proveedor activo</div>
                                <div class="fs-4 text-white fw-bold">{{ $stats['proveedor_activo'] }}</div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="text-white small mb-1">Saldo Culqi</div>
                            <div class="fs-5 text-success-custom fw-bold">S/ {{ number_format($stats['saldo_culqi'], 0) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertas de seguridad recientes -->
                <div class="card card-glass rounded-4 mb-4 mb-lg-0 border-0"
                    style="border-bottom: 1px solid rgba(255,255,255,0.08) !important;">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap align-items-center mb-4 gap-2">
                            <h5 class="text-white fw-bold mb-0">Alertas de seguridad recientes</h5>
                            <span class="badge px-3 py-1 rounded-pill"
                                style="background:rgba(255, 59, 92, 0.1) !important; color:#ff3b5c !important; border: 1px solid rgba(255, 59, 92, 0.2);">{{ $stats['alertas_seguridad'] }}
                                activas</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>IP</th>
                                        <th>Evento</th>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_logs as $log)
                                        <tr>
                                            <td class="fw-medium text-white">
                                                {{ $log->created_at->format('d/m H:i') }}
                                            </td>
                                            <td class="text-white fw-bold" style="font-family: monospace;">
                                                {{ $log->ip_address }}</td>
                                            <td>
                                                @php
                                                    $eventType = match($log->event_type) {
                                                        'invalid_admin_secret' => 'Clave inválida',
                                                        'unauthorized_admin_access_attempt' => 'Acceso no autorizado',
                                                        default => ucfirst(str_replace('_', ' ', $log->event_type)),
                                                    };
                                                @endphp
                                                <span class="badge-alerta"
                                                    style="background-color: rgba(255, 59, 92, 0.1); color: #ff3b5c; border: 1px solid rgba(255, 59, 92, 0.2);">{{ $eventType }}</span>
                                            </td>
                                            <td class="text-white fw-medium">
                                                {{ $log->user->name ?? 'Anónimo' }}
                                            </td>
                                            <td>
                                                <button
                                                    onclick="openSecurityModal({{ $log->id }}, '{{ $log->ip_address }}', '{{ addslashes($eventType) }}', '{{ $log->created_at->format('d/m/Y H:i:s') }}', '{{ addslashes($log->user->name ?? 'Anónimo') }}', {{ json_encode($log->details ?? []) }})"
                                                    class="btn btn-sm btn-dark border border-secondary text-white fw-medium px-3"
                                                    style="font-size: 0.75rem; background: rgba(255,255,255,0.05) !important;">
                                                    <i class="fas fa-eye me-1"></i> Revisar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if(count($recent_logs) === 0)
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted border-0">No hay alertas de
                                                seguridad</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="5" class="border-0 px-0 pt-4 pb-0">
                                            <a href="{{ route('admin.security-alerts', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                               class="text-white small text-decoration-none opacity-60" style="transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.6">
                                                <i class="fas fa-shield-alt me-1 text-danger"></i> Ver todas las alertas
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-1">
                                                    <path d="M5 12h14" /><path d="m12 5 7 7-7 7" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                {{-- ── Security detail mini-modal (plain JS, no Livewire needed) ── --}}
                <div id="secModalOverlay" onclick="if(event.target===this) closeSecurityModal()"
                     style="display:none; position:fixed; inset:0; z-index:4000; background:rgba(0,0,0,0.75); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem;">
                    <div style="background:var(--surface); border:1px solid var(--border); border-radius:20px; padding:2rem; width:100%; max-width:520px; max-height:90vh; overflow-y:auto;">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <h5 class="text-white fw-bold mb-0" style="font-family:'Syne',sans-serif;">
                                <i class="fas fa-search-plus text-danger me-2"></i> Detalle del evento
                            </h5>
                            <button onclick="closeSecurityModal()" style="background:transparent;border:none;color:rgba(255,255,255,0.5);font-size:1.2rem;cursor:pointer;"><i class="fas fa-times"></i></button>
                        </div>
                        <div id="secModalBody" style="font-size:0.88rem;"></div>
                        <div class="mt-4 d-flex gap-2 justify-content-end" style="border-top:1px solid var(--border); padding-top:1rem;">
                            <a id="secModalLink" href="#" class="btn btn-sm"
                               style="background:rgba(255,59,92,0.15); color:#f87171; border:1px solid rgba(255,59,92,0.3); border-radius:8px; font-weight:700; font-size:0.8rem; padding:0.4rem 1rem;">
                                <i class="fas fa-shield-alt me-1"></i> Ver en panel completo
                            </a>
                            <button onclick="closeSecurityModal()" style="background:rgba(255,255,255,0.05); border:1px solid var(--border); color:rgba(255,255,255,0.6); border-radius:8px; padding:0.4rem 1rem; font-weight:700; font-size:0.8rem; cursor:pointer;">Cerrar</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Column 2: ZettaBot, Tickets Soporte -->
            <div class="col-lg-4">

                <!-- ZettaBot -->
                <div class="card card-glass border-0 mb-4 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold mb-4">ZettaBot</h5>

                        <div class="d-flex justify-content-between mb-3 py-1 border-bottom border-dark">
                            <div class="text-white text-opacity-75">Estado</div>
                            <div class="text-success-custom fw-medium"><span class="dot-active"></span> Activo</div>
                        </div>

                        <div class="d-flex justify-content-between mb-3 py-1 border-bottom border-dark">
                            <div class="text-white text-opacity-75">Último mensaje</div>
                            <div class="text-white fw-bold">hace 2 min</div>
                        </div>

                        <div class="d-flex justify-content-between mb-3 py-1 border-bottom border-dark">
                            <div class="text-white text-opacity-75">Última respuesta</div>
                            <div class="text-white fw-bold">hace 3 min</div>
                        </div>

                        <div class="d-flex justify-content-between mb-3 py-1 border-bottom border-dark">
                            <div class="text-white text-opacity-75">T. respuesta prom.</div>
                            <div class="text-white fw-bold">1m 48s</div>
                        </div>

                        <div class="d-flex justify-content-between py-1">
                            <div class="text-white text-opacity-75">Sin respuesta hoy</div>
                            <div class="text-warning-custom fw-medium">2 proveedores</div>
                        </div>
                    </div>
                </div>

                <!-- Tickets Soporte -->
                <div class="card card-glass border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="text-white fw-bold mb-4">Tickets soporte</h5>

                        @foreach($recentTickets as $ticket)
                            <div class="mb-4 d-flex align-items-start">
                                <span
                                    class="{{ $ticket->status === 'abierta' ? 'dot-danger' : 'dot-warning' }} mt-2 me-2"></span>
                                <div>
                                    <div class="text-white fw-bold mb-1">
                                        #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }} —
                                        {{ str_replace(['📦 ', '🔄 ', '💔 ', '💳 ', '❓ '], '', $ticket->tipo_label) }}
                                    </div>
                                    <div class="text-white text-opacity-50 small">
                                        {{ explode(' ', $ticket->customer->name ?? 'Cliente')[0] }}
                                        {{ explode(' ', $ticket->customer->name ?? 'Cliente')[1] ?? '' }} · hace
                                        {{ $ticket->created_at->diffInHours(now()) }}h @if($ticket->status === 'abierta') sin
                                        respuesta @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if($recentTickets->isEmpty())
                            <div class="text-center py-3 text-white">
                                <span class="dot-active me-2"></span> No hay tickets pendientes
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-center mt-5 mb-3 opacity-25">
                    <div
                        style="width: 40px; height: 40px; border-radius: 50%; border: 1px solid white; display: inline-flex; justify-content: center; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14" />
                            <path d="m19 12-7 7-7-7" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </div>

<script>
function openSecurityModal(id, ip, event, date, user, details) {
    const overlay = document.getElementById('secModalOverlay');
    const body    = document.getElementById('secModalBody');
    const link    = document.getElementById('secModalLink');
    const secret  = '{{ env("ADMIN_URL_SECRET", "Repuesto-Sape-2026") }}';

    const row = (icon, label, value) =>
        `<div style="display:flex;gap:1rem;padding:0.65rem 0;border-bottom:1px solid rgba(255,255,255,0.06);">
            <span style="color:rgba(255,255,255,0.4);font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;min-width:110px;padding-top:2px;">
                <i class="fas ${icon} me-1"></i>${label}
            </span>
            <span style="color:#fff;font-size:0.88rem;word-break:break-all;">${value}</span>
        </div>`;

    let detailsHtml = '';
    if (details && Object.keys(details).length > 0) {
        detailsHtml = `<div style="padding:0.65rem 0;">
            <span style="color:rgba(255,255,255,0.4);font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:.5rem;"><i class="fas fa-code me-1"></i>Detalles técnicos</span>
            <pre style="background:rgba(0,0,0,0.3);border:1px solid rgba(255,255,255,0.06);border-radius:8px;padding:.75rem 1rem;font-family:'Courier New',monospace;font-size:.75rem;color:#a5b4fc;white-space:pre-wrap;margin:0;max-height:150px;overflow-y:auto;">${JSON.stringify(details, null, 2)}</pre>
        </div>`;
    }

    body.innerHTML = row('fa-calendar','Fecha', date)
                   + row('fa-network-wired','IP', `<span style="font-family:monospace;font-size:1rem;font-weight:700;">${ip}</span>`)
                   + row('fa-tag','Evento', event)
                   + row('fa-user','Usuario', user)
                   + detailsHtml;

    link.href = `/Ayoro-sape-${secret}/security-alerts`;
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeSecurityModal() {
    document.getElementById('secModalOverlay').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSecurityModal(); });
</script>
@endsection