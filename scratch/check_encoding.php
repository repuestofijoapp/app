<?php
$file = 'resources/views/livewire/search-components/main-search.blade.php';
$content = file_get_contents($file);
$valid = mb_check_encoding($content, 'UTF-8');
echo $valid ? "UTF-8 VALIDO\n" : "UTF-8 INVALIDO - HAY CARACTERES MALOS\n";

// Find lines with 'digo Original'
$lines = explode("\n", $content);
foreach ($lines as $i => $line) {
    if (strpos($line, 'digo Original') !== false) {
        echo "Linea " . ($i+1) . ": " . trim($line) . "\n";
    }
}
