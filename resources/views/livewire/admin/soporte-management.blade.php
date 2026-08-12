<div>
    {{-- SOPORTE MANAGEMENT --}}
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
    </style>

    <div class="px-3 px-md-4 py-4">

        {{-- ── HEADER ────────────────────────────────────────────── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h4 class="text-white fw-bold mb-0">
                    <i class="fas fa-headset me-2" style="color:#ff3b5c;"></i> Soporte · Incidencias
                </h4>
                <p class="text-white small mb-0">Bandeja de incidencias reportadas por clientes</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge rounded-pill px-3 py-2"
                    style="background:#ff3b5c20; color:#ff3b5c; font-size:.8rem;">
                    {{ $totalAbiertas }} Abiertas
                </span>
                <span class="badge rounded-pill px-3 py-2"
                    style="background:#fbbf2420; color:#fbbf24; font-size:.8rem;">
                    {{ $totalEnRevision }} En revisión
                </span>
                <span class="badge rounded-pill px-3 py-2"
                    style="background:#00d68f20; color:#00d68f; font-size:.8rem;">
                    {{ $totalResueltas }} Resueltas
                </span>
            </div>
        </div>

        {{-- ── STATS ─────────────────────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card-soporte">
                    <div class="stat-num" style="color:#ff3b5c;">{{ $totalAbiertas }}</div>
                    <div class="text-white small mt-1">Abiertas</div>
                    <div class="mt-2"><i class="fas fa-inbox" style="color:#ff3b5c; opacity:.5;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-soporte">
                    <div class="stat-num" style="color:#fbbf24;">{{ $totalEnRevision }}</div>
                    <div class="text-white small mt-1">En revisión</div>
                    <div class="mt-2"><i class="fas fa-search" style="color:#fbbf24; opacity:.5;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-soporte">
                    <div class="stat-num" style="color:#00d68f;">{{ $totalResueltas }}</div>
                    <div class="text-white small mt-1">Resueltas</div>
                    <div class="mt-2"><i class="fas fa-check-circle" style="color:#00d68f; opacity:.5;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-soporte">
                    <div class="stat-num text-white">{{ $totalAbiertas + $totalEnRevision + $totalResueltas }}</div>
                    <div class="text-white small mt-1">Total</div>
                    <div class="mt-2"><i class="fas fa-list" style="color:#6B7A99; opacity:.5;"></i></div>
                </div>
            </div>
        </div>

        {{-- ── FILTROS ────────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            <input wire:model.live="search" type="text" placeholder="Buscar cliente o pedido #..."
                class="filter-select-s flex-grow-1" style="min-width: 200px;">
            <select wire:model.live="statusFilter" class="filter-select-s">
                <option value="">Todos los estados</option>
                <option value="abierta">Abiertas</option>
                <option value="en_revision">En revisión</option>
                <option value="resuelta">Resueltas</option>
                <option value="cerrada">Cerradas</option>
            </select>
            <select wire:model.live="tipoFilter" class="filter-select-s">
                <option value="">Todos los tipos</option>
                <option value="no_llego">No llegó el pedido</option>
                <option value="producto_incorrecto">Producto incorrecto</option>
                <option value="producto_defectuoso">Producto defectuoso</option>
                <option value="cobro_incorrecto">Cobro incorrecto</option>
                <option value="otro">Otro</option>
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

        {{-- ── TABLA ──────────────────────────────────────────────── --}}
        <div class="card border-0" style="background:rgba(255,255,255,0.03); border-radius:14px; overflow:hidden;">
            @forelse($incidencias as $inc)
                <div class="inc-row px-4 py-3 d-flex flex-wrap align-items-center gap-3">

                    {{-- Status dot --}}
                    <div class="flex-shrink-0">
                        <span class="d-inline-block rounded-circle" style="width:10px; height:10px; background:{{ $inc->status_color }};
                                                                   box-shadow: 0 0 6px {{ $inc->status_color }}80;"></span>
                    </div>

                    {{-- Info principal --}}
                    <div class="flex-grow-1" style="min-width: 200px;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold text-white small">#{{ $inc->pedido_id }}</span>
                            <span class="badge-tipo text-white">{{ $inc->tipo_label }}</span>
                        </div>
                        <div class="text-white x-small">
                            {{ $inc->customer->name ?? 'Cliente' }} ·
                            {{ $inc->created_at->diffForHumans() }}
                        </div>
                        @if($inc->descripcion)
                            <div class="text-white-50 small mt-1"
                                style="font-size:0.8rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:400px;">
                                "{{ $inc->descripcion }}"
                            </div>
                        @endif
                    </div>

                    {{-- Estado --}}
                    <div class="text-center" style="min-width:110px;">
                        <span class="badge rounded-pill px-3 py-1"
                            style="background:{{ $inc->status_color }}20; color:{{ $inc->status_color }}; font-size:0.75rem;">
                            {{ $inc->status_label }}
                        </span>
                        @if($inc->resolved_at)
                            <div class="x-small text-white mt-1">{{ $inc->resolved_at->format('d/m/y') }}</div>
                        @endif
                    </div>

                    {{-- Acción --}}
                    <div class="flex-shrink-0">
                        <button wire:click="openModal({{ $inc->id }})" class="btn btn-sm px-3 rounded-pill fw-medium"
                            style="background:rgba(255,255,255,0.08); color:#fff; border:1px solid rgba(255,255,255,0.12); font-size:0.8rem;">
                            <i class="fas fa-eye me-1"></i> Gestionar
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-3x mb-3" style="color:rgba(255,255,255,0.1);"></i>
                    <p class="text-white">No hay incidencias que coincidan con los filtros.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="mt-4">{{ $incidencias->links('vendor.pagination.custom-repuestofijo') }}</div>
    </div>

    {{-- ── MODAL GESTIÓN ─────────────────────────────────────── --}}
    @if($showModal && $selected)
        <div class="modal-overlay-custom" wire:click.self="closeModal">
            <div class="modal-box-custom" style="max-width:560px;">

                {{-- Header --}}
                <div class="modal-header-custom d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h5 class="text-white fw-bold mb-1">
                            <i class="fas fa-headset me-2" style="color:#fbbf24;"></i>
                            Incidencia #{{ $selected->id }}
                        </h5>
                        <div class="text-white small">Pedido #{{ $selected->pedido_id }}
                            · {{ $selected->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <button wire:click="closeModal" class="btn-close-custom">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Datos del cliente --}}
                <div class="info-box mb-3">
                    <div class="x-small text-uppercase ls-1 text-white mb-2">Cliente</div>
                    <div class="fw-bold text-white">{{ $selected->customer->name ?? '—' }}</div>
                    <div class="small text-white">{{ $selected->customer->email ?? '—' }}</div>
                </div>

                {{-- Tipo + Descripción --}}
                <div class="info-box mb-3">
                    <div class="x-small text-uppercase ls-1 text-white mb-2">Tipo de incidencia</div>
                    <div class="fw-bold text-white mb-2">{{ $selected->tipo_label }}</div>
                    @if($selected->descripcion)
                        <div class="small text-white-50 p-3 rounded"
                            style="background:rgba(0,0,0,0.2); border-left:3px solid #fbbf24;">
                            "{{ $selected->descripcion }}"
                        </div>
                    @endif
                </div>

                {{-- Gestión --}}
                <div class="mb-3">
                    <label class="x-small text-uppercase ls-1 text-white mb-2 d-block">Estado</label>
                    <select wire:model="newStatus" class="filter-select-s w-100">
                        <option value="abierta">🔴 Abierta</option>
                        <option value="en_revision">🟡 En revisión</option>
                        <option value="resuelta">🟢 Resuelta</option>
                        <option value="cerrada">⚫ Cerrada</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="x-small text-uppercase ls-1 text-white mb-2 d-block">Resolución / Nota interna</label>
                    <textarea wire:model="resolucion" rows="3" placeholder="Describe cómo se resolvió o agrega una nota..."
                        class="form-control bg-transparent text-white border-secondary"
                        style="resize:vertical; font-size:0.9rem;"></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button wire:click="saveGestion" class="btn fw-bold flex-grow-1 py-2"
                        style="background:#00d68f; color:#0a1628; border:none; border-radius:10px;">
                        <i class="fas fa-save me-2"></i> Guardar cambios
                    </button>
                    <button wire:click="closeModal" class="btn py-2 px-4"
                        style="background:rgba(255,255,255,0.08); color:#fff; border:1px solid rgba(255,255,255,0.12); border-radius:10px;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>