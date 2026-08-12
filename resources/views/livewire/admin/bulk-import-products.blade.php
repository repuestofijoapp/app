<div class="d-inline-block">
    {{-- Button to open --}}
    <button wire:click="openModal" class="btn-purple-glow px-4 fw-bold text-white">
        <i class="fas fa-file-excel me-2"></i> Importación Masiva
    </button>

    {{-- Modal Overlay --}}
    @if($showModal)
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="background: rgba(10, 15, 30, 0.85); z-index: 1100; backdrop-filter: blur(12px);">
            <div class="col-12 col-xl-11" style="max-height: 95vh; overflow-y: auto; padding: 15px;">
                <div class="card border-0 shadow-lg"
                    style="background: #0d1222; border: 1px solid rgba(139, 92, 246, 0.2) !important; border-radius: 24px;">

                    {{-- Modal Header --}}
                    <div class="d-flex align-items-center justify-content-between p-4"
                        style="border-bottom: 1px solid rgba(255,255,255,0.06); background: rgba(139, 92, 246, 0.03); border-top-left-radius: 24px; border-top-right-radius: 24px;">
                        <div class="d-flex align-items-center">
                            <div class="bg-purple-light p-3 rounded-4 me-3">
                                <i class="fas fa-magic text-purple-glow fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 text-white fw-bold" style="font-family: 'Syne', sans-serif;">Wizard de
                                    Importación Inteligente</h4>
                                <p class="mb-0 text-gray-400" style="font-size: 0.82rem;">Paso {{ $step }} de 2:
                                    {{ $step == 1 ? 'Datos base de importación' : 'Lector y grilla de catálogo' }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="btn text-white hover-white p-2"
                            style="font-size: 2.5rem; line-height: 1; border: none; background: transparent;">&times;</button>
                    </div>

                    <div class="p-4">
                        {{-- Paso 1: Configuración base --}}
                        @if($step == 1)
                            <div class="row g-4 justify-content-center">
                                <div class="col-lg-6">
                                    <div class="p-4 rounded-4"
                                        style="background: #141a2f; border: 1px solid rgba(255,255,255,0.04);">
                                        <h5 class="text-white fw-bold mb-4" style="font-family: 'Syne', sans-serif;"><i
                                                class="fas fa-sliders-h text-purple-glow me-2"></i> Configuración Inicial</h5>

                                        <div class="mb-3">
                                            <label class="small fw-bold text-gray-300 mb-2 d-block">PROVEEDOR DESTINO *</label>
                                            <select wire:model="provider_id" class="form-select border-0 px-3 py-3 text-white"
                                                style="background: #1E293B; border-radius: 12px; font-size: 0.9rem;">
                                                <option value="">— Seleccionar Proveedor —</option>
                                                @foreach($providers as $p)
                                                    <option value="{{ $p->id }}">{{ $p->business_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('provider_id') <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="small fw-bold text-gray-300 mb-2 d-block">CATEGORÍA DEL PRODUCTO
                                                *</label>
                                            <select wire:model="category_id" class="form-select border-0 px-3 py-3 text-white"
                                                style="background: #1E293B; border-radius: 12px; font-size: 0.9rem;">
                                                <option value="">— Seleccionar Categoría —</option>
                                                @foreach($categories as $c)
                                                    <option value="{{ $c->id }}">
                                                        {{ $c->parent ? $c->parent->name . ' › ' : '' }}{{ $c->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id') <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="small fw-bold text-gray-300 mb-2 d-block">MARCA DE FABRICANTE
                                                (REPUESTO) *</label>
                                            <input type="text" wire:model="brand"
                                                class="form-control border-0 px-3 py-3 text-white"
                                                style="background: #1E293B; border-radius: 12px; font-size: 0.9rem;"
                                                placeholder="Ej: NPR, SWG, DAIDO...">
                                            @error('brand') <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="small fw-bold text-gray-300 mb-2 d-block">TIPO DE COMBUSTIBLE
                                                *</label>
                                            <select wire:model="fuel_type" class="form-select border-0 text-white px-3 py-3"
                                                style="background: #1E293B; border-radius: 12px; font-size: 0.9rem;">
                                                <option value="">Elije tipo de combustible</option>
                                                <option value="GASOLINA">⛽ GASOLINA</option>
                                                <option value="DIESEL">🛢️ DIESEL</option>
                                                <option value="GAS">💨 GAS</option>
                                                <option value="HIBRIDO">🔋 HÍBRIDO</option>
                                            </select>
                                            @error('fuel_type') <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="small fw-bold text-gray-300 mb-2 d-block">MARCA DEL VEHÍCULO *</label>
                                            <select wire:model="vehicle_make" class="form-select border-0 text-white px-3 py-3"
                                                style="background: #1E293B; border-radius: 12px; font-size: 0.9rem;">
                                                <option value="">— Seleccionar Marca Vehículo —</option>
                                                @foreach($makes as $m)
                                                    <option value="{{ $m }}">{{ $m }}</option>
                                                @endforeach
                                            </select>
                                            @error('vehicle_make') <small
                                            class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="p-4 rounded-4 h-100 d-flex flex-column justify-content-between"
                                        style="background: #141a2f; border: 1px solid rgba(255,255,255,0.04);">
                                        <div>
                                            <h5 class="text-white fw-bold mb-4" style="font-family: 'Syne', sans-serif;"><i
                                                    class="fas fa-image text-purple-glow me-2"></i> Imagen del Lote</h5>
                                            <p class="text-gray-400 small mb-3">Sube una única imagen para todos los productos
                                                de esta importación masiva (puedes cambiarla individualmente después).</p>

                                            <div class="mb-3">
                                                <input type="file" wire:model="tempImage"
                                                    class="form-control border-0 text-white text-sm"
                                                    style="background: #1E293B; border-radius: 10px; padding: 12px;"
                                                    accept="image/*">
                                                <div wire:loading wire:target="tempImage" class="text-xs text-info mt-2">
                                                    <i class="fas fa-spinner fa-spin me-1"></i>Subiendo imagen temporal...
                                                </div>
                                            </div>

                                            @if($tempImage)
                                                <div class="p-3 rounded-3 mt-3 d-flex align-items-center gap-3"
                                                    style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3);">
                                                    <i class="fas fa-check-circle text-success fs-4"></i>
                                                    <div>
                                                        <div class="text-white fw-bold small">Imagen cargada correctamente</div>
                                                        <div class="text-gray-400 text-xs">Lista para usarse como vista previa en el
                                                            paso 2</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <button wire:click="nextStep"
                                            class="btn btn-purple-glow w-100 py-3 mt-4 fw-bold rounded-4 shadow-lg text-white">
                                            <span>Siguiente Paso</span> <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Paso 2: Entrada de datos, lector PDF y tabla interactiva --}}
                        @if($step == 2)
                            <div class="row g-4">
                                {{-- Resumen del Paso 1 --}}
                                <div class="col-12">
                                    <div class="p-3 rounded-4 d-flex flex-column gap-3 text-white"
                                        style="background: #141a2f; border: 1px solid rgba(139, 92, 246, 0.15);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="fw-bold d-flex align-items-center gap-2">
                                                <i class="fas fa-info-circle text-purple-glow"></i> Datos de Configuración del
                                                Paso 1
                                            </div>
                                            <button wire:click="$toggle('showSummary')"
                                                class="btn btn-sm btn-outline-light border-0 py-1"
                                                style="font-size: 0.85rem; border-radius: 8px;">
                                                <i class="fas {{ $showSummary ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                                {{ $showSummary ? 'Ocultar Resumen' : 'Mostrar Resumen' }}
                                            </button>
                                        </div>

                                        @if($showSummary)
                                            <div class="row g-3"
                                                style="font-size: 0.85rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 15px;">
                                                <div class="col-md-7">
                                                    <div class="row g-2">
                                                        <div class="col-6 text-gray-400">Proveedor:</div>
                                                        <div class="col-6 fw-bold text-white">
                                                            {{ \App\Models\Provider::find($provider_id)?->business_name ?? '—' }}
                                                        </div>

                                                        <div class="col-6 text-gray-400">Categoría:</div>
                                                        <div class="col-6 fw-bold text-white">
                                                            {{ \App\Models\Category::find($category_id)?->name ?? '—' }}
                                                        </div>

                                                        <div class="col-6 text-gray-400">Fabricante:</div>
                                                        <div class="col-6 fw-bold text-white">{{ $brand }}</div>

                                                        <div class="col-6 text-gray-400">Combustible:</div>
                                                        <div class="col-6 fw-bold text-white">{{ $fuel_type }}</div>

                                                        <div class="col-6 text-gray-400">Marca Vehículo:</div>
                                                        <div class="col-6 fw-bold text-white">{{ $vehicle_make }}</div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-5 d-flex align-items-center justify-content-center border-start border-secondary border-opacity-10">
                                                    @if($tempImage)
                                                        <img src="{{ $tempImage->temporaryUrl() }}" class="rounded-3 shadow-md border"
                                                            style="max-height: 100px; max-width: 100%; object-fit: contain; border-color: rgba(255,255,255,0.1);">
                                                    @else
                                                        <div class="text-gray-400 text-xs text-center"><i
                                                                class="fas fa-image fa-2x mb-2 d-block opacity-40"></i> Sin imagen
                                                            cargada</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Formularios de Pegar e Importar PDF --}}
                                @if(empty($parsedProducts))
                                    <div class="col-lg-7">
                                        <div class="p-4 rounded-4 h-100"
                                            style="background: #141a2f; border: 1px solid rgba(255,255,255,0.04);">
                                            <label class="small fw-bold text-gray-300 mb-2 d-block">PEGA AQUÍ LOS DATOS A
                                                ESCANEAR</label>
                                            <textarea wire:model="bulkText" rows="11"
                                                class="form-control border-0 px-4 py-3 text-white font-monospace"
                                                style="background: #1E293B; border-radius: 16px; font-size: 0.85rem; resize: none;"
                                                placeholder="SDG-30002 025 D4AE 100MM 3X2X4&#10;SDG-30002 STD D4AE 100MM 3X2X4&#10;SDG-30043 025 D4EA 83MM 1.93X2X3"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        <div class="p-4 rounded-4 h-100 d-flex flex-column justify-content-between"
                                            style="background: #141a2f; border: 1px solid rgba(255,255,255,0.04);">
                                            <div>
                                                <label class="small fw-bold text-gray-300 mb-2 d-block">SUBIR CATÁLOGO DEL
                                                    FABRICANTE (PDF) *</label>
                                                <input type="file" wire:model="catalogPdf"
                                                    class="form-control border-0 text-white text-sm"
                                                    style="background: #1E293B; border-radius: 10px; padding: 12px;"
                                                    accept="application/pdf">
                                                <div class="text-xs text-gray-400 mt-2">
                                                    <i class="fas fa-info-circle me-1"></i> El sistema buscará de manera inteligente
                                                    y eficiente cada código en el archivo PDF localmente y extraerá la información
                                                    técnica.
                                                </div>
                                                <div wire:loading wire:target="catalogPdf" class="text-xs text-info mt-2">
                                                    <i class="fas fa-spinner fa-spin me-1"></i>Cargando archivo PDF...
                                                </div>
                                                @error('catalogPdf') <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                @enderror
                                                @error('bulkText') <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="d-flex gap-3 mt-4">
                                                <button wire:click="prevStep"
                                                    class="btn btn-outline-secondary py-3 fw-bold rounded-4 text-white"
                                                    style="flex: 1;">
                                                    <i class="fas fa-arrow-left me-2"></i> Atrás
                                                </button>
                                                <button wire:click="scanPdfCatalog"
                                                    class="btn btn-purple-glow py-3 fw-bold rounded-4 text-white" style="flex: 2;"
                                                    wire:loading.attr="disabled" wire:target="scanPdfCatalog">
                                                    <span wire:loading.remove wire:target="scanPdfCatalog"><i
                                                            class="fas fa-robot me-2"></i> ESCANEAR PDF Y ANALIZAR</span>
                                                    <span wire:loading wire:target="scanPdfCatalog"><i
                                                            class="fas fa-spinner fa-spin me-2"></i> PROCESANDO...</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $isMetalsView = false;
                                        $isPistonsView = false;
                                        if ($category_id) {
                                            $cat = \App\Models\Category::with('parent')->find($category_id);
                                            if ($cat) {
                                                $isMetalsView = stripos($cat->name, 'Metal') !== false || ($cat->parent && stripos($cat->parent->name, 'Metal') !== false);
                                                $isPistonsView = stripos($cat->name, 'Piston') !== false || stripos($cat->name, 'Pistón') !== false
                                                    || ($cat->parent && (stripos($cat->parent->name, 'Piston') !== false || stripos($cat->parent->name, 'Pistón') !== false));
                                            }
                                        }
                                    @endphp
                                    {{-- Grilla Interactiva / Tabla de resultados --}}
                                    <div class="col-12">
                                        <div class="p-3 rounded-4 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3 text-white"
                                            style="background: rgba(139, 92, 246, 0.08); border: 1px solid rgba(139, 92, 246, 0.2);">
                                            <div class="d-flex align-items-center gap-3">
                                                <h6 class="mb-0 fw-bold"><i class="fas fa-table me-2 text-purple-glow"></i>
                                                    Resultados del Análisis ({{ count($parsedProducts) }} códigos unificados)</h6>
                                                <button wire:click="$set('parsedProducts', [])"
                                                    class="btn btn-sm btn-outline-light py-1"
                                                    style="font-size: 0.78rem; border-radius: 8px;">
                                                    <i class="fas fa-undo me-1"></i> Reiniciar e Importar otro lote
                                                </button>
                                            </div>

                                            {{-- Aplicador de valores por defecto --}}
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <div style="width: 140px;">
                                                    <input type="number" step="0.01" wire:model="defaultPrice"
                                                        class="form-control form-control-sm text-white"
                                                        placeholder="Precio por defecto"
                                                        style="background: #1A2235; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">
                                                </div>
                                                <div style="width: 120px;">
                                                    <input type="number" wire:model="defaultStock"
                                                        class="form-control form-control-sm text-white"
                                                        placeholder="Stock por defecto"
                                                        style="background: #1A2235; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;">
                                                </div>
                                                <button wire:click="applyDefaults" class="btn btn-sm fw-bold text-white px-3"
                                                    style="background: #8b5cf6; border-radius: 8px; font-size: 0.78rem; min-height: 31px;">
                                                    <i class="fas fa-check-double me-1"></i> Aplicar a activos
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Tabla editable --}}
                                        <div class="table-responsive rounded-4 shadow-lg"
                                            style="background: #111827; border: 1px solid rgba(255,255,255,0.05); max-height: 480px; overflow-y: auto;">
                                            <table class="table table-dark table-hover mb-0 align-middle"
                                                style="font-size: 0.8rem;">
                                                <thead class="sticky-top bg-dark1 shadow-sm"
                                                    style="background: #1a2235; z-index: 10;">
                                                    <tr>
                                                        <th class="border-0 text-white ps-3 py-3" style="min-width: 110px;">Código
                                                        </th>
                                                        <th class="border-0 text-white py-3" style="min-width: 110px;">Cód. Original
                                                        </th>
                                                        <th class="border-0 text-white py-3" style="min-width: 120px;">Código OEM
                                                        </th>
                                                        <th class="border-0 text-white py-3" style="min-width: 140px;">Modelo Chasis
                                                        </th>
                                                        <th class="border-0 text-white py-3" style="min-width: 140px;">Motor y cc
                                                        </th>
                                                        @if($isPistonsView)
                                                            <th class="border-0 text-white py-3" style="min-width: 70px;">Ø mm</th>
                                                            <th class="border-0 text-white py-3" style="min-width: 55px;">CYL</th>
                                                            <th class="border-0 text-white py-3" style="min-width: 175px;">Medidas
                                                                pistón (L / CH / H)</th>
                                                            <th class="border-0 text-white py-3" style="min-width: 100px;">PIN (Ø×L)
                                                            </th>
                                                            <th class="border-0 text-white py-3" style="min-width: 80px;">Circlip</th>
                                                        @elseif(!$isMetalsView)
                                                            <th class="border-0 text-white py-3" style="min-width: 140px;">Medida
                                                                d1(D)/h1(B)</th>
                                                            <th class="border-0 text-white py-3" style="min-width: 100px;">Radial a1(T)
                                                            </th>
                                                            <th class="border-0 text-white py-3" style="min-width: 120px;">Forma Shape
                                                            </th>
                                                        @endif
                                                        <th class="border-0 text-white py-3" style="min-width: 320px;">Sobremedida
                                                            (Checkbox + Precio / Stock)</th>
                                                        <th class="border-0 text-white py-3" style="min-width: 250px;">Título
                                                            descriptivo</th>
                                                        <th class="border-0 text-white py-3 text-center" style="min-width: 90px;">
                                                            Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($parsedProducts as $index => $p)
                                                        @php
                                                            $isMissing = !$p['found_in_pdf'];
                                                        @endphp
                                                        <tr
                                                            style="{{ $isMissing ? 'background: rgba(245, 158, 11, 0.04); border-left: 4px solid #f59e0b !important;' : '' }}">
                                                            {{-- Código proveedor --}}
                                                            <td class="ps-3 py-2">
                                                                <input type="text"
                                                                    wire:model="parsedProducts.{{ $index }}.supplier_code"
                                                                    class="grid-input" style="font-weight: bold; color: #a78bfa;">
                                                            </td>
                                                            {{-- Código original --}}
                                                            <td class="py-2">
                                                                <input type="text" wire:model="parsedProducts.{{ $index }}.catalog_code"
                                                                    class="grid-input" placeholder="Cód. catálogo">
                                                            </td>
                                                            {{-- OEM codes --}}
                                                            <td class="py-2">
                                                                <input type="text" wire:model="parsedProducts.{{ $index }}.oem_codes"
                                                                    class="grid-input" placeholder="OEMs separados por |">
                                                            </td>
                                                            {{-- Chassis --}}
                                                            <td class="py-2">
                                                                <input type="text" wire:model="parsedProducts.{{ $index }}.chassis"
                                                                    class="grid-input" placeholder="Chasis separados por |">
                                                            </td>
                                                            {{-- Motor y cc --}}
                                                            <td class="py-2">
                                                                <div class="d-flex flex-column gap-1">
                                                                    @if(isset($p['engine_details']) && is_array($p['engine_details']))
                                                                        @foreach($p['engine_details'] as $eIdx => $eDetail)
                                                                            <div
                                                                                class="d-flex align-items-center gap-1 bg-dark bg-opacity-50 p-1 rounded">
                                                                                <input type="text"
                                                                                    wire:model="parsedProducts.{{ $index }}.engine_details.{{ $eIdx }}.engine"
                                                                                    class="grid-input py-0 px-1" placeholder="Motor"
                                                                                    style="flex: 2; height: 22px; font-size: 0.72rem;">
                                                                                <input type="text"
                                                                                    wire:model="parsedProducts.{{ $index }}.engine_details.{{ $eIdx }}.cc"
                                                                                    class="grid-input py-0 px-1" placeholder="CC"
                                                                                    style="flex: 1; height: 22px; font-size: 0.72rem;">
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            @if($isPistonsView)
                                                                {{-- Ø Cilindro --}}
                                                                <td class="py-2">
                                                                    <input type="text" wire:model="parsedProducts.{{ $index }}.bore_mm"
                                                                        class="grid-input" placeholder="68.5">
                                                                </td>
                                                                {{-- Cilindros --}}
                                                                <td class="py-2">
                                                                    <input type="text" wire:model="parsedProducts.{{ $index }}.cylinders"
                                                                        class="grid-input" placeholder="3">
                                                                </td>
                                                                {{-- Medidas pistón: Length / Comp. Height / Height --}}
                                                                <td class="py-2">
                                                                    <div class="d-flex flex-column gap-1">
                                                                        <div class="d-flex align-items-center gap-1">
                                                                            <span class="text-white text-end pe-1"
                                                                                style="font-size:0.65rem; width:45px;">Length</span>
                                                                            <input type="text"
                                                                                wire:model="parsedProducts.{{ $index }}.piston_length"
                                                                                class="grid-input py-0 px-1" placeholder="55.0"
                                                                                style="height:21px; font-size:0.72rem;">
                                                                        </div>
                                                                        <div class="d-flex align-items-center gap-1">
                                                                            <span class="text-white text-end pe-1"
                                                                                style="font-size:0.65rem; width:45px;">Comp.</span>
                                                                            <input type="text"
                                                                                wire:model="parsedProducts.{{ $index }}.comp_height"
                                                                                class="grid-input py-0 px-1" placeholder="25.0"
                                                                                style="height:21px; font-size:0.72rem;">
                                                                        </div>
                                                                        <div class="d-flex align-items-center gap-1">
                                                                            <span class="text-white text-end pe-1"
                                                                                style="font-size:0.65rem; width:45px;">Height 1</span>
                                                                            <input type="text"
                                                                                wire:model="parsedProducts.{{ $index }}.height_1"
                                                                                class="grid-input py-0 px-1" placeholder="-2.5"
                                                                                style="height:21px; font-size:0.72rem;">
                                                                        </div>
                                                                        <div class="d-flex align-items-center gap-1">
                                                                            <span class="text-white text-end pe-1"
                                                                                style="font-size:0.65rem; width:45px;">Height 2</span>
                                                                            <input type="text"
                                                                                wire:model="parsedProducts.{{ $index }}.height_2"
                                                                                class="grid-input py-0 px-1" placeholder="-3.6"
                                                                                style="height:21px; font-size:0.72rem;">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                {{-- PIN --}}
                                                                <td class="py-2">
                                                                    <div class="d-flex flex-column gap-1">
                                                                        <div class="d-flex align-items-center gap-1">
                                                                            <span class="text-muted"
                                                                                style="font-size:0.65rem; width:16px;">Ø</span>
                                                                            <input type="text"
                                                                                wire:model="parsedProducts.{{ $index }}.pin_diameter"
                                                                                class="grid-input py-0 px-1" placeholder="16.0"
                                                                                style="height:21px; font-size:0.72rem;">
                                                                        </div>
                                                                        <div class="d-flex align-items-center gap-1">
                                                                            <span class="text-muted"
                                                                                style="font-size:0.65rem; width:16px;">L</span>
                                                                            <input type="text"
                                                                                wire:model="parsedProducts.{{ $index }}.pin_length"
                                                                                class="grid-input py-0 px-1" placeholder="52.8"
                                                                                style="height:21px; font-size:0.72rem;">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                {{-- Circlip --}}
                                                                <td class="py-2">
                                                                    <select wire:model="parsedProducts.{{ $index }}.circlip"
                                                                        class="grid-input" style="padding: 3px 6px;">
                                                                        <option value="">—</option>
                                                                        <option value="required">required</option>
                                                                        <option value="not required">not req.</option>
                                                                    </select>
                                                                </td>
                                                            @elseif(!$isMetalsView)
                                                                {{-- Medida (bore heights) --}}
                                                                <td class="py-2">
                                                                    <input type="text" wire:model="parsedProducts.{{ $index }}.bore_heights"
                                                                        class="grid-input" placeholder="100 MM 3X2X4">
                                                                </td>
                                                                {{-- Radial --}}
                                                                <td class="py-2">
                                                                    <input type="text" wire:model="parsedProducts.{{ $index }}.radial"
                                                                        class="grid-input" placeholder="Radial a1">
                                                                </td>
                                                                {{-- Shape --}}
                                                                <td class="py-2">
                                                                    <input type="text" wire:model="parsedProducts.{{ $index }}.shape"
                                                                        class="grid-input" placeholder="Shape">
                                                                </td>
                                                            @endif
                                                            {{-- Sobremedidas --}}
                                                            <td class="py-2">
                                                                <div class="d-flex flex-column gap-2" style="font-size: 0.72rem;">
                                                                    <div class="d-flex flex-wrap gap-2">
                                                                        @foreach(array_keys($p['oversizes']) as $ovKey)
                                                                            <label
                                                                                class="d-inline-flex align-items-center gap-1 cursor-pointer text-white px-2 py-1 rounded bg-secondary bg-opacity-25"
                                                                                style="min-width: 60px;">
                                                                                <input type="checkbox"
                                                                                    wire:model.live="parsedProducts.{{ $index }}.oversizes.{{ $ovKey }}.enabled">
                                                                                <span>{{ $ovKey }}</span>
                                                                            </label>
                                                                        @endforeach
                                                                    </div>
                                                                    {{-- Config precio / stock para habilitados --}}
                                                                    <div class="d-flex flex-column gap-1">
                                                                        @foreach($p['oversizes'] as $ovKey => $ovVal)
                                                                            @if($ovVal['enabled'])
                                                                                <div
                                                                                    class="d-flex align-items-center gap-2 bg-dark bg-opacity-50 p-1 rounded">
                                                                                    <span class="text-purple-300 fw-bold text-xs"
                                                                                        style="width: 35px;">{{ $ovKey }}:</span>
                                                                                    <input type="number" step="0.01"
                                                                                        wire:model="parsedProducts.{{ $index }}.oversizes.{{ $ovKey }}.price"
                                                                                        class="grid-input py-0 px-1" placeholder="Precio"
                                                                                        style="max-width: 65px; height: 20px; font-size: 0.7rem;">
                                                                                    <input type="number"
                                                                                        wire:model="parsedProducts.{{ $index }}.oversizes.{{ $ovKey }}.stock"
                                                                                        class="grid-input py-0 px-1" placeholder="Stock"
                                                                                        style="max-width: 55px; height: 20px; font-size: 0.7rem;">
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            {{-- Título --}}
                                                            <td class="py-2">
                                                                <input type="text" wire:model="parsedProducts.{{ $index }}.title"
                                                                    class="grid-input" placeholder="Título descriptivo">
                                                            </td>
                                                            {{-- Estado --}}
                                                            <td class="py-2 text-center">
                                                                @if($p['found_in_pdf'])
                                                                    <span
                                                                        class="badge rounded-pill bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-2 py-1"><i
                                                                            class="fas fa-check-circle me-1"></i>Catálogo OK</span>
                                                                @else
                                                                    <span
                                                                        class="badge rounded-pill bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 px-2 py-1"><i
                                                                            class="fas fa-exclamation-triangle me-1"></i>Manual</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <button wire:click="saveImport"
                                            class="btn btn-purple-glow w-100 py-3 mt-4 fw-bold rounded-4 shadow-lg bg-green-glow border-0 text-white">
                                            <i class="fas fa-cloud-upload-alt me-2"></i> CONFIRMAR Y GUARDAR TODO
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @endif

    <style>
        .hover-white:hover {
            color: white !important;
        }

        .bg-purple-light {
            background: rgba(139, 92, 246, 0.1);
        }

        .text-purple-glow {
            color: #8b5cf6;
            text-shadow: 0 0 10px rgba(139, 92, 246, 0.3);
        }

        .btn-purple-glow {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            font-family: 'Syne', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3);
            transition: 0.3s;
        }

        .btn-purple-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.5);
            color: white;
        }

        .bg-green-glow {
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }

        .font-monospace {
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
        }

        .bg-dark1 {
            background: #1A2235;
        }

        .text-gray-300 {
            color: #d1d5db;
        }

        .text-gray-400 {
            color: #9ca3af;
        }

        .grid-input {
            width: 100%;
            background: #1a2035;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 6px;
            color: #ffffff;
            font-size: 0.76rem;
            padding: 4px 8px;
            transition: border-color 0.2s;
        }

        .grid-input:focus {
            outline: none;
            border-color: #8b5cf6;
            background: #202740;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
</div>