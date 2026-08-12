<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Provider;
use App\Models\Category;
use App\Models\Make;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\ProductOversize;
use Livewire\Component;
use Livewire\WithFileUploads;

class BulkImportProducts extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $step = 1;

    // Step 1 variables
    public $provider_id = '';
    public $category_id = '';
    public $brand = ''; 
    public $fuel_type = '';
    public $vehicle_make = '';
    public $tempImage;

    // Step 2 variables
    public $bulkText = '';
    public $catalogPdf;
    public $showSummary = true;
    public $isParsing = false;
    public $parsedProducts = [];

    // Global defaults
    public $defaultPrice = '';
    public $defaultStock = '10';

    protected $rules = [
        'provider_id' => 'required|exists:providers,id',
    ];

    protected $messages = [
        'provider_id.required' => 'Debes seleccionar un proveedor para importar.',
    ];

    public function openModal()
    {
        $this->showModal = true;
        $this->step = 1;
        $this->provider_id = '';
        $this->category_id = '';
        $this->brand = '';
        $this->fuel_type = '';
        $this->vehicle_make = '';
        $this->tempImage = null;
        
        $this->bulkText = '';
        $this->catalogPdf = null;
        $this->showSummary = true;
        $this->parsedProducts = [];
        $this->defaultPrice = '';
        $this->defaultStock = '10';
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function nextStep()
    {
        $this->validate([
            'provider_id' => 'required|exists:providers,id',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:100',
            'fuel_type' => 'required|string',
            'vehicle_make' => 'required|string',
            'tempImage' => 'nullable|image|max:5120',
        ], [
            'provider_id.required' => 'El proveedor es requerido.',
            'category_id.required' => 'La categoría es requerida.',
            'brand.required' => 'La marca de fabricante es requerida.',
            'fuel_type.required' => 'El tipo de combustible es requerido.',
            'vehicle_make.required' => 'La marca del vehículo es requerida.',
            'tempImage.image' => 'El archivo debe ser una imagen.',
            'tempImage.max' => 'La imagen no debe pesar más de 5MB.',
        ]);

        $this->step = 2;
    }

    public function prevStep()
    {
        $this->step = 1;
    }

    public function applyDefaults()
    {
        if (empty($this->parsedProducts)) return;

        foreach ($this->parsedProducts as $pKey => $pVal) {
            foreach ($pVal['oversizes'] as $ovKey => $ovVal) {
                if ($ovVal['enabled']) {
                    $this->parsedProducts[$pKey]['oversizes'][$ovKey]['price'] = $this->defaultPrice;
                    $this->parsedProducts[$pKey]['oversizes'][$ovKey]['stock'] = $this->defaultStock;
                }
            }
        }
        $this->dispatch('notify', ['type' => 'info', 'message' => 'Valores por defecto aplicados a todas las sobremedidas activas.']);
    }

    public function scanPdfCatalog()
    {
        $this->validate([
            'bulkText' => 'required',
            'catalogPdf' => 'required|file|mimes:pdf|max:20480', // max 20MB
        ], [
            'bulkText.required' => 'Debes ingresar los códigos del excel.',
            'catalogPdf.required' => 'Debes subir el catálogo en formato PDF.',
            'catalogPdf.mimes' => 'El catálogo debe ser un archivo PDF.',
            'catalogPdf.max' => 'El catálogo no debe pesar más de 20MB.',
        ]);

        $this->isParsing = true;
        $this->parsedProducts = [];

        // Allow up to 5 minutes
        set_time_limit(300);

        try {
            // 1. Parse lines from excel paste
            $lines = explode("\n", $this->bulkText);
            $codesToSearch = []; // baseSearchCode => [raw_code, clean_code, measures]

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Split line: typically "SDG-30002 025 D4AE 100MM 3X2X4"
                $parts = preg_split('/\s+/', $line, 3);
                if (count($parts) < 2) continue;

                $rawCode = strtoupper($parts[0]);
                $measure = strtoupper($parts[1]);

                $cleanCode = str_replace(['-', ' ', '_', '/'], '', $rawCode);
                
                // For PDF search: strip trailing letters to find the product family in the PDF
                // e.g. PTHO40750EG -> PTHO40750 for PDF search
                // But KEEP the full cleanCode as the product identity key
                $baseSearchCode = $cleanCode;
                if (preg_match('/^([A-Z]+[0-9]+)/', $cleanCode, $matches)) {
                    $baseSearchCode = $matches[1];
                }

                $mappedMeasure = $this->mapMeasure($measure);

                // Group by FULL cleanCode so that PTHO40750EG and PTHO40750GG remain separate products
                if (!isset($codesToSearch[$cleanCode])) {
                    $codesToSearch[$cleanCode] = [
                        'raw_code'        => $rawCode,
                        'clean_code'      => $cleanCode,
                        'base_search_code'=> $baseSearchCode,  // used only for PDF search
                        'measures'        => [],
                    ];
                }
                $codesToSearch[$cleanCode]['measures'][] = $mappedMeasure;
            }

            if (empty($codesToSearch)) {
                throw new \Exception("No se pudieron extraer códigos válidos del texto.");
            }

            // 2. Parse PDF pages
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($this->catalogPdf->getRealPath());
            $pages = $pdf->getPages();

            $pageTexts = [];
            foreach ($pages as $index => $page) {
                $pageTexts[$index + 1] = $page->getText();
            }

            // Locate matching pages — search by base_search_code (stripped suffix) in PDF
            $codeToPages = [];
            foreach ($codesToSearch as $cleanCode => $info) {
                $searchKey = $info['base_search_code'];
                $codeToPages[$cleanCode] = [];
                foreach ($pageTexts as $pageNum => $text) {
                    $cleanText = str_replace(['-', ' ', '_', '/'], '', strtoupper($text));
                    if (str_contains($cleanText, $searchKey)) {
                        $codeToPages[$cleanCode][] = $pageNum;
                    }
                }
            }

            // Group codes by page to minimize Gemini requests
            $pagesToQuery = [];
            foreach ($codeToPages as $cleanCode => $pageNums) {
                foreach ($pageNums as $pageNum) {
                    $pagesToQuery[$pageNum][] = $cleanCode;
                }
            }

            $extractedData = []; // cleanCode => array of extracted data

            $apiKey = config('app.gemini_api_key', env('GEMINI_API_KEY'));

            // ─── ENFOQUE: UNA SOLA PETICION CON TODAS LAS PAGINAS RELEVANTES ─────────────
            // Gemini Flash soporta hasta 1M tokens. En vez de hacer 1 petición por página
            // (que quema el rate limit), mandamos todas las páginas relevantes juntas.

            // Recopilar todas las páginas únicas que tienen al menos un código buscado
            $relevantPages = [];
            foreach ($codeToPages as $cleanCode => $pageNums) {
                foreach ($pageNums as $pageNum) {
                    $relevantPages[$pageNum] = true;
                }
            }

            // Todos los códigos que tenemos que buscar en total (full cleanCodes for Gemini)
            $allCodes = array_keys($codesToSearch);
            
            // Códigos que no se encontraron en ninguna página del PDF
            $notFoundCodes = [];
            foreach ($codesToSearch as $cleanCode => $info) {
                if (empty($codeToPages[$cleanCode])) {
                    $notFoundCodes[] = $cleanCode;
                }
            }

            $categoryModel = \App\Models\Category::with('parent')->find($this->category_id);
            $categoryName = $categoryModel?->name ?? 'Anillos';
            $parentName = $categoryModel?->parent?->name ?? '';
            $isMetals = stripos($categoryName, 'Metal') !== false || stripos($parentName, 'Metal') !== false;
            $isPistons = stripos($categoryName, 'Piston') !== false || stripos($categoryName, 'Pistón') !== false
                      || stripos($parentName, 'Piston') !== false || stripos($parentName, 'Pistón') !== false;

            if (!empty($relevantPages)) {
                // Construir el texto combinado de todas las páginas relevantes
                $combinedText = '';
                foreach (array_keys($relevantPages) as $pageNum) {
                    $pageTextRaw = $pageTexts[$pageNum] ?? '';
                    // Limpieza agresiva de UTF-8
                    $cleanedPage = @iconv('UTF-8', 'UTF-8//IGNORE', $pageTextRaw);
                    if ($cleanedPage === false) $cleanedPage = $pageTextRaw;
                    $cleanedPage = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $cleanedPage);
                    if (!mb_check_encoding($cleanedPage, 'UTF-8')) {
                        $cleanedPage = mb_convert_encoding($cleanedPage, 'UTF-8', 'UTF-8');
                    }
                    $combinedText .= "\n\n=== PAGINA $pageNum ===\n" . $cleanedPage;
                }

                $codesStr = implode(', ', $allCodes);
                
                if ($isPistons) {
                    $prompt = "Eres un extractor experto de catalogos tecnicos de PISTONES de motor (engine pistons).\n"
                        . "A continuacion tienes texto extraido de varias paginas de un catalogo tecnico. IMPORTANTE: el texto fue extraido de un PDF tabulado y la estructura de columnas se ha perdido. Las lineas de texto de distintas columnas pueden mezclarse.\n\n"
                        . "Busca y extrae la informacion de los siguientes codigos de PISTON SET: $codesStr\n\n"
                        . "=== FORMATOS DE CATALOGO ===\n\n"
                        . "FORMATO 1 - GASOLINA (multiples motores por pagina en tabla horizontal):\n"
                        . "- El catalogo muestra columnas: ENGINE MODEL | PISTON+RINGS | CYL. SLEEVE | PISTON SET | RINGS SET | PISTON HEAD\n"
                        . "- Los OEM codes del PISTON SET aparecen listados debajo del codigo, en la misma seccion del engine model\n"
                        . "- En este formato el PREFIJO de los OEM debe ser COHERENTE con el codigo del motor:\n"
                        . "  * Motor B6 (Mazda) -> OEM empiezan con B6xx o BPxx. Si aparece E302-xx = OTRO motor, IGNORAR\n"
                        . "  * Motor D17 (Honda) -> OEM empiezan con 13010-. Los 13011-, 13021-, 13031- son del RINGS SET, IGNORAR\n"
                        . "  * Cuando el prefijo del OEM cambia radicalmente = fin del bloque de este motor\n\n"
                        . "FORMATO 2 - DIESEL (un motor por pagina con referencia explicita por columna):\n"
                        . "- El catalogo muestra el modelo del motor en la cabecera de la pagina (ej: 'SH K3600 New')\n"
                        . "- Cada columna (LINER KIT, PISTON+RINGS, CYL. LINER, PISTON SET, RINGS SET, PIN BUSHING) tiene su propia seccion 'Reference No.' con sus OEM codes\n"
                        . "- En este formato DEBES extraer SOLO los OEM codes que aparecen debajo de 'Reference No.' en la columna PISTON SET\n"
                        . "- Los OEM de las columnas RINGS SET y PIN BUSHING aparecen en sus propias secciones 'Reference No.' separadas - IGNORARLAS\n"
                        . "- Las medidas del piston (length, comp. height, pin diameter/length) aparecen en la seccion 'piston' de la pagina\n\n"
                        . "=== REGLAS GENERALES PARA AMBOS FORMATOS ===\n\n"
                        . "MEDIDAS DEL PISTON SET:\n"
                        . "- length: largo del piston\n"
                        . "- comp_height: altura de compresion (COMP HEIGHT o comp. height)\n"
                        . "- height_1 y height_2: valores de HEIGHT bajo comp. height (pueden ser negativos, ej: -7.55 y -20.0). Si solo hay un valor usa height_1\n"
                        . "- pin_diameter: valor de 'diameter' en la seccion 'pin' (ej: 34.0)\n"
                        . "- pin_length: valor de 'length' en la seccion 'pin' (ej: 82.0)\n"
                        . "- circlip: 'required' si dice 'circlip required', 'not required' si dice 'circlip not required'. En formato diesel puede no mencionarse (dejar vacio)\n\n"
                        . "ENGINE MODEL:\n"
                        . "- engines: codigo(s) del motor (ej: 'SH K3600' o 'B6-ZE|B6-ME')\n"
                        . "- displacement: cilindrada en cc (ej: '3598' o '1597')\n"
                        . "- bore_mm: diametro de cilindro en mm\n"
                        . "- cylinders: numero de cilindros\n"
                        . "- chassis: SOLO nombres de vehiculos (KIA, Sorento, 323, etc.), NO codigos de motor. Array JSON: [\"KIA\"]\n\n"
                        . "Devuelve UN OBJETO por cada codigo encontrado, JSON array estricto sin markdown ni texto extra:\n"
                        . "[{\"supplier_code\":\"PTKI410003DO\",\"oem_codes\":\"K47A-11-102|K47A-11-SA0|K4Y2-11-SA0|OK4Y2-11-SCO\",\"chassis\":[\"KIA\"],\"engines\":\"SH K3600\",\"displacement\":\"3598\",\"bore_mm\":\"100.0\",\"cylinders\":\"4\",\"length\":\"102.0\",\"comp_height\":\"56.2\",\"height_1\":\"-7.55\",\"height_2\":\"-20.0\",\"pin_diameter\":\"34.0\",\"pin_length\":\"82.0\",\"circlip\":\"\"}]\n\n"
                        . "TEXTO DEL CATALOGO:\n$combinedText";
            } elseif ($isMetals) {
                    $prompt = "Eres un experto extractor de datos de catalogos tecnicos de Metales de Motor (Engine Bearings).\n"
                        . "A continuacion tienes texto extraido de varias paginas de un catalogo tecnico.\n"
                        . "Busca y extrae la informacion de los siguientes codigos de fabricante: $codesStr\n\n"
                        . "REGLAS IMPORTANTES:\n"
                        . "1. Asocia cada codigo con la cabecera correcta. Por ejemplo:\n"
                        . "   - Metales de biela (CB) estan bajo 'CON.ROD Bearing'\n"
                        . "   - Metales de bancada (MS) estan bajo 'MAIN Bearing'\n"
                        . "   - Bocinas/Compensadores (BB/DB/JB/PB) bajo 'Piston pin balancer bushing'\n"
                        . "   - Separadores (TW) bajo 'Thrust Washer'\n"
                        . "   - Levas (SH) bajo 'Camshaft Bearing'\n"
                        . "2. Extrae TODOS los codigos OEM asociados al codigo buscado. Los OEMs pueden omitir su prefijo en lineas subsiguientes (ej: '-PA5-003/4', '-634-0031'). Debes reconstruirlos usando el prefijo completo de la linea superior (ej: '13211/7-634-003/4'). Devuelve los OEMs separados por el caracter '|' en 'oem_codes'.\n"
                        . "3. Identifica los vehiculos (chasis/modelo). En la columna 'Engine Model', a veces el nombre del modelo viene debajo del codigo del motor. Los motores suelen ser codigos cortos/alfanumericos (ej: D16A, B18A, EE) mientras que los modelos son palabras descriptivas y normales (ej: Civic, Accord, Acty). Agrega SOLO los modelos (palabras) en el campo 'chassis' y NO los codigos de motor. Devuelve 'chassis' como un array JSON de modelos: [\"Civic\", \"Accord\"].\n"
                        . "4. Extrae los motores (Engine) y su respectiva cilindrada (CC) de la columna Displacement. DEBES asegurar que haya la misma cantidad de motores que de cilindradas si es posible, extrayendo ambos campos en orden y separandolos por el caracter '|' tanto en 'engines' como en 'displacement'. Ej engines: 'D16A|B18A', displacement: '1590|1834'.\n"
                        . "5. No extraer medidas de tamaño, radial ni forma. Dejalas vacias o null.\n"
                        . "6. Devuelve UN OBJETO por cada codigo base encontrado en un JSON array estricto, sin markdown ni texto extra:\n"
                        . "[{\"supplier_code\":\"CB-1134\",\"catalog_code\":\"CB-1134GP\",\"oem_codes\":\"13211/7-634-003/4|13211/7-PA5-003/4|13211/7-PD1-003\",\"chassis\":[\"Civic\"],\"engines\":\"EE|EJ|EN\",\"displacement\":\"1238|1335|1335\",\"bore_heights\":\"\",\"radial\":\"\",\"shape\":\"\"}]\n\n"
                        . "TEXTO DEL CATALOGO:\n$combinedText";
                } else {
                    $prompt = "Eres un extractor de datos de catalogos tecnicos de anillos de motor (piston rings).\n"
                        . "A continuacion tienes el texto extraido de varias paginas de un catalogo tecnico.\n"
                        . "Busca y extrae la informacion de los siguientes codigos base: $codesStr\n\n"
                        . "REGLAS IMPORTANTES:\n"
                        . "1. Los codigos base pueden aparecer en el catalogo con letras adicionales al final (ej: si buscas SDG30052, en el texto aparece SDG30052ZZ o SDG30052ZX). Extrae la fila si el codigo del catalogo EMPIEZA con el codigo base.\n"
                        . "2. Devuelve UN OBJETO por cada codigo base que encuentres. Si no encuentras un codigo, no lo incluyas en el resultado.\n"
                        . "3. Devuelve SOLO un JSON array, sin markdown, sin texto extra:\n"
                        . "[{\"supplier_code\":\"SDG30052\",\"catalog_code\":\"SDG30052ZZ\",\"oem_codes\":\"23040-27940\",\"chassis\":[\"Santa Fe 2.2 CRDI\",\"Tucson\",\"D4EB\"],\"engines\":\"D4EB\",\"displacement\":\"2,188\",\"bore_heights\":\"87.0 MM 2.5X2.0X3.0\",\"radial\":\"3.35/3.7/3.85\",\"shape\":\"BF-K2/T1/E-BC16\"}, ...]\n"
                        . "IMPORTANTE: El campo 'chassis' DEBE ser un array JSON con cada modelo de vehiculo como un elemento separado. Ejemplo: [\"Datsun\",\"Caravan\",\"Cabster Truck\",\"N521\",\"UN521\"]. NO pongas todos los modelos en un solo string.\n\n"
                        . "TEXTO DEL CATALOGO:\n$combinedText";
                }

                $payload = [
                    'contents' => [[
                        'parts' => [['text' => $prompt]]
                    ]],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json',
                        'temperature' => 0.1,
                    ]
                ];

                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";
                $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

                if ($jsonPayload !== false) {
                    // Reintentos con backoff exponencial
                    $maxRetries = 3;
                    $httpCode = 0;
                    $response = '';
                    for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
                        if ($attempt > 0) {
                            $waitSeconds = $attempt * 15; // 15s, 30s...
                            \Log::warning("Reintentando en {$waitSeconds}s (intento $attempt, HTTP $httpCode)...");
                            sleep($waitSeconds);
                        }
                        $ch = curl_init($url);
                        curl_setopt_array($ch, [
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST           => true,
                            CURLOPT_POSTFIELDS     => $jsonPayload,
                            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
                            CURLOPT_TIMEOUT        => 120,
                            CURLOPT_SSL_VERIFYPEER => false,
                        ]);
                        $response = curl_exec($ch);
                        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        curl_close($ch);

                        if ($httpCode === 200 || $httpCode === 400) break;
                        \Log::warning("Gemini HTTP $httpCode en intento $attempt, reintentando...");
                    }

                    if ($httpCode === 200) {
                        $decoded = json_decode($response, true);
                        $rawJson = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
                        \Log::info("Gemini single-request response:", ['response' => substr($rawJson, 0, 500)]);

                        // Limpiar posible markdown
                        $rawJson = preg_replace('/^```(json)?\s*/i', '', trim($rawJson));
                        $rawJson = preg_replace('/\s*```$/', '', $rawJson);

                        $rows = json_decode($rawJson, true);
                        if (is_array($rows)) {
                            foreach ($rows as $row) {
                                $sc = strtoupper(trim(str_replace(['-', ' ', '_', '/'], '', $row['supplier_code'] ?? '')));
                                if ($sc) {
                                    $extractedData[$sc] = $row;
                                }
                            }
                        }
                    } else {
                        \Log::error("Gemini API Error final:", ['httpCode' => $httpCode, 'response' => substr($response, 0, 300)]);
                        $this->dispatch('notify', ['type' => 'warning', 'message' => "La IA no pudo analizar el catalogo (HTTP $httpCode). Los codigos se muestran sin datos del catalogo."]);
                    }
                }
            }

            // 4. Construct final parsedProducts list
            $index = 0;
            foreach ($codesToSearch as $cleanCode => $info) {
                // Remove the dash from supplier code as requested
                $cleanSupplierCode = str_replace('-', '', $info['raw_code']);

                $found = false;
                $data = null;
                $baseSearchCode = $info['base_search_code'];
                
                // Match exacto por código limpio completo (PTHO40750EG)
                if (isset($extractedData[$cleanCode])) {
                    $found = true;
                    $data = $extractedData[$cleanCode];
                } else {
                    // Gemini puede devolver el código sin el sufijo final de letras, o con variaciones
                    // Buscar cualquier entrada que empiece con el base_search_code y termine similar al cleanCode
                    foreach ($extractedData as $extKey => $extRow) {
                        // Match si Gemini devolvió exactamente el código
                        if ($extKey === $cleanCode) {
                            $found = true;
                            $data = $extRow;
                            break;
                        }
                        // Match si Gemini devolvió el código truncado (base)
                        // y el cleanCode de este producto empieza con ese base
                        if ($extKey === $baseSearchCode || str_starts_with($cleanCode, $extKey)) {
                            $found = true;
                            $data = $extRow;
                            break;
                        }
                    }
                }

                $oversizesObj = [
                    'STD' => ['enabled' => false, 'price' => $this->defaultPrice ?: '', 'stock' => $this->defaultStock ?: 10],
                    '025' => ['enabled' => false, 'price' => $this->defaultPrice ?: '', 'stock' => $this->defaultStock ?: 10],
                    '050' => ['enabled' => false, 'price' => $this->defaultPrice ?: '', 'stock' => $this->defaultStock ?: 10],
                    '075' => ['enabled' => false, 'price' => $this->defaultPrice ?: '', 'stock' => $this->defaultStock ?: 10],
                    '100' => ['enabled' => false, 'price' => $this->defaultPrice ?: '', 'stock' => $this->defaultStock ?: 10],
                    '125' => ['enabled' => false, 'price' => $this->defaultPrice ?: '', 'stock' => $this->defaultStock ?: 10],
                    '150' => ['enabled' => false, 'price' => $this->defaultPrice ?: '', 'stock' => $this->defaultStock ?: 10],
                ];

                foreach (array_unique($info['measures']) as $m) {
                    if (isset($oversizesObj[$m])) {
                        $oversizesObj[$m]['enabled'] = true;
                    }
                }

                $brandUpper = strtoupper($this->brand);
                $makeUpper = strtoupper($this->vehicle_make);

                $enginesRawList = array_values(array_filter(array_map('trim', explode('|', $data['engines'] ?? ''))));
                $dispRawList = array_values(array_filter(array_map('trim', explode('|', $data['displacement'] ?? ''))));
                
                $engineDetails = [];
                foreach ($enginesRawList as $idx => $eng) {
                    $engineDetails[] = [
                        'engine' => $eng,
                        'cc' => $dispRawList[$idx] ?? (count($dispRawList) === 1 ? $dispRawList[0] : '')
                    ];
                }
                if (empty($engineDetails)) {
                    $engineDetails[] = ['engine' => '', 'cc' => ''];
                }

                $chassisRaw = $data['chassis'] ?? '';
                // Gemini may return chassis as a JSON array or a pipe/comma-separated string
                if (is_array($chassisRaw)) {
                    $chassisList = array_filter(array_map('trim', $chassisRaw));
                } elseif (is_string($chassisRaw) && str_starts_with(trim($chassisRaw), '[')) {
                    $decoded = json_decode($chassisRaw, true);
                    $chassisList = is_array($decoded) ? array_filter(array_map('trim', $decoded)) : array_filter(array_map('trim', explode('|', $chassisRaw)));
                } else {
                    $chassisList = array_filter(array_map('trim', explode('|', $chassisRaw)));
                }
                $chassisList = array_values(array_unique($chassisList));
                $chassisStr = implode(', ', $chassisList);

                $firstEngine = $engineDetails[0]['engine'] ?? '';

                // Auto title: categoria -> (Metales/Anillos) + Marca en mayusculas + Motor + Marca Vehiculo + modelos de chasis encontrados
                if (isset($isPistons) && $isPistons) {
                    $titlePrefix = 'Juego de pistones';
                } elseif (isset($isMetals) && $isMetals) {
                    $titlePrefix = $categoryName ?: 'Metales';
                } else {
                    $titlePrefix = 'Anillos';
                }
                
                $titleParts = [$titlePrefix, $brandUpper];
                if (!empty($firstEngine)) {
                    $titleParts[] = trim($firstEngine);
                }
                if (!empty($makeUpper)) {
                    $titleParts[] = trim($makeUpper);
                }
                if (!empty($chassisStr)) {
                    $titleParts[] = trim($chassisStr);
                }
                $title = implode(' ', array_filter($titleParts));

                $finalOemCodes = $data['oem_codes'] ?? '';
                if (empty(trim($finalOemCodes))) {
                    $finalOemCodes = $info['raw_code'];
                }

                // Piston-specific fields (empty for non-piston products)
                $pistonFields = [];
                if (isset($isPistons) && $isPistons) {
                    $pistonFields = [
                        'bore_mm'       => $data['bore_mm'] ?? '',
                        'cylinders'     => $data['cylinders'] ?? '',
                        'piston_length' => $data['length'] ?? '',
                        'comp_height'   => $data['comp_height'] ?? '',
                        'height_1'      => $data['height_1'] ?? '',
                        'height_2'      => $data['height_2'] ?? '',
                        'pin_diameter'  => $data['pin_diameter'] ?? '',
                        'pin_length'    => $data['pin_length'] ?? '',
                        'circlip'       => $data['circlip'] ?? '',
                    ];
                }

                $this->parsedProducts[] = array_merge([
                    'supplier_code'  => $cleanSupplierCode,
                    'catalog_code'   => $data['catalog_code'] ?? '',
                    'oem_codes'      => $finalOemCodes,
                    'chassis'        => $chassisList,
                    'engine_details' => $engineDetails,
                    'bore_heights'   => $data['bore_heights'] ?? '',
                    'radial'         => $data['radial'] ?? '',
                    'shape'          => $data['shape'] ?? '',
                    'oversizes'      => $oversizesObj,
                    'title'          => $title,
                    'found_in_pdf'   => $found,
                ], $pistonFields);
                $index++;
            }

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Escaneo completado.']);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            if (str_contains(strtolower($errorMsg), 'secured pdf')) {
                $errorMsg = 'El PDF está protegido (encriptado). Por favor, usa la función "Imprimir -> Guardar como PDF" en tu navegador para quitarle la seguridad y sube ese nuevo archivo.';
            }
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al analizar: ' . $errorMsg]);
        }

        $this->isParsing = false;
    }

    private function mapMeasure($m)
    {
        if (str_contains($m, 'STD'))
            return 'STD';
        if (str_contains($m, '0.25') || $m == '025')
            return '025';
        if (str_contains($m, '0.50') || $m == '050')
            return '050';
        if (str_contains($m, '0.75') || $m == '075')
            return '075';
        if (str_contains($m, '1.00') || $m == '100')
            return '100';
        if (str_contains($m, '1.25') || $m == '125')
            return '125';
        if (str_contains($m, '1.50') || $m == '150')
            return '150';
        return 'STD';
    }

    public function saveImport()
    {
        if (empty($this->parsedProducts)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No hay productos para guardar.']);
            return;
        }

        $count = 0;
        
        // Handle common single image upload
        $commonImagePath = null;
        if ($this->tempImage) {
            $imageName = 'product_' . time() . '_' . uniqid() . '.' . $this->tempImage->getClientOriginalExtension();
            $commonImagePath = $this->tempImage->storeAs('products', $imageName, 'public');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function() use ($commonImagePath, &$count) {
                foreach ($this->parsedProducts as $p) {
                    $supplierCodeClean = strtoupper(trim($p['supplier_code']));
                    if (empty($supplierCodeClean)) continue;

                    // Parse OEM codes
                    $oemCodesList = array_filter(array_map('trim', explode('|', $p['oem_codes'])));
                    $primaryOem = !empty($oemCodesList) ? $oemCodesList[0] : null;
                    $additionalOem = count($oemCodesList) > 1 ? array_values(array_slice($oemCodesList, 1)) : [];

                    // Put catalog_code into additional_oem_codes as requested
                    if (!empty($p['catalog_code'])) {
                        $catalogCodeUpper = strtoupper(trim($p['catalog_code']));
                        if (!in_array($catalogCodeUpper, $additionalOem)) {
                            $additionalOem[] = $catalogCodeUpper;
                        }
                    }
                    $additionalOem = !empty($additionalOem) ? array_values(array_unique(array_filter($additionalOem))) : null;

                    // Engines y Displacements array
                    $enginesList = [];
                    foreach ($p['engine_details'] ?? [] as $eDetail) {
                        $engCode = strtoupper(trim($eDetail['engine'] ?? ''));
                        if ($engCode) {
                            $enginesList[] = $engCode;
                        }
                    }
                    $enginesList = array_values(array_unique($enginesList));

                    // Chassis / models — already parsed into array at scan time
                    $chassisList = is_array($p['chassis'])
                        ? array_filter(array_map('trim', $p['chassis']))
                        : array_filter(array_map('trim', explode('|', (string)$p['chassis'])));

                    // Resolve models & engines ids in DB
                    $modelIds = [];
                    $engineIds = [];

                    $make = Make::firstOrCreate(['name' => strtoupper($this->vehicle_make)]);

                    foreach ($chassisList as $modelName) {
                        $modelName = strtoupper(trim($modelName));
                        if (!$modelName) continue;
                        $model = CarModel::firstOrCreate([
                            'make_id' => $make->id,
                            'name' => $modelName,
                        ]);
                        $modelIds[] = $model->id;

                        foreach ($p['engine_details'] ?? [] as $eDetail) {
                            $engCode = strtoupper(trim($eDetail['engine'] ?? ''));
                            if (!$engCode) continue;

                            $dispValue = trim($eDetail['cc'] ?? '');
                            // Clean displacement - do not append CC to avoid CCcc issues
                            $disp = trim(preg_replace('/(cc|c\.c\.|c\.c)/i', '', $dispValue));

                            $engine = Engine::firstOrCreate([
                                'car_model_id' => $model->id,
                                'engine_code' => $engCode,
                            ], [
                                'displacement' => $disp ?: null,
                            ]);
                            $engineIds[] = $engine->id;
                        }
                    }

                    // Build specs
                    $specs = null;
                    if (isset($p['piston_length'])) {
                        // === PISTON specs — NO 'raw' field (that is for rings/metals only) ===
                        $height1 = trim((string)($p['height_1'] ?? ''));
                        $height2 = trim((string)($p['height_2'] ?? ''));
                        $heights = array_values(array_filter([$height1, $height2]));
                        $specs = [
                            'bore'        => $p['bore_mm'] ?: null,
                            'cylinders'   => $p['cylinders'] ?: null,
                            'length'      => $p['piston_length'] ?: null,
                            'comp_height' => $p['comp_height'] ?: null,
                            'height'      => !empty($heights) ? $heights : null,
                            'pin'         => ($p['pin_diameter'] && $p['pin_length'])
                                                ? $p['pin_diameter'] . 'X' . $p['pin_length']
                                                : null,
                            'circlip_required' => ($p['circlip'] === 'required' || $p['circlip'] === 'required') ? 'required' : null,
                        ];
                        // Remove null values so the specs array stays clean
                        $specs = array_filter($specs, fn($v) => $v !== null && $v !== '');
                    } elseif (!empty($p['bore_heights'])) {
                        // === RINGS / METALS specs ===
                        $radialParts = array_filter(array_map('trim', explode('/', $p['radial'])));
                        $specs = [
                            'raw'    => $p['bore_heights'],
                            'radial' => !empty($radialParts) ? $radialParts : null,
                            'shape'  => $p['shape'] ?: null,
                        ];
                    }

                    // Price fallback for legacy compatibility
                    $legacyPrice = null;
                    $legacyOversize = 'STD';
                    $legacyIsActive = false;

                    $stdVariant = $p['oversizes']['STD'] ?? null;
                    if ($stdVariant && $stdVariant['enabled']) {
                        $legacyPrice = floatval($stdVariant['price']);
                        $legacyOversize = 'STD';
                        $legacyIsActive = intval($stdVariant['stock']) > 0;
                    } else {
                        $firstEnabled = collect($p['oversizes'])->first(fn($v) => $v['enabled'] ?? false);
                        if ($firstEnabled) {
                            $legacyPrice = floatval($firstEnabled['price']);
                            $legacyOversize = array_search($firstEnabled, $p['oversizes']);
                            $legacyIsActive = intval($firstEnabled['stock']) > 0;
                        }
                    }

                    // Find or create product
                    $product = Product::updateOrCreate(
                        [
                            'provider_id' => $this->provider_id,
                            'supplier_code' => $supplierCodeClean,
                        ],
                        [
                            'category_id' => $this->category_id ?: null,
                            'brand' => strtoupper($this->brand),
                            'vehicle_make' => strtoupper($this->vehicle_make),
                            'oem_code' => $primaryOem ?: ($product->oem_code ?? null),
                            'additional_oem_codes' => $additionalOem ?: ($product->additional_oem_codes ?? null),
                            'fuel_type' => strtoupper($this->fuel_type),
                            'name' => $p['title'],
                            'compatible_engines' => !empty($enginesList) ? $enginesList : ($product->compatible_engines ?? null),
                            'compatible_vehicles' => !empty($chassisList) ? array_values(array_unique($chassisList)) : ($product->compatible_vehicles ?? null),
                            'compatible_model_ids' => !empty($modelIds) ? array_map('intval', array_unique($modelIds)) : ($product->compatible_model_ids ?? null),
                            'compatible_engine_ids' => !empty($engineIds) ? array_map('intval', array_unique($engineIds)) : ($product->compatible_engine_ids ?? null),
                            'specs'    => $specs ?: ($product->specs ?? null),
                            // For pistons: PIN is already in specs['pin'], no need to duplicate in notes
                            'notes'    => !empty($p['catalog_code']) && !isset($p['piston_length'])
                                ? 'Cód. Catálogo: ' . $p['catalog_code']
                                : ($product->notes ?? null),
                            'image_path' => $commonImagePath ?: ($product->image_path ?? null),
                            'price' => $legacyPrice,
                            'oversize' => $legacyOversize,
                            'is_active' => $legacyIsActive,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]
                    );

                    // Sync variants
                    foreach ($p['oversizes'] as $key => $val) {
                        if ($val['enabled'] ?? false) {
                            ProductOversize::updateOrCreate(
                                ['product_id' => $product->id, 'oversize' => $key],
                                [
                                    'price' => floatval($val['price'] ?: 0),
                                    'stock' => intval($val['stock'] ?? 0),
                                    'is_active' => intval($val['stock'] ?? 0) > 0,
                                ]
                            );
                        } else {
                            ProductOversize::where('product_id', $product->id)->where('oversize', $key)->delete();
                        }
                    }

                    $count++;
                }
            });

            $this->dispatch('notify', ['type' => 'success', 'message' => "¡$count productos importados/actualizados con éxito!"]);
            \Illuminate\Support\Facades\Cache::forget('brands_with_products');
            \Illuminate\Support\Facades\Cache::forget('all_makes_names');
            $this->dispatch('products-updated');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al guardar la importación: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.admin.bulk-import-products', [
            'providers' => Provider::orderBy('business_name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'makes' => Make::orderBy('name')->pluck('name')->toArray(),
        ]);
    }
}
