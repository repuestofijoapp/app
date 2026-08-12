<?php

/**
 * Script para iniciar sesión en APIsPERU y generar un Certificado PFX de prueba.
 */

$username = 'hola@repuestofijo.com';
$password = 'Agrup@12';
$ruc = '10421922557';
$certPassword = 'Hola2026';

$loginUrl = 'https://facturacion.apisperu.com/api/v1/auth/login';
$certUrl = 'https://facturacion.apisperu.com/api/v1/companies/certificate/free';

echo "Iniciando sesión en APIsPERU para {$username}...\n";

// 1. Login
$ch = curl_init($loginUrl);
$loginPayload = json_encode([
    'username' => $username,
    'password' => $password
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, $loginPayload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$loginResponse = curl_exec($ch);
$loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo 'Error de login Curl: ' . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}
curl_close($ch);

if ($loginHttpCode !== 200) {
    echo "Error de inicio de sesión (Código HTTP {$loginHttpCode}):\n";
    echo $loginResponse . "\n";
    exit(1);
}

$loginData = json_decode($loginResponse, true);
$token = $loginData['token'] ?? null;

if (!$token) {
    echo "No se encontró el token en la respuesta de login.\n";
    exit(1);
}

echo "Inicio de sesión exitoso. Token obtenido.\n";

// 2. Generate Certificate
echo "Generando certificado PFX de prueba...\n";
$ch = curl_init($certUrl);
$certPayload = json_encode([
    'password' => $certPassword,
    'ruc' => $ruc
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, $certPayload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$certResponse = curl_exec($ch);
$certHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo 'Error de cert Curl: ' . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}
curl_close($ch);

if ($certHttpCode === 200) {
    // Save to scratch and public so it's easy to download if they want
    $filenameScratch = "scratch/certificado_pruebas_{$ruc}.pfx";
    $filenamePublic = "public/certificado_pruebas_{$ruc}.pfx";
    
    file_put_contents($filenameScratch, $certResponse);
    file_put_contents($filenamePublic, $certResponse);
    
    echo "\n";
    echo "========================================================\n";
    echo "¡CERTIFICADO GENERADO CON ÉXITO!\n";
    echo "========================================================\n";
    echo "Archivo guardado en: {$filenameScratch}\n";
    echo "Archivo público para descargar: {$filenamePublic}\n";
    echo "Enlace de descarga directa:\n";
    echo "http://localhost:8000/certificado_pruebas_{$ruc}.pfx\n";
    echo "(O a través de tu URL ngrok activa)\n";
    echo "Clave de protección del certificado: {$certPassword}\n";
    echo "========================================================\n";
} else {
    echo "Error al generar certificado (Código HTTP {$certHttpCode}):\n";
    echo $certResponse . "\n";
}
