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

        /* Modal fixes */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(5px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-content-custom {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            width: 100%;
            max-width: 860px;
            padding: 2rem 2.5rem;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .info-box {
            background: var(--surface2);
            border: 1px solid var(--border);
            padding: 1.2rem;
            border-radius: 15px;
            color: #fff;
        }

        .btn-action {
            background: var(--surface3);
            border: 1px solid var(--border);
            padding: 9px;
            border-radius: 10px;
            transition: 0.2s;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff !important;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .btn-blue {
            background: rgba(59, 130, 246, 0.45);
        }

        .btn-blue:hover {
            border-color: #3b82f6;
        }

        .btn-green {
            background: rgba(16, 185, 129, 0.45);
        }

        .btn-green:hover {
            border-color: #10b981;
        }
    </style>

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h2 fw-bold text-white mb-1" style="font-family: 'Syne', sans-serif; letter-spacing: -0.5px;">Gestión
            de Pedidos</h1>
        <p class="text-white text-sm opacity-70">Administra los pedidos de ZettaBot y su estado de entrega.</p>
    </div>

    {{-- Admin Stats Dashboard --}}
    @if(auth()->user() && auth()->user()->isAdmin() && $stats)
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="p-4 rounded-4 d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.02) 100%); border: 1px solid rgba(59, 130, 246, 0.15); border-radius: 20px;">
                    <div>
                        <div class="text-white extra-small text-uppercase ls-1 mb-1" style="font-size: 0.7rem; font-weight:700; letter-spacing: 0.5px;">Ventas Acumuladas (Ingresos)</div>
                        <div class="h3 fw-bold text-white mb-0">S/ {{ number_format($stats['ingresos'], 2) }}</div>
                        <span class="extra-small text-white" style="font-size: 0.65rem;">Solo pedidos pagados</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle" style="width: 50px; height: 50px;">
                        <i class="fas fa-wallet text-primary fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.02) 100%); border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 20px;">
                    <div>
                        <div class="text-white extra-small text-uppercase ls-1 mb-1" style="font-size: 0.7rem; font-weight:700; letter-spacing: 0.5px;">Costo de Proveedores (Egresos)</div>
                        <div class="h3 fw-bold text-white mb-0">S/ {{ number_format($stats['egresos'], 2) }}</div>
                        <span class="extra-small text-white" style="font-size: 0.65rem;">Precio base de repuestos</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-circle" style="width: 50px; height: 50px;">
                        <i class="fas fa-hand-holding-usd text-danger fs-4"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.02) 100%); border: 1px solid rgba(16, 185, 129, 0.15); border-radius: 20px;">
                    <div>
                        <div class="text-success extra-small text-uppercase ls-1 mb-1" style="font-size: 0.7rem; font-weight:700; letter-spacing: 0.5px;">Comisión Neta (Ganancia 10%)</div>
                        <div class="h3 fw-bold text-success mb-0">S/ {{ number_format($stats['ganancia'], 2) }}</div>
                        <span class="extra-small text-success" style="font-size: 0.65rem; font-weight: 600;"><i class="fas fa-arrow-up me-1"></i>Margen del 10%</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-circle" style="width: 50px; height: 50px;">
                        <i class="fas fa-chart-line text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="page-card">
        {{-- Toolbar --}}
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 pb-4">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" class="search-input ps-5"
                        placeholder="Buscar pedido o cliente..." style="min-width: 300px;">
                </div>

                <select wire:model.live="statusFilter" class="filter-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="por_confirmar">Por confirmar</option>
                    <option value="pagado">Pagado</option>
                    <option value="en_preparacion">Preparación</option>
                    <option value="en_camino">En camino</option>
                    <option value="entregado">Entregado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div class="d-flex align-items-center gap-2 text-white">
                <span class="small opacity-50">Mostrar</span>
                <select wire:model.live="perPage"
                    class="form-select form-select-sm bg-dark text-white border-white border-opacity-10"
                    style="width: auto; border-radius: 8px;">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="small opacity-50">registros</span>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Cliente</th>
                        <th>Tipo Envío</th>
                        <th>Destino</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td>
                                <div class="fw-bold">#{{ $pedido->id }}</div>
                                <div class="extra-small opacity-50">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-white bg-opacity-5 d-flex align-items-center justify-content-center"
                                        style="width: 35px; height: 35px;">
                                        <i class="fas fa-user-circle opacity-50"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">{{ $pedido->customer->name ?? 'N/A' }}</div>
                                        <div class="extra-small opacity-50">{{ $pedido->telefono_contacto }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($pedido->tipo_envio === 'Lima')
                                    <span class="badge"
                                        style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3);">
                                        <i class="fas fa-motorcycle me-1"></i> Lima
                                    </span>
                                @else
                                    <span class="badge"
                                        style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">
                                        <i class="fas fa-bus me-1"></i> Provincia
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="small">{{ $pedido->distrito }}</div>
                                <div class="extra-small opacity-50 text-truncate" style="max-width: 150px;">
                                    {{ $pedido->direccion }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">S/ {{ number_format($pedido->total, 2) }}</div>
                                <div class="extra-small opacity-50">{{ $pedido->metodo_pago }}</div>
                            </td>
                            <td>
                                <select wire:change="updateStatus({{ $pedido->id }}, $event.target.value)"
                                    class="form-select form-select-sm bg-dark text-white border-white border-opacity-10 rounded-pill"
                                    style="font-size: 0.75rem;">
                                    <option value="pendiente" {{ $pedido->status == 'pendiente' ? 'selected' : '' }}>Pendiente
                                    </option>
                                    <option value="por_confirmar" {{ $pedido->status == 'por_confirmar' ? 'selected' : '' }}>
                                        Por confirmar</option>
                                    <option value="pagado" {{ $pedido->status == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                    <option value="en_preparacion" {{ $pedido->status == 'en_preparacion' ? 'selected' : '' }}>Preparación</option>
                                    <option value="en_camino" {{ $pedido->status == 'en_camino' ? 'selected' : '' }}>En camino
                                    </option>
                                    <option value="entregado" {{ $pedido->status == 'entregado' ? 'selected' : '' }}>Entregado
                                    </option>
                                    <option value="cancelado" {{ $pedido->status == 'cancelado' ? 'selected' : '' }}>Cancelado
                                    </option>
                                </select>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button wire:click="openDetail({{ $pedido->id }})" class="btn-action btn-blue"
                                        title="Ver Detalle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button wire:click="sendInvoice({{ $pedido->id }})" class="btn-action btn-green"
                                        title="Enviar factura por correo">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-box-open fs-1 opacity-20 mb-3"></i>
                                <p class="opacity-50">No hay pedidos registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pedidos->links('vendor.pagination.custom-repuestofijo') }}
        </div>
    </div>

    {{-- Detail Modal --}}
    @if($showDetailModal && $selectedPedido)
        <div class="modal-overlay" wire:click.self="closeDetail">
            <div class="modal-content-custom">
                <div
                    class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-white border-opacity-10">
                    <h3 class="fw-bold text-white mb-0">Detalle del Pedido #{{ $selectedPedido->id }}</h3>
                    <button type="button" class="btn btn-link text-white p-0 text-decoration-none" wire:click="closeDetail">
                        <i class="fas fa-times fs-4"></i>
                    </button>
                </div>

                <div class="row g-4 mb-4">
                    <!-- INFORMACIÓN DEL CLIENTE -->
                    <div class="col-md-6">
                        <h6 class="text-white extra-small text-uppercase ls-1 mb-3">Información del Cliente</h6>
                        <div class="info-box">
                            <div class="fw-bold mb-2">{{ $selectedPedido->customer->name }}</div>
                            <div class="small opacity-75 mb-1"><i
                                    class="fas fa-envelope me-2"></i>{{ $selectedPedido->customer->email }}</div>
                            <div class="small opacity-75 mb-1"><i
                                    class="fas fa-phone me-2"></i>{{ $selectedPedido->telefono_contacto }}</div>
                            <div class="small opacity-75"><i
                                    class="fas fa-map-marker-alt me-2"></i>{{ $selectedPedido->distrito }},
                                {{ $selectedPedido->direccion }}
                            </div>
                        </div>

                        @if($selectedPedido->tipo_envio === 'Provincia' && $selectedPedido->clave_secreta)
                            <div
                                class="mt-3 p-3 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-20 d-flex justify-content-between align-items-center">
                                <span class="text-warning small fw-bold"><i class="fas fa-key me-2"></i>CLAVE SECRETA:</span>
                                <span
                                    class="h4 text-warning fw-bold mb-0 letter-spacing-2">{{ $selectedPedido->clave_secreta_decrypted }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- PAGO -->
                    <div class="col-md-6">
                        <h6 class="text-white extra-small text-uppercase ls-1 mb-3">Pago</h6>
                        <div class="info-box">
                            <div class="d-flex align-items-center gap-3">
                                @if($selectedPedido->metodo_pago === 'Culqi')
                                    <div class="bg-white rounded p-1 px-2" style="height: 35px;">
                                        <img src="{{ asset('assets/img/logos/culqi_logo.png') }}" alt="Culqi"
                                            style="height: 100%;">
                                    </div>
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded"
                                        style="width:35px; height:35px; background:rgba(255,255,255,.07);">
                                        <i class="fas fa-money-bill-wave" style="color:#00d68f;"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="small fw-bold">{{ $selectedPedido->metodo_pago ?? 'Sin especificar' }}
                                    </div>
                                    @if($selectedPedido->status === 'pagado' && $selectedPedido->payment_confirmed_at)
                                        <div class="extra-small text-success">
                                            <i class="fas fa-check-circle me-1"></i>Pago confirmado —
                                            {{ $selectedPedido->payment_confirmed_at->format('d/m/Y H:i') }}
                                        </div>
                                    @elseif($selectedPedido->status === 'por_confirmar')
                                        <div class="extra-small" style="color:#fbbf24;">
                                            <i class="fas fa-hourglass-half me-1"></i>Pendiente de pago
                                        </div>
                                    @elseif($selectedPedido->status === 'cancelado')
                                        <div class="extra-small" style="color:#ff3b5c;">
                                            <i class="fas fa-times-circle me-1"></i>Pedido cancelado
                                            @if($selectedPedido->cancellation_reason)
                                                · {{ $selectedPedido->cancellation_reason }}
                                            @endif
                                        </div>
                                    @else
                                        <div class="extra-small" style="color:var(--muted);">
                                            <i class="fas fa-clock me-1"></i>En proceso
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ITEMS DEL PEDIDO A ANCHO COMPLETO -->
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <h6 class="text-white extra-small text-uppercase ls-1 mb-3">Resumen de Productos</h6>
                        <div class="info-box p-0 overflow-hidden">
                            <div class="p-3">
                                @foreach($selectedPedido->items as $item)
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-white bg-opacity-10 rounded p-1" style="width: 45px; height: 45px;">
                                                <img src="{{ $item->product->image_url ?? 'https://placehold.co/45x45/111827/white?text=📦' }}"
                                                    class="img-fluid rounded" style="width:100%; height:100%; object-fit:cover;">
                                            </div>
                                            <div>
                                                <div class="small fw-bold">
                                                    {{ $item->product->name }}
                                                </div>
                                                <div class="extra-small opacity-50">
                                                    {{ $item->product->supplier_code }}
                                                    @php
                                                        $itemOversize = $item->oversize;
                                                        if (empty($itemOversize) && $item->product) {
                                                            $itemOversize = $item->product->oversize;
                                                        }
                                                    @endphp
                                                    @if(!empty($itemOversize) && $itemOversize !== 'STD')
                                                        <span class="badge ms-1 rounded-pill" style="background:rgba(59,130,246,.2);color:#93c5fd;font-size:9px;font-weight:700;">+{{ $itemOversize }}</span>
                                                    @elseif(($itemOversize ?? '') === 'STD')
                                                        <span class="badge ms-1 rounded-pill" style="background:rgba(59,130,246,.2);color:#93c5fd;font-size:9px;font-weight:700;">STD</span>
                                                    @endif
                                                    @if($item->product->oem_code) · OEM: {{ $item->product->oem_code }} @endif
                                                </div>
                                                <div class="extra-small opacity-50">{{ $item->cantidad }} x S/
                                                    {{ number_format($item->precio_unitario, 2) }}
                                                </div>
                                                @if(auth()->user()->isAdmin())
                                                    @php
                                                        $costoProv = round($item->precio_unitario / 1.10, 2);
                                                        $gananciaItem = $item->precio_unitario - $costoProv;
                                                    @endphp
                                                    <div class="extra-small text-success fw-bold" style="font-size: 0.65rem; margin-top: 2px;">
                                                        <i class="fas fa-hand-holding-usd me-1"></i>Prov: S/ {{ number_format($costoProv, 2) }} | Ganancia: S/ {{ number_format($gananciaItem * $item->cantidad, 2) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="small fw-bold text-end">S/ {{ number_format($item->subtotal, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="p-3 border-top border-white border-opacity-10"
                                style="background: rgba(0,0,0,0.25);">
                                <div class="d-flex justify-content-between extra-small mb-1">
                                    <span>Subtotal</span>
                                    <span>S/ {{ number_format($selectedPedido->subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between extra-small mb-1">
                                    <span>Envío</span>
                                    <span>S/ {{ number_format($selectedPedido->costo_envio, 2) }}</span>
                                </div>
                                <div
                                    class="d-flex justify-content-between fw-bold mt-2 pt-2 border-top border-white border-opacity-10">
                                    <span>TOTAL</span>
                                    <span class="text-success">S/ {{ number_format($selectedPedido->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ANÁLISIS DE COMISIÓN -->
                @if(auth()->user()->isAdmin())
                    @php
                        $totalIng = $selectedPedido->subtotal;
                        $totalEg = 0;
                        foreach($selectedPedido->items as $item) {
                            $costoProv = round($item->precio_unitario / 1.10, 2);
                            $totalEg += ($costoProv * $item->cantidad);
                        }
                        $netGain = $totalIng - $totalEg;
                    @endphp
                    <div class="p-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-20" style="border-radius: 15px;">
                        <h6 class="text-success extra-small text-uppercase ls-1 mb-2 fw-bold" style="font-size: 0.7rem; font-weight:700;"><i class="fas fa-chart-line me-2"></i>Análisis de Comisión (10% Neto)</h6>
                        <div class="d-flex justify-content-between extra-small mb-1 text-white opacity-75" style="font-size: 0.72rem;">
                            <span>Ingreso (Cobrado):</span>
                            <span>S/ {{ number_format($totalIng, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between extra-small mb-1 text-white opacity-75" style="font-size: 0.72rem;">
                            <span>Egreso (Pago Proveedor):</span>
                            <span>S/ {{ number_format($totalEg, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold mt-2 pt-2 border-top border-white border-opacity-10 text-success" style="font-size: 0.75rem;">
                            <span>Ganancia Neta (Comisión):</span>
                            <span>S/ {{ number_format($netGain, 2) }}</span>
                        </div>
                    </div>
                @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>