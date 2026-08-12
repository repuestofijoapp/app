<?php
try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/placasX1000.db');

    // Get table names
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);

    $output = "";
    $output .= "TABLES: " . implode(", ", $tables) . "\n\n";

    $plates = ['AAA287', 'AAA723'];
    foreach ($plates as $plate) {
        $stmt = $db->prepare("SELECT rowid, * FROM placasX WHERE Placa = ?");
        $stmt->execute([$plate]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "PLATE $plate: " . json_encode($res) . "\n";
    }

    foreach ($tables as $tableName) {
        if ($tableName === 'sqlite_sequence')
            continue;

        $cols = $db->query("PRAGMA table_info($tableName)");
        $columns = [];
        while ($col = $cols->fetch(PDO::FETCH_ASSOC)) {
            $columns[] = $col['name'];
        }

        $output .= "TABLE: $tableName\n";
        $output .= "COLUMNS: " . implode(", ", $columns) . "\n";

        $samples = $db->query("SELECT * FROM $tableName LIMIT 20");
        $data = $samples->fetchAll(PDO::FETCH_ASSOC);
        $output .= "DATA:\n";
        foreach ($data as $row) {
            $output .= json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
        }
        $output .= "\n";
    }
    file_put_contents('e:/xampp/htdocs/fast2/Repuestofijo/source_inspection.txt', $output);
    echo "Done. Saved to source_inspection.txt\n";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
