<?php

$file = 'resources/views/livewire/search-components/main-search.blade.php';
$content = file_get_contents($file);

$newBanner = <<<'EOD'
            {{-- UNIVERSAL BREADCRUMB BANNER --}}
            @if(in_array($viewState, ['vehicle_found', 'subcategories', 'products_list']) && ($vehicle || $selectedEngineObj || $searchType === 'oem'))
                <div class="position-sticky z-3 mb-4" style="top: 100px;">
                    <div class="bg-white p-3 p-md-4 rounded shadow-sm border border-light d-flex flex-nowrap overflow-auto align-items-center">
                        
                        {{-- STEP 1: VEHICLE / ENGINE --}}
                        <div class="d-flex align-items-center me-3" style="min-width: max-content;">
                            <div class="bg-primary-custom text-white rounded p-2 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-car fa-lg"></i>
                            </div>
                            <div class="cursor-pointer hover-opacity" wire:click="clearManualSearch" title="Volver al inicio">
                                <h4 class="mb-0 fw-medium text-dark text-uppercase" style="letter-spacing: 0.5px;">
                                    @if($vehicle)
                                        {{ $vehicle->brand }} {{ $vehicle->model }}
                                    @elseif($selectedEngineObj)
                                        {{ strtoupper($selectedEngineObj['brand']) }} {{ strtoupper($selectedEngineObj['model'] ?? '') }}
                                    @endif
                                </h4>
                                <div class="small text-muted mt-1" style="line-height: 1.4; font-size: 0.75rem;">
                                    @if($vehicle && $searchType === 'plate')
                                        <div>Placa: {{ $vehicle->plate }}</div>
                                    @endif
                                    <div class="d-flex gap-3">
                                        @if($vehicle && $vehicle->engine_code)
                                            <div>Motor: <span class="fw-bold text-dark">{{ $vehicle->engine_code }}</span></div>
                                        @elseif($selectedEngineObj && $selectedEngineObj['engine_code'])
                                            <div>Motor: <span class="fw-bold text-dark">{{ $selectedEngineObj['engine_code'] }}</span></div>
                                        @endif
                                        @if($vehicle && $vehicle->body_type && $searchType === 'plate')
                                            <div>Carrocería: <span class="fw-bold text-dark">{{ $vehicle->body_type }}</span></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2: CATEGORY --}}
                        @if(in_array($viewState, ['subcategories', 'products_list']))
                            <div class="d-flex align-items-center me-3" style="min-width: max-content;">
                                <i class="fas fa-angle-double-right fa-2x text-dark mx-3 opacity-75"></i>
                                <div class="cursor-pointer hover-opacity" wire:click="$set('viewState', 'vehicle_found')" title="Volver a Categorías">
                                    <h4 class="mb-0 fw-medium text-dark" style="letter-spacing: 0.5px;">
                                        {{ $selectedCategory->name ?? 'Categorías' }}
                                    </h4>
                                    <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                        
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- STEP 3: SUBCATEGORY / PRODUCTS --}}
                        @if($viewState === 'products_list')
                            <div class="d-flex align-items-center" style="min-width: max-content;">
                                <i class="fas fa-angle-double-right fa-2x text-dark mx-3 opacity-75"></i>
                                <div>
                                    <h4 class="mb-0 fw-bold text-danger" style="letter-spacing: 0.5px;">
                                        @if($selectedSubcategory)
                                            {{ $selectedSubcategory->name }}
                                        @elseif($searchType === 'oem')
                                            Resultados: {{ $oemSearch }}
                                        @endif
                                    </h4>
                                    <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                        {{ isset($products) ? $products->total() : 0 }} producto(s) encontrado(s)
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif
EOD;

// We need to replace the current universal banner.
$startSearch = '            {{-- UNIVERSAL BREADCRUMB BANNER --}}';
$endSearch = '            {{-- 4. INLINE RESULTS --}}';

$startPos = strpos($content, $startSearch);
$endPos = strpos($content, $endSearch, $startPos);

if ($startPos !== false && $endPos !== false) {
    $content = substr($content, 0, $startPos) . $newBanner . "\n" . substr($content, $endPos);
    file_put_contents($file, $content);
    echo "Banner updated successfully.\n";
} else {
    echo "Could not find banner bounds.\n";
}
