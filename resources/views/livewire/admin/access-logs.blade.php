<div class="container-fluid">
    <style>
        .page-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255,255,255,0.2);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
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
            min-width: 170px;
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
            gap: 8px;
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }
        .user-badge.clickable:hover {
            background: rgba(255,255,255,0.06);
            cursor: pointer;
        }
        .avatar-mini {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: rgba(99,102,241,.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
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
        /* Modal Timeline Styles */
        .timeline-modal-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .timeline-modal-card {
            background: #121820;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 20px;
            width: 100%;
            max-width: 820px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }
        .timeline-scroll {
            overflow-y: auto;
            padding: 1.5rem 2rem;
        }
        .timeline-track {
            position: relative;
            padding-left: 28px;
        }
        .timeline-track::before {
            content: '';
            position: absolute;
            top: 10px;
            bottom: 10px;
            left: 11px;
            width: 2px;
            background: rgba(255,255,255,0.1);
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-icon-box {
            position: absolute;
            left: -28px;
            top: 2px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            z-index: 2;
        }
    </style>

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 text-white fw-bold mb-1" style="font-family:'Syne',sans-serif;">Registro de Actividad y Sesiones</h1>
            <p class="text-white small mb-0 opacity-75">Monitoreo en tiempo real de consultas, navegación y sesiones de usuarios.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge" style="background:rgba(16,185,129,0.15);color:#34d399;font-size:0.75rem;padding:6px 12px;border:1px solid rgba(16,185,129,0.3);">
                <i class="fas fa-satellite-dish me-1"></i> Auditoría Activa
            </span>
        </div>
    </div>

    {{-- Cards de Estadísticas Resumidas (Hoy) --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-white small opacity-75 mb-1">Usuarios Activos Hoy</div>
                    <div class="h3 text-white fw-bold mb-0" style="font-family:'Syne',sans-serif;">{{ $topStats['active_users'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(59,130,246,0.15);color:#60a5fa;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-white small opacity-75 mb-1">Sesiones Hoy</div>
                    <div class="h3 text-white fw-bold mb-0" style="font-family:'Syne',sans-serif;">{{ $topStats['sessions_today'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.15);color:#34d399;">
                    <i class="fas fa-key"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-white small opacity-75 mb-1">Consultas y Acciones Hoy</div>
                    <div class="h3 text-white fw-bold mb-0" style="font-family:'Syne',sans-serif;">{{ $topStats['requests_today'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(168,85,247,0.15);color:#c084fc;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div>
                    <div class="text-white small opacity-75 mb-1">Sesiones Abiertas Ahora</div>
                    <div class="h3 text-white fw-bold mb-0 text-success" style="font-family:'Syne',sans-serif;">{{ $topStats['active_now'] }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(34,197,94,0.15);color:#4ade80;">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
            </div>
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

    {{-- Tabs Principales --}}
    <div class="al-tabs">
        <button type="button" class="al-tab {{ $tab === 'accesos' ? 'active' : '' }}"
                wire:click="$set('tab','accesos')">
            <i class="fas fa-list-ol me-1"></i> Consultas y Actividad HTTP
        </button>
        <button type="button" class="al-tab {{ $tab === 'sesiones' ? 'active' : '' }}"
                wire:click="$set('tab','sesiones')">
            <i class="fas fa-sign-in-alt me-1"></i> Historial de Sesiones (Login / Logout)
        </button>
    </div>

    <div class="page-card">

        {{-- Barra de Filtros --}}
        <div class="d-flex flex-column flex-xl-row justify-content-between gap-3 pb-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                {{-- Filtro usuario --}}
                <div class="position-relative">
                    <i class="fas fa-user position-absolute top-50 start-0 translate-middle-y ms-3 text-white" style="font-size:.75rem;"></i>
                    <input type="text" wire:model.live.debounce.300ms="userFilter" class="search-input ps-5"
                           placeholder="Buscar usuario o email...">
                </div>

                {{-- Filtro Categoría (solo en tab accesos) --}}
                @if($tab === 'accesos')
                <div class="position-relative">
                    <select wire:model.live="categoryFilter" class="filter-select">
                        <option value="">Todas las categorías</option>
                        <option value="catalogo">🔍 Catálogo y Búsquedas</option>
                        <option value="admin">⚙️ Panel Admin</option>
                        <option value="auth">🔑 Autenticación / Sesión</option>
                        <option value="b2b">🏢 Portal Proveedores B2B</option>
                        <option value="legal">⚖️ Reclamaciones y Legal</option>
                        <option value="interaccion">⚡ Interacción Livewire</option>
                    </select>
                </div>
                @endif

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

                {{-- Botón Limpiar --}}
                @if($ipFilter || $dateFilter || $userFilter || $categoryFilter)
                    <button type="button" wire:click="$set('ipFilter',''); $set('dateFilter',''); $set('userFilter',''); $set('categoryFilter','')"
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

        {{-- ═══════════ TAB: ACCESOS Y CONSULTAS HTTP ═══════════ --}}
        @if($tab === 'accesos')
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="ps-4">Hora (Perú)</th>
                        <th>Acción / Consulta</th>
                        <th>Usuario</th>
                        <th>IP</th>
                        <th>Navegador</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $actionMeta = \App\Services\AccessLogHelper::formatDisplay($log->action_name ?? null, $log->route, $log->method, $log->category ?? null);
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="text-white fw-bold" style="font-size:.82rem;">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}</div>
                                <div class="text-white small" style="font-family:monospace; color:#94a3b8 !important;">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge" style="background:{{ $actionMeta['badge_bg'] }}; color:{{ $actionMeta['badge_color'] }}; font-size:0.72rem; border:1px solid rgba(255,255,255,0.08);">
                                        <i class="{{ $actionMeta['icon'] }} me-1"></i> {{ $actionMeta['badge_label'] }}
                                    </span>
                                    <span class="text-white fw-semibold" style="font-size:.84rem;">
                                        {{ $actionMeta['action_name'] }}
                                    </span>
                                </div>
                                <div class="text-muted small" style="font-family:monospace; font-size:0.72rem; color:#64748b !important;">
                                    <span class="badge py-0 px-1 me-1" style="background:rgba(255,255,255,0.06);color:#94a3b8;font-size:0.68rem;">{{ $log->method }}</span>
                                    /{{ ltrim($log->route, '/') }}
                                </div>
                            </td>
                            <td>
                                @if($log->user_id)
                                    <div class="user-badge clickable" wire:click="inspectUser({{ $log->user_id }})" title="Clic para ver historial completo del usuario">
                                        <div class="avatar-mini">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="text-white fw-bold d-flex align-items-center gap-1" style="font-size:.8rem;">
                                                <span>{{ $log->user_name ?? 'Usuario #'.$log->user_id }}</span>
                                                <i class="fas fa-external-link-alt text-muted" style="font-size:0.65rem;"></i>
                                            </div>
                                            <div style="font-size:.7rem; color:#94a3b8;">{{ $log->user_email ?? '' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color:#94a3b8; font-size:.8rem;"><i class="fas fa-user-secret me-1"></i> Anónimo</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);font-weight:normal;color:#fff;">
                                    {{ $log->ip }}
                                </span>
                            </td>
                            <td>
                                <div class="text-white small text-truncate" style="max-width:200px;" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent ?: 'N/A' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-shield-alt fs-2 mb-3 opacity-50 d-block" style="color:#94a3b8;"></i>
                                <span style="color:#94a3b8;">No se encontraron registros con los filtros seleccionados.</span>
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
                        <th>Inicio (Login)</th>
                        <th>Salida (Logout)</th>
                        <th>Tiempo Conectado</th>
                        <th>IP</th>
                        <th>Dispositivo</th>
                        <th class="text-end pe-4">Acción</th>
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
                                <div class="user-badge clickable" wire:click="inspectUser({{ $session->user_id }})" title="Clic para ver historial completo del usuario">
                                    <div class="avatar-mini">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-bold d-flex align-items-center gap-1" style="font-size:.8rem;">
                                            <span>{{ $session->user_name }}</span>
                                            <i class="fas fa-external-link-alt text-muted" style="font-size:0.65rem;"></i>
                                        </div>
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
                                <span class="badge" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);font-weight:normal;color:#fff;">
                                    {{ $session->ip ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <div class="text-white small text-truncate" style="max-width:180px;" title="{{ $session->user_agent }}">
                                    {{ $session->user_agent ?: 'N/A' }}
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1" style="font-size:0.75rem;" wire:click="inspectUser({{ $session->user_id }})">
                                    <i class="fas fa-history me-1"></i> Línea de tiempo
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-sign-in-alt fs-2 mb-3 opacity-50 d-block" style="color:#94a3b8;"></i>
                                <span style="color:#94a3b8;">Sin sesiones registradas aún con los filtros actuales.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $sessions->links('vendor.pagination.custom-repuestofijo') }}</div>
        @endif

    </div>

    {{-- ═══════════ MODAL: TIMELINE / INSPECTOR DE USUARIO ═══════════ --}}
    @if($inspectingUserId && $inspectingUser)
        <div class="timeline-modal-backdrop" wire:click.self="closeInspectModal">
            <div class="timeline-modal-card">
                {{-- Cabecera del modal --}}
                <div class="p-4 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center" style="background:rgba(255,255,255,0.02);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:50%;background:rgba(204,0,0,0.2);border:2px solid var(--accent-red);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;font-weight:bold;">
                            {{ strtoupper(substr($inspectingUser['name'] ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="text-white fw-bold mb-0" style="font-family:'Syne',sans-serif;">{{ $inspectingUser['name'] }}</h5>
                                <span class="badge" style="background:rgba(255,255,255,0.1);font-size:0.7rem;text-transform:uppercase;">
                                    {{ $inspectingUser['role'] ?? 'Cliente' }}
                                </span>
                            </div>
                            <div class="text-muted small">{{ $inspectingUser['email'] }}</div>
                        </div>
                    </div>
                    <button type="button" wire:click="closeInspectModal" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-times text-white"></i>
                    </button>
                </div>

                {{-- Resumen de Métricas del Usuario --}}
                <div class="px-4 py-3 border-bottom border-secondary border-opacity-25" style="background:rgba(0,0,0,0.2);">
                    <div class="row g-2 text-center text-sm">
                        <div class="col-3 border-end border-secondary border-opacity-25">
                            <span class="text-muted d-block small">Sesiones Totales</span>
                            <strong class="text-white fs-6">{{ $userStats['total_sessions'] ?? 0 }}</strong>
                        </div>
                        <div class="col-3 border-end border-secondary border-opacity-25">
                            <span class="text-muted d-block small">Acciones / Consultas</span>
                            <strong class="text-white fs-6">{{ $userStats['total_requests'] ?? 0 }}</strong>
                        </div>
                        <div class="col-3 border-end border-secondary border-opacity-25">
                            <span class="text-muted d-block small">Última IP</span>
                            <strong class="text-white fs-6 font-monospace">{{ $userStats['last_ip'] ?? '—' }}</strong>
                        </div>
                        <div class="col-3">
                            <span class="text-muted d-block small">Última Actividad</span>
                            <strong class="text-white fs-6">
                                {{ $userStats['last_active_at'] ? \Carbon\Carbon::parse($userStats['last_active_at'])->diffForHumans() : '—' }}
                            </strong>
                        </div>
                    </div>
                </div>

                {{-- Cuerpo: Línea de tiempo cronológica --}}
                <div class="timeline-scroll">
                    <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2" style="font-family:'Syne',sans-serif;">
                        <i class="fas fa-stream text-danger"></i> Línea de Tiempo de Actividad (Cronología Exacta)
                    </h6>

                    @if(empty($userTimeline))
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-hourglass-start fs-3 mb-2 d-block opacity-50"></i>
                            Sin eventos registrados aún para este usuario.
                        </div>
                    @else
                        <div class="timeline-track">
                            @foreach($userTimeline as $event)
                                <div class="timeline-item">
                                    <div class="timeline-icon-box" style="background:{{ $event['bg'] }}; color:{{ $event['color'] }}; border: 1px solid {{ $event['color'] }};">
                                        <i class="{{ $event['icon'] }}"></i>
                                    </div>
                                    <div class="ps-2">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-white fw-bold" style="font-size:0.85rem;">{{ $event['title'] }}</span>
                                                @if(isset($event['category']))
                                                    <span class="badge" style="background:{{ $event['bg'] }}; color:{{ $event['color'] }}; font-size:0.65rem;">
                                                        {{ $event['category'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-muted" style="font-size:0.75rem; font-family:monospace;">
                                                <span class="text-white opacity-75">{{ $event['time']->format('d/m/Y H:i:s') }}</span>
                                                <span class="opacity-50">({{ $event['time']->diffForHumans() }})</span>
                                            </div>
                                        </div>
                                        <div class="text-muted small mb-1" style="font-size:0.78rem;">
                                            {{ $event['description'] }}
                                        </div>
                                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size:0.7rem;">
                                            <span><i class="fas fa-network-wired me-1 opacity-50"></i>{{ $event['ip'] }}</span>
                                            @if(!empty($event['user_agent']))
                                                <span class="text-truncate opacity-75" style="max-width:350px;" title="{{ $event['user_agent'] }}">
                                                    <i class="fas fa-laptop me-1 opacity-50"></i>{{ $event['user_agent'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Footer del Modal --}}
                <div class="p-3 border-top border-secondary border-opacity-25 d-flex justify-content-end" style="background:rgba(255,255,255,0.02);">
                    <button type="button" wire:click="closeInspectModal" class="btn btn-sm btn-secondary rounded-pill px-4">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>