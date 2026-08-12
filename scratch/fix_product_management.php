<?php
$file = 'app/Livewire/Admin/ProductManagement.php';
$lines = file($file);
$totalLines = count($lines);

// Find line 857 (closing brace of scanFormCatalog) and line 1042 (render())
// We need to find the first "public function render()" after line 857
$scanEndLine = null;
$renderLine = null;

for ($i = 856; $i < $totalLines; $i++) {
    if ($scanEndLine === null && trim($lines[$i]) === '}') {
        $scanEndLine = $i;
    }
    if (strpos($lines[$i], 'public function render()') !== false) {
        $renderLine = $i;
        break;
    }
}

echo "scanFormCatalog ends at line: " . ($scanEndLine + 1) . "\n";
echo "render() starts at line: " . ($renderLine + 1) . "\n";

if ($scanEndLine !== null && $renderLine !== null && $renderLine > $scanEndLine + 1) {
    // Remove lines between scanEndLine and renderLine
    $newLines = array_merge(
        array_slice($lines, 0, $scanEndLine + 1),
        ["\r\n"],
        array_slice($lines, $renderLine)
    );
    file_put_contents($file, implode('', $newLines));
    echo "Removed " . ($renderLine - $scanEndLine - 1) . " duplicate lines.\n";
} else {
    echo "Nothing to remove or structure not found.\n";
}
