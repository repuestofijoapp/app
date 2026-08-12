<?php
try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/1a.db');
    $tableName = 'placasX';

    // Get column names
    $cols = $db->query("PRAGMA table_info($tableName)");
    echo "COLUMNS:\n";
    while ($col = $cols->fetch(PDO::FETCH_ASSOC)) {
        echo "- " . $col['name'] . "\n";
    }

    echo "\nSAMPLES (5 records):\n";
    $samples = $db->query("SELECT * FROM $tableName LIMIT 5");
    $data = $samples->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
