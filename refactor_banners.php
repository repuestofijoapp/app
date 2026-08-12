<?php

$file = 'resources/views/livewire/search-components/main-search.blade.php';
$content = file_get_contents($file);

// 1. Vehicle found banner
$start1 = '                    <div
                        class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded shadow-sm border border-light position-sticky z-3" style="top: 100px;">';
$end1 = '                    <h5 class="fw-medium text-dark mb-4 border-bottom pb-2">CATEGORÍAS DISPONIBLES</h5>';

$startPos = strpos($content, $start1);
if ($startPos !== false) {
    $endPos = strpos($content, $end1, $startPos);
    if ($endPos !== false) {
        $content = substr($content, 0, $startPos) . substr($content, $endPos);
        echo "Removed 1\n";
    }
}

// 2. Subcategories banner
$start2 = '                    <div
                        class="d-flex align-items-center justify-content-between mb-4 bg-white p-4 rounded shadow-sm border border-light position-sticky z-3" style="top: 100px;">';
$end2 = '                    <div class="row g-3">';

$startPos = strpos($content, $start2);
if ($startPos !== false) {
    $endPos = strpos($content, $end2, $startPos);
    if ($endPos !== false) {
        $content = substr($content, 0, $startPos) . substr($content, $endPos);
        echo "Removed 2\n";
    }
}

// 3. Products list banner
$start3 = '                    <div
                        class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 bg-white p-4 rounded shadow-sm border border-light gap-3 position-sticky" style="top: 100px; z-index: 1020;">';
$end3 = '                    <div class="row g-4">';

$startPos = strpos($content, $start3);
if ($startPos !== false) {
    $endPos = strpos($content, $end3, $startPos);
    if ($endPos !== false) {
        $content = substr($content, 0, $startPos) . substr($content, $endPos);
        echo "Removed 3\n";
    }
}

file_put_contents($file, $content);
