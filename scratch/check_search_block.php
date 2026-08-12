<?php
$file = 'resources/views/livewire/search-components/main-search.blade.php';
$lines = file($file);
// Show lines 579-600
for ($i = 578; $i < 602 && $i < count($lines); $i++) {
    echo ($i+1) . ": " . $lines[$i];
}
