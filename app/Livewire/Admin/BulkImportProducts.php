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
    public $fuel_types = [];
    public $vehicle_make = '';
    public $tempImage;


    // Step 2 variables
    public $bulkText = '';
    public $catalogPdf;
    public $showSummary = true;
    public $isParsing = false;
    public $scanId = null; // New property for async job tracking
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
        $this->fuel_types = [];
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
            'fuel_types' => 'nullable|array',
            'vehicle_make' => 'required|string',
            'tempImage' => 'nullable|image|max:5120',
        ], [
            'provider_id.required' => 'El proveedor es requerido.',
            'category_id.required' => 'La categoría es requerida.',
            'brand.required' => 'La marca de fabricante es requerida.',
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
        $this->scanId = uniqid('scan_', true);

        try {
            // Guardar el PDF temporalmente para que el Job pueda leerlo
            $pdfPath = $this->catalogPdf->store('temp_catalogs');

            if (app()->environment('local')) {
                // En local: ejecutar el Job de forma SINCRÓNICA directa
                // No necesita queue:work — se ejecuta aquí mismo y espera el resultado
                $job = new \App\Jobs\ProcessPdfCatalog(
                    $this->bulkText,
                    $pdfPath,
                    $this->category_id,
                    $this->brand,
                    $this->vehicle_make,
                    $this->scanId
                );
                // Quitar el límite de tiempo de PHP para esta petición
                set_time_limit(0);
                $job->handle();

                // Leer resultado del caché que el Job acaba de escribir
                $result = \Illuminate\Support\Facades\Cache::get('scan_result_' . $this->scanId);
                $error  = \Illuminate\Support\Facades\Cache::get('scan_error_' . $this->scanId);

                if ($error) {
                    $this->isParsing = false;
                    $this->scanId = null;
                    $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al analizar: ' . $error]);
                } elseif ($result !== null) {
                    $this->parsedProducts = $result;
                    $this->isParsing = false;
                    $this->dispatch('notify', ['type' => 'success', 'message' => 'Escaneo completado exitosamente.']);
                }
            } else {
                // En producción: enviar a la cola en segundo plano (Cron Job la procesa)
                \App\Jobs\ProcessPdfCatalog::dispatch(
                    $this->bulkText,
                    $pdfPath,
                    $this->category_id,
                    $this->brand,
                    $this->vehicle_make,
                    $this->scanId
                );
            }

        } catch (\Exception $e) {
            $this->isParsing = false;
            $this->scanId = null;
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al iniciar el análisis: ' . $e->getMessage()]);
        }
    }

    public function checkScanStatus()
    {
        if (!$this->scanId) return;

        // Verificar si hay error
        $error = \Illuminate\Support\Facades\Cache::get('scan_error_' . $this->scanId);
        if ($error) {
            $this->isParsing = false;
            $this->scanId = null;
            
            if (str_contains(strtolower($error), 'secured pdf')) {
                $error = 'El PDF está protegido (encriptado). Por favor, usa la función "Imprimir -> Guardar como PDF" en tu navegador para quitarle la seguridad y sube ese nuevo archivo.';
            }
            
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al analizar: ' . $error]);
            \Illuminate\Support\Facades\Cache::forget('scan_error_' . $this->scanId);
            return;
        }

        // Verificar si hay resultado
        $result = \Illuminate\Support\Facades\Cache::get('scan_result_' . $this->scanId);
        if ($result !== null) {
            $this->parsedProducts = $result;
            $this->isParsing = false;
            $this->scanId = null;
            
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Escaneo completado exitosamente.']);
            \Illuminate\Support\Facades\Cache::forget('scan_result_' . $this->scanId);
        }
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

                    // Map Gemini AI extracted fuel type if available, otherwise use default
                    $finalFuelTypes = $this->fuel_types; // Default from Step 1
                    if (!empty($p['ai_fuel_type'])) {
                        $aiF = strtoupper(trim($p['ai_fuel_type']));
                        if (str_contains($aiF, 'G') && str_contains($aiF, 'D')) {
                            $finalFuelTypes = ['GASOLINA', 'DIESEL'];
                        } elseif (str_contains($aiF, 'G')) {
                            $finalFuelTypes = ['GASOLINA'];
                        } elseif (str_contains($aiF, 'D')) {
                            $finalFuelTypes = ['DIESEL'];
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
                            'fuel_type' => count($finalFuelTypes) === 1 ? $finalFuelTypes[0] : null,
                            'fuel_types' => !empty($finalFuelTypes) ? array_values($finalFuelTypes) : null,
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
            'categories' => Category::with('parent.parent')->orderBy('name')->get(),
            'makes' => Make::orderBy('name')->pluck('name')->toArray(),
        ]);
    }
}
