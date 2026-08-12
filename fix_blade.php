<?php
$file = 'resources/views/livewire/search-components/main-search.blade.php';
$content = file_get_contents($file);

$search = '                    <h5 class="fw-medium text-dark mb-4 border-bottom pb-2">CATEGORÍAS DISPONIBLES</h5>

                                                </div>
                                                @if($product->oem_code || !empty($product->additional_oem_codes))';

$replace = '                    <h5 class="fw-medium text-dark mb-4 border-bottom pb-2">CATEGORÍAS DISPONIBLES</h5>

                    <div class="row g-3">
                        @foreach($categories as $category)
                            <div class="col-6 col-md-3">
                                <div class="card bg-white p-3 p-md-4 text-center category-card border border-light shadow-sm h-100"
                                    wire:click="selectCategory({{ $category->id }})">
                                    <div class="mb-3 d-flex align-items-center justify-content-center" style="height: 80px;">
                                        @if($category->image_url)
                                            <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}"
                                                class="img-fluid" style="max-height: 80px; width: auto; object-fit: contain;">
                                        @else
                                            <i class="{{ $category->icon ?? \'fas fa-cogs\' }} fa-2x text-primary-custom"></i>
                                        @endif
                                    </div>
                                    <h6 class="mb-0 fw-medium text-dark small">{{ $category->name }}</h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 4.2 SUBCATEGORIES --}}
            @if($viewState === \'subcategories\' && $selectedCategory)
                <div class="py-4">
                    <div
                        class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded shadow-sm border border-light">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-custom text-white rounded p-3 me-3 shadow-sm">
                                <i class="{{ $selectedCategory->icon ?? \'fas fa-th-large\' }} fa-2x"></i>
                            </div>
                            <div>
                                <h3 class="mb-0 fw-medium text-dark">{{ $selectedCategory->name }}</h3>
                                <p class="mb-0 text-muted">Seleccione una subcategoría para
                                    {{ $vehicle ? $vehicle->model : \'su vehículo\' }}
                                </p>
                            </div>
                        </div>
                        <button class="btn btn-outline-secondary px-4 fw-medium d-flex align-items-center gap-2" wire:click="goBack">
                            <i class="fas fa-arrow-left"></i> Volver a Categorías
                        </button>
                    </div>

                    <div class="row g-3">
                        @foreach($selectedCategory->children as $sub)
                            <div class="col-md-3">
                                <div class="card bg-white p-4 text-center category-card border border-light shadow-sm h-100"
                                    wire:click="selectSubcategory({{ $sub->id }})">
                                    <div class="mb-3 text-primary-custom">
                                        <i class="fas fa-arrow-right fa-lg"></i>
                                    </div>
                                    <h6 class="mb-0 fw-medium text-dark">{{ $sub->name }}</h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 4.3 PRODUCTS LIST --}}
            @if($viewState === \'products_list\')
                <div class="py-4">
                    <div
                        class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 bg-white p-4 rounded shadow-sm border border-light gap-3">
                        <div class="d-flex align-items-center">
                            <div class="text-white rounded p-1 me-3 shadow-sm d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <img src="{{ asset(\'images/cars/Car_hide.webp\') }}" class="img-fluid"
                                    style="max-height: 45px;">
                            </div>
                            <div>
                                @if($selectedSubcategory)
                                    @if($vehicle)
                                        <div class="small text-muted mb-1">
                                            {{ $vehicle->brand }} {{ $vehicle->model }} &rsaquo; {{ $selectedCategory->name ?? \'\' }}
                                        </div>
                                    @endif
                                    <h3 class="mb-0 fw-medium text-dark">{{ $selectedSubcategory->name }}</h3>
                                @elseif($searchType === \'oem\')
                                    <h3 class="mb-0 fw-medium text-dark">Resultados para: <span
                                            class="text-primary-custom">{{ $oemSearch }}</span></h3>
                                @elseif($selectedEngineObj)
                                    <h3 class="mb-0 fw-medium text-dark">
                                        {{ strtoupper($selectedEngineObj[\'brand\']) }}
                                        {{ strtoupper($selectedEngineObj[\'model\']) }}
                                        @if($selectedEngineObj[\'engine_code\'])
                                            <span class="badge bg-secondary ms-2"
                                                style="font-size:0.8rem;">{{ $selectedEngineObj[\'engine_code\'] }}</span>
                                        @endif
                                    </h3>
                                @endif
                                <p class="mb-0 text-muted">{{ $products->total() }} producto(s) encontrado(s)</p>
                            </div>
                        </div>
                        <button class="btn btn-outline-secondary px-4 fw-medium d-flex align-items-center gap-2" wire:click="goBack">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                    </div>

                    <style>
                        @media (min-width: 768px) {
                            .product-card-inner {
                                padding-left: 100px !important;
                                padding-right: 100px !important;
                            }
                        }
                    </style>
                    <div class="row g-4">
                        @foreach($products as $product)
                            @php
                                $activeOversizes = $product->oversizes->where(\'is_active\', true)->sortBy(\'oversize\');
                                $hasMultipleOversizes = $activeOversizes->count() > 1;
                            @endphp
                            <div class="col-12"
                                 x-data="{ selectedOversize: \'\' }"
                                 wire:key="prod-card-{{ $product->id }}-{{ $activeOversizes->pluck(\'oversize\')->implode(\'-\') }}">
                                <div class="card bg-white border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
                                    <div class="d-flex flex-column flex-md-row align-items-center gap-0 product-card-inner">

                                        {{-- Imagen --}}
                                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 p-4"
                                            style="width: 220px; cursor: pointer;" wire:click="openDetails({{ $product->id }})">
                                            <img src="{{ $product->image_url ?? \'https://via.placeholder.com/150\' }}"
                                                class="img-fluid hover-scale-sm"
                                                style="max-height: 200px; max-width: 200px; object-fit: contain; transition: all 0.2s ease;">
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-grow-1 p-4 ps-md-2" style="min-width: 0;">

                                            {{-- Fila 1: Título + Código --}}
                                            <h3 class="fw-bold text-dark mb-1 hover-text-danger" style="font-size: 1.25rem; line-height: 1.3; cursor: pointer; transition: color 0.2s ease;" wire:click="openDetails({{ $product->id }})">
                                                {{ $product->name }}
                                            </h3>
                                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-1 gap-md-2 mb-3" style="font-size: 0.88rem;">
                                                <div>
                                                    <span class="text-muted">Código:</span>
                                                    <span class="fw-bold text-primary">{{ $product->supplier_code }}</span>
                                                </div>
                                                @if($product->oem_code || !empty($product->additional_oem_codes))';

$newContent = str_replace($search, $replace, $content);
file_put_contents($file, $newContent);
echo "Replaced successfully!\n";
