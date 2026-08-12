<div class="container-fluid">
    <style>
        .page-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
        }
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        .table-custom th {
            color: var(--muted);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: 0.75rem 1rem;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 1px;
        }
        .table-custom tr td {
            background: var(--surface2);
            padding: 1rem;
            color: #fff;
            transition: background 0.2s;
            vertical-align: middle;
        }
        .table-custom tr td:first-child { border-radius: 12px 0 0 12px; }
        .table-custom tr td:last-child  { border-radius: 0 12px 12px 0; }
        .search-input, .filter-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: #fff;
            min-width: 180px;
        }
        .search-input:focus, .filter-select:focus {
            outline: none;
            border-color: var(--accent-red);
        }
        .per-page-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            color: #fff;
            outline: none;
        }
        /* ── event badges ── */
        .event-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.3rem 0.75rem; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;
            text-transform: uppercase; white-space: nowrap;
        }
        .event-invalid-secret { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .event-unauthorized   { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .event-other          { background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.3); }
        /* ── blocked pill ── */
        .ip-blocked-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.2rem 0.6rem; border-radius: 20px;
            font-size: 0.68rem; font-weight: 700;
            background: rgba(239,68,68,0.15); color: #f87171;
            border: 1px solid rgba(239,68,68,0.3);
        }
        /* ── stat cards ── */
        .stat-card {
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 16px; padding: 1.2rem 1.5rem;
        }
        /* ── Modal ── */
        .sa-modal-overlay {
            position: fixed; inset: 0; z-index: 3000;
            background: rgba(0,0,0,0.75); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center; padding: 1rem;
        }
        .sa-modal {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 20px; padding: 2rem; width: 100%; max-width: 600px;
            max-height: 90vh; overflow-y: auto;
        }
        .detail-row {
            display: flex; gap: 1rem; padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            color: var(--muted); font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            min-width: 130px; padding-top: 0.1rem;
        }
        .detail-value { color: #fff; font-size: 0.88rem; word-break: break-all; }
        .json-detail {
            background: rgba(0,0,0,0.3); border: 1px solid var(--border);
            border-radius: 8px; padding: 0.75rem 1rem;
            font-family: 'Courier New', monospace; font-size: 0.78rem;
            color: #a5b4fc; white-space: pre-wrap; max-height: 180px; overflow-y: auto;
        }
        .btn-block-ip {
            background: rgba(239,68,68,0.15); color: #f87171;
            border: 1px solid rgba(239,68,68,0.4); border-radius: 10px;
            padding: 0.55rem 1.2rem; font-weight: 700; font-size: 0.82rem;
            cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-block-ip:hover { background: rgba(239,68,68,0.3); color: #fff; }
        .btn-unblock-ip {
            background: rgba(16,185,129,0.15); color: #34d399;
            border: 1px solid rgba(16,185,129,0.4); border-radius: 10px;
            padding: 0.55rem 1.2rem; font-weight: 700; font-size: 0.82rem;
            cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-unblock-ip:hover { background: rgba(16,185,129,0.3); color: #fff; }
        .btn-revisar {
            background: rgba(255,255,255,0.06); border: 1px solid var(--border);
            color: rgba(255,255,255,0.8); border-radius: 8px;
            padding: 0.3rem 0.8rem; font-size: 0.75rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-revisar:hover { background: rgba(255,255,255,0.12); color: #fff; }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-white fw-bold mb-1" style="font-family:'Syne',sans-serif;">
                <i class="fas fa-shield-alt text-danger me-2"></i> Alertas de Seguridad
            </h1>
            <p class="text-white small mb-0 opacity-75">Intentos de acceso no autorizado al panel de administración.</p>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert d-flex align-items-center gap-3 mb-4"
             style="background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); border-radius: 12px; color: #34d399;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="text-white opacity-60 small mb-1"><i class="fas fa-exclamation-triangle me-1"></i> Total eventos</div>
                <div class="text-white fw-bold fs-4">{{ $logs->total() }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="text-white opacity-60 small mb-1"><i class="fas fa-ban me-1 text-danger"></i> IPs bloqueadas</div>
                <div class="fw-bold fs-4" style="color:#f87171;">{{ $totalBlocked }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="text-white opacity-60 small mb-1"><i class="fas fa-key me-1"></i> Claves inválidas</div>
                <div class="text-white fw-bold fs-4">
                    {{ $logs->getCollection()->where('event_type', 'invalid_admin_secret')->count() }}
                    <span class="text-white opacity-40 small fw-normal">esta página</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="text-white opacity-60 small mb-1"><i class="fas fa-user-slash me-1"></i> Sin autorización</div>
                <div class="text-white fw-bold fs-4">
                    {{ $logs->getCollection()->where('event_type', 'unauthorized_admin_access_attempt')->count() }}
                    <span class="text-white opacity-40 small fw-normal">esta página</span>
                </div>
            </div>
        </div>
    </div>

    <div class="page-card">
        {{-- Toolbar --}}
        <div class="d-flex flex-wrap justify-content-between gap-3 pb-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white opacity-40"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" class="search-input ps-5"
                           placeholder="Buscar por IP...">
                </div>
                <select wire:model.live="eventFilter" class="filter-select">
                    <option value="">Todos los eventos</option>
                    @foreach($eventTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model.live="dateFilter" class="search-input"
                       style="color:#fff; color-scheme:dark;">
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white small">Mostrar</span>
                <select wire:model.live="perPage" class="per-page-select">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-white small">registros</span>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="ps-4">Fecha / Hora</th>
                        <th>IP</th>
                        <th>Tipo de Evento</th>
                        <th>Usuario</th>
                        <th>Estado IP</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <div class="text-white">{{ $log->created_at->format('d/m/Y') }}</div>
                                <div class="text-white small opacity-60">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:#fff; font-family:monospace; font-weight:600; font-size:0.82rem; padding:0.4rem 0.7rem;">
                                    {{ $log->ip_address }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $eventClass = match($log->event_type) {
                                        'invalid_admin_secret'            => 'event-invalid-secret',
                                        'unauthorized_admin_access_attempt' => 'event-unauthorized',
                                        default                           => 'event-other',
                                    };
                                    $eventLabel = match($log->event_type) {
                                        'invalid_admin_secret'            => 'Clave secreta inválida',
                                        'unauthorized_admin_access_attempt' => 'Acceso no autorizado',
                                        default => ucfirst(str_replace('_', ' ', $log->event_type)),
                                    };
                                    $eventIcon = match($log->event_type) {
                                        'invalid_admin_secret'            => 'fa-key',
                                        'unauthorized_admin_access_attempt' => 'fa-user-slash',
                                        default => 'fa-exclamation-circle',
                                    };
                                @endphp
                                <span class="event-badge {{ $eventClass }}">
                                    <i class="fas {{ $eventIcon }}"></i> {{ $eventLabel }}
                                </span>
                            </td>
                            <td>
                                @if($log->user)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width:26px;height:26px;">
                                            <i class="fas fa-user text-primary" style="font-size:0.65rem;"></i>
                                        </div>
                                        <div>
                                            <div class="text-white" style="font-size:0.82rem;">{{ $log->user->name }}</div>
                                            <div class="text-white opacity-50" style="font-size:0.72rem;">{{ $log->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-white opacity-50 small"><i class="fas fa-user-secret me-1"></i> Anónimo</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($log->ip_address, $blockedIps))
                                    <span class="ip-blocked-badge"><i class="fas fa-ban"></i> Bloqueada</span>
                                @else
                                    <span style="color: rgba(255,255,255,0.3); font-size:0.78rem;"><i class="fas fa-check me-1"></i> Libre</span>
                                @endif
                            </td>
                            <td>
                                <button wire:click="openDetail({{ $log->id }})" class="btn-revisar">
                                    <i class="fas fa-eye me-1"></i> Revisar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-shield-alt fs-2 d-block mb-3 opacity-25"></i>
                                <span class="text-white opacity-50">No hay alertas de seguridad registradas.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links('vendor.pagination.custom-repuestofijo') }}
        </div>
    </div>

    {{-- ── Detail Modal ── --}}
    @if($showDetail && $detailLog)
        <div class="sa-modal-overlay" wire:click.self="closeDetail">
            <div class="sa-modal">
                {{-- Modal header --}}
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h2 class="h5 fw-bold text-white mb-1" style="font-family:'Syne',sans-serif;">
                            <i class="fas fa-search-plus text-danger me-2"></i> Detalle del Evento
                        </h2>
                        <p class="text-white opacity-50 small mb-0">Evento #{{ $detailLog['id'] }}</p>
                    </div>
                    <button wire:click="closeDetail" style="background:transparent; border:none; color:rgba(255,255,255,0.5); font-size:1.2rem; cursor:pointer; padding:0.2rem 0.5rem;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Detail rows --}}
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-calendar me-1"></i> Fecha</span>
                    <span class="detail-value">{{ $detailLog['date'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-network-wired me-1"></i> IP</span>
                    <span class="detail-value" style="font-family:monospace; font-size:1rem; font-weight:700;">
                        {{ $detailLog['ip'] }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-tag me-1"></i> Evento</span>
                    <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $detailLog['event'])) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fas fa-user me-1"></i> Usuario</span>
                    <span class="detail-value">
                        {{ $detailLog['user'] }}
                        @if($detailLog['user_email'])
                            <span class="opacity-50 small ms-2">{{ $detailLog['user_email'] }}</span>
                        @endif
                    </span>
                </div>
                @if(!empty($detailLog['details']))
                    <div class="detail-row" style="flex-direction:column; gap:0.5rem;">
                        <span class="detail-label"><i class="fas fa-code me-1"></i> Detalles técnicos</span>
                        <div class="json-detail">{{ json_encode($detailLog['details'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</div>
                    </div>
                @endif

                {{-- IP status + actions --}}
                <div class="mt-4 pt-3 d-flex flex-wrap gap-2 align-items-center justify-content-between"
                     style="border-top: 1px solid var(--border);">
                    <div>
                        @if($detailLog['is_blocked'])
                            <span class="ip-blocked-badge" style="font-size:0.82rem; padding:0.4rem 0.9rem;">
                                <i class="fas fa-ban"></i> IP bloqueada actualmente
                            </span>
                        @else
                            <span style="color: rgba(255,255,255,0.4); font-size:0.82rem;">
                                <i class="fas fa-check-circle me-1 text-success"></i> IP libre (sin bloqueo)
                            </span>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        @if($detailLog['is_blocked'])
                            <button wire:click="unblockIp('{{ $detailLog['ip'] }}')" class="btn-unblock-ip">
                                <i class="fas fa-unlock"></i> Desbloquear
                            </button>
                        @else
                            <button wire:click="blockIp('{{ $detailLog['ip'] }}')" class="btn-block-ip">
                                <i class="fas fa-ban"></i> Bloquear IP (72h)
                            </button>
                        @endif
                        <button wire:click="closeDetail" style="background:rgba(255,255,255,0.05); border:1px solid var(--border); color:rgba(255,255,255,0.6); border-radius:10px; padding:0.55rem 1rem; font-weight:700; font-size:0.82rem; cursor:pointer;">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
