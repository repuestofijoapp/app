<?php
$key = getenv("GEMINI_API_KEY") ?: "AQ.Ab8RN6I2rLpe9Fneo1Iq1RKvu5HxGZMzc0Eva0O-Hf2tjhIqaA";
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$key}";

$payload = json_encode([
    "contents" => [["role" => "user", "parts" => [["text" => "Responde solo: OK"]]]]
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_SSL_VERIFYPEER => false, CURLOPT_TIMEOUT => 15,
]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP: $code\n";
$data = json_decode($resp, true);
if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
    echo "Respuesta IA: " . $data['candidates'][0]['content']['parts'][0]['text'] . "\n";
} else {
    echo "Error: " . ($data['error']['message'] ?? $resp) . "\n";
}
