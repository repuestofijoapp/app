<?php
$ollamaUrl   = 'http://127.0.0.1:11434/api/generate';
$ollamaModel = 'moondream';

$prompt = 'Analyze this image and return a JSON list of products with fields: supplier_code, oem_code, models, engines. Output ONLY raw JSON, do not wrap in markdown or add explanations.';

// We need an actual image to test. Let us find files in storage/app/livewire-tmp or similar, or create a mock 1x1 image.
$imgData = base64_encode(imagejpeg(imagecreatetruecolor(10, 10))); 
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
        'num_predict' => 500,
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
echo "Response:\n";
var_dump($decoded['response'] ?? $response);
