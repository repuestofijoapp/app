<?php
$file = 'resources/views/livewire/search-components/main-search.blade.php';
$content = file_get_contents($file);

// Flexibly match @if($product->oem_code) ... @endif ignoring accents or exact wordings
$pattern2 = '/@if\s*\(\s*\$product->oem_code\s*\)[\s\S]*?Original:[\s\S]*?@endif/';
$replace = '@if($product->oem_code || !empty($product->additional_oem_codes))
                                                    <div class="d-none d-md-block text-muted px-1">|</div>
                                                    <div>
                                                        <span class="text-muted">Código Original:</span>
                                                        @php
                                                            $allOems = array_filter(array_unique(array_merge(
                                                                array_filter(array_map("trim", explode(",", $product->oem_code ?? ""))),
                                                                $product->additional_oem_codes ?? []
                                                            )));
                                                        @endphp
                                                        <span class="fw-bold text-primary">{{ implode(", ", $allOems) }}</span>
                                                    </div>
                                                @endif';

$cleanContent2 = preg_replace($pattern2, $replace, $content, 1, $count2);

if ($count2 > 0) {
    file_put_contents($file, $cleanContent2);
    echo "Reemplazo robusto 2 exitoso! ($count2 reemplazos)\n";
} else {
    echo "No se pudo emparejar el patrón.\n";
}
