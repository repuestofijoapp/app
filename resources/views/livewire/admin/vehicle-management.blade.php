<div class="container-fluid">
    <style>
        /* ─── Page Card ─── */
        .vm-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem;
        }

        /* ─── Tabs ─── */
        .vm-tabs {
            display: flex;
            gap: .5rem;
            background: var(--surface3);
            border-radius: 14px;
            padding: 5px;
        }

        .vm-tab {
            flex: 1;
            text-align: center;
            padding: .65rem 1.25rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: .82rem;
            color: var(--muted);
            background: transparent;
            transition: all .2s;
            letter-spacing: .5px;
        }

        .vm-tab.active {
            background: var(--accent-red);
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-red-glow);
        }

        .vm-tab:hover:not(.active) {
            background: rgba(255,255,255,.06);
            color: #fff;
        }

        /* ─── Table ─── */
        .vm-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }

        .vm-table th {
            color: var(--muted);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            padding: .75rem 1rem;
            text-transform: uppercase;
            font-size: .72rem;
            letter-spacing: 1px;
        }

        .vm-table td {
            background: var(--surface2);
            padding: 1rem;
            color: #fff;
            transition: all .25s;
            font-size: .88rem;
        }

        .vm-table tr:hover td { background: rgba(255,255,255,.05); }

        .vm-table td:first-child { border-radius: 12px 0 0 12px; }
        .vm-table td:last-child  { border-radius: 0 12px 12px 0; }

        /* ─── Badges ─── */
        .badge-count {
            background: rgba(59,130,246,.15);
            color: #60a5fa;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: .75rem;
            font-weight: 700;
        }

        .badge-fuel {
            font-size: .72rem;
            border-radius: 6px;
            padding: 2px 8px;
            font-weight: 700;
        }

        .badge-gas   { background: rgba(16,185,129,.15); color: #34d399; }
        .badge-diesel { background: rgba(245,158,11,.15); color: #fbbf24; }
        .badge-elec  { background: rgba(139,92,246,.15); color: #a78bfa; }

        /* ─── Buttons ─── */
        .btn-add {
            background: var(--accent-red);
            color: #fff;
            padding: .7rem 1.4rem;
            border-radius: 12px;
            font-weight: 700;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Syne', sans-serif;
            font-size: .85rem;
            transition: .25s;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 18px var(--accent-red-glow); }

        .btn-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,.04);
            color: var(--muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: .2s;
            font-size: .8rem;
        }
        .btn-icon:hover { color: #fff; background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); }
        .btn-icon.danger:hover { color: #ff4d4d; border-color: rgba(255,77,77,.4); background: rgba(255,77,77,.1); }
        .btn-icon.edit:hover   { color: #60a5fa; border-color: rgba(96,165,250,.4); background: rgba(96,165,250,.1); }

        /* ─── Search + Filter row ─── */
        .vm-search-input {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .65rem 1rem;
            color: #fff;
            min-width: 180px;
            flex: 1;
            transition: border-color .2s;
        }
        .vm-search-input::placeholder { color: var(--muted); }
        .vm-search-input:focus { outline: none; border-color: var(--accent-red); }

        .vm-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .65rem 1rem;
            color: #fff;
            min-width: 160px;
        }
        .vm-select option { background: #1a2235; }

        /* ─── Modal ─── */
        .vm-modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.75);
            backdrop-filter: blur(6px);
            z-index: 3000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            animation: fadeIn .15s ease;
        }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

        .vm-modal-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            width: 100%;
            max-width: 540px;
            padding: 2rem;
            animation: slideUp .2s ease;
            max-height: 90vh;
            overflow-y: auto;
        }
        @keyframes slideUp { from { opacity:0; transform: translateY(30px); } to { opacity:1; transform: translateY(0); } }

        .vm-input-label { font-size: .75rem; color: var(--muted); font-weight: 700; letter-spacing: .5px; text-transform: uppercase; margin-bottom: 5px; display: block; }

        .vm-input {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .7rem 1rem;
            color: #fff;
            width: 100%;
            transition: border-color .2s;
        }
        .vm-input:focus { outline: none; border-color: var(--accent-red); }
        .vm-input::placeholder { color: var(--muted); }

        .vm-select-full {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .7rem 1rem;
            color: #fff;
            width: 100%;
        }
        .vm-select-full option { background: #1a2235; }
        .vm-select-full:focus { outline: none; border-color: var(--accent-red); }

        .btn-save {
            background: var(--accent-red);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: .85rem 2rem;
            font-weight: 700;
            font-family: 'Syne', sans-serif;
            cursor: pointer;
            transition: .25s;
        }
        .btn-save:hover { box-shadow: 0 6px 18px var(--accent-red-glow); transform: translateY(-1px); }

        .btn-cancel {
            background: rgba(255,255,255,.06);
            color: var(--muted);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .85rem 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: .2s;
        }
        .btn-cancel:hover { background: rgba(255,255,255,.1); color: #fff; }

        .vm-error { color: #ff4d4d; font-size: .78rem; margin-top: 4px; }
        /* ─── Car image thumb ─── */
        .car-thumb {
            width: 52px;
            height: 36px;
            object-fit: contain;
            border-radius: 6px;
            background: rgba(255,255,255,.04);
        }

        /* ─── Image picker grid ─── */
        .img-picker-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            max-height: 200px;
            overflow-y: auto;
        }
        .img-picker-item {
            border: 2px solid transparent;
            border-radius: 10px;
            cursor: pointer;
            padding: 6px;
            background: var(--surface3);
            text-align: center;
            transition: .2s;
        }
        .img-picker-item:hover { border-color: rgba(255,255,255,.3); }
        .img-picker-item.selected { border-color: var(--accent-red); background: rgba(190,60,59,.1); }
        .img-picker-item img { width: 100%; height: 50px; object-fit: contain; border-radius: 6px; }
        .img-picker-item span { font-size: .65rem; color: var(--muted); display: block; margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ─── Fix for broken pagination backdrop ─── */
        .vm-card { min-height: auto !important; overflow: visible !important; }
        nav[role="navigation"] { background: transparent !important; }
    </style>

    {{-- ─── Header ─── --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="mb-0 fw-bold text-white" style="font-family:'Syne',sans-serif;">
                <i class="fas fa-car me-2" style="color:var(--accent-red);"></i>
                Vehículos
            </h2>
            <p class="mb-0 mt-1" style="color:var(--muted); font-size:.85rem;">
                Gestiona marcas, modelos y motores del catálogo de compatibilidades
            </p>
        </div>
    </div>

    {{-- ─── Tabs ─── --}}
    <div class="vm-tabs mb-4">
        <button class="vm-tab {{ $tab === 'makes' ? 'active' : '' }}" wire:click="switchTab('makes')">
            <i class="fas fa-tag me-1"></i> Marcas
        </button>
        <button class="vm-tab {{ $tab === 'models' ? 'active' : '' }}" wire:click="switchTab('models')">
            <i class="fas fa-car me-1"></i> Modelos
        </button>
        <button class="vm-tab {{ $tab === 'engines' ? 'active' : '' }}" wire:click="switchTab('engines')">
            <i class="fas fa-cog me-1"></i> Motores
        </button>
    </div>

    {{-- ══════════════════════════════════════════
         TAB: MARCAS
    ══════════════════════════════════════════ --}}
    @if($tab === 'makes')
        <div class="vm-card">
            <div class="d-flex gap-3 align-items-center mb-4 flex-wrap">
                <input type="text" wire:model.live.debounce.300ms="searchMake"
                    class="vm-search-input" placeholder="Buscar marca...">
                <button wire:click="openCreateMake" class="btn-add ms-auto">
                    <i class="fas fa-plus"></i> Nueva Marca
                </button>
            </div>

            <div class="table-responsive">
                <table class="vm-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Modelos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($makes as $make)
                            <tr>
                                <td style="width:50px; color:var(--muted);">{{ $make->id }}</td>
                                <td>
                                    <strong class="text-white">{{ $make->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge-count">{{ $make->car_models_count }} modelos</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn-icon edit" wire:click="editMake({{ $make->id }})" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="btn-icon danger" wire:click="confirmDeleteMake({{ $make->id }})" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4" style="color:var(--muted);">
                                    <i class="fas fa-search me-2"></i>No se encontraron marcas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $makes->links() }}
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════
         TAB: MODELOS
    ══════════════════════════════════════════ --}}
    @if($tab === 'models')
        <div class="vm-card">
            <div class="d-flex gap-3 align-items-center mb-4 flex-wrap">
                <input type="text" wire:model.live.debounce.300ms="searchModel"
                    class="vm-search-input" placeholder="Buscar modelo...">
                <select wire:model.live="filterMakeForModels" class="vm-select">
                    <option value="">— Todas las marcas —</option>
                    @foreach($allMakes as $mk)
                        <option value="{{ $mk['id'] }}">{{ $mk['name'] }}</option>
                    @endforeach
                </select>
                <button wire:click="openCreateModel" class="btn-add ms-auto">
                    <i class="fas fa-plus"></i> Nuevo Modelo
                </button>
            </div>

            <div class="table-responsive">
                <table class="vm-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">Foto</th>
                            <th>Marca</th>
                            <th>Nombre</th>
                            <th>Versión</th>
                            <th>Años</th>
                            <th>Motores</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($models as $model)
                            <tr>
                                <td style="width: 60px;">
                                    @php
                                        $imgFile = $model->image;
                                        $imgSrc = $imgFile
                                            ? asset('images/cars/' . $imgFile)
                                            : asset('images/cars/Car_hide.webp');
                                    @endphp
                                    <img src="{{ $imgSrc }}" class="car-thumb" alt="{{ $model->name }}">
                                </td>
                                <td>
                                    <span style="color:var(--muted); font-size:.78rem; font-weight:700;">
                                        {{ $model->make->name ?? '—' }}
                                    </span>
                                </td>
                                <td><strong class="text-white">{{ $model->name }}</strong></td>
                                <td style="color:var(--muted);">{{ $model->version_no ?: '—' }}</td>
                                <td style="color:var(--muted);">
                                    @if($model->start_year)
                                        {{ $model->start_year }}
                                        @if($model->end_year && $model->end_year != $model->start_year)
                                            – {{ $model->end_year }}
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-count">{{ $model->engines_count }} mot.</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn-icon edit" wire:click="editModel({{ $model->id }})" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="btn-icon danger" wire:click="confirmDeleteModel({{ $model->id }})" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4" style="color:var(--muted);">
                                    <i class="fas fa-search me-2"></i>No se encontraron modelos
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $models->links() }}
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════
         TAB: MOTORES
    ══════════════════════════════════════════ --}}
    @if($tab === 'engines')
        <div class="vm-card">
            <div class="d-flex gap-3 align-items-center mb-4 flex-wrap">
                <input type="text" wire:model.live.debounce.300ms="searchEngine"
                    class="vm-search-input" placeholder="Buscar código motor...">
                <select wire:model.live="filterMakeForEngines" class="vm-select">
                    <option value="">— Todas las marcas —</option>
                    @foreach($allMakes as $mk)
                        <option value="{{ $mk['id'] }}">{{ $mk['name'] }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterModelForEngines" class="vm-select">
                    <option value="">— Todos los modelos —</option>
                    @foreach($allModels as $md)
                        <option value="{{ $md['id'] }}">{{ $md['label'] }}</option>
                    @endforeach
                </select>
                <button wire:click="openCreateEngine" class="btn-add ms-auto">
                    <i class="fas fa-plus"></i> Nuevo Motor
                </button>
            </div>

            <div class="table-responsive">
                <table class="vm-table">
                    <thead>
                        <tr>
                            <th>Código Motor</th>
                            <th>Marca / Modelo</th>
                            <th>CC</th>
                            <th>Combustible</th>
                            <th>HP</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($engines as $engine)
                            <tr>
                                <td>
                                    <strong class="text-white" style="font-family:'Syne',sans-serif;">
                                        {{ $engine->engine_code }}
                                    </strong>
                                </td>
                                <td>
                                    <span style="color:var(--muted); font-size:.78rem;">
                                        {{ $engine->carModel->make->name ?? '—' }}
                                    </span>
                                    <span class="d-block text-white" style="font-size:.85rem;">
                                        {{ $engine->carModel->name ?? '—' }}
                                    </span>
                                </td>
                                <td style="color:var(--muted);">{{ $engine->displacement ? $engine->displacement . ' CC' : '—' }}</td>
                                <td>
                                    @php
                                        $fuel = strtoupper($engine->fuel_type ?? '');
                                        $fuelClass = match(true) {
                                            str_contains($fuel, 'DIESEL') => 'badge-diesel',
                                            str_contains($fuel, 'ELEC')   => 'badge-elec',
                                            default                        => 'badge-gas',
                                        };
                                    @endphp
                                    <span class="badge-fuel {{ $fuelClass }}">{{ $fuel ?: 'GASOLINA' }}</span>
                                </td>
                                <td style="color:var(--muted);">{{ $engine->engine_power ? $engine->engine_power . ' HP' : '—' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn-icon edit" wire:click="editEngine({{ $engine->id }})" title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button class="btn-icon danger" wire:click="confirmDeleteEngine({{ $engine->id }})" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4" style="color:var(--muted);">
                                    <i class="fas fa-search me-2"></i>No se encontraron motores
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $engines->links() }}
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════
         MODAL
    ══════════════════════════════════════════ --}}
    @if($showModal)
        <div class="vm-modal-overlay" wire:click.self="closeModal">
            <div class="vm-modal-box">

                {{-- ─ MAKE FORM ─ --}}
                @if($modalMode === 'make')
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold text-white" style="font-family:'Syne',sans-serif;">
                            <i class="fas fa-tag me-2" style="color:var(--accent-red);"></i>
                            {{ $editingId ? 'Editar Marca' : 'Nueva Marca' }}
                        </h5>
                        <button wire:click="closeModal" class="btn-icon">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="vm-input-label">Nombre de la Marca</label>
                        <input type="text" wire:model="make_name"
                            class="vm-input" placeholder="Ej: TOYOTA, CHEVROLET, HONDA...">
                        @error('make_name') <span class="vm-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <button wire:click="closeModal" class="btn-cancel">Cancelar</button>
                        <button wire:click="saveMake" class="btn-save">
                            <i class="fas fa-check me-2"></i>{{ $editingId ? 'Guardar cambios' : 'Crear Marca' }}
                        </button>
                    </div>

                {{-- ─ MODEL FORM ─ --}}
                @elseif($modalMode === 'model')
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold text-white" style="font-family:'Syne',sans-serif;">
                            <i class="fas fa-car me-2" style="color:var(--accent-red);"></i>
                            {{ $editingId ? 'Editar Modelo' : 'Nuevo Modelo' }}
                        </h5>
                        <button wire:click="closeModal" class="btn-icon">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="vm-input-label">Marca del Vehículo *</label>
                            <select wire:model="model_make_id" class="vm-select-full">
                                <option value="">— Seleccionar marca —</option>
                                @foreach($allMakes as $mk)
                                    <option value="{{ $mk['id'] }}">{{ $mk['name'] }}</option>
                                @endforeach
                            </select>
                            @error('model_make_id') <span class="vm-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12">
                            <label class="vm-input-label">Nombre del Modelo *</label>
                            <input type="text" wire:model="model_name"
                                class="vm-input" placeholder="Ej: COROLLA, CIVIC, AVEO...">
                            @error('model_name') <span class="vm-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12">
                            <label class="vm-input-label">Versión / Variante</label>
                            <input type="text" wire:model="model_version"
                                class="vm-input" placeholder="Ej: 1.5L XLI, 2.0 EX...">
                        </div>
                        <div class="col-6">
                            <label class="vm-input-label">Año Inicio</label>
                            <input type="number" wire:model="model_start_year"
                                class="vm-input" placeholder="Ej: 2010" min="1960" max="2035">
                        </div>
                        <div class="col-6">
                            <label class="vm-input-label">Año Fin</label>
                            <input type="number" wire:model="model_end_year"
                                class="vm-input" placeholder="Ej: 2020" min="1960" max="2035">
                        </div>

                        {{-- Image Picker --}}
                        <div class="col-12">
                            <label class="vm-input-label">Imagen del Modelo</label>

                            {{-- Current preview --}}
                            <div class="d-flex align-items-center gap-3 mb-3">
                                @php
                                    $previewSrc = $model_image_current
                                        ? asset('images/cars/' . $model_image_current)
                                        : asset('images/cars/Car_hide.webp');
                                @endphp
                                <img id="model-img-preview" src="{{ $previewSrc }}"
                                    style="height:70px; object-fit:contain; border-radius:10px; background:rgba(255,255,255,.05); padding:6px;">
                                <div>
                                    <div style="font-size:.78rem; color:var(--muted);">Imagen actual</div>
                                    <div style="font-size:.72rem; color:var(--accent-red); font-weight:600;">{{ $model_image_current ?: 'Sin imagen asignada (usa Car_hide)' }}</div>
                                </div>
                            </div>

                            {{-- Pick from existing --}}
                            @php
                                $carImagesDir = public_path('images/cars');
                                $existingImages = array_values(array_filter(scandir($carImagesDir), function($f) use ($carImagesDir) {
                                    return !in_array($f, ['.', '..']) && is_file($carImagesDir . DIRECTORY_SEPARATOR . $f);
                                }));
                            @endphp

                            <div style="font-size:.75rem; color:var(--muted); margin-bottom:6px; font-weight:700; text-transform:uppercase; letter-spacing:.5px;">
                                Elige una imagen existente:
                            </div>
                            <div class="img-picker-grid mb-3">
                                @foreach($existingImages as $imgFile)
                                    <label class="img-picker-item {{ $model_selected_existing === $imgFile ? 'selected' : '' }}">
                                        <input type="radio" wire:model="model_selected_existing" value="{{ $imgFile }}" style="display:none;">
                                        <img src="{{ asset('images/cars/' . $imgFile) }}" alt="{{ $imgFile }}">
                                        <span>{{ $imgFile }}</span>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Or upload a new one --}}
                            <div style="font-size:.75rem; color:var(--muted); margin-bottom:6px; font-weight:700; text-transform:uppercase; letter-spacing:.5px;">
                                O sube una nueva imagen:
                            </div>
                            <div style="position:relative; border: 2px dashed var(--border); border-radius:12px; padding:1rem; text-align:center; cursor:pointer;"
                                onclick="document.getElementById('car-img-upload').click()">
                                <i class="fas fa-cloud-upload-alt fa-2x" style="color:var(--muted); margin-bottom:6px;"></i>
                                <div style="color:var(--muted); font-size:.8rem;">Haz clic para subir una imagen (JPG, PNG, WEBP)</div>
                                <input id="car-img-upload" type="file" wire:model="model_image_upload" accept="image/*" style="display:none;">
                            </div>
                            @if($model_image_upload)
                                <div class="mt-2" style="font-size:.75rem; color:#34d399;">
                                    <i class="fas fa-check-circle me-1"></i> Imagen lista para subir: {{ $model_image_upload->getClientOriginalName() }}
                                </div>
                            @endif
                            @error('model_image_upload') <span class="vm-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <button wire:click="closeModal" class="btn-cancel">Cancelar</button>
                        <button wire:click="saveModel" class="btn-save">
                            <i class="fas fa-check me-2"></i>{{ $editingId ? 'Guardar cambios' : 'Crear Modelo' }}
                        </button>
                    </div>

                {{-- ─ ENGINE FORM ─ --}}
                @elseif($modalMode === 'engine')
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold text-white" style="font-family:'Syne',sans-serif;">
                            <i class="fas fa-cog me-2" style="color:var(--accent-red);"></i>
                            {{ $editingId ? 'Editar Motor' : 'Nuevo Motor' }}
                        </h5>
                        <button wire:click="closeModal" class="btn-icon">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="vm-input-label">Modelo de Vehículo *</label>
                            <select wire:model="engine_car_model_id" class="vm-select-full">
                                <option value="">— Seleccionar modelo —</option>
                                @foreach($allModels as $md)
                                    <option value="{{ $md['id'] }}">{{ $md['label'] }}</option>
                                @endforeach
                            </select>
                            @error('engine_car_model_id') <span class="vm-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12">
                            <label class="vm-input-label">Código de Motor *</label>
                            <input type="text" wire:model="engine_code"
                                class="vm-input" placeholder="Ej: DL(NEW), 2GR-FE, Z18XER...">
                            @error('engine_code') <span class="vm-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6">
                            <label class="vm-input-label">Desplazamiento (CC)</label>
                            <input type="text" wire:model="engine_displacement"
                                class="vm-input" placeholder="Ej: 2765">
                        </div>
                        <div class="col-6">
                            <label class="vm-input-label">Potencia (HP)</label>
                            <input type="text" wire:model="engine_power"
                                class="vm-input" placeholder="Ej: 150">
                        </div>
                        <div class="col-12">
                            <label class="vm-input-label">Tipo de Combustible</label>
                            <select wire:model="engine_fuel_type" class="vm-select-full">
                                <option value="GASOLINA">GASOLINA</option>
                                <option value="DIESEL">DIESEL</option>
                                <option value="ELECTRICO">ELÉCTRICO</option>
                                <option value="HIBRIDO">HÍBRIDO</option>
                                <option value="GNV">GNV</option>
                                <option value="GLP">GLP</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <button wire:click="closeModal" class="btn-cancel">Cancelar</button>
                        <button wire:click="saveEngine" class="btn-save">
                            <i class="fas fa-check me-2"></i>{{ $editingId ? 'Guardar cambios' : 'Crear Motor' }}
                        </button>
                    </div>
                @endif

            </div>
        </div>
    @endif

    {{-- SweetAlert confirm delete --}}
    <script>
        window.addEventListener('swal:confirm-delete-vehicle', e => {
            const d = e.detail[0] || e.detail;
            Swal.fire({
                title: d.title || '¿Eliminar?',
                text: d.text || 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#BE3C3B',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#111827',
                color: '#F0F4FF',
            }).then(result => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete-vehicle-confirmed', { id: d.id, type: d.type });
                }
            });
        });
    </script>
</div>
