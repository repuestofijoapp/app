<?php
try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/1a.db');

    $tableName = 'placasX';
    $cols = $db->query("PRAGMA table_info($tableName)");
    $columns = [];
    while ($col = $cols->fetch(PDO::FETCH_ASSOC)) {
        $columns[] = $col['name'];
    }

    $samples = $db->query("SELECT * FROM $tableName LIMIT 5");
    $data = $samples->fetchAll(PDO::FETCH_ASSOC);

    echo "COLUMNS:\n" . implode(", ", $columns) . "\n\n";
    echo "DATA_JSON:\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
