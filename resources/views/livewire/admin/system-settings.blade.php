<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">Configuración del Sistema
            </h1>
            <p class="text-white text-sm opacity-75">Ajustes globales y preferencias de la plataforma.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert"
            style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399;">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-4">

        {{-- ─── Módulo de Búsqueda ──────────────────────────────────────── --}}
        <div class="col-md-6">
            <div class="card border-secondary border-opacity-25 shadow-sm rounded-4 h-100"
                style="background-color: var(--surface);">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2"
                        style="font-family: 'Syne', sans-serif;">
                        <i class="fas fa-search text-primary"></i> Módulo de Búsqueda
                    </h5>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3"
                        style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1);">
                        <div>
                            <h6 class="text-white fw-bold mb-1">Búsqueda por Placa</h6>
                            <p class="text-white small mb-0">Habilita o deshabilita la búsqueda de modelos de auto por
                                número de placa en la vista pública.</p>
                        </div>
                        <div class="form-check form-switch fs-4 mb-0 ms-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="plateSearchSwitch"
                                wire:click="togglePlateSearch" {{ $enable_plate_search ? 'checked' : '' }}
                                style="cursor: pointer; {{ $enable_plate_search ? 'background-color: var(--accent-red); border-color: var(--accent-red);' : '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── Banner / Slider Promocional ────────────────────────────── --}}
        <div class="col-12">
            <div class="card border-secondary border-opacity-25 shadow-sm rounded-4"
                style="background-color: var(--surface);">
                <div class="card-body p-4">

                    <h5 class="fw-bold text-white mb-1 d-flex align-items-center gap-2"
                        style="font-family: 'Syne', sans-serif;">
                        <i class="fas fa-images" style="color: var(--accent-red);"></i> Banner Promocional Principal
                    </h5>
                    <p class="text-white opacity-50 small mb-4">
                        Las imágenes se muestran como un slider automático en la página de búsqueda principal.
                        Tamaño recomendado: <strong>900 × 380 px</strong>.
                    </p>

                    {{-- ── Formulario añadir slide ──────────────────────── --}}
                    <div class="p-4 rounded-4 mb-4"
                        style="background: rgba(139,92,246,0.06); border: 1px dashed rgba(139,92,246,0.35);">
                        <h6 class="text-white fw-bold mb-3"><i
                                class="fas fa-plus-circle me-2 text-purple-glow"></i>Añadir nuevo slide</h6>

                        <div class="row g-3">
                            {{-- Imagen --}}
                            <div class="col-12">
                                <label class="small fw-bold text-white mb-1 d-block">IMAGEN DEL BANNER *</label>
                                <input type="file" wire:model="newSlideImage" accept="image/*"
                                    class="form-control border-0 text-white"
                                    style="background:#1E293B; border-radius:10px; padding:10px;">
                                <div wire:loading wire:target="newSlideImage" class="text-info small mt-1">
                                    <i class="fas fa-spinner fa-spin me-1"></i> Subiendo imagen...
                                </div>
                                @error('newSlideImage') <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror

                                @if($newSlideImage)
                                    <div class="mt-2">
                                        <img src="{{ $newSlideImage->temporaryUrl() }}" class="rounded-3 shadow"
                                            style="max-height:120px; object-fit:cover; border:2px solid rgba(139,92,246,0.4);">
                                    </div>
                                @endif
                            </div>

                            {{-- Texto opcional --}}
                            <div class="col-md-6">
                                <label class="small fw-bold text-white mb-1 d-block">TÍTULO (opcional)</label>
                                <input type="text" wire:model="newSlideTitle" class="form-control border-0 text-white"
                                    style="background:#1E293B; border-radius:10px;"
                                    placeholder="Ej: ¡Oferta de temporada!">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-white mb-1 d-block">SUBTÍTULO (opcional)</label>
                                <input type="text" wire:model="newSlideSubtitle"
                                    class="form-control border-0 text-white"
                                    style="background:#1E293B; border-radius:10px;"
                                    placeholder="Ej: Hasta 30% en metales de motor">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-white mb-1 d-block">TEXTO DEL BOTÓN
                                    (opcional)</label>
                                <input type="text" wire:model="newSlideBtnText" class="form-control border-0 text-white"
                                    style="background:#1E293B; border-radius:10px;" placeholder="Ej: VER OFERTAS">
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-white mb-1 d-block">URL DEL BOTÓN (opcional)</label>
                                <input type="url" wire:model="newSlideBtnUrl" class="form-control border-0 text-white"
                                    style="background:#1E293B; border-radius:10px;" placeholder="https://...">
                            </div>

                            <div class="col-12 text-end">
                                <button wire:click="addSlide"
                                    class="btn btn-purple-glow px-4 py-2 fw-bold text-white rounded-3"
                                    wire:loading.attr="disabled" wire:target="addSlide">
                                    <span wire:loading.remove wire:target="addSlide">
                                        <i class="fas fa-plus me-2"></i> Añadir Slide
                                    </span>
                                    <span wire:loading wire:target="addSlide">
                                        <i class="fas fa-spinner fa-spin me-2"></i> Guardando...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ── Lista de slides actuales ─────────────────────── --}}
                    @if(count($slides) === 0)
                        <div class="text-center py-5 text-white opacity-40">
                            <i class="fas fa-images fa-3x mb-3 d-block"></i>
                            <p class="small">No hay slides configurados. Añade el primero arriba.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($slides as $idx => $slide)
                                <div class="col-12" wire:key="slide-{{ $slide['id'] }}">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3"
                                        style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,{{ $slide['active'] ? '0.12' : '0.05' }});">

                                        {{-- Preview imagen --}}
                                        <div style="flex-shrink:0;">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($slide['image_path']) }}"
                                                class="rounded-2 shadow"
                                                style="width:110px; height:62px; object-fit:cover; {{ !$slide['active'] ? 'opacity:0.35;' : '' }}">
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="text-white fw-bold small text-truncate">
                                                {{ $slide['title'] ?: '(Sin título)' }}
                                            </div>
                                            <div class="text-gray-400 text-xs text-truncate">
                                                {{ $slide['subtitle'] ?: '' }}
                                            </div>
                                            <div class="mt-1">
                                                @if($slide['active'])
                                                    <span class="badge rounded-pill"
                                                        style="background:rgba(16,185,129,0.2); color:#34d399; font-size:0.7rem;">
                                                        <i class="fas fa-circle me-1" style="font-size:0.5rem;"></i>Activo
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill"
                                                        style="background:rgba(255,255,255,0.08); color:#9ca3af; font-size:0.7rem;">
                                                        <i class="fas fa-circle me-1" style="font-size:0.5rem;"></i>Oculto
                                                    </span>
                                                @endif
                                                <span class="ms-2 text-gray-400" style="font-size:0.7rem;">Slide
                                                    {{ $idx + 1 }}</span>
                                            </div>
                                        </div>

                                        {{-- Acciones --}}
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            {{-- Mover arriba/abajo --}}
                                            <button wire:click="moveUp({{ $slide['id'] }})"
                                                class="btn btn-sm btn-outline-secondary border-0 text-white p-1"
                                                title="Mover arriba" {{ $idx === 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-chevron-up"></i>
                                            </button>
                                            <button wire:click="moveDown({{ $slide['id'] }})"
                                                class="btn btn-sm btn-outline-secondary border-0 text-white p-1"
                                                title="Mover abajo" {{ $idx === count($slides) - 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-chevron-down"></i>
                                            </button>

                                            {{-- Toggle activo --}}
                                            <button wire:click="toggleSlide({{ $slide['id'] }})"
                                                class="btn btn-sm border-0 p-1 {{ $slide['active'] ? 'text-success' : 'text-secondary' }}"
                                                title="{{ $slide['active'] ? 'Ocultar' : 'Mostrar' }}">
                                                <i class="fas {{ $slide['active'] ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            </button>

                                            {{-- Eliminar --}}
                                            <button wire:click="deleteSlide({{ $slide['id'] }})"
                                                wire:confirm="¿Eliminar este slide? Esta acción no se puede deshacer."
                                                class="btn btn-sm border-0 text-danger p-1" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ─── Novedades / Featured Products ────────────────────────────── --}}
        <div class="col-12">
            <div class="card border-secondary border-opacity-25 shadow-sm rounded-4"
                style="background-color: var(--surface);">
                <div class="card-body p-4">

                    <h5 class="fw-bold text-white mb-1 d-flex align-items-center gap-2"
                        style="font-family: 'Syne', sans-serif;">
                        <i class="fas fa-star text-warning"></i> Novedades en RepuestoFijo
                    </h5>
                    <p class="text-white opacity-50 small mb-4">
                        Selecciona los productos que aparecerán en el carrusel de novedades en la página principal.
                    </p>

                    {{-- ── Buscador para añadir producto ────────────────────── --}}
                    <div class="p-4 rounded-4 mb-4"
                        style="background: rgba(16,185,129,0.06); border: 1px dashed rgba(16,185,129,0.35);">
                        <h6 class="text-white fw-bold mb-3"><i class="fas fa-search me-2 text-success"></i>Añadir producto a novedades</h6>
                        
                        <div class="position-relative">
                            <input type="text" wire:model.live.debounce.300ms="productSearchQuery"
                                class="form-control border-0 text-white w-100"
                                style="background:#1E293B; border-radius:10px; padding:12px 15px;"
                                placeholder="Busca por nombre, código OEM o proveedor (mínimo 3 letras)...">

                            @if(strlen($productSearchQuery) >= 3)
                                <div class="position-absolute w-100 mt-2 rounded-3 shadow-lg z-3 overflow-hidden"
                                    style="background: #1E293B; border: 1px solid rgba(255,255,255,0.1); max-height: 300px; overflow-y: auto;">
                                    @if(count($productSearchResults) > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($productSearchResults as $prod)
                                                <button wire:click="addFeaturedProduct({{ $prod['id'] }})" class="list-group-item list-group-item-action bg-transparent border-bottom text-white text-start px-4 py-3" style="border-color: rgba(255,255,255,0.05) !important;">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="{{ $prod['image_path'] ? asset('storage/' . $prod['image_path']) : 'https://via.placeholder.com/50?text=Sin+Foto' }}" alt="img" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                        <div>
                                                            <div class="fw-bold small">{{ $prod['name'] }}</div>
                                                            <div class="text-gray-400" style="font-size: 0.75rem;">
                                                                OEM: {{ $prod['oem_code'] }} | REF: {{ $prod['supplier_code'] }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-3 text-center text-gray-400 small">
                                            No se encontraron productos que coincidan.
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── Lista de novedades actuales ──────────────────────── --}}
                    @if(count($featuredProducts) === 0)
                        <div class="text-center py-5 text-white opacity-40">
                            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                            <p class="small">No hay productos destacados. Busca y selecciona uno arriba.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($featuredProducts as $idx => $fp)
                                <div class="col-12" wire:key="fp-{{ $fp['id'] }}">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3"
                                        style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,{{ $fp['active'] ? '0.12' : '0.05' }});">

                                        {{-- Preview imagen producto --}}
                                        <div style="flex-shrink:0;">
                                            <img src="{{ $fp['product']['image_path'] ? asset('storage/' . $fp['product']['image_path']) : 'https://via.placeholder.com/60?text=No+Foto' }}"
                                                class="rounded-2 shadow"
                                                style="width:60px; height:60px; object-fit:cover; {{ !$fp['active'] ? 'opacity:0.35;' : '' }}">
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="text-white fw-bold small text-truncate">
                                                {{ $fp['product']['name'] }}
                                            </div>
                                            <div class="text-gray-400 text-xs text-truncate">
                                                OEM: {{ $fp['product']['oem_code'] ?? 'N/A' }} | Proveedor: {{ $fp['product']['supplier_code'] ?? 'N/A' }}
                                            </div>
                                            <div class="mt-1">
                                                @if($fp['active'])
                                                    <span class="badge rounded-pill" style="background:rgba(16,185,129,0.2); color:#34d399; font-size:0.7rem;">
                                                        <i class="fas fa-circle me-1" style="font-size:0.5rem;"></i>Activo
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill" style="background:rgba(255,255,255,0.08); color:#9ca3af; font-size:0.7rem;">
                                                        <i class="fas fa-circle me-1" style="font-size:0.5rem;"></i>Oculto
                                                    </span>
                                                @endif
                                                <span class="ms-2 text-gray-400" style="font-size:0.7rem;">Orden {{ $idx + 1 }}</span>
                                            </div>
                                        </div>

                                        {{-- Acciones --}}
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            {{-- Mover arriba/abajo --}}
                                            <button wire:click="moveUpFeatured({{ $fp['id'] }})"
                                                class="btn btn-sm btn-outline-secondary border-0 text-white p-1"
                                                title="Mover arriba" {{ $idx === 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-chevron-up"></i>
                                            </button>
                                            <button wire:click="moveDownFeatured({{ $fp['id'] }})"
                                                class="btn btn-sm btn-outline-secondary border-0 text-white p-1"
                                                title="Mover abajo" {{ $idx === count($featuredProducts) - 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-chevron-down"></i>
                                            </button>

                                            {{-- Toggle activo --}}
                                            <button wire:click="toggleFeaturedProduct({{ $fp['id'] }})"
                                                class="btn btn-sm border-0 p-1 {{ $fp['active'] ? 'text-success' : 'text-secondary' }}"
                                                title="{{ $fp['active'] ? 'Ocultar' : 'Mostrar' }}">
                                                <i class="fas {{ $fp['active'] ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                            </button>

                                            {{-- Eliminar --}}
                                            <button wire:click="deleteFeaturedProduct({{ $fp['id'] }})"
                                                wire:confirm="¿Quitar este producto de novedades?"
                                                class="btn btn-sm border-0 text-danger p-1" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>