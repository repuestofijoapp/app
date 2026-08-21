<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessPdfCatalog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutos máximo
    public $tries = 1;

    protected $bulkText;
    protected $pdfPath;
    protected $categoryId;
    protected $brand;
    protected $vehicleMake;
    protected $scanId;

    public function __construct($bulkText, $pdfPath, $categoryId, $brand, $vehicleMake, $scanId)
    {
        $this->bulkText = $bulkText;
        $this->pdfPath = $pdfPath;
        $this->categoryId = $categoryId;
        $this->brand = $brand;
        $this->vehicleMake = $vehicleMake;
        $this->scanId = $scanId;
    }

    public function handle(): void
    {
        // Sin límite de tiempo — el PDF + llamada a Gemini puede tardar más de 60s
        set_time_limit(0);
        try {
            Log::info("Iniciando Job ProcessPdfCatalog para scan ID: {$this->scanId}");
            
            // 1. Parse lines from excel paste
            $lines = explode("\n", $this->bulkText);
            $codesToSearch = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $parts = preg_split('/\s+/', $line, 3);
                if (count($parts) < 2) continue;

                $rawCode = strtoupper($parts[0]);
                $measure = strtoupper($parts[1]);

                $cleanCode = str_replace(['-', ' ', '_', '/'], '', $rawCode);
                
                $baseSearchCode = $cleanCode;
                if (preg_match('/^([A-Z]+[0-9]+)/', $cleanCode, $matches)) {
                    $baseSearchCode = $matches[1];
                }

                $mappedMeasure = $this->mapMeasure($measure);

                if (!isset($codesToSearch[$cleanCode])) {
                    $codesToSearch[$cleanCode] = [
                        'raw_code'        => $rawCode,
                        'clean_code'      => $cleanCode,
                        'base_search_code'=> $baseSearchCode,
                        'measures'        => [],
                    ];
                }
                $codesToSearch[$cleanCode]['measures'][] = $mappedMeasure;
            }

            if (empty($codesToSearch)) {
                throw new \Exception("No se pudieron extraer códigos válidos del texto.");
            }

            // 2. Parse PDF pages
            $fullPdfPath = Storage::path($this->pdfPath);
            if (!file_exists($fullPdfPath)) {
                throw new \Exception("El archivo PDF temporal no se encontró en el servidor. Ruta buscada: " . $fullPdfPath);
            }

            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($fullPdfPath);
            $pages = $pdf->getPages();

            $pageTexts = [];
            foreach ($pages as $index => $page) {
                $pageTexts[$index + 1] = $page->getText();
            }

            // Locate matching pages
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

            $extractedData = [];
            $apiKey = config('app.gemini_api_key', env('GEMINI_API_KEY'));

            $relevantPages = [];
            foreach ($codeToPages as $cleanCode => $pageNums) {
                foreach ($pageNums as $pageNum) {
                    $relevantPages[$pageNum] = true;
                }
            }

            $allCodes = array_keys($codesToSearch);

            $categoryModel = \App\Models\Category::with('parent')->find($this->categoryId);
            $categoryName = $categoryModel?->name ?? 'Anillos';
            $parentName = $categoryModel?->parent?->name ?? '';
            $isMetals = stripos($categoryName, 'Metal') !== false || stripos($parentName, 'Metal') !== false;
            $isPistons = stripos($categoryName, 'Piston') !== false || stripos($categoryName, 'Pistón') !== false
                      || stripos($parentName, 'Piston') !== false || stripos($parentName, 'Pistón') !== false;

            if (!empty($relevantPages)) {
                $combinedText = '';
                foreach (array_keys($relevantPages) as $pageNum) {
                    $pageTextRaw = $pageTexts[$pageNum] ?? '';
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
                        . "- chassis: SOLO nombres de vehiculos (KIA, Sorento, 323, etc.), NO codigos de motor. Array JSON: [\"KIA\"]\n"
                        . "- fuel_type: Busca si se indica el tipo de motor (G para gasolina, D para diesel, G/D para ambos). Devuelve la letra o vacio.\n\n"
                        . "Devuelve UN OBJETO por cada codigo encontrado, JSON array estricto sin markdown ni texto extra:\n"
                        . "[{\"supplier_code\":\"PTKI410003DO\",\"oem_codes\":\"K47A-11-102|K47A-11-SA0|K4Y2-11-SA0|OK4Y2-11-SCO\",\"chassis\":[\"KIA\"],\"engines\":\"SH K3600\",\"displacement\":\"3598\",\"bore_mm\":\"100.0\",\"cylinders\":\"4\",\"length\":\"102.0\",\"comp_height\":\"56.2\",\"height_1\":\"-7.55\",\"height_2\":\"-20.0\",\"pin_diameter\":\"34.0\",\"pin_length\":\"82.0\",\"circlip\":\"\",\"fuel_type\":\"D\"}]\n\n"
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
                        . "6. Extrae el tipo de combustible de la columna 'Group No.' o similar (G para gasolina, D para diesel). Si aplica a ambos usa 'G/D'. Si no se menciona dejalo vacio. Devuelvelo en la clave 'fuel_type'.\n"
                        . "7. Devuelve UN OBJETO por cada codigo base encontrado en un JSON array estricto, sin markdown ni texto extra:\n"
                        . "[{\"supplier_code\":\"CB-1134\",\"catalog_code\":\"CB-1134GP\",\"oem_codes\":\"13211/7-634-003/4|13211/7-PA5-003/4|13211/7-PD1-003\",\"chassis\":[\"Civic\"],\"engines\":\"EE|EJ|EN\",\"displacement\":\"1238|1335|1335\",\"bore_heights\":\"\",\"radial\":\"\",\"shape\":\"\",\"fuel_type\":\"G\"}]\n\n"
                        . "TEXTO DEL CATALOGO:\n$combinedText";
                } else {
                    $prompt = "Eres un extractor de datos de catalogos tecnicos de anillos de motor (piston rings).\n"
                        . "A continuacion tienes el texto extraido de varias paginas de un catalogo tecnico.\n"
                        . "Busca y extrae la informacion de los siguientes codigos base: $codesStr\n\n"
                        . "REGLAS IMPORTANTES:\n"
                        . "1. Los codigos base pueden aparecer en el catalogo con letras adicionales al final (ej: si buscas SDG30052, en el texto aparece SDG30052ZZ o SDG30052ZX). Extrae la fila si el codigo del catalogo EMPIEZA con el codigo base.\n"
                        . "2. Devuelve UN OBJETO por cada codigo base que encuentres. Si no encuentras un codigo, no lo incluyas en el resultado.\n"
                        . "3. Extrae el tipo de combustible si se especifica (G para gasolina, D para diesel). Si no se menciona dejalo vacio. Devuelvelo en 'fuel_type'.\n"
                        . "4. Devuelve SOLO un JSON array, sin markdown, sin texto extra:\n"
                        . "[{\"supplier_code\":\"SDG30052\",\"catalog_code\":\"SDG30052ZZ\",\"oem_codes\":\"23040-27940\",\"chassis\":[\"Santa Fe 2.2 CRDI\",\"Tucson\",\"D4EB\"],\"engines\":\"D4EB\",\"displacement\":\"2,188\",\"bore_heights\":\"87.0 MM 2.5X2.0X3.0\",\"radial\":\"3.35/3.7/3.85\",\"shape\":\"BF-K2/T1/E-BC16\",\"fuel_type\":\"D\"}, ...]\n"
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
                    $maxRetries = 3;
                    $httpCode = 0;
                    $response = '';
                    for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
                        if ($attempt > 0) {
                            $waitSeconds = $attempt * 15;
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
                    }

                    if ($httpCode === 200) {
                        $decoded = json_decode($response, true);
                        $rawJson = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
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
                        throw new \Exception("La API de Gemini falló (HTTP $httpCode).");
                    }
                }
            }

            // 4. Construct final parsedProducts list
            $parsedProducts = [];
            foreach ($codesToSearch as $cleanCode => $info) {
                $cleanSupplierCode = str_replace('-', '', $info['raw_code']);
                $found = false;
                $data = null;
                $baseSearchCode = $info['base_search_code'];
                
                if (isset($extractedData[$cleanCode])) {
                    $found = true;
                    $data = $extractedData[$cleanCode];
                } else {
                    foreach ($extractedData as $extKey => $extRow) {
                        if ($extKey === $cleanCode) {
                            $found = true;
                            $data = $extRow;
                            break;
                        }
                        if ($extKey === $baseSearchCode || str_starts_with($cleanCode, $extKey)) {
                            $found = true;
                            $data = $extRow;
                            break;
                        }
                    }
                }

                $oversizesObj = [
                    'STD' => ['enabled' => false, 'price' => '', 'stock' => 10],
                    '025' => ['enabled' => false, 'price' => '', 'stock' => 10],
                    '050' => ['enabled' => false, 'price' => '', 'stock' => 10],
                    '075' => ['enabled' => false, 'price' => '', 'stock' => 10],
                    '100' => ['enabled' => false, 'price' => '', 'stock' => 10],
                    '125' => ['enabled' => false, 'price' => '', 'stock' => 10],
                    '150' => ['enabled' => false, 'price' => '', 'stock' => 10],
                ];

                foreach (array_unique($info['measures']) as $m) {
                    if (isset($oversizesObj[$m])) {
                        $oversizesObj[$m]['enabled'] = true;
                    }
                }

                $brandUpper = strtoupper($this->brand);
                $makeUpper = strtoupper($this->vehicleMake);

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

                $allEngines = implode('/', array_filter(array_column($engineDetails, 'engine')));

                if (isset($isPistons) && $isPistons) {
                    $titlePrefix = 'Juego de pistones';
                } elseif (isset($isMetals) && $isMetals) {
                    $titlePrefix = $categoryName ?: 'Metales';
                } else {
                    $titlePrefix = 'Anillos';
                }
                
                $titleParts = [$titlePrefix, $brandUpper];
                if (!empty($allEngines)) {
                    $titleParts[] = trim($allEngines);
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

                $parsedProducts[] = array_merge([
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
                    'ai_fuel_type'   => $data['fuel_type'] ?? '',
                ], $pistonFields);
            }

            // Guardar resultado en caché por 2 horas
            Cache::put('scan_result_' . $this->scanId, $parsedProducts, now()->addHours(2));
            Log::info("Job ProcessPdfCatalog finalizado con éxito para scan ID: {$this->scanId}");

            // Borrar PDF temporal
            Storage::delete($this->pdfPath);

        } catch (\Exception $e) {
            Log::error("Error en ProcessPdfCatalog: " . $e->getMessage());
            Cache::put('scan_error_' . $this->scanId, $e->getMessage(), now()->addHours(2));
        }
    }

    private function mapMeasure($m)
    {
        if (str_contains($m, 'STD')) return 'STD';
        if (str_contains($m, '0.25') || $m == '025') return '025';
        if (str_contains($m, '0.50') || $m == '050') return '050';
        if (str_contains($m, '0.75') || $m == '075') return '075';
        if (str_contains($m, '1.00') || $m == '100') return '100';
        if (str_contains($m, '1.25') || $m == '125') return '125';
        if (str_contains($m, '1.50') || $m == '150') return '150';
        return 'STD';
    }
}
