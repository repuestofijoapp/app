<?php
try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/1a.db');
    $sample = $db->query("SELECT * FROM placasX WHERE Placa IS NOT NULL LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $out = "";
    if ($sample) {
        foreach ($sample as $key => $val) {
            $out .= "[$key] => " . ($val ?? 'NULL') . "\n";
        }
        file_put_contents('final_inspection.txt', $out);
        echo "Check final_inspection.txt";
    }
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
