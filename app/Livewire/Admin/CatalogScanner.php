<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\ProductOversize;
use App\Models\Make;
use App\Models\CarModel;
use App\Models\Engine;
use Livewire\Component;
use Livewire\WithFileUploads;

class CatalogScanner extends Component
{
    use WithFileUploads;

    public bool $showModal = false;
    public bool $scanning = false;

    // Inputs
    public $catalogImage;          // uploaded file
    public string $productCodeFilter = '';  // Optional: filter by code

    // Results from AI
    public array $scannedData = [];
    public string $errorMessage = '';
    public bool $showResults = false;

    // Categorías que tienen soporte de escaner
    public static array $SUPPORTED_CATEGORY_SLUGS = ['anillos'];

    // Category name passed from parent
    public string $categoryName = '';

    public function openModal(): void
    {
        $this->reset(['catalogImage', 'scannedData', 'errorMessage', 'showResults', 'productCodeFilter']);
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function scanCatalog(): void
    {
        $this->validate([
            'catalogImage' => 'required|image|max:5120',
        ], [
            'catalogImage.required' => 'Debes subir una captura del catálogo.',
            'catalogImage.max' => 'La imagen no debe pesar más de 5MB.',
        ]);

        $this->scanning = true;
        $this->errorMessage = '';
        $this->scannedData = [];
        $this->showResults = false;

        // Allow up to 5 minutes for local execution
        set_time_limit(300);

        try {
            $imageData = base64_encode(file_get_contents($this->catalogImage->getRealPath()));
            $mimeType = $this->catalogImage->getMimeType();

            $prompt = $this->buildPrompt();

            $backend = env('SCAN_BACKEND', 'gemini');
            $rawText = '';

            if ($backend === 'ollama') {
                $ollamaUrl   = rtrim(env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'), '/') . '/api/generate';
                $ollamaModel = env('OLLAMA_VISION_MODEL', 'llava:7b');

                $payload = [
                    'model'  => $ollamaModel,
                    'prompt' => $prompt,
                    'images' => [$imageData],
                    'stream' => false,
                    'format' => 'json',
                    'options' => [
                        'temperature' => 0.1,
                        'num_predict' => 2048,
                        'num_gpu'     => 0, // Force CPU rendering, bypass VRAM crashes
                    ],
                ];

                $ch = curl_init($ollamaUrl);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST           => true,
                    CURLOPT_POSTFIELDS     => json_encode($payload),
                    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
                    CURLOPT_TIMEOUT        => 300,
                    CURLOPT_SSL_VERIFYPEER => false,
                ]);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlErr  = curl_error($ch);
                curl_close($ch);

                if ($curlErr) {
                    throw new \Exception("Error de conexión con Ollama: {$curlErr}");
                }

                if ($httpCode !== 200) {
                    $errDec = json_decode($response, true);
                    $msg = $errDec['error'] ?? "Error HTTP {$httpCode} desde Ollama";
                    throw new \Exception("Ollama: {$msg}");
                }

                $decoded = json_decode($response, true);
                $rawText = $decoded['response'] ?? '';

            } elseif ($backend === 'groq') {
                $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
                $groqKey = env('GROQ_API_KEY');
                if (!$groqKey) {
                    throw new \Exception("Configuración incompleta: Falta definir GROQ_API_KEY en tu archivo .env");
                }

                $payload = [
                    'model' => 'llama-3.2-11b-vision-preview',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $prompt
                                ],
                                [
                                    'type' => 'image_url',
                                    'image_url' => [
                                        'url' => 'data:' . $mimeType . ';base64,' . $imageData
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.1,
                    'max_tokens' => 2048
                ];

                $ch = curl_init($groqUrl);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST           => true,
                    CURLOPT_POSTFIELDS     => json_encode($payload),
                    CURLOPT_HTTPHEADER     => [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $groqKey
                    ],
                    CURLOPT_TIMEOUT        => 60,
                    CURLOPT_SSL_VERIFYPEER => false,
                ]);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $decoded = json_decode($response, true);
                if ($httpCode !== 200) {
                    $msg = $decoded['error']['message'] ?? "Error HTTP {$httpCode} desde Groq";
                    throw new \Exception("Groq: {$msg}");
                }
                $rawText = $decoded['choices'][0]['message']['content'] ?? '';

            } else {
                $payload = [
                    'contents' => [
                        [
                            'parts' => [
                                ['inline_data' => ['mime_type' => $mimeType, 'data' => $imageData]],
                                ['text' => $prompt],
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json',
                        'temperature' => 0.1,
                    ]
                ];

                $apiKey = config('app.gemini_api_key', env('GEMINI_API_KEY'));
                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($payload),
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_SSL_VERIFYPEER => false,
                ]);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlErr = curl_error($ch);
                curl_close($ch);

                if ($curlErr) {
                    throw new \Exception("Error de conexión con Gemini: {$curlErr}");
                }

                $decoded = json_decode($response, true);

                if ($httpCode !== 200) {
                    $msg = $decoded['error']['message'] ?? "Error HTTP {$httpCode}";
                    if ($httpCode === 429) {
                        throw new \Exception(
                            "⏳ Cuota de la API agotada en Google Cloud: {$msg}"
                        );
                    }
                    throw new \Exception($msg);
                }

                $rawText = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '';
            }

            // ── Parse shared JSON response (Ultra robust extraction) ──────────
            $jsonStart = strpos($rawText, '[');
            $jsonEnd   = strrpos($rawText, ']');
            if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
                $rawText = substr($rawText, $jsonStart, $jsonEnd - $jsonStart + 1);
            } else {
                // Fallback to curly braces if array wrapper was omitted
                $jsonStart = strpos($rawText, '{');
                $jsonEnd   = strrpos($rawText, '}');
                if ($jsonStart !== false && $jsonEnd !== false && $jsonEnd > $jsonStart) {
                    $rawText = '[' . substr($rawText, $jsonStart, $jsonEnd - $jsonStart + 1) . ']';
                }
            }

            $data = json_decode(trim($rawText), true);

            if (!$data) {
                throw new \Exception("No se pudo interpretar la respuesta de la IA. Por favor, asegúrate de que el formato sea un JSON válido.");
            }

            $this->scannedData = is_array($data) ? $data : [];
            $this->showResults = true;

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }

        $this->scanning = false;
    }

    /**
     * Apply scanned data to products in the DB.
     * Since the migration to product_oversizes, each supplier_code maps to ONE master product.
     */
    public function applyToProducts(): void
    {
        if (empty($this->scannedData))
            return;

        $applied = 0;
        $skipped = [];

        foreach ($this->scannedData as $row) {
            // Helper sanitization
            $supplierCode = $row['supplier_code'] ?? '';
            if (is_array($supplierCode)) {
                $supplierCode = implode('', $supplierCode);
            }
            $code = strtoupper(trim($supplierCode));
            if (!$code)
                continue;

            // Normalize code by stripping dashes, spaces, underscores, slashes
            $cleanCode = str_replace(['-', ' ', '_', '/'], '', $code);

            // Find the master product (unique, post-migration)
            $product = Product::whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(supplier_code, '-', ''), ' ', ''), '_', ''), '/', '') = ?", [$cleanCode])->first();

            // If not found, try matching by stripping common suffixes (e.g. SWG10026ZX -> SWG10026)
            if (!$product) {
                if (preg_match('/^([A-Z]+[0-9]+)/', $cleanCode, $matches)) {
                    $baseCode = $matches[1];
                    $product = Product::whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(supplier_code, '-', ''), ' ', ''), '_', ''), '/', '') = ?", [$baseCode])->first();
                }
            }

            // If still not found, try similarity matching on digits + prefix (e.g. SDD10026ZX -> SJD10026)
            if (!$product) {
                if (preg_match('/([0-9]+)/', $cleanCode, $numMatches)) {
                    $digits = $numMatches[1];
                    $candidates = Product::where('supplier_code', 'LIKE', '%' . $digits . '%')->get();
                    if ($candidates->isNotEmpty()) {
                        if (preg_match('/^([A-Z]+)/', $cleanCode, $prefixMatches)) {
                            $scannedPrefix = $prefixMatches[1];
                            $bestCandidate = null;
                            $bestScore = -1;

                            foreach ($candidates as $cand) {
                                if (preg_match('/^([A-Z]+)/', $cand->supplier_code, $candPrefixMatches)) {
                                    $candPrefix = $candPrefixMatches[1];
                                    $score = 0;
                                    if (strlen($scannedPrefix) === 3 && strlen($candPrefix) === 3) {
                                        if ($scannedPrefix[0] === $candPrefix[0]) $score += 2;
                                        if ($scannedPrefix[2] === $candPrefix[2]) $score += 2;
                                        if ($scannedPrefix[1] === $candPrefix[1]) $score += 1;
                                    } else {
                                        $minLen = min(strlen($scannedPrefix), strlen($candPrefix));
                                        for ($i = 0; $i < $minLen; $i++) {
                                            if ($scannedPrefix[$i] === $candPrefix[$i]) {
                                                $score++;
                                            }
                                        }
                                    }

                                    if ($score > $bestScore) {
                                        $bestScore = $score;
                                        $bestCandidate = $cand;
                                    }
                                }
                            }

                            if ($bestScore >= 2 && $bestCandidate) {
                                $product = $bestCandidate;
                            }
                        }
                    }
                }
            }

            if (!$product) {
                $skipped[] = $code;
                continue;
            }

            // Normalize make
            $makeNameRaw = $row['make'] ?? '';
            if (is_array($makeNameRaw)) {
                $makeNameRaw = implode(' ', $makeNameRaw);
            }
            $makeName = strtoupper(trim($makeNameRaw));

            // Normalize models to array
            $models = $row['models'] ?? [];
            if (is_string($models)) {
                $models = array_filter(array_map('trim', explode(',', $models)));
            }

            // Normalize engines to array
            $engines = $row['engines'] ?? [];
            if (is_string($engines)) {
                $engines = array_filter(array_map('trim', explode(',', $engines)));
            }

            // Resolve/create vehicle compatibility
            $makeId = null;
            $modelIds = [];
            $engineIds = [];

            if ($makeName) {
                $make = Make::firstOrCreate(['name' => $makeName]);
                $makeId = $make->id;

                foreach ($models as $modelName) {
                    $modelName = strtoupper(trim($modelName));
                    if (!$modelName)
                        continue;
                    $model = CarModel::firstOrCreate([
                        'make_id' => $makeId,
                        'name' => $modelName,
                    ]);
                    $modelIds[] = $model->id;

                    // Create engine for this model if engine code provided
                    foreach ($engines as $engCode) {
                        $engCode = strtoupper(trim($engCode));
                        if (!$engCode)
                            continue;

                        $displacementRaw = $row['displacement'] ?? null;
                        if (is_array($displacementRaw)) {
                            $displacementRaw = implode(' ', $displacementRaw);
                        }
                        $displacement = trim($displacementRaw) ?: null;

                        $engine = Engine::firstOrCreate([
                            'car_model_id' => $model->id,
                            'engine_code' => $engCode,
                        ], [
                            'displacement' => $displacement,
                        ]);
                        $engineIds[] = $engine->id;
                    }
                }
            }

            // Normalize oem_code to string
            $oemCodeRaw = $row['oem_code'] ?? '';
            if (is_array($oemCodeRaw)) {
                $oemCodeRaw = implode(', ', $oemCodeRaw);
            }
            $oemCode = strtoupper(trim($oemCodeRaw));

            // Normalize bore
            $boreRaw = $row['bore'] ?? '';
            if (is_array($boreRaw)) {
                $boreRaw = implode(' ', $boreRaw);
            }
            $bore = trim($boreRaw);

            // Normalize heights
            $heightsRaw = $row['heights'] ?? '';
            if (is_array($heightsRaw)) {
                $heightsRaw = implode(' ', $heightsRaw);
            }
            $heights = trim($heightsRaw);

            // Normalize radial
            $radialRaw = $row['radial'] ?? '';
            if (is_array($radialRaw)) {
                $radialRaw = implode('/', $radialRaw);
            }
            $radial = trim($radialRaw);

            // Normalize shape
            $shapeRaw = $row['shape'] ?? '';
            if (is_array($shapeRaw)) {
                $shapeRaw = implode('/', $shapeRaw);
            }
            $shape = trim($shapeRaw);

            // Normalize notes
            $notesRaw = $row['notes'] ?? '';
            if (is_array($notesRaw)) {
                $notesRaw = implode(' ', $notesRaw);
            }
            $notes = trim($notesRaw);

            // Build specs
            $specs = null;
            if (!empty($bore)) {
                $specs = [
                    'raw' => trim($bore . ' ' . $heights),
                    'radial' => !empty($radial)
                        ? array_map('trim', explode('/', $radial))
                        : null,
                    'shape' => $shape ?: null,
                ];
            }

            $updateData = array_filter([
                'vehicle_make' => $makeName ?: null,
                'oem_code' => $oemCode ?: null,
                'compatible_engines' => !empty($engines)
                    ? array_map('strtoupper', array_map('trim', $engines))
                    : null,
                'compatible_vehicles' => !empty($models)
                    ? implode(', ', array_map('strtoupper', array_map('trim', $models)))
                    : null,
                'compatible_model_ids' => !empty($modelIds) ? $modelIds : null,
                'compatible_engine_ids' => !empty($engineIds) ? $engineIds : null,
                'specs' => $specs,
                'notes' => $notes ?: null,
            ], fn($v) => $v !== null);

            $product->update($updateData);

            // If the AI row includes per-oversize pricing data, update product_oversizes too.
            // Expected format: "oversizes": [{"oversize": "STD", "price": 25.00, "stock": 10}, ...]
            if (!empty($row['oversizes']) && is_array($row['oversizes'])) {
                foreach ($row['oversizes'] as $ovData) {
                    $ovKey = strtoupper(trim($ovData['oversize'] ?? ''));
                    $ovPrice = isset($ovData['price']) ? floatval($ovData['price']) : null;
                    $ovStock = isset($ovData['stock']) ? intval($ovData['stock']) : null;
                    if (!$ovKey || $ovPrice === null) continue;

                    ProductOversize::updateOrCreate(
                        ['product_id' => $product->id, 'oversize' => $ovKey],
                        array_filter([
                            'price' => $ovPrice,
                            'stock' => $ovStock,
                            'is_active' => $ovStock !== null ? $ovStock > 0 : null,
                        ], fn($v) => $v !== null)
                    );
                }
            }

            $applied++;
        }

        $msg = "✅ {$applied} producto(s) actualizados.";
        $type = 'success';
        if (!empty($skipped)) {
            $msg .= " ⚠️ No encontrados en BD: " . implode(', ', $skipped);
            if ($applied === 0) {
                $type = 'warning';
            }
        }

        $this->dispatch('notify', ['type' => $type, 'message' => $msg]);
        $this->closeModal();
    }



    private function buildPrompt(): string
    {
        return <<<PROMPT
Eres un extractor de datos de catálogos técnicos de anillos de motor (piston rings).

Analiza la imagen de la tabla del catálogo y devuelve un JSON con la estructura exacta siguiente.
NO inventes datos de ejemplo ni uses los códigos del ejemplo de abajo, lee exclusivamente el texto de la imagen real.

Para CADA fila de producto en la imagen, extrae:

```json
[
  {
    "supplier_code": "<CODIGO_PROVEEDOR_CON_GUION_EJ_SWG-10046>",
    "make": "<MARCA_VEHICULO_EJ_TOYOTA>",
    "models": ["<MODELO_1>", "<MODELO_2>"],
    "engines": ["<CODIGO_MOTOR_1>", "<CODIGO_MOTOR_2>"],
    "displacement": "<CILINDRADA_SOLO_NUMERO_EJ_1498>",
    "bore": "<DIAMETRO_EJ_74.71MM>",
    "heights": "<ALTURAS_EJ_1.0X1.2X2.0>",
    "radial": "<RADIAL_EJ_2.25/2.8/2.2>",
    "shape": "<FORMA_EJ_BF/TUC/NIFF-H>",
    "material": "<MATERIAL_EJ_SR/CR/SR>",
    "surface": "<TRATAMIENTO_SUPERFICIAL_EJ_Cr/Fe>",
    "oem_code": "<CODIGO_OEM_REFERENCIA>",
    "notes": ""
  }
]
```

Reglas importantes:
- El campo "supplier_code" es el código NPR/SWG del catálogo (ej: SWG10046ZZ → SWG-10046, siempre con guión)
- "heights" usa el formato DiámetroXAltura1XAltura2XAltura3 o similar con X como separador
- "radial" usa / como separador  
- "shape" une las formas con /
- "models" es una lista de nombres de modelo de vehículo (ej: Sail, Spin)
- "engines" es una lista de códigos de motor
- "oem_code" es la referencia del fabricante del vehículo (Reference column)
- Si hay múltiples filas para el mismo código (distintos anillos 1st/2nd/Oil), agrúpalos en UNA sola entrada
- Devuelve SOLO el JSON sin texto adicional ni markdown
PROMPT;
    }

    public function render()
    {
        return view('livewire.admin.catalog-scanner');
    }
}
