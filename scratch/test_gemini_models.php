<?php
$key = "AQ.Ab8RN6I2rLpe9Fneo1Iq1RKvu5HxGZMzc0Eva0O-Hf2tjhIqaA";

// Test v1beta list models
$ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models?key={$key}");
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10, CURLOPT_SSL_VERIFYPEER => false]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "v1beta list models HTTP: $code\n";
$data = json_decode($resp, true);
if ($code === 200 && isset($data['models'])) {
    foreach ($data['models'] as $m) {
        if (in_array('generateContent', $m['supportedGenerationMethods'] ?? [])) {
            echo "  - " . $m['name'] . "\n";
        }
    }
} else {
    echo "Error: " . ($data['error']['message'] ?? $resp) . "\n";
}
