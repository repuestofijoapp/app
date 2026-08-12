<?php
require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'repuestofijo',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    $sourceDb = new PDO('sqlite:e:/xampp/htdocs/fast2/DBplacas/placasX1000.db');

    // El punto de inicio es rowid > 277 (Placa AAA723)
    $startRowId = 277;

    echo "Iniciando importación desde rowid > $startRowId...\n";

    $stmt = $sourceDb->prepare("SELECT rowid, * FROM placasX WHERE rowid > ? ORDER BY rowid ASC");
    $stmt->execute([$startRowId]);

    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extraer engine_code del Motor (tomar primera palabra o prefijo alfanumérico)
        $engine_no = $row['Motor'] ?? null;
        $engine_code = null;
        if ($engine_no) {
            // Lógica simple: tomar hasta el primer número o longitud fija si hay patrón
            // En el proyecto parece que toman una parte significativa
            if (preg_match('/^[A-Z0-9]+/', $engine_no, $matches)) {
                $engine_code = $matches[0];
            }
        }

        Capsule::table('vehicles')->updateOrInsert(
        ['plate' => $row['Placa']],
        [
            'vin' => $row['VIN'] ?? null,
            'engine_code' => $engine_code,
            'engine_no' => $engine_no,
            'brand' => $row['Marca'] ?? null,
            'model' => $row['Modelo'] ?? null,
            'year' => (int)($row['Año'] ?: $row['a_modelo'] ?: 0),
            'manufacturing_year' => $row['a_modelo'] ?? null,
            'body_type' => $row['Carroceria'] ?? null,
            'color' => null, // No viene en el origen
            'version_no' => $row['Version'] ?? null,
            'engine_power' => $row['potencia_motor'] ?? null,
            'fuel_type' => $row['t_combustible'] ?? null,
            'cylinders' => (int)($row['n_cilindros'] ?: null),
            'displacement' => $row['Cilindrada'] ?? null,
            'weight_dry' => $row['p_neto'] ?? null,
            'weight_net' => $row['p_neto'] ?? null,
            'payload' => $row['carga_util'] ?? null,
            'weight_gross' => $row['p_bruto'] ?? null,
            'seats' => (int)($row['asientos'] ?: null),
            'length' => $row['Longitud'] ?? null,
            'width' => $row['ancho'] ?? null,
            'height' => $row['altura'] ?? null,
            'wheel_formula' => $row['formula_rodante'] ?? null,
            'passengers' => (int)($row['pasajeros'] ?: null),
            'wheels' => (int)($row['n_ruedas'] ?: null),
            'axles' => (int)($row['n_ejes'] ?: null),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]
        );

        $count++;
        if ($count % 50 == 0) {
            echo "Procesados $count registros...\n";
        }
    }

    echo "\nImportación completada con éxito. Se insertaron/actualizaron $count registros.\n";


}
catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
