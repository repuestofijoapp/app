<div class="container-fluid">
    <style>
        .product-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .product-card {
                padding: 1rem;
            }
        }

        .product-img-thumb {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid var(--border);
            padding: 2px;
            background: var(--surface);
        }

        .img-placeholder {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface3);
            border-radius: 12px;
            color: var(--muted);
            font-size: 1.2rem;
            border: 2px dashed var(--border);
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

        .table-custom tr.inactive td {
            opacity: 0.5;
        }

        .table-custom tr td:first-child {
            border-radius: 12px 0 0 12px;
        }

        .table-custom tr td:last-child {
            border-radius: 0 12px 12px 0;
        }

        .badge-code {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 3px 10px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .badge-oem {
            background: rgba(168, 85, 247, 0.15);
            color: #c084fc;
            border: 1px solid rgba(168, 85, 247, 0.3);
            padding: 3px 10px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.78rem;
        }

        .badge-brand {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .badge-oversize {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .btn-add {
            background: var(--accent-red);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Syne', sans-serif;
        }

        .btn-add {
            background: var(--accent-red);
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Syne', sans-serif;
            cursor: pointer;
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 5px 15px var(--accent-red-glow); color: #fff; }


        .search-input {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: #fff;
            min-width: 250px;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-red);
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .filter-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            color: #fff;
            outline: none;
            min-width: 170px;
        }

        .per-page-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            color: #fff;
            outline: none;
        }

        /* Modal */
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
            max-width: 1100px;
            padding: 2rem 2.5rem;
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: #fff;
            margin-bottom: 0.35rem;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent-red);
            box-shadow: 0 0 0 2px var(--accent-red-glow);
        }

        .form-label {
            display: block;
            margin-bottom: 0.35rem;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .form-hint {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: -0.2rem;
            margin-bottom: 0.5rem;
        }

        .section-title {
            color: var(--accent-red);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 1.2rem 0 0.8rem;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(190, 60, 59, 0.2);
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
        }

        .btn-action-edit:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .btn-action-delete:hover {
            background: rgba(255, 59, 92, 0.1);
            border-color: var(--red);
            color: var(--red);
        }

        .engines-pill {
            display: inline-block;
            background: rgba(96, 165, 250, 0.12);
            color: #93c5fd;
            border-radius: 5px;
            padding: 2px 7px;
            font-size: 0.72rem;
            font-weight: 700;
            margin: 1px;
            font-family: monospace;
        }
    </style>

    {{-- Header: Título --}}
    <div class="mb-3">
        <h1 class="h2 fw-bold text-white mb-1" style="font-family: 'Syne', sans-serif; letter-spacing: -0.5px;">Gestión de Repuestos</h1>
        <p class="text-white text-sm opacity-70">Catálogo de repuestos y trazabilidad de cambios.</p>
    </div>

    {{-- Botones: un extremo cada uno --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <button wire:click="openModal" class="btn-add">
            <i class="fas fa-plus"></i> Nuevo Repuesto
        </button>
        <div class="d-flex gap-2 align-items-center">
            @if($filterCategory == 3)
                {{-- Escáner IA: solo disponible para Anillos --}}
                <livewire:admin.catalog-scanner :categoryName="'Anillos'" />
            @endif
            <livewire:admin.bulk-import-products />
        </div>
    </div>

    <div class="product-card mt-4">

        {{-- Toolbar --}}
        <div class="d-flex flex-wrap gap-3 pb-4 align-items-center">
            <input type="text" wire:model.live.debounce.300ms="search" class="search-input"
                placeholder="Buscar código, marca, vehículo...">

            <select wire:model.live="filterProvider" class="filter-select">
                <option value="">Todos los proveedores</option>
                @foreach($providers as $prov)
                    <option value="{{ $prov->id }}">{{ $prov->business_name }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterCategory" class="filter-select">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <div class="ms-auto d-flex align-items-center gap-2">
                <span class="text-white text-sm">Mostrar</span>
                <select wire:model.live="perPage" class="per-page-select">
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-white text-sm">registros</span>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Imágen</th>
                        <th>Código</th>
                        <th>Marca</th>
                        <th>Motores</th>
                        <th>Vehículo</th>
                        <th>Editado por</th>
                        <th>Estado</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                </thead>
                <script>
                    window.addEventListener('products-updated', event => {
                        @this.dispatch('$refresh');
                    });
                </script>
                <tbody>
                    @forelse($products as $product)
                        <tr class="{{ !$product->is_active ? 'inactive' : '' }}">
                            <td>
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="product-img-thumb"
                                        alt="Product">
                                @else
                                    <div class="img-placeholder">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @php
                                        $addOem = $product->additional_oem_codes;
                                        if (is_string($addOem)) {
                                            $addOem = array_filter(array_map('trim', explode(',', $addOem)));
                                        }
                                        $firstAddOem = !empty($addOem) ? collect($addOem)->first() : null;
                                    @endphp
                                    <span class="badge-code">
                                        {{ $product->supplier_code }}
                                        @if($firstAddOem)
                                            <span style="color: rgba(96,165,250,0.5); font-weight: 400; margin: 0 2px;">—</span>
                                            {{ $firstAddOem }}
                                        @endif
                                    </span>
                                    @if($product->oem_code)
                                        <span class="badge-oem">Código Original: {{ $product->oem_code }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($product->brand)
                                    <span class="badge-brand">{{ $product->brand }}</span>
                                @else
                                    <span class="text-white text-xs">—</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($product->compatible_engines))
                                    @foreach($product->compatible_engines as $eng)
                                        <span class="engines-pill">{{ $eng }}</span>
                                    @endforeach
                                @else
                                    <span class="text-white text-xs">—</span>
                                @endif
                                @if($product->fuel_type)
                                    <div class="mt-1">
                                        @php
                                            $fuelIcon = match($product->fuel_type) {
                                                'DIESEL'  => '🛢️',
                                                'GAS'     => '💨',
                                                'HIBRIDO' => '🔋',
                                                default   => '⛽',
                                            };
                                        @endphp
                                        <span style="font-size: 0.68rem; background: rgba(234,179,8,0.15); color: #fbbf24; border: 1px solid rgba(234,179,8,0.3); border-radius: 5px; padding: 2px 7px; font-weight: 700;">
                                            {{ $fuelIcon }} {{ $product->fuel_type }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $make = strtoupper($product->vehicle_make ?? '');
                                    $rawVehicles = $product->compatible_vehicles;
                                    if (is_array($rawVehicles)) {
                                        $vehicles = array_filter(array_map('trim', $rawVehicles));
                                    } elseif (is_string($rawVehicles) && $rawVehicles) {
                                        $vehicles = array_filter(array_map('trim', explode(',', $rawVehicles)));
                                    } else {
                                        $vehicles = [];
                                    }
                                @endphp
                                @if($make)
                                    <span style="
                                        display: inline-block;
                                        background: rgba(190,60,59,0.18);
                                        color: #f87171;
                                        border: 1px solid rgba(190,60,59,0.45);
                                        padding: 3px 10px;
                                        border-radius: 6px;
                                        font-size: 0.78rem;
                                        font-weight: 800;
                                        letter-spacing: 0.5px;
                                        margin-bottom: 4px;
                                    ">{{ $make }}</span>
                                    @if(!empty($vehicles))
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            @foreach(array_slice($vehicles, 0, 3) as $veh)
                                                <span style="
                                                    display: inline-block;
                                                    background: rgba(190,60,59,0.10);
                                                    color: #fca5a5;
                                                    border: 1px solid rgba(190,60,59,0.25);
                                                    padding: 1px 7px;
                                                    border-radius: 4px;
                                                    font-size: 0.68rem;
                                                    font-weight: 600;
                                                ">{{ strtoupper($veh) }}</span>
                                            @endforeach
                                            @if(count($vehicles) > 3)
                                                <span style="color: rgba(255,255,255,0.4); font-size: 0.65rem; align-self: center;">+{{ count($vehicles) - 3 }} más</span>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <span class="text-white text-xs">—</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $editor = $product->updatedBy ?? $product->createdBy;
                                    $editorLabel = $editor ? $editor->name : '—';
                                    $editorTime = $product->updated_at->diffForHumans();
                                @endphp
                                <div title="{{ $editorLabel }} · {{ $product->updated_at->format('d/m/Y H:i') }}"
                                    style="cursor:help;">
                                    <span class="text-white" style="font-size: 0.78rem;">{{ $editorLabel }}</span><br>
                                    <span class="text-white" style="font-size: 0.7rem;">{{ $editorTime }}</span>
                                </div>
                            </td>
                            <td>
                                @if($product->is_active)
                                    <span class="text-green-400 text-xs font-bold"><i
                                            class="fas fa-check-circle mr-1"></i>Activo</span>
                                @else
                                    <span class="text-red-400 text-xs font-bold"><i
                                            class="fas fa-times-circle mr-1"></i>Inactivo</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="editProduct({{ $product->id }})" class="btn-action btn-action-edit"
                                        title="Editar"
                                        style="color: #fff !important; background: rgba(59, 130, 246, 0.45);">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button wire:click="toggleStatus({{ $product->id }})" class="btn-action"
                                        title="Cambiar estado"
                                        style="color: #fff !important; background: rgba(255, 255, 255, 0.1);">
                                        <i
                                            class="fas {{ $product->is_active ? 'fa-eye-slash' : 'fa-eye text-green-400' }}"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $product->id }})"
                                        class="btn-action btn-action-delete" title="Eliminar"
                                        style="color: #fff !important; background: rgba(239, 68, 68, 0.45);">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-white py-10">
                                <i class="fas fa-box-open fa-2x mb-3 opacity-30"></i><br>
                                No se encontraron productos. Agrega el primero con el botón de arriba.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-premium mt-6">
            {{ $products->links('vendor.pagination.custom-repuestofijo') }}
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    @if($showModal)
        <div class="modal-overlay">
            <div class="modal-content-custom">
                <h2 class="h5 fw-bold mb-2 text-white" style="font-family: 'Syne', sans-serif;">
                    {{ $editingProduct ? 'Editar Producto' : 'Nuevo Producto' }}
                </h2>

                <form wire:submit.prevent="saveProduct">
                    <div class="row g-3">
                        {{-- PASO 1: Datos Base (Imagen, Proveedor, Categoría) --}}
                        <div class="col-12">
                            <div class="section-title"><span class="badge bg-primary me-2">PASO 1</span> Datos Base del Producto</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Imagen del Producto</label>
                            <div class="d-flex align-items-center gap-3">
                                @if($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="product-img-thumb"
                                        style="width: 80px; height: 80px;">
                                @elseif($existing_image)
                                    <img src="{{ asset('storage/' . $existing_image) }}" class="product-img-thumb"
                                        style="width: 80px; height: 80px;">
                                @else
                                    <div class="img-placeholder" style="width: 80px; height: 80px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <label for="product-image-upload"
                                        class="d-flex flex-column align-items-center justify-content-center p-3 w-100"
                                        style="border: 2px dashed rgba(255,255,255,0.1); border-radius: 12px; cursor: pointer; background: rgba(255,255,255,0.02); transition: 0.3s;"
                                        onmouseover="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='var(--accent-red)';"
                                        onmouseout="this.style.background='rgba(255,255,255,0.02)'; this.style.borderColor='rgba(255,255,255,0.1)';">
                                        <i class="fas fa-cloud-upload-alt mb-1"
                                            style="color: var(--accent-red); font-size: 1.2rem;"></i>
                                        <span class="text-white"
                                            style="font-size: 0.65rem; font-weight: 800; font-family: 'Syne', sans-serif; letter-spacing: 0.5px;">{{ $image ? 'CAMBIAR' : 'ELEGIR FOTO' }}</span>
                                    </label>
                                    <input type="file" wire:model="image" id="product-image-upload" class="d-none"
                                        accept="image/*">
                                    <div wire:loading wire:target="image" class="text-xs text-blue-400 mt-2">
                                        <i class="fas fa-spinner fa-spin me-1"></i>Procesando...
                                    </div>
                                    @error('image') <span class="text-danger text-xs d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Proveedor + Categoría --}}
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Proveedor <span class="text-danger">*</span></label>
                                    <select wire:model="provider_id" class="form-input">
                                        <option value="">— Seleccionar —</option>
                                        @foreach($providers as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->business_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('provider_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Categoría</label>
                                    <select wire:model="category_id" class="form-input">
                                        <option value="">— Sin categoría —</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">
                                                {{ $cat->parent ? $cat->parent->name . ' › ' : '' }}{{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- PASO 2: Marca del Vehículo --}}
                        <div class="col-12">
                            <div class="section-title"><span class="badge bg-primary me-2">PASO 2</span> Marca del Vehículo (Manual)</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-light-emphasis">Marca del vehículo compatible</label>
                            <select wire:model.live="form_make" class="form-input">
                                <option value="">— Seleccionar marca —</option>
                                @foreach($all_makes as $mk)
                                    <option value="{{ $mk }}">{{ strtoupper($mk) }}</option>
                                @endforeach
                            </select>
                            <p class="form-hint">Seleccione la marca antes de escanear la captura para que la IA relacione los modelos correctamente.</p>
                        </div>

                        {{-- PASO 3: IA Catalog Autocomplete Scanner --}}
                        <div class="col-12">
                            <div class="section-title"><span class="badge bg-primary me-2">PASO 3</span> Escanear Detalles de Catálogo (IA)</div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded border" style="background: rgba(37, 99, 235, 0.08); border-color: rgba(56, 189, 248, 0.25); border-radius: 12px; {{ empty($form_make) ? 'opacity: 0.6; pointer-events: none;' : '' }}">
                                <label class="form-label text-white fw-bold mb-2 d-flex align-items-center gap-2" style="font-size: 0.85rem; font-family: 'Syne', sans-serif;">
                                    <i class="fas fa-magic" style="color: #38bdf8;"></i> Autocompletar Formulario con Escáner de Catálogo (IA)
                                </label>
                                @if(empty($form_make))
                                    <div class="alert alert-warning py-1 px-2 text-xs mb-2" style="font-size: 0.75rem;">
                                        <i class="fas fa-exclamation-triangle me-1"></i> Por favor, elija primero la marca del vehículo en el PASO 2.
                                    </div>
                                @endif
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="flex-grow-1" style="min-width: 200px;">
                                        <input type="file" wire:model="scannerImage" id="scanner-image-autocomplete" class="form-control form-control-sm text-white" accept="image/*" style="background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.15); border-radius: 8px;" {{ empty($form_make) ? 'disabled' : '' }}>
                                    </div>
                                    <button type="button" wire:click="scanFormCatalog" class="btn btn-sm fw-bold px-3" style="background: #e63946; color: #fff; border-radius: 8px; font-size: 0.78rem; min-height: 31px;" wire:loading.attr="disabled" wire:target="scannerImage" {{ empty($form_make) || !$scannerImage ? 'disabled' : '' }}>
                                        <i class="fas fa-robot me-1"></i> Analizar Captura
                                    </button>
                                </div>
                                <div wire:loading wire:target="scanFormCatalog" class="text-xs text-info mt-2" style="color: #38bdf8 !important;">
                                    <i class="fas fa-spinner fa-spin me-1"></i> Leyendo captura y resolviendo compatibilidades en base de datos...
                                </div>
                                @error('scannerImage') <span class="text-danger text-xs d-block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- PASO 4: Validar Datos del Producto --}}
                        <div class="col-12">
                            <div class="section-title"><span class="badge bg-primary me-2">PASO 4</span> Validar Identificación del Producto</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Marca Fabricante</label>
                            <input type="text" wire:model="brand" class="form-input" placeholder="NPR, NDC, Toyan...">
                            <p class="form-hint">Marca del repuesto (no del vehículo)</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Código Proveedor <span class="text-danger">*</span></label>
                            <input type="text" wire:model="supplier_code" class="form-input"
                                placeholder="SWH-30433, CB-1134">
                            @error('supplier_code') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo de Combustible</label>
                            <select wire:model="fuel_type" class="form-input">
                                <option value="">— Sin especificar —</option>
                                <option value="GASOLINA">⛽ GASOLINA</option>
                                <option value="DIESEL">🛢️ DIESEL</option>
                                <option value="GAS">💨 GAS</option>
                                <option value="HIBRIDO">🔋 HÍBRIDO</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Código OEM (Fabricante vehículo)</label>
                            <input type="text" wire:model="oem_code" class="form-input" placeholder="13011-PH3-000">
                            <p class="form-hint">Opcional (Honda, Toyota, etc.)</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código original Adicional</label>
                            <input type="text" wire:model="additional_oem_codes" class="form-input"
                                placeholder="REF-123, ALT-456">
                            <p class="form-hint">Referencias cruzadas separadas por coma</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre descriptivo</label>
                            <input type="text" wire:model="name" class="form-input"
                                placeholder="Anillo Honda Prelude 87mm STD">
                        </div>

                        {{-- Compatibilidad con vehículos --}}
                        <div class="col-12">
                            <div class="section-title">Compatibilidad con Vehículos Autocompletada</div>
                        </div>

                        {{-- RESUMEN DE SELECCIÓN ACTUAL (Real-time summary) --}}
                        <div class="col-12 mb-2">
                            <div class="p-3 rounded border"
                                style="background: rgba(30, 41, 59, 0.5); border-color: rgba(255,255,255,0.1);">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-white small fw-bold mb-0">
                                        <i class="fas fa-link me-2 text-accent-red"></i>VINCULACIÓN ACTUAL
                                    </h6>
                                    @if($form_make || !empty($form_model_ids) || !empty($form_engine_ids))
                                        <button type="button" wire:click="clearVehicleSelection"
                                            class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.65rem;">
                                            Limpiar selección
                                        </button>
                                    @endif
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="text-white x-small fw-bold mb-1">MARCA</div>
                                        <div class="text-white small fw-bold">{{ $form_make ?: '—' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-white x-small fw-bold mb-1">MODELOS ({{ count($form_model_ids) }})
                                        </div>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse(collect($available_models)->whereIn('id', $form_model_ids) as $mod)
                                                <span class="badge bg-success"
                                                    style="font-size: 0.65rem;">{{ $mod['label'] }}</span>
                                            @empty
                                                <span class="text-white small italic">Ninguno</span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-white x-small fw-bold mb-1">MOTORES ({{ count($form_engine_ids) }})
                                        </div>
                                        <div class="d-flex flex-wrap gap-1">
                                            @php
                                                $shownEngines = collect($available_engines)->filter(fn($eng) =>
                                                    !empty(array_intersect($eng['ids'] ?? [$eng['id']], $form_engine_ids))
                                                );
                                            @endphp
                                            @forelse($shownEngines as $eng)
                                                <span class="badge bg-warning text-dark"
                                                    style="font-size: 0.65rem;">{{ $eng['label'] }}</span>
                                            @empty
                                                <span class="text-white small italic">Ninguno</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PASO 1: Marca --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-light-emphasis">
                                <span class="badge bg-primary me-1">1</span> Marca del vehículo
                            </label>
                            <select wire:model.live="form_make" class="form-input">
                                <option value="">— Seleccionar marca —</option>
                                @foreach($all_makes as $mk)
                                    <option value="{{ $mk }}">{{ strtoupper($mk) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PASO 2: Modelos (multi) --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-light-emphasis">
                                <span class="badge bg-primary me-1">2</span> Modelos compatibles
                                @if(!empty($form_model_ids))
                                    <span class="badge bg-success ms-1">{{ count($form_model_ids) }} sel.</span>
                                @endif
                            </label>
                            <select wire:model.live="form_model_ids" class="form-input" multiple style="min-height: 100px;"
                                {{ empty($available_models) ? 'disabled' : '' }}>
                                @forelse($available_models as $mod)
                                    <option value="{{ $mod['id'] }}">{{ $mod['label'] }}</option>
                                @empty
                                    <option disabled>Primero seleccione una marca</option>
                                @endforelse
                            </select>
                            <p class="form-hint">Ctrl+Click para seleccionar varios</p>
                            <button type="button" wire:click="selectAllModels"
                                class="btn btn-link p-0 text-xs text-blue-400 text-decoration-none">Seleccionar
                                todos</button>

                            {{-- Quick-add model --}}
                            @if($form_make)
                                <div class="mt-2 p-3 rounded"
                                    style="background: rgba(37,99,235,0.1); border: 1px dashed rgba(37,99,235,0.4);">
                                    <p class="text-xs fw-bold mb-2" style="color: #93c5fd;">
                                        <i class="fas fa-plus-circle me-1"></i>¿El modelo no está en la lista? Agrégalo:
                                    </p>
                                    <div class="mb-2">
                                        <label class="text-xs mb-1 d-block" style="color:#94a3b8;">Nombre del modelo *</label>
                                        <input type="text" wire:model="new_model_name" class="form-input w-100"
                                            placeholder="Ej: MATIZ III, SPARK, N300" />
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-xs mb-1 d-block" style="color:#94a3b8;">Años (opcional)</label>
                                        <input type="text" wire:model="new_model_year" class="form-input w-100"
                                            placeholder="Ej: 1999-2010 ó solo 2005" />
                                    </div>
                                    <button type="button" wire:click="addNewModel" class="btn btn-sm w-100 fw-bold"
                                        style="background:#2563EB; color:#fff; border:0;">
                                        <i class="fas fa-plus me-1"></i> Crear y seleccionar modelo
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- PASO 3: Motores (multi-checkbox) --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-light-emphasis">
                                <span class="badge bg-primary me-1">3</span> Motores compatibles
                                @if(!empty($form_engine_ids))
                                    <span class="badge bg-success ms-1">{{ count($form_engine_ids) }} sel.</span>
                                @endif
                            </label>
                            <div class="border rounded p-2"
                                style="min-height: 80px; max-height: 140px; overflow-y: auto; background: rgba(255,255,255,0.05);">
                                @forelse($available_engines as $eng)
                                    <label class="d-flex align-items-center gap-2 py-1 cursor-pointer"
                                        style="font-size: 0.82rem; color: #e2e8f0;">
                                        <input type="checkbox" wire:model.live="form_engine_ids" value="{{ $eng['id'] }}"
                                            class="form-check-input m-0">
                                        <span>{{ $eng['label'] }}</span>
                                    </label>
                                @empty
                                    <span class="text-muted small d-block text-center mt-3">Seleccione modelos primero</span>
                                @endforelse
                            </div>

                            {{-- Quick-add engine --}}
                            @if(!empty($form_model_ids))
                                <div class="mt-2 p-3 rounded"
                                    style="background: rgba(234,88,12,0.1); border: 1px dashed rgba(234,88,12,0.4);">
                                    <p class="text-xs fw-bold mb-2" style="color:#fdba74;">
                                        <i class="fas fa-plus-circle me-1"></i>¿Motor no listado? Agrégalo:
                                    </p>
                                    <div class="mb-2">
                                        <label class="text-xs mb-1 d-block" style="color:#94a3b8;">Código de motor *</label>
                                        <input type="text" wire:model="new_engine_code" class="form-input w-100"
                                            placeholder="Ej: 4EA, 4EC, G10" />
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-xs mb-1 d-block" style="color:#94a3b8;">Cilindrada (opcional)</label>
                                        <input type="text" wire:model="new_engine_disp" class="form-input w-100"
                                            placeholder="Ej: 995 ó 796cc" />
                                    </div>

                                    <button type="button" wire:click="addNewEngine" class="btn btn-sm w-100 fw-bold"
                                        style="background:#ea580c; color:#fff; border:0;">
                                        <i class="fas fa-plus me-1"></i> Crear y seleccionar motor
                                    </button>
                                    <p class="text-xs mt-1 mb-0" style="color:#6b7280;">Se vincula a todos los modelos
                                        seleccionados.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Removed redundant summary --}}

                        {{-- Specs y Notas --}}
                        <div class="col-12">
                            <div class="section-title">Especificaciones Técnicas (Pistones, Anillos, Metales)</div>
                        </div>

                        {{-- Fila 1: Piston Basics --}}
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold text-light-emphasis">Ø Bore (mm)</label>
                            <input type="text" wire:model="specs_bore" class="form-input" placeholder="Ej: 92.0">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label small fw-bold text-light-emphasis">Cilindros</label>
                            <input type="text" wire:model="specs_cylinders" class="form-input" placeholder="Ej: 4">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold text-light-emphasis">Length</label>
                            <input type="text" wire:model="specs_length" class="form-input" placeholder="Ej: 55.0">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label small fw-bold text-light-emphasis">Comp. Height</label>
                            <input type="text" wire:model="specs_comp_height" class="form-input" placeholder="Ej: 25.0">
                        </div>

                        {{-- Fila 2: Piston Heights & PIN --}}
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold text-light-emphasis">Height 1</label>
                            <input type="text" wire:model="specs_height_1" class="form-input" placeholder="Ej: -2.5">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold text-light-emphasis">Height 2</label>
                            <input type="text" wire:model="specs_height_2" class="form-input" placeholder="Ej: -3.6">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small fw-bold text-light-emphasis">PIN</label>
                            <input type="text" wire:model="specs_pin" class="form-input" placeholder="Ej: 16.0X52.8">
                        </div>
                        <div class="col-md-3 mb-2 d-flex align-items-center gap-3 pt-1" style="margin-top: 1.5rem;">
                            <div class="d-flex align-items-center gap-2 mt-2" style="padding: 0.5rem 0.75rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; cursor: pointer;" onclick="document.getElementById('circlip-check').click()">
                                <input type="checkbox" id="circlip-check" wire:model="specs_circlip"
                                    style="width: 16px; height: 16px; accent-color: #e63946; cursor: pointer; flex-shrink: 0;">
                                <label for="circlip-check" class="form-label mb-0 fw-bold" style="cursor: pointer; color: #f8fafc; font-size: 0.82rem;">
                                    ✅ Requiere Circlip
                                </label>
                            </div>
                        </div>

                        {{-- Fila 3: Rings/Metals & Notes --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-light-emphasis">Raw/Diámetro (Anillos/Metales)</label>
                            <input type="text" wire:model="specs_raw" class="form-input" placeholder="Ej: 87MM 1.2X1.2X2.8">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label small fw-bold text-light-emphasis">Radial (a1)</label>
                            <input type="text" wire:model="specs_radial" class="form-input" placeholder="Ej: 3.1, 3.3">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label small fw-bold text-light-emphasis">Forma (Shape)</label>
                            <input type="text" wire:model="specs_shape" class="form-input" placeholder="Ej: BF-IB">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-light-emphasis">Notas / Material</label>
                            <input type="text" wire:model="notes" class="form-input" placeholder="METAL, 100% GRAFITO...">
                        </div>
                        {{-- Sobremedidas / Variantes con precio y stock --}}
                        <div class="col-12">
                            <div class="section-title">Sobremedidas / Variantes</div>
                        </div>
                        <div class="col-12">
                            @error('oversizesData') <div class="alert alert-danger py-2 mb-2 text-sm">{{ $message }}</div> @enderror
                            <div class="table-responsive">
                                <table style="width:100%; border-collapse: separate; border-spacing: 0 6px;">
                                    <thead>
                                        <tr>
                                            <th style="color:var(--muted); font-size:.72rem; font-weight:700; padding:.5rem; text-transform:uppercase; letter-spacing:1px; width:40px;"></th>
                                            <th style="color:var(--muted); font-size:.72rem; font-weight:700; padding:.5rem; text-transform:uppercase; letter-spacing:1px;">Sobremedida</th>
                                            <th style="color:var(--muted); font-size:.72rem; font-weight:700; padding:.5rem; text-transform:uppercase; letter-spacing:1px;">Precio (S/.)</th>
                                            <th style="color:var(--muted); font-size:.72rem; font-weight:700; padding:.5rem; text-transform:uppercase; letter-spacing:1px;">Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($oversizeList as $key => $label)
                                            <tr style="background: var(--surface2); border-radius: 10px;">
                                                <td style="padding:.6rem .8rem; border-radius: 10px 0 0 10px;">
                                                    <input type="checkbox"
                                                        wire:model.live="oversizesData.{{ $key }}.enabled"
                                                        class="form-check-input m-0"
                                                        style="width:18px; height:18px; cursor:pointer;">
                                                </td>
                                                <td style="padding:.6rem .8rem; color:#fff; font-weight:700; font-family:'Syne',sans-serif; font-size:.85rem;">
                                                    {{ $label }}
                                                </td>
                                                <td style="padding:.6rem .5rem;">
                                                    <input type="number"
                                                        wire:model="oversizesData.{{ $key }}.price"
                                                        class="form-input"
                                                        placeholder="0.00"
                                                        step="0.01" min="0"
                                                        style="max-width:120px; {{ !($oversizesData[$key]['enabled'] ?? false) ? 'opacity:.4; pointer-events:none;' : '' }}"
                                                        {{ !($oversizesData[$key]['enabled'] ?? false) ? 'disabled' : '' }}>
                                                    @error("oversizesData.{$key}.price") <span class="text-danger d-block" style="font-size:.72rem;">{{ $message }}</span> @enderror
                                                </td>
                                                <td style="padding:.6rem .5rem; border-radius: 0 10px 10px 0;">
                                                    <input type="number"
                                                        wire:model="oversizesData.{{ $key }}.stock"
                                                        class="form-input"
                                                        placeholder="0"
                                                        min="0"
                                                        style="max-width:90px; {{ !($oversizesData[$key]['enabled'] ?? false) ? 'opacity:.4; pointer-events:none;' : '' }}"
                                                        {{ !($oversizesData[$key]['enabled'] ?? false) ? 'disabled' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p class="form-hint mt-2">Activa las medidas disponibles, asigna precio y stock a cada una.</p>
                        </div>

                        <!-- Checkbox "Producto activo" retirado porque ya existe el toggle fuera de la modal -->
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-3 mt-4">
                        <button type="button" wire:click="closeModal"
                            style="height: 50px; border-radius: 12px; font-weight: 700; color: #fff; border: 1px solid var(--border); background: transparent; flex: 1;">
                            Cancelar
                        </button>
                        <button type="submit" class="btn-add" style="height: 50px; flex: 1;">
                            <i class="fas {{ $editingProduct ? 'fa-save' : 'fa-plus' }}"></i>
                            {{ $editingProduct ? 'Guardar Cambios' : 'Crear Producto' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- SweetAlert --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Init tooltips
            const initTooltips = () => {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                    if (!el._tooltipInstance) {
                        el._tooltipInstance = new bootstrap.Tooltip(el, { trigger: 'hover' });
                    }
                });
            };
            initTooltips();
            document.addEventListener('livewire:morph', initTooltips);

            Livewire.on('swal:confirm-delete-product', (data) => {
                const info = data[0];
                Swal.fire({
                    title: info.title, text: info.text, icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF3B5C', cancelButtonColor: '#6B7A99',
                    confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
                    background: '#111827', color: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('delete-product-confirmed', { id: info.id });
                    }
                });
            });
        });
    </script>
</div>