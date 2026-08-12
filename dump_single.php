<?php
try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/1a.db');
    $sample = $db->query("SELECT * FROM placasX WHERE Placa IS NOT NULL LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($sample) {
        foreach ($sample as $key => $val) {
            echo "[$key] => $val\n";
        }
    }
    else {
        echo "No data found.";
    }
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
