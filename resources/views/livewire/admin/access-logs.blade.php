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
            transition: all 0.3s;
            vertical-align: middle;
        }

        .table-custom tr td:first-child {
            border-radius: 12px 0 0 12px;
        }

        .table-custom tr td:last-child {
            border-radius: 0 12px 12px 0;
        }

        .search-input,
        .filter-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: #fff;
            min-width: 200px;
        }

        .search-input:focus,
        .filter-select:focus {
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

        .info-box {
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: 1.2rem;
            border-radius: 15px;
            color: #fff;
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-white fw-bold mb-1">Registro de Accesos</h1>
            <p class="text-white small mb-0">Control y monitoreo de la actividad en el sistema.</p>
        </div>
        <!-- <a href="{{ route('admin.dashboard', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
            class="btn btn-outline-light rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Volver
        </a> -->
    </div>

    @if(count($suspiciousIps) > 0)
        <div
            class="alert bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-4 d-flex align-items-center mb-4 text-warning p-4">
            <i class="fas fa-exclamation-triangle fs-3 me-4"></i>
            <div>
                <strong class="d-block mb-1">Atención: Tráfico inusual detectado</strong>
                Se han detectado IPs con actividad inusualmente alta (>{{ $failedThreshold }} peticiones en el día
                seleccionado).
                <ul class="mb-0 mt-2 list-unstyled">
                    @foreach($suspiciousIps as $ip => $count)
                        <li class="mb-1">
                            <i class="fas fa-desktop me-2 opacity-75"></i> <strong>{{ $ip }}</strong> ({{ $count }} peticiones)
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

    <div class="page-card">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 pb-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white"></i>
                    <input type="text" wire:model.live.debounce.300ms="ipFilter" class="search-input ps-5"
                        placeholder="Buscar por IP...">
                </div>
                <div class="position-relative">
                    <input type="date" wire:model.live="dateFilter" class="search-input"
                        style="color: #fff; color-scheme: dark;">
                </div>
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

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="ps-4">Fecha / Hora</th>
                        <th>IP</th>
                        <th>Método / Ruta</th>
                        <th>Usuario ID</th>
                        <th>Navegador (User Agent)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="ps-4">
                                <div class="text-white">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}</div>
                                <div class="text-white small">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge"
                                    style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); font-weight: normal; color: #fff;">
                                    {{ $log->ip }}
                                </span>
                            </td>
                            <td>
                                <span class="badge me-2"
                                    style="background: {{ $log->method === 'POST' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(59, 130, 246, 0.2)' }}; color: {{ $log->method === 'POST' ? '#34d399' : '#60a5fa' }};">
                                    {{ $log->method }}
                                </span>
                                <span class="text-white">{{ $log->route }}</span>
                            </td>
                            <td>
                                @if($log->user_id)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 28px; height: 28px;">
                                            <i class="fas fa-user text-primary" style="font-size: 0.7rem;"></i>
                                        </div>
                                        <span class="text-white">User #{{ $log->user_id }}</span>
                                    </div>
                                @else
                                    <span class="text-white small"><i class="fas fa-user-secret me-1"></i> Anónimo</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-white small text-truncate" style="max-width: 250px;"
                                    title="{{ $log->user_agent }}">
                                    {{ $log->user_agent ?: 'N/A' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-white">
                                    <i class="fas fa-shield-alt fs-2 mb-3 opacity-50 d-block"></i>
                                    No se encontraron registros de acceso.
                                </div>
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
</div>