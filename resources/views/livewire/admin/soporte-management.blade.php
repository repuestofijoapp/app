<div>
    {{-- SOPORTE MANAGEMENT · INCIDENCIAS & AUDITORÍA PoD --}}
    <style>
        .stat-card-soporte {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card-soporte:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }
        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }
        .inc-row {
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            transition: background .15s;
        }
        .inc-row:hover {
            background: rgba(255, 255, 255, 0.04);
        }
        .badge-tipo {
            font-size: 0.72rem;
            padding: 4px 10px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.08);
        }
        .filter-select-s {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #fff;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 0.85rem;
        }
        .filter-select-s option {
            background: #1a2535;
            color: #fff;
        }
        .pod-audit-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .pod-img-thumb {
            width: 100%;
            height: 170px;
            object-fit: cover;
            background: #0b111e;
            cursor: pointer;
            transition: opacity .2s;
        }
        .pod-img-thumb:hover {
            opacity: 0.9;
        }
        .modal-overlay-custom {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.82);
            backdrop-filter: blur(6px);
            z-index: 10550;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow-y: auto;
        }
        .modal-box-custom {
            background: #131826;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            width: 100%;
            max-height: 92vh;
            overflow-y: auto;
            padding: 1.75rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        .btn-close-custom {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .timeline-item {
            position: relative;
            padding-left: 24px;
            border-left: 2px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 12px;
        }
        .timeline-dot {
            position: absolute;
            left: -6px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ff3b5c;
        }
    </style>

    <div class="px-3 px-md-4 py-4">

        {{-- ── HEADER ────────────────────────────────────────────── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h4 class="text-white fw-bold mb-0">
                    <i class="fas fa-headset me-2" style="color:#ff3b5c;"></i> Soporte · Incidencias
                </h4>
                <p class="text-white small mb-0">Gestión de reclamos de clientes, SLA y Auditoría PoD</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill px-3 py-2"
                    style="background:#ff3b5c20; color:#ff3b5c; font-size:.8rem; border:1px solid #ff3b5c40;">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $totalAbiertas }} Abiertas
                </span>
                <span class="badge rounded-pill px-3 py-2"
                    style="background:#fbbf2420; color:#fbbf24; font-size:.8rem; border:1px solid #fbbf2440;">
                    <i class="fas fa-search me-1"></i> {{ $totalEnRevision }} En revisión
                </span>
                <span class="badge rounded-pill px-3 py-2"
                    style="background:#00d68f20; color:#00d68f; font-size:.8rem; border:1px solid #00d68f40;">
                    <i class="fas fa-check-circle me-1"></i> {{ $totalResueltas }} Resueltas
                </span>
            </div>
        </div>

        {{-- ── STATS CARDS ────────────────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card-soporte">
                    <div class="stat-num" style="color:#ff3b5c;">{{ $totalAbiertas }}</div>
                    <div class="text-white small mt-1">Abiertas (Pendientes)</div>
                    <div class="mt-2"><i class="fas fa-inbox" style="color:#ff3b5c; opacity:.5;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-soporte">
                    <div class="stat-num" style="color:#fbbf24;">{{ $totalEnRevision }}</div>
                    <div class="text-white small mt-1">En revisión (Auditando PoD)</div>
                    <div class="mt-2"><i class="fas fa-clipboard-check" style="color:#fbbf24; opacity:.5;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-soporte">
                    <div class="stat-num" style="color:#00d68f;">{{ $totalResueltas }}</div>
                    <div class="text-white small mt-1">Resueltas / Cerradas</div>
                    <div class="mt-2"><i class="fas fa-check-double" style="color:#00d68f; opacity:.5;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-soporte">
                    <div class="stat-num text-white">{{ $totalAbiertas + $totalEnRevision + $totalResueltas }}</div>
                    <div class="text-white small mt-1">Total acumulado</div>
                    <div class="mt-2"><i class="fas fa-list-ol" style="color:#6B7A99; opacity:.5;"></i></div>
                </div>
            </div>
        </div>

        {{-- ── FILTROS ────────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            <input wire:model.live.debounce.250ms="search" type="text" placeholder="Buscar código INC-, pedido # o cliente..."
                class="filter-select-s flex-grow-1" style="min-width: 220px;">
            <select wire:model.live="statusFilter" class="filter-select-s">
                <option value="">Todos los estados</option>
                <option value="abierta">🔴 Abiertas</option>
                <option value="en_revision">🟡 En revisión (PoD)</option>
                <option value="reenvio_camino">🟣 Reenvío en camino</option>
                <option value="reembolsado">🔵 Reembolsado</option>
                <option value="resuelta">🟢 Resueltas</option>
                <option value="rechazada">🟠 Rechazadas</option>
                <option value="cerrada">⚫ Cerradas</option>
            </select>
            <select wire:model.live="tipoFilter" class="filter-select-s">
                <option value="">Todos los motivos</option>
                <option value="producto_incorrecto">Producto o cantidad incorrectos</option>
                <option value="daniado_falla">Llegó dañado o con falla</option>
                <option value="no_llego">No llegó mi pedido</option>
                <option value="otro">Otro problema</option>
            </select>

            <div class="d-flex align-items-center gap-2 text-white ms-md-auto">
                <span class="small opacity-50">Mostrar</span>
                <select wire:model.live="perPage" class="filter-select-s" style="padding: 4px 10px;">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="small opacity-50">registros</span>
            </div>
        </div>

        {{-- ── TABLA DE INCIDENCIAS ────────────────────────────────── --}}
        <div class="card border-0 shadow-lg" style="background:rgba(255,255,255,0.03); border-radius:14px; overflow:hidden;">
            @forelse($incidencias as $inc)
                @php
                    $sla = $inc->sla;
                @endphp
                <div class="inc-row px-4 py-3 d-flex flex-wrap align-items-center gap-3">

                    {{-- Status Indicator Dot --}}
                    <div class="flex-shrink-0">
                        <span class="d-inline-block rounded-circle" style="width:12px; height:12px; background:{{ $inc->status_color }};
                                                                   box-shadow: 0 0 8px {{ $inc->status_color }}90;"></span>
                    </div>

                    {{-- Código + Pedido + Tipo --}}
                    <div class="flex-grow-1" style="min-width: 240px;">
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="fw-bold text-warning" style="font-size: 0.95rem; letter-spacing: 0.5px;">
                                {{ $inc->codigo ?? 'INC-#' . $inc->id }}
                            </span>
                            <span class="badge bg-dark border border-secondary text-white-50" style="font-size: 0.72rem;">
                                Pedido #{{ $inc->pedido_id }}
                            </span>
                            <span class="badge-tipo text-white">{{ $inc->tipo_label }}</span>
                            @if($inc->submotivo)
                                <span class="badge bg-secondary bg-opacity-25 text-white-50" style="font-size: 0.7rem;">
                                    {{ $inc->submotivo_label }}
                                </span>
                            @endif
                        </div>
                        <div class="text-white-50 small">
                            <span class="text-white fw-semibold">{{ $inc->customer->name ?? 'Cliente' }}</span>
                            @if($inc->telefono_verificado)
                                · <span class="text-success"><i class="fab fa-whatsapp me-1"></i>+{{ $inc->telefono_verificado }}</span>
                            @endif
                            · {{ $inc->created_at->diffForHumans() }}
                        </div>
                        @if($inc->descripcion)
                            <div class="text-white-50 small mt-1 fst-italic"
                                style="font-size:0.8rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:550px;">
                                "{{ $inc->descripcion }}"
                            </div>
                        @endif
                    </div>

                    {{-- SLA Timer --}}
                    <div class="text-center" style="min-width: 140px;">
                        <span class="badge rounded-pill px-3 py-1 fw-bold"
                            style="background: {{ $sla['bg'] }}; color: {{ $sla['color'] }}; font-size: 0.76rem; border: 1px solid {{ $sla['color'] }}40;">
                            <i class="fas fa-clock me-1"></i> {{ $sla['label'] }}
                        </span>
                    </div>

                    {{-- Estado --}}
                    <div class="text-center" style="min-width: 120px;">
                        <span class="badge rounded-pill px-3 py-1 fw-semibold"
                            style="background:{{ $inc->status_color }}25; color:{{ $inc->status_color }}; font-size:0.78rem; border: 1px solid {{ $inc->status_color }}50;">
                            {{ $inc->status_label }}
                        </span>
                        @if($inc->resolved_at)
                            <div class="text-white-50 x-small mt-1" style="font-size: 0.7rem;">
                                <i class="fas fa-check me-1 text-success"></i>{{ $inc->resolved_at->format('d/m H:i') }}
                            </div>
                        @endif
                    </div>

                    {{-- Acciones (Auditoría PoD + Gestionar) --}}
                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                        {{-- Botón Auditoría PoD (Sección 9) --}}
                        <button wire:click="openAudit({{ $inc->id }})" class="btn btn-sm px-3 rounded-pill fw-semibold"
                            style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.35); font-size: 0.8rem;"
                            title="Auditoría visual de los 4 hitos y evidencia">
                            <i class="fas fa-clipboard-check me-1"></i> Auditoría PoD
                        </button>

                        {{-- Botón Gestionar --}}
                        <button wire:click="openModal({{ $inc->id }})" class="btn btn-sm px-3 rounded-pill fw-semibold"
                            style="background: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.15); font-size: 0.8rem;"
                            title="Cambiar estado o agregar notas">
                            <i class="fas fa-sliders-h me-1"></i> Gestionar
                        </button>
                    </div>

                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-3x mb-3 text-white text-opacity-15"></i>
                    <p class="text-white text-opacity-60 mb-0">No hay incidencias que coincidan con los filtros seleccionados.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="mt-4">{{ $incidencias->links('vendor.pagination.custom-repuestofijo') }}</div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL AUDITORÍA PoD (Sección 9: Grilla 3 cols × 2 filas)       --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    @if($showAuditModal && $auditIncidencia)
        <div class="modal-overlay-custom" wire:click.self="closeAudit">
            <div class="modal-box-custom" style="max-width: 1050px;">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50 px-2 py-1">
                                <i class="fas fa-shield-alt me-1"></i> AUDITORÍA DE CONTRADICCIÓN PoD
                            </span>
                            <span class="fw-bold text-warning fs-5">{{ $auditIncidencia->codigo }}</span>
                        </div>
                        <h5 class="text-white fw-bold mb-0 mt-1">
                            Pedido #{{ $auditIncidencia->pedido_id }} · Cliente: {{ $auditIncidencia->customer->name ?? 'Cliente' }}
                        </h5>
                        <p class="text-white-50 small mb-0">
                            Compara los hitos fotográficos de la cadena de custodia con la evidencia y comentario del cliente.
                        </p>
                    </div>
                    <button wire:click="closeAudit" class="btn-close-custom">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- GRILLA 3 COLUMNAS × 2 FILAS --}}
                <div class="row g-3">

                    {{-- ── COLUMNA 1: Hito 1 (Recolecta) + Hito 3 (Despacho Motorizado) ── --}}
                    <div class="col-12 col-md-4 d-flex flex-column gap-3">
                        {{-- HITO 1: Recojo Proveedor --}}
                        @php $h1 = $auditHitos['recojo_proveedor'] ?? null; @endphp
                        <div class="pod-audit-card">
                            <div class="p-2 px-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.03);">
                                <span class="fw-bold text-white small"><i class="fas fa-store me-1 text-primary"></i> 1. Recojo en Proveedor</span>
                                <span class="badge {{ $h1 ? 'bg-success' : 'bg-secondary' }} bg-opacity-25 text-white" style="font-size:0.68rem;">
                                    {{ $h1 ? 'Completado' : 'Pendiente' }}
                                </span>
                            </div>
                            @if($h1 && $h1->foto_url)
                                <a href="{{ $h1->foto_url }}" target="_blank" title="Clic para ampliar">
                                    <img src="{{ $h1->foto_url }}" class="pod-img-thumb" alt="Foto Hito 1">
                                </a>
                            @else
                                <div class="pod-img-thumb d-flex align-items-center justify-content-center text-white-50 small">
                                    <i class="fas fa-image me-1"></i> Sin foto registrada
                                </div>
                            @endif
                            <div class="p-2 px-3 small text-white-50" style="font-size: 0.78rem;">
                                <div><strong>Operario:</strong> {{ $h1->operador_nombre ?? ($h1->operador->name ?? '—') }}</div>
                                <div><strong>Fecha/Hora:</strong> {{ $h1 ? $h1->created_at->format('d/m/Y H:i') : '—' }}</div>
                                @if($h1 && $h1->observaciones)
                                    <div class="fst-italic mt-1 text-white-50">Obs: {{ $h1->observaciones }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- HITO 3: Despacho a Motorizado --}}
                        @php $h3 = $auditHitos['entrega_motorizado'] ?? null; @endphp
                        <div class="pod-audit-card">
                            <div class="p-2 px-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.03);">
                                <span class="fw-bold text-white small"><i class="fas fa-motorcycle me-1 text-info"></i> 3. Despacho Motorizado</span>
                                <span class="badge {{ $h3 ? 'bg-success' : 'bg-secondary' }} bg-opacity-25 text-white" style="font-size:0.68rem;">
                                    {{ $h3 ? 'Completado' : 'Pendiente' }}
                                </span>
                            </div>
                            @if($h3 && $h3->foto_url)
                                <a href="{{ $h3->foto_url }}" target="_blank" title="Clic para ampliar">
                                    <img src="{{ $h3->foto_url }}" class="pod-img-thumb" alt="Foto Hito 3">
                                </a>
                            @else
                                <div class="pod-img-thumb d-flex align-items-center justify-content-center text-white-50 small">
                                    <i class="fas fa-image me-1"></i> Sin foto registrada
                                </div>
                            @endif
                            <div class="p-2 px-3 small text-white-50" style="font-size: 0.78rem;">
                                <div><strong>Operario/Motorizado:</strong> {{ $h3->operador_nombre ?? ($h3->operador->name ?? '—') }}</div>
                                <div><strong>Fecha/Hora:</strong> {{ $h3 ? $h3->created_at->format('d/m/Y H:i') : '—' }}</div>
                                @if($h3 && $h3->observaciones)
                                    <div class="fst-italic mt-1 text-white-50">Obs: {{ $h3->observaciones }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── COLUMNA 2: Hito 2 (Embalaje Hub) + Hito 4 (Entrega Cliente) ── --}}
                    <div class="col-12 col-md-4 d-flex flex-column gap-3">
                        {{-- HITO 2: Embalaje Hub --}}
                        @php $h2 = $auditHitos['embalaje_hub'] ?? null; @endphp
                        <div class="pod-audit-card">
                            <div class="p-2 px-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.03);">
                                <span class="fw-bold text-white small"><i class="fas fa-box-open me-1 text-warning"></i> 2. Embalaje Hub Central</span>
                                <span class="badge {{ $h2 ? 'bg-success' : 'bg-secondary' }} bg-opacity-25 text-white" style="font-size:0.68rem;">
                                    {{ $h2 ? 'Completado' : 'Pendiente' }}
                                </span>
                            </div>
                            @if($h2 && $h2->foto_url)
                                <a href="{{ $h2->foto_url }}" target="_blank" title="Clic para ampliar">
                                    <img src="{{ $h2->foto_url }}" class="pod-img-thumb" alt="Foto Hito 2">
                                </a>
                            @else
                                <div class="pod-img-thumb d-flex align-items-center justify-content-center text-white-50 small">
                                    <i class="fas fa-image me-1"></i> Sin foto registrada
                                </div>
                            @endif
                            <div class="p-2 px-3 small text-white-50" style="font-size: 0.78rem;">
                                <div><strong>Operador Hub:</strong> {{ $h2->operador_nombre ?? ($h2->operador->name ?? '—') }}</div>
                                <div><strong>Fecha/Hora:</strong> {{ $h2 ? $h2->created_at->format('d/m/Y H:i') : '—' }}</div>
                                @if($h2 && $h2->observaciones)
                                    <div class="fst-italic mt-1 text-white-50">Obs: {{ $h2->observaciones }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- HITO 4: Entrega Cliente (PoD Clave) --}}
                        @php $h4 = $auditHitos['entrega_cliente'] ?? null; @endphp
                        <div class="pod-audit-card">
                            <div class="p-2 px-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.03);">
                                <span class="fw-bold text-white small"><i class="fas fa-check-circle me-1 text-success"></i> 4. Entrega al Cliente (PoD)</span>
                                <span class="badge {{ $h4 ? 'bg-success' : 'bg-secondary' }} bg-opacity-25 text-white" style="font-size:0.68rem;">
                                    {{ $h4 ? 'Completado' : 'Pendiente' }}
                                </span>
                            </div>
                            @if($h4 && $h4->foto_url)
                                <a href="{{ $h4->foto_url }}" target="_blank" title="Clic para ampliar">
                                    <img src="{{ $h4->foto_url }}" class="pod-img-thumb" alt="Foto Hito 4 PoD">
                                </a>
                            @else
                                <div class="pod-img-thumb d-flex align-items-center justify-content-center text-white-50 small">
                                    <i class="fas fa-image me-1"></i> Sin foto registrada
                                </div>
                            @endif
                            <div class="p-2 px-3 small text-white-50" style="font-size: 0.78rem;">
                                <div><strong>Entregado por:</strong> {{ $h4->operador_nombre ?? ($h4->operador->name ?? 'Motorizado') }}</div>
                                <div><strong>Fecha/Hora:</strong> {{ $h4 ? $h4->created_at->format('d/m/Y H:i') : '—' }}</div>
                                @if($h4 && $h4->distancia_metros !== null)
                                    <div><strong>Rango GPS:</strong> {{ $h4->distancia_metros }}m {{ $h4->dentro_de_rango ? '✅ En rango' : '⚠️ Fuera de rango' }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── COLUMNA 3: Reclamo del Cliente + Evidencia Fotográfica ── --}}
                    <div class="col-12 col-md-4 d-flex flex-column gap-3">
                        {{-- Detalle del Reclamo --}}
                        <div class="pod-audit-card">
                            <div class="p-2 px-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center" style="background: rgba(220, 38, 38, 0.1);">
                                <span class="fw-bold text-white small"><i class="fas fa-exclamation-triangle me-1 text-danger"></i> Reclamo del Cliente</span>
                                <span class="badge rounded-pill" style="background: {{ $auditIncidencia->status_color }}30; color: {{ $auditIncidencia->status_color }}; font-size: 0.68rem;">
                                    {{ $auditIncidencia->status_label }}
                                </span>
                            </div>
                            <div class="p-3 text-white small flex-grow-1" style="font-size: 0.82rem;">
                                <div class="mb-2">
                                    <div class="text-white-50 x-small text-uppercase">Motivo:</div>
                                    <div class="fw-bold text-warning">{{ $auditIncidencia->tipo_label }}</div>
                                    @if($auditIncidencia->submotivo)
                                        <div class="text-white-50">{{ $auditIncidencia->submotivo_label }}</div>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <div class="text-white-50 x-small text-uppercase">Comentario del cliente:</div>
                                    <div class="p-2 rounded mt-1 fst-italic" style="background: rgba(0,0,0,0.3); border-left: 2px solid #ef4444;">
                                        "{{ $auditIncidencia->descripcion ?: 'Sin comentario adicional.' }}"
                                    </div>
                                </div>
                                <div>
                                    <div class="text-white-50 x-small text-uppercase">WhatsApp Verificado:</div>
                                    <div class="text-success fw-bold"><i class="fab fa-whatsapp me-1"></i>+{{ $auditIncidencia->telefono_verificado ?? '—' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Evidencia Fotográfica del Cliente --}}
                        <div class="pod-audit-card">
                            <div class="p-2 px-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.03);">
                                <span class="fw-bold text-white small"><i class="fas fa-camera me-1 text-warning"></i> Evidencia del Cliente</span>
                                <span class="badge {{ $auditIncidencia->foto_signed_url ? 'bg-warning text-dark' : 'bg-secondary' }}" style="font-size:0.68rem;">
                                    {{ $auditIncidencia->foto_signed_url ? 'Adjuntada' : 'Sin foto' }}
                                </span>
                            </div>
                            @if($auditIncidencia->foto_signed_url)
                                <a href="{{ $auditIncidencia->foto_signed_url }}" target="_blank" title="Clic para ampliar">
                                    <img src="{{ $auditIncidencia->foto_signed_url }}" class="pod-img-thumb" alt="Evidencia cliente">
                                </a>
                            @else
                                <div class="pod-img-thumb d-flex align-items-center justify-content-center text-white-50 small">
                                    <i class="fas fa-image me-1"></i> El cliente no adjuntó foto
                                </div>
                            @endif
                            <div class="p-2 px-3 small text-white-50 text-center" style="font-size: 0.75rem;">
                                @if($auditIncidencia->foto_signed_url)
                                    <a href="{{ $auditIncidencia->foto_signed_url }}" target="_blank" class="text-info text-decoration-none">
                                        <i class="fas fa-external-link-alt me-1"></i> Abrir imagen en pestaña nueva
                                    </a>
                                @else
                                    Evidencia no requerida o no proporcionada.
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer de Auditoría --}}
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary border-opacity-25 flex-wrap gap-2">
                    <button wire:click="closeAudit" class="btn btn-outline-light border-0 rounded-pill px-4 py-2" style="background: rgba(255,255,255,0.08); font-size: 0.85rem;">
                        Cerrar Auditoría
                    </button>
                    <button wire:click="closeAudit; openModal({{ $auditIncidencia->id }})" class="btn btn-warning fw-bold rounded-pill px-4 py-2" style="font-size: 0.85rem;">
                        <i class="fas fa-sliders-h me-1"></i> Proceder a Gestionar Reclamo
                    </button>
                </div>

            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL DE GESTIÓN Y RESOLUCIÓN                                  --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    @if($showModal && $selected)
        <div class="modal-overlay-custom" wire:click.self="closeModal">
            <div class="modal-box-custom" style="max-width: 620px;">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                    <div>
                        <div class="fw-bold text-warning" style="font-size: 1.15rem;">
                            {{ $selected->codigo ?? 'INC-#' . $selected->id }}
                        </div>
                        <div class="text-white small">
                            Pedido #{{ $selected->pedido_id }} · Registrado el {{ $selected->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <button wire:click="closeModal" class="btn-close-custom">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Info rápida --}}
                <div class="p-3 rounded-3 mb-3" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">
                    <div class="row g-2 small text-white">
                        <div class="col-6">
                            <span class="text-white-50 d-block x-small">CLIENTE:</span>
                            <strong>{{ $selected->customer->name ?? '—' }}</strong>
                        </div>
                        <div class="col-6">
                            <span class="text-white-50 d-block x-small">WHATSAPP:</span>
                            <strong class="text-success">+{{ $selected->telefono_verificado ?? ($selected->customer->phone ?? '—') }}</strong>
                        </div>
                        <div class="col-12 mt-2">
                            <span class="text-white-50 d-block x-small">MOTIVO REPORTADO:</span>
                            <span class="text-warning fw-semibold">{{ $selected->tipo_label }}</span>
                            @if($selected->submotivo)
                                · <span class="text-white-50">{{ $selected->submotivo_label }}</span>
                            @endif
                        </div>
                        @if($selected->descripcion)
                            <div class="col-12 mt-1">
                                <span class="text-white-50 d-block x-small">COMENTARIO:</span>
                                <div class="p-2 rounded bg-dark text-white-50 fst-italic">"{{ $selected->descripcion }}"</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Formulario de Gestión --}}
                <div class="mb-3">
                    <label class="x-small text-uppercase ls-1 text-white fw-bold mb-1 d-block">Estado de la incidencia</label>
                    <select wire:model="newStatus" class="filter-select-s w-100 py-2">
                        <option value="abierta">🔴 Abierta (Pendiente de atención)</option>
                        <option value="en_revision">🟡 En revisión (Auditando PoD)</option>
                        <option value="reenvio_camino">🟣 Reenvío en camino (Nueva pieza)</option>
                        <option value="reembolsado">🔵 Reembolsado (Pago devuelto)</option>
                        <option value="resuelta">🟢 Resuelta (Caso solucionado)</option>
                        <option value="rechazada">🟠 Rechazada (Evidencia no procede)</option>
                        <option value="cerrada">⚫ Cerrada (Archivada)</option>
                    </select>
                </div>

                {{-- Nota visible para el cliente --}}
                <div class="mb-3">
                    <label class="x-small text-uppercase ls-1 text-info fw-bold mb-1 d-block">
                        <i class="fas fa-eye me-1"></i> Nota visible para el cliente (en su portal)
                    </label>
                    <textarea wire:model="notaCliente" rows="2"
                        placeholder="Ej: Estimado cliente, validamos el caso y hemos aprobado el reenvío inmediato de su repuesto."
                        class="form-control text-white border-secondary"
                        style="background: rgba(59,130,246,0.06); font-size: 0.85rem; border-color: rgba(59,130,246,0.3) !important;"
                    ></textarea>
                    <small class="text-white-50" style="font-size: 0.72rem;">Esta nota será leída por el cliente al consultar el estado de su reclamo.</small>
                </div>

                {{-- Resolución interna / Auditoría --}}
                <div class="mb-3">
                    <label class="x-small text-uppercase ls-1 text-white fw-bold mb-1 d-block">
                        <i class="fas fa-lock me-1 text-warning"></i> Resolución interna / Bitácora
                    </label>
                    <textarea wire:model="resolucion" rows="2"
                        placeholder="Nota interna confidencial para el equipo de soporte..."
                        class="form-control bg-transparent text-white border-secondary"
                        style="font-size: 0.85rem;"
                    ></textarea>
                </div>

                {{-- Historial de Estados (Auditoría) --}}
                @if(!empty($selected->historial_estados) && is_array($selected->historial_estados))
                    <div class="mb-3">
                        <label class="x-small text-uppercase ls-1 text-white-50 fw-bold mb-2 d-block">Historial de cambios</label>
                        <div class="p-2 px-3 rounded bg-dark border border-secondary border-opacity-25" style="max-height: 120px; overflow-y: auto;">
                            @foreach(array_reverse($selected->historial_estados) as $h)
                                <div class="timeline-item small text-white-50" style="font-size: 0.74rem;">
                                    <div class="timeline-dot"></div>
                                    <strong class="text-white">{{ ucfirst($h['status'] ?? 'cambio') }}</strong>
                                    · <span class="text-white-50">{{ !empty($h['timestamp']) ? \Carbon\Carbon::parse($h['timestamp'])->format('d/m H:i') : '' }}</span>
                                    @if(!empty($h['user_name']))
                                        · por <span class="text-info">{{ $h['user_name'] }}</span>
                                    @endif
                                    @if(!empty($h['nota']))
                                        <div class="fst-italic text-white-50">"{{ $h['nota'] }}"</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Botones de acción --}}
                <div class="d-flex gap-2 mt-4 pt-2 border-top border-secondary border-opacity-25">
                    <button wire:click="saveGestion" class="btn btn-success fw-bold flex-grow-1 py-2 rounded-pill shadow-none"
                        style="background: #00d68f; color: #0a1628; border: none; font-size: 0.88rem;">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                    <button wire:click="closeModal" class="btn btn-outline-light border-0 py-2 px-4 rounded-pill"
                        style="background: rgba(255,255,255,0.08); font-size: 0.85rem;">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
