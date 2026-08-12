<?php

use App\Models\Vehicle;

try {
    $db = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/1a.db');
    $tableName = 'placasX';

    $results = $db->query("SELECT * FROM $tableName");
    $records = $results->fetchAll(PDO::FETCH_ASSOC);

    $count = 0;
    foreach ($records as $row) {
        $fullMotor = $row['Motor'] ?? null;
        $engineCode = null;

        if ($fullMotor) {
            // Lógica Heurística: Separar el código (prefijo) del número de serie (final numérico largo)
            // Ejemplo: DL465Q5056563 -> DL465Q (código) y DL465Q5056563 (motor completo)
            // Buscamos la posición donde terminan los caracteres o números cortos y empieza una secuencia de 6+ dígitos
            if (preg_match('/^(.+?)([0-9]{6,})$/', $fullMotor, $matches)) {
                $engineCode = $matches[1];
            }
            else {
                // Si no hay un patrón claro, dejamos el código vacío para que no se duplique lo mismo
                $engineCode = $fullMotor;
            }
        }

        Vehicle::updateOrCreate(
        ['plate' => $row['Placa']],
        [
            'vin' => $row['VIN'] ?? null,
            'engine_code' => $engineCode,
            'engine_no' => $fullMotor, // El número completo va aquí
            'brand' => $row['Marca'] ?? null,
            'model' => $row['Modelo'] ?? null,
            'year' => (int)($row['Año'] ?? $row['a_modelo'] ?? 0),
            'body_type' => $row['Carroceria'] ?? null,
            'version_no' => $row['Version'] ?? null,
            'engine_power' => $row['potencia_motor'] ?? null,
            'fuel_type' => $row['t_combustible'] ?? null,
            'cylinders' => (int)($row['n_cilindros'] ?? null),
            'displacement' => $row['Cilindrada'] ?? null,
            'weight_dry' => $row['p_neto'] ?? null, // Peso Seco = Neto
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

    echo "Refinamiento completado. Se actualizaron $count registros.\n";
}
catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
