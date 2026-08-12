<?php

/**
 * Script para generar un Certificado PFX Gratuito de Pruebas a través de APIsPERU.
 * 
 * Uso desde la terminal:
 * php scratch/generate_test_cert.php <APISPERU_TOKEN> <RUC_EMPRESA> <PASSWORD_CERTIFICADO>
 */

if ($argc < 4) {
    echo "Uso: php scratch/generate_test_cert.php <APISPERU_TOKEN> <RUC_EMPRESA> <PASSWORD_CERTIFICADO>\n";
    exit(1);
}

$token = $argv[1];
$ruc = $argv[2];
$password = $argv[3];

$url = 'https://facturacion.apisperu.com/api/v1/companies/certificate/free';

echo "Solicitando certificado PFX de prueba para RUC {$ruc}...\n";

$ch = curl_init($url);
$payload = json_encode([
    'password' => $password,
    'ruc' => $ruc
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo 'Error de Curl: ' . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

curl_close($ch);

if ($httpCode === 200) {
    $filename = "scratch/certificado_pruebas_{$ruc}.pfx";
    file_put_contents($filename, $response);
    echo "¡Éxito! Certificado guardado correctamente en: {$filename}\n";
    echo "Clave de protección del certificado: {$password}\n";
    echo "Ya puedes subir este archivo (.pfx) al panel de APIsPERU.\n";
} else {
    echo "Error al generar certificado (Código HTTP {$httpCode}):\n";
    echo $response . "\n";
}
