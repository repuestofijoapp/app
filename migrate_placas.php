<?php

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/1a.db');
    $tableName = 'placasX';

    $results = $db->query("SELECT * FROM $tableName");
    $records = $results->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;
    foreach ($records as $row) {
        Vehicle::updateOrCreate(
        ['plate' => $row['Placa']],
        [
            'vin' => $row['VIN'] ?? null,
            'engine_code' => $row['Motor'] ?? null,
            'brand' => $row['Marca'] ?? null,
            'model' => $row['Modelo'] ?? null,
            'year' => (int)($row['Anio'] ?? 0),
            'body_type' => $row['Carroceria'] ?? null,
            'version_no' => $row['Version'] ?? null,
            'engine_power' => $row['potencia_motor'] ?? null,
            'fuel_type' => $row['t_combustible'] ?? null,
            'cylinders' => (int)($row['n_cilindros'] ?? null),
            'displacement' => $row['Cilindrada'] ?? null,
            'weight_net' => $row['p_neto'] ?? null,
            'payload' => $row['carga_util'] ?? null,
            'weight_gross' => $row['p_bruto'] ?? null,
            'seats' => (int)($row['asientos'] ?? null),
            'length' => $row['Longitud'] ?? null,
            'width' => $row['ancho'] ?? null,
            'height' => $row['altura'] ?? null,
            'wheel_formula' => $row['formula_rodante'] ?? null,
            'passengers' => (int)($row['pasajeros'] ?? null),
            'wheels' => (int)($row['n_ruedas'] ?? null),
            'axles' => (int)($row['n_ejes'] ?? null),
        ]
        );
        $count++;
    }

    echo "Migración completada. Se procesaron $count registros en la tabla vehicles.\n";
}
catch (Exception $e) {
    echo "Error durante la migración: " . $e->getMessage() . "\n";
}
