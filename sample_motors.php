<?php
try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/1a.db');
    $results = $db->query("SELECT Motor FROM placasX WHERE Motor IS NOT NULL LIMIT 15");
    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Motor'] . "\n";
    }
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
