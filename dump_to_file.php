<?php
try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/1a.db');
    $sample = $db->query("SELECT * FROM placasX LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $out = "";
    if ($sample) {
        $fields = ['Placa', 'Motor', 'Serie', 'VIN', 'Marca', 'Modelo', 'Version', 'p_neto', 'p_bruto', 'carga_util'];
        foreach ($fields as $f) {
            echo "[$f] => " . ($sample[$f] ?? 'N/A') . "\n";
        }
        file_put_contents('debug_placas_v2.txt', "Dump v2\n"); // Just as marker
    }
    else {
        echo "No data found.";
    }
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
