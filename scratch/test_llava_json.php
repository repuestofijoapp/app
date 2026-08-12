<?php
$ollamaUrl   = 'http://127.0.0.1:11434/api/generate';
$ollamaModel = 'llava:7b';

$prompt = 'Eres un extractor de datos de catálogos técnicos de anillos de motor (piston rings).
Analiza la imagen de la tabla y devuelve un array JSON con la estructura exacta siguiente.
Devuelve SOLO el JSON sin texto adicional ni markdown:
[
  {
    "supplier_code": "SWD-10033",
    "brand": "NPR",
    "make": "HONDA",
    "models": ["Civic"],
    "engines": ["ED3"],
    "displacement": "1488",
    "bore": "74.0MM",
    "heights": "1.5X1.5X4.0",
    "radial": "2.8/3.2/3.05",
    "shape": "BF/T1/NIFF-S",
    "oem_code": "13011-657-670",
    "notes": ""
  }
]';

ob_start();
$im = imagecreatetruecolor(10, 10);
imagejpeg($im);
$imgData = base64_encode(ob_get_clean());
imagedestroy($im);

$payload = [
    'model'  => $ollamaModel,
    'prompt' => $prompt,
    'images' => [$imgData],
    'stream' => false,
    'options' => [
        'temperature' => 0.1,
        'num_predict' => 1024,
        'num_gpu'     => 0
    ],
];

$ch = curl_init($ollamaUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT        => 60,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
$decoded = json_decode($response, true);
echo "Response raw text:\n";
var_dump($decoded['response'] ?? $response);
