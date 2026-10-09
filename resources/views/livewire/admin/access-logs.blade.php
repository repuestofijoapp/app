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
            padding: 0.85rem 1rem;
            color: #fff;
            transition: all 0.3s;
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
        .al-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 1.5rem;
        }
        .al-tab {
            padding: 8px 20px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface2);
            color: var(--muted);
            font-size: 0.82rem;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
        }
        .al-tab.active {
            background: rgba(204,0,0,.18);
            border-color: var(--accent-red);
            color: #fff;
        }
        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
        }
        .avatar-mini {
            width: 26px; height: 26px;
            border-radius: 50%;
            background: rgba(99,102,241,.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem;
            color: #a5b4fc;
            flex-shrink: 0;
        }
        .duration-badge {
            font-size: 0.72rem;
            padding: 3px 8px;
            border-radius: 6px;
            background: rgba(16,185,129,.15);
            color: #34d399;
            font-family: monospace;
            font-weight: 700;
        }
        .duration-badge.active {
            background: rgba(59,130,246,.15);
            color: #60a5fa;
        }
        .duration-badge.unknown {
            background: rgba(255,255,255,.06);
            color: var(--muted);
        }
    </style>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-white fw-bold mb-1" style="font-family:'Syne',sans-serif;">Registro de Actividad</h1>
            <p class="text-white small mb-0">Control y monitoreo de accesos y sesiones de usuarios.</p>
        </div>
    </div>

    {{-- Alertas de tráfico inusual --}}
    @if(count($suspiciousIps) > 0)
        <div class="alert bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-4 d-flex align-items-center mb-4 text-warning p-4">
            <i class="fas fa-exclamation-triangle fs-3 me-4"></i>
            <div>
                <strong class="d-block mb-1">Atención: Tráfico inusual detectado</strong>
                IPs con actividad alta (> {{ $failedThreshold }} peticiones hoy):
                <ul class="mb-0 mt-2 list-unstyled">
                    @foreach($suspiciousIps as $ip => $count)
                        <li class="mb-1">
                            <i class="fas fa-desktop me-2 opacity-75"></i>
                            <strong>{{ $ip }}</strong> ({{ $count }} peticiones)
                            <a href="#" wire:click.prevent="$set('ipFilter', '{{ $ip }}')"
                               class="badge bg-warning text-dark ms-2 text-decoration-none">
                                <i class="fas fa-filter"></i> Filtrar
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="al-tabs">
        <button type="button" class="al-tab {{ $tab === 'accesos' ? 'active' : '' }}"
                wire:click="$set('tab','accesos')">
            <i class="fas fa-list-ol me-1"></i> Accesos HTTP
        </button>
        <button type="button" class="al-tab {{ $tab === 'sesiones' ? 'active' : '' }}"
                wire:click="$set('tab','sesiones')">
            <i class="fas fa-sign-in-alt me-1"></i> Sesiones (Login / Logout)
        </button>
    </div>

    <div class="page-card">

        {{-- Filtros --}}
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 pb-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                {{-- Filtro usuario --}}
                <div class="position-relative">
                    <i class="fas fa-user position-absolute top-50 start-0 translate-middle-y ms-3 text-white" style="font-size:.75rem;"></i>
                    <input type="text" wire:model.live.debounce.300ms="userFilter" class="search-input ps-5"
                           placeholder="Buscar usuario o email...">
                </div>
                {{-- Filtro IP (solo en tab accesos) --}}
                @if($tab === 'accesos')
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white" style="font-size:.75rem;"></i>
                    <input type="text" wire:model.live.debounce.300ms="ipFilter" class="search-input ps-5"
                           placeholder="Buscar por IP...">
                </div>
                @endif
                {{-- Filtro fecha --}}
                <div class="position-relative">
                    <input type="date" wire:model.live="dateFilter" class="search-input"
                           style="color:#fff; color-scheme: dark;">
                </div>
                {{-- Limpiar --}}
                @if($ipFilter || $dateFilter || $userFilter)
                    <button type="button" wire:click="$set('ipFilter',''); $set('dateFilter',''); $set('userFilter','')"
                            class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size:0.75rem;">
                        <i class="fas fa-times me-1"></i> Limpiar filtros
                    </button>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white text-sm">Mostrar</span>
                <select wire:model.live="perPage" class="per-page-select">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-white text-sm">registros</span>
            </div>
        </div>

        {{-- ═══════════ TAB: ACCESOS HTTP ═══════════ --}}
        @if($tab === 'accesos')
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="ps-4">Fecha / Hora</th>
                        <th>IP</th>
                        <th>Método / Ruta</th>
                        <th>Usuario</th>
                        <th>Navegador</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <div class="text-white fw-bold" style="font-size:.82rem;">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}</div>
                                <div class="text-white small" style="font-family:monospace; color:#94a3b8 !important;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);font-weight:normal;color:#fff;">
                                    {{ $log->ip }}
                                </span>
                            </td>
                            <td>
                                <span class="badge me-2"
                                    style="background:{{ $log->method === 'POST' ? 'rgba(16,185,129,.2)' : 'rgba(59,130,246,.2)' }};color:{{ $log->method === 'POST' ? '#34d399' : '#60a5fa' }};">
                                    {{ $log->method }}
                                </span>
                                <span class="text-white" style="font-size:.82rem;">{{ $log->route }}</span>
                            </td>
                            <td>
                                @if($log->user_id)
                                    <div class="user-badge">
                                        <div class="avatar-mini">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="text-white fw-bold" style="font-size:.8rem;">{{ $log->user_name ?? 'Usuario #'.$log->user_id }}</div>
                                            <div style="font-size:.7rem; color:#94a3b8;">{{ $log->user_email ?? '' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color:#94a3b8; font-size:.8rem;"><i class="fas fa-user-secret me-1"></i> Anónimo</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-white small text-truncate" style="max-width:220px;" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent ?: 'N/A' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-shield-alt fs-2 mb-3 opacity-50 d-block" style="color:#94a3b8;"></i>
                                <span style="color:#94a3b8;">No se encontraron registros.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $logs->links('vendor.pagination.custom-repuestofijo') }}</div>
        @endif

        {{-- ═══════════ TAB: SESIONES (LOGIN / LOGOUT) ═══════════ --}}
        @if($tab === 'sesiones')
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Login</th>
                        <th>Logout</th>
                        <th>Duración</th>
                        <th>IP</th>
                        <th>Dispositivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        @php
                            $loginAt  = \Carbon\Carbon::parse($session->logged_in_at);
                            $logoutAt = $session->logged_out_at ? \Carbon\Carbon::parse($session->logged_out_at) : null;
                            if ($logoutAt) {
                                $diff    = $loginAt->diff($logoutAt);
                                $durText = ($diff->h > 0 ? $diff->h.'h ' : '') . $diff->i.'m ' . $diff->s.'s';
                                $durClass = 'duration-badge';
                            } else {
                                $durText  = 'En sesión';
                                $durClass = 'duration-badge active';
                            }
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="user-badge">
                                    <div class="avatar-mini">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold" style="font-size:.8rem;">{{ $session->user_name }}</div>
                                        <div style="font-size:.7rem; color:#94a3b8;">{{ $session->user_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-white fw-bold" style="font-size:.82rem;">{{ $loginAt->format('d/m/Y') }}</div>
                                <div style="font-family:monospace; font-size:.78rem; color:#00d68f;">{{ $loginAt->format('H:i:s') }}</div>
                            </td>
                            <td>
                                @if($logoutAt)
                                    <div class="text-white" style="font-size:.82rem;">{{ $logoutAt->format('d/m/Y') }}</div>
                                    <div style="font-family:monospace; font-size:.78rem; color:#f87171;">{{ $logoutAt->format('H:i:s') }}</div>
                                @else
                                    <span class="badge" style="background:rgba(59,130,246,.2);color:#60a5fa;font-size:.72rem;">
                                        <i class="fas fa-circle me-1" style="font-size:.5rem;"></i> Activa
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $durClass }}">{{ $durText }}</span>
                            </td>
                            <td>
                                <span class="badge" style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);font-weight:normal;color:#fff;">
                                    {{ $session->ip ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-white small text-truncate" style="max-width:200px;" title="{{ $session->user_agent }}">
                                    {{ $session->user_agent ?: 'N/A' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-sign-in-alt fs-2 mb-3 opacity-50 d-block" style="color:#94a3b8;"></i>
                                <span style="color:#94a3b8;">Sin sesiones registradas aún. Los logins futuros aparecerán aquí.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $sessions->links('vendor.pagination.custom-repuestofijo') }}</div>
        @endif

    </div>
</div>