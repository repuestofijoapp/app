<?php
$file = 'resources/views/livewire/search-components/main-search.blade.php';
$content = file_get_contents($file);

// Replace "Código Original" to HTML entity "&oacute;digo Original" to avoid encoding issues completely
$content = str_replace('Código Original:', 'C&oacute;digo Original:', $content);
$content = str_replace('Cód. Original:', 'C&oacute;d. Original:', $content);

// Convert encoding if it is not valid UTF-8
if (!mb_check_encoding($content, 'UTF-8')) {
    $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
}

file_put_contents($file, $content);
echo "Codificación corregida!\n";
