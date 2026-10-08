<div>
    {{-- RECLAMACIONES MANAGEMENT --}}
    <style>
        .stat-card-rec {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card-rec:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,.3);
        }
        .rec-stat-num {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }
        .rec-row {
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: background .15s;
        }
        .rec-row:hover { background: rgba(255,255,255,0.04); }

        .filter-rec {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: #fff;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 0.85rem;
        }
        .filter-rec option { background: #1a2535; color: #fff; }

        .estado-badge {
            font-size: 0.72rem;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
        }
        .estado-pendiente  { background:#ff3b5c20; color:#ff3b5c; }
        .estado-en_revision { background:#fbbf2420; color:#fbbf24; }
        .estado-respondida { background:#00d68f20; color:#00d68f; }
        .estado-cerrada    { background:rgba(255,255,255,0.06); color:#6B7A99; }

        .tipo-badge {
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 20px;
            background: rgba(255,255,255,0.07);
            color: #aaa;
        }
        .code-badge {
            font-family: 'Courier New', monospace;
            font-size: 0.78rem;
            color: #ff3b5c;
            background: rgba(255,59,92,0.08);
            border-radius: 6px;
            padding: 2px 8px;
        }
        /* modal */
        .modal-overlay-rec {
            position: fixed; inset: 0; z-index: 3500 !important;
            background: rgba(0,0,0,.75);
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
        }
        .modal-box-rec {
            background: #1a2535;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .info-block-rec {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: .75rem;
        }
        .info-label-rec {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6B7A99;
            margin-bottom: .25rem;
        }
        .btn-close-rec {
            background: rgba(255,255,255,0.08);
            border: none; border-radius: 50%;
            width: 32px; height: 32px;
            color: #fff; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .btn-close-rec:hover { background: rgba(255,59,92,0.3); }
    </style>

    <div class="px-3 px-md-4 py-4">

        {{-- ── HEADER ─────────────────────────────────────────── --}}
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h4 class="text-white fw-bold mb-0">
                    <i class="fas fa-book-open me-2" style="color:#ff3b5c;"></i>
                    Libro de Reclamaciones
                </h4>
                <p class="text-white-50 small mb-0">Reclamaciones y quejas recibidas del portal público</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="estado-badge estado-pendiente">{{ $totalPendientes }} Pendientes</span>
                <span class="estado-badge estado-en_revision">{{ $totalEnRevision }} En revisión</span>
                <span class="estado-badge estado-respondida">{{ $totalRespondidas }} Respondidas</span>
                <span class="estado-badge estado-cerrada">{{ $totalCerradas }} Cerradas</span>
            </div>
        </div>

        {{-- ── STATS ──────────────────────────────────────────── --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card-rec">
                    <div class="rec-stat-num" style="color:#ff3b5c;">{{ $totalPendientes }}</div>
                    <div class="text-white-50 small mt-1">Pendientes</div>
                    <div class="mt-2"><i class="fas fa-clock" style="color:#ff3b5c; opacity:.4;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-rec">
                    <div class="rec-stat-num" style="color:#fbbf24;">{{ $totalEnRevision }}</div>
                    <div class="text-white-50 small mt-1">En revisión</div>
                    <div class="mt-2"><i class="fas fa-search" style="color:#fbbf24; opacity:.4;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-rec">
                    <div class="rec-stat-num" style="color:#00d68f;">{{ $totalRespondidas }}</div>
                    <div class="text-white-50 small mt-1">Respondidas</div>
                    <div class="mt-2"><i class="fas fa-check-circle" style="color:#00d68f; opacity:.4;"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card-rec">
                    <div class="rec-stat-num text-white">{{ $totalPendientes + $totalEnRevision + $totalRespondidas + $totalCerradas }}</div>
                    <div class="text-white-50 small mt-1">Total</div>
                    <div class="mt-2"><i class="fas fa-list" style="color:#6B7A99; opacity:.4;"></i></div>
                </div>
            </div>
        </div>

        {{-- ── FILTROS ─────────────────────────────────────────── --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            <input wire:model.live="search" type="text" placeholder="Buscar nombre, email, código, pedido..."
                class="filter-rec flex-grow-1" style="min-width:220px;">

            <select wire:model.live="estadoFilter" class="filter-rec">
                <option value="">Todos los estados</option>
                <option value="pendiente">🔴 Pendiente</option>
                <option value="en_revision">🟡 En revisión</option>
                <option value="respondida">🟢 Respondida</option>
                <option value="cerrada">⚫ Cerrada</option>
            </select>

            <select wire:model.live="tipoFilter" class="filter-rec">
                <option value="">Todos los tipos</option>
                <option value="reclamacion">Reclamación</option>
                <option value="queja">Queja</option>
            </select>

            <div class="d-flex align-items-center gap-2 text-white ms-md-auto">
                <span class="small opacity-50">Mostrar</span>
                <select wire:model.live="perPage" class="filter-rec" style="padding:4px 10px;">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="small opacity-50">registros</span>
            </div>
        </div>

        {{-- ── TABLA ───────────────────────────────────────────── --}}
        <div class="card border-0" style="background:rgba(255,255,255,0.03); border-radius:14px; overflow:hidden;">
            @forelse($rows as $rec)
                <div class="rec-row px-4 py-3 d-flex flex-wrap align-items-center gap-3" wire:key="rec-row-{{ $rec->id }}">

                    {{-- Estado dot --}}
                    <div class="flex-shrink-0">
                        @php
                            $dot = match($rec->estado) {
                                'pendiente'   => '#ff3b5c',
                                'en_revision' => '#fbbf24',
                                'respondida'  => '#00d68f',
                                default       => '#6B7A99',
                            };
                        @endphp
                        <span class="d-inline-block rounded-circle"
                            style="width:10px;height:10px;background:{{ $dot }};box-shadow:0 0 6px {{ $dot }}80;"></span>
                    </div>

                    {{-- Info principal --}}
                    <div class="flex-grow-1" style="min-width:200px;">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <span class="code-badge">{{ $rec->code }}</span>
                            @if(\App\Livewire\Admin\ReclamacionManagement::isQueja($rec->tipo_reclamacion))
                                <span class="badge rounded-pill" style="background:#fbbf2420;color:#fbbf24;font-size:0.7rem;">Queja</span>
                            @else
                                <span class="badge rounded-pill" style="background:#ff3b5c20;color:#ff3b5c;font-size:0.7rem;">Reclamación</span>
                            @endif
                            <span class="tipo-badge">{{ \App\Livewire\Admin\ReclamacionManagement::formatTipo($rec->tipo_reclamacion) }}</span>
                        </div>
                        <div class="fw-bold text-white small">{{ $rec->nombre }}</div>
                        <div class="text-white-50" style="font-size:.8rem;">
                            {{ $rec->email }} · {{ $rec->telefono }}
                            @if($rec->num_doc)
                                · <span class="text-white-50">{{ strtoupper($rec->tipo_doc ?? 'Doc') }}: {{ $rec->num_doc }}</span>
                            @endif
                            @if($rec->num_pedido)
                                · <span style="color:#00d68f;">Pedido #{{ $rec->num_pedido }}</span>
                            @endif
                            · {{ \Carbon\Carbon::parse($rec->created_at)->diffForHumans() }}
                        </div>
                        @if($rec->descripcion)
                            <div class="text-white-50 mt-1"
                                style="font-size:.78rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:480px;">
                                "{{ $rec->descripcion }}"
                            </div>
                        @endif
                    </div>

                    {{-- Estado badge --}}
                    <div class="text-center" style="min-width:110px;">
                        <span class="estado-badge estado-{{ $rec->estado }}">
                            {{ match($rec->estado) {
                                'pendiente'   => 'Pendiente',
                                'en_revision' => 'En revisión',
                                'respondida'  => 'Respondida',
                                'cerrada'     => 'Cerrada',
                                default       => $rec->estado,
                            } }}
                        </span>
                        @if($rec->respondida_at)
                            <div class="text-white-50 mt-1" style="font-size:.72rem;">
                                {{ \Carbon\Carbon::parse($rec->respondida_at)->format('d/m/y') }}
                            </div>
                        @endif
                    </div>

                    {{-- Acción --}}
                    <div class="flex-shrink-0">
                        <button type="button" wire:click="openModal({{ $rec->id }})" wire:key="rec-btn-{{ $rec->id }}"
                            class="btn btn-sm px-3 rounded-pill fw-medium"
                            style="background:rgba(255,255,255,0.08);color:#fff;border:1px solid rgba(255,255,255,0.12);font-size:.8rem;">
                            <i class="fas fa-eye me-1"></i> Ver / Gestionar
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x mb-3" style="color:rgba(255,255,255,0.1);"></i>
                    <p class="text-white-50">No hay reclamaciones que coincidan con los filtros.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="mt-4">{{ $rows->links('vendor.pagination.custom-repuestofijo') }}</div>

    </div>

    {{-- ── MODAL DETALLE / GESTIÓN ────────────────────────────── --}}
    @if($showModal && $selected)
        <div class="modal-overlay-rec" style="position:fixed;inset:0;z-index:3500 !important;background:rgba(0,0,0,0.75);display:flex;align-items:center;justify-content:center;padding:1rem;" wire:click.self="closeModal">
            <div class="modal-box-rec" style="max-width:680px;">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h5 class="text-white fw-bold mb-0">
                                <i class="fas fa-book-open me-2" style="color:#ff3b5c;"></i>
                                Reclamación
                            </h5>
                            <span class="code-badge fs-6">{{ $selected['code'] }}</span>
                            @if(\App\Livewire\Admin\ReclamacionManagement::isQueja($selected['tipo_reclamacion']))
                                <span class="badge rounded-pill" style="background:#fbbf2420;color:#fbbf24;font-size:0.75rem;">Queja</span>
                            @else
                                <span class="badge rounded-pill" style="background:#ff3b5c20;color:#ff3b5c;font-size:0.75rem;">Reclamación</span>
                            @endif
                        </div>
                        <div class="text-white-50 small">
                            Recibida el {{ \Carbon\Carbon::parse($selected['created_at'])->format('d/m/Y \a \l\a\s H:i') }}
                            · IP: {{ $selected['ip_address'] ?? '—' }}
                        </div>
                    </div>
                    <button wire:click="closeModal" class="btn-close-rec" title="Cerrar">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- 1. DATOS DEL RECLAMANTE --}}
                <div class="info-block-rec">
                    <div class="info-label-rec"><i class="fas fa-user me-1" style="color:#ff3b5c;"></i> Datos del reclamante</div>
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                        <div class="fw-bold text-white fs-6">{{ $selected['nombre'] }}</div>
                        <div>
                            @if($selected['num_doc'])
                                <span class="badge" style="background:rgba(255,255,255,0.08);color:#fff;border:1px solid rgba(255,255,255,0.15);">
                                    <i class="fas fa-id-card me-1 opacity-50"></i>
                                    {{ strtoupper($selected['tipo_doc'] ?? 'DOC') }}: <strong>{{ $selected['num_doc'] }}</strong>
                                </span>
                            @else
                                <span class="badge text-white-50" style="background:rgba(255,255,255,0.04);">
                                    <i class="fas fa-id-card me-1 opacity-25"></i> Documento: No indicado
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 text-white-50 small">
                        <div>
                            <i class="fas fa-envelope me-1 opacity-50"></i>
                            <a href="mailto:{{ $selected['email'] }}" class="text-decoration-none" style="color:#7dd3fc;">
                                {{ $selected['email'] }}
                            </a>
                        </div>
                        <div>
                            <i class="fas fa-phone me-1 opacity-50"></i>
                            <span>{{ $selected['telefono'] }}</span>
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $selected['telefono'] ?? '');
                                if (strlen($cleanPhone) === 9) { $cleanPhone = '51' . $cleanPhone; }
                            @endphp
                            @if(strlen($cleanPhone) >= 9)
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" rel="noopener noreferrer"
                                    class="badge ms-1 text-decoration-none" style="background:#25D36625;color:#25D366;">
                                    <i class="fab fa-whatsapp"></i> Chat
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 2. DATOS DEL PEDIDO --}}
                <div class="info-block-rec">
                    <div class="info-label-rec"><i class="fas fa-box me-1" style="color:#ff3b5c;"></i> Datos del pedido</div>
                    <div class="row g-2 align-items-center">
                        <div class="col-sm-6">
                            <span class="text-white-50 small d-block">Número de pedido:</span>
                            @if(!empty($selected['num_pedido']))
                                <strong class="text-white" style="color:#00d68f !important;">#{{ $selected['num_pedido'] }}</strong>
                            @else
                                <span class="text-white-50 fst-italic small">No aplica / No disponible</span>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <span class="text-white-50 small d-block">Fecha aproximada del pedido:</span>
                            @if(!empty($selected['fecha_pedido']))
                                <strong class="text-white">{{ \Carbon\Carbon::parse($selected['fecha_pedido'])->format('d/m/Y') }}</strong>
                            @else
                                <span class="text-white-50 fst-italic small">No especificada</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 3. DETALLE DE LA RECLAMACIÓN --}}
                <div class="info-block-rec">
                    <div class="info-label-rec"><i class="fas fa-exclamation-circle me-1" style="color:#ff3b5c;"></i> Detalle del caso</div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <span class="text-white-50 small d-block">Motivo:</span>
                            <span class="fw-semibold text-white">
                                {{ \App\Livewire\Admin\ReclamacionManagement::formatTipo($selected['tipo_reclamacion']) }}
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-white-50 small d-block">Solución esperada por el cliente:</span>
                            <span class="fw-semibold" style="color:#fbbf24;">
                                {{ \App\Livewire\Admin\ReclamacionManagement::formatSolucion($selected['solucion_esperada']) }}
                            </span>
                        </div>
                    </div>

                    <span class="text-white-50 small d-block mb-1">Descripción de lo ocurrido:</span>
                    <div class="text-white small p-3 rounded"
                        style="background:rgba(0,0,0,.35); border-left:3px solid #ff3b5c; line-height:1.6; white-space:pre-wrap; max-height:220px; overflow-y:auto;">
                        {{ $selected['descripcion'] }}
                    </div>
                </div>

                {{-- 4. GESTIÓN DEL ESTADO --}}
                <div class="mb-3">
                    <label class="info-label-rec d-block mb-2">Estado del caso</label>
                    <select wire:model="nuevoEstado" class="filter-rec w-100 py-2">
                        <option value="pendiente">🔴 Pendiente de revisión</option>
                        <option value="en_revision">🟡 En revisión (en proceso de investigación)</option>
                        <option value="respondida">🟢 Respondida (se enviará email con la resolución)</option>
                        <option value="cerrada">⚫ Cerrada (caso finalizado)</option>
                    </select>
                </div>

                {{-- 5. RESPUESTA AL CLIENTE --}}
                <div class="mb-4">
                    <label class="info-label-rec d-block mb-2">
                        Respuesta al cliente
                        <span class="text-white-50 ms-1" style="font-size:.7rem;">
                            (se enviará automáticamente a <strong>{{ $selected['email'] }}</strong> cuando el estado sea "Respondida")
                        </span>
                    </label>
                    <textarea wire:model="respuesta" rows="4"
                        placeholder="Escribe aquí la respuesta formal y detallada para el cliente..."
                        class="form-control bg-transparent text-white border-secondary"
                        style="resize:vertical;font-size:.9rem;"></textarea>
                    @if($selected['respondida_at'])
                        <div class="text-white-50 mt-1" style="font-size:0.75rem;">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            Respondida previamente el {{ \Carbon\Carbon::parse($selected['respondida_at'])->format('d/m/Y H:i') }}
                        </div>
                    @endif
                </div>

                {{-- 6. ACCIONES --}}
                <div class="d-flex gap-2">
                    <button wire:click="guardar" wire:loading.attr="disabled"
                        class="btn fw-bold flex-grow-1 py-2"
                        style="background:#00d68f;color:#0a1628;border:none;border-radius:10px;">
                        <span wire:loading.remove wire:target="guardar">
                            <i class="fas fa-save me-2"></i> Guardar cambios
                        </span>
                        <span wire:loading wire:target="guardar">
                            <i class="fas fa-spinner fa-spin me-2"></i> Guardando...
                        </span>
                    </button>
                    <button wire:click="closeModal"
                        class="btn py-2 px-4"
                        style="background:rgba(255,255,255,0.08);color:#fff;border:1px solid rgba(255,255,255,0.12);border-radius:10px;">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
