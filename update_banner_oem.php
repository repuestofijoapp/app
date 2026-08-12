<?php

$file = 'resources/views/livewire/search-components/main-search.blade.php';
$content = file_get_contents($file);

$bannerStart = <<<'EOD'
                    <div class="bg-white p-3 p-md-4 rounded shadow-sm border border-light d-flex flex-nowrap overflow-auto align-items-center">
                        
                        @if($searchType === 'oem')
                            <div>
                                <h4 class="mb-0 fw-medium text-dark" style="letter-spacing: 0.5px;">
                                    Resultados: <span class="fw-bold text-danger text-uppercase">{{ $oemSearch }}</span>
                                </h4>
                                <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                    {{ isset($products) ? $products->total() : 0 }} producto(s) encontrado(s)
                                </div>
                            </div>
                        @else
                            {{-- STEP 1: VEHICLE / ENGINE --}}
EOD;

$content = str_replace(
    '<div class="bg-white p-3 p-md-4 rounded shadow-sm border border-light d-flex flex-nowrap overflow-auto align-items-center">
                        
                        {{-- STEP 1: VEHICLE / ENGINE --}}',
    $bannerStart,
    $content
);

$bannerEnd = <<<'EOD'
                                </div>
                            </div>
                        @endif
                        @endif
EOD;

$content = str_replace(
    '                                </div>
                            </div>
                        @endif

                    </div>',
    $bannerEnd . "\n\n                    </div>",
    $content
);

file_put_contents($file, $content);
echo "Done.\n";
