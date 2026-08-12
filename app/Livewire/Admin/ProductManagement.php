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
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class ProductManagement extends Component
{
    use WithPagination, WithFileUploads;

    public function paginationView()
    {
        return 'vendor.pagination.custom-repuestofijo';
    }

    public array $oversizesData = [];
    public array $oversizeList = [
        'STD' => 'STD (Standard)',
        '025' => '0.25mm = 0.010"',
        '050' => '0.50mm = 0.020"',
        '075' => '0.75mm = 0.030"',
        '100' => '1.00mm = 0.040"',
        '125' => '1.25mm = 0.050"',
        '150' => '1.50mm = 0.060"',
        'UNICA' => 'Única / Sin sobremedida',
    ];

    // Filters
    public $search = '';
    public $filterProvider = '';
    public $filterCategory = '';
    public $perPage = 25;

    // Modal
    public $showModal = false;
    public $editingProduct = null;

    // Form fields
    public $provider_id = '';
    public $category_id = '';
    public $brand = '';
    public $supplier_code = '';
    public $oem_code = '';
    public $additional_oem_codes = '';
    public $oversize = '';
    public $fuel_type = '';
    public $name = '';
    public $specs_raw = '';
    public $specs_radial = '';
    public $specs_shape = '';
    public $notes = '';
    public $specs_bore = '';
    public $specs_cylinders = '';
    public $specs_length = '';
    public $specs_comp_height = '';
    public $specs_height_1 = '';
    public $specs_height_2 = '';
    public $specs_pin = '';
    public $specs_circlip = false;
    public $price = '';
    public $is_active = true;
    public $image;
    public $existing_image = null;
    public $scannerImage;
    public $vehicle_make = '';

    // Vehicle compatibility via DB selects (new)
    public $form_make = '';          // selected make name
    public $form_model_ids = [];     // selected car_model IDs
    public $form_engine_ids = [];    // selected engine IDs

    // Prevent reactive hooks from clearing data during restoration
    protected bool $isLoading = false;

    // Options populated dynamically
    public $all_makes = [];
    public $available_models = [];   // filtered by form_make
    public $available_engines = [];  // filtered by form_model_ids

    public $categorySearch = '';
    public $categoryResults = [];

    // Quick-add for unlisted vehicle data
    public $new_model_name = '';
    public $new_model_year = '';
    public $new_engine_code = '';
    public $new_engine_disp = '';
    public $new_engine_fuel_type = 'GASOLINA';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterProvider()
    {
        $this->resetPage();
    }
    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->all_makes = Make::orderBy('name')->pluck('name')->toArray();
    }

    public function updatedFormMake($value)
    {
        if ($this->isLoading)
            return;
        $this->form_model_ids = [];
        $this->form_engine_ids = [];
        $this->available_engines = [];
        if ($value) {
            $make = Make::where('name', $value)->first();
            $this->available_models = $make
                ? CarModel::where('make_id', $make->id)
                    ->orderBy('name')
                    ->get(['id', 'name', 'start_year', 'end_year'])
                    ->map(fn($m) => [
                        'id' => $m->id,
                        'label' => $m->name . ($m->start_year ? ' (' . $m->start_year . ($m->end_year && $m->end_year !== $m->start_year ? '-' . $m->end_year : '') . ')' : ''),
                    ])->toArray()
                : [];
        } else {
            $this->available_models = [];
        }
    }

    public function updatedFormModelIds($value)
    {
        if ($this->isLoading)
            return;
        $this->form_engine_ids = [];
        $this->loadAvailableEngines();
    }

    /**
     * Quick-add: create a new CarModel for the selected make.
     */
    public function addNewModel()
    {
        $this->new_model_name = strtoupper(trim($this->new_model_name));
        if (!$this->form_make || !$this->new_model_name) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Ingrese un nombre de modelo.']);
            return;
        }
        $make = Make::where('name', $this->form_make)->first();
        if (!$make)
            return;

        // Parse year range: "1995-2002" or "1995"
        $startYear = null;
        $endYear = null;
        if ($this->new_model_year) {
            $parts = preg_split('/[-–]/', trim($this->new_model_year));
            $startYear = trim($parts[0]) ?: null;
            $endYear = isset($parts[1]) ? trim($parts[1]) : $startYear;
        }

        $existing = CarModel::where('make_id', $make->id)
            ->whereRaw('UPPER(name) = ?', [$this->new_model_name])
            ->first();

        $model = $existing ?? CarModel::create([
            'make_id' => $make->id,
            'name' => $this->new_model_name,
            'start_year' => $startYear,
            'end_year' => $endYear,
        ]);

        $this->new_model_name = '';
        $this->new_model_year = '';
        $this->updatedFormMake($this->form_make); // reload list
        // Auto-select the new/existing model
        $this->form_model_ids = array_unique(array_merge($this->form_model_ids, [(string) $model->id]));
        $this->updatedFormModelIds($this->form_model_ids);
        $this->dispatch('notify', ['type' => 'success', 'message' => "Modelo '{$model->name}' " . ($existing ? 'ya existía y fue seleccionado.' : 'creado y seleccionado.')]);
    }

    /**
     * Quick-add: create a new Engine for the selected models.
     */
    public function addNewEngine()
    {
        $code = strtoupper(trim($this->new_engine_code));
        if (empty($this->form_model_ids) || !$code) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Seleccione al menos un modelo e ingrese el código de motor.']);
            return;
        }

        $newIds = [];
        foreach ($this->form_model_ids as $modelId) {
            $existing = Engine::where('car_model_id', $modelId)
                ->whereRaw('UPPER(engine_code) = ?', [$code])
                ->first();

            $engine = $existing ?? Engine::create([
                'car_model_id' => $modelId,
                'engine_code' => $code,
                'displacement' => trim($this->new_engine_disp) ?: null,
                'fuel_type' => $this->new_engine_fuel_type ?: 'GASOLINA',
            ]);
            $newIds[] = (string) $engine->id;
        }

        $this->new_engine_code = '';
        $this->new_engine_disp = '';
        $this->new_engine_fuel_type = 'GASOLINA';
        $this->updatedFormModelIds($this->form_model_ids); // reload engine list
        $this->form_engine_ids = array_unique(array_merge($this->form_engine_ids, $newIds));
        $this->dispatch('notify', ['type' => 'success', 'message' => "Motor '{$code}' añadido y seleccionado."]);
    }

    public function clearVehicleSelection()
    {
        $this->form_make = '';
        $this->form_model_ids = [];
        $this->form_engine_ids = [];
        $this->available_models = [];
        $this->available_engines = [];
    }

    public function selectAllModels()
    {
        $this->form_model_ids = collect($this->available_models)->pluck('id')->map(fn($id) => (string) $id)->toArray();
        $this->updatedFormModelIds($this->form_model_ids);
    }

    /**
     * Load available engines deduplicated by engine_code label.
     * When multiple models share the same engine code (e.g. W04C-T),
     * only ONE entry appears in the list. Selecting it auto-selects
     * all engine IDs with that code across the selected models.
     */
    private function loadAvailableEngines(): void
    {
        if (empty($this->form_model_ids)) {
            $this->available_engines = [];
            return;
        }

        $engines = Engine::whereIn('car_model_id', $this->form_model_ids)
            ->orderBy('engine_code')
            ->get();

        // Group by label (engine_code + displacement) — deduplicate visually
        $grouped = [];
        foreach ($engines as $e) {
            $label = $e->engine_code
                . ($e->displacement ? ' ' . $e->displacement . 'CC' : '')
                . ($e->engine_power  ? ' ' . $e->engine_power  . 'HP' : '');

            if (!isset($grouped[$label])) {
                $grouped[$label] = [
                    'id'    => (string) $e->id,   // representative ID for the checkbox value
                    'ids'   => [],                 // all IDs sharing this label
                    'label' => $label,
                ];
            }
            $grouped[$label]['ids'][] = (string) $e->id;
        }

        $this->available_engines = array_values($grouped);
    }

    /**
     * When the user checks an engine (by representative ID), expand to ALL
     * engine IDs sharing the same label so every model variant gets covered.
     */
    public function updatedFormEngineIds(): void
    {
        if (empty($this->form_engine_ids) || empty($this->available_engines)) {
            return;
        }

        $selectedIds = $this->form_engine_ids;
        $expanded    = [];

        foreach ($this->available_engines as $eng) {
            // If any of the engine's IDs is selected, include all of them
            if (array_intersect($selectedIds, $eng['ids'])) {
                $expanded = array_merge($expanded, $eng['ids']);
            }
        }

        $this->form_engine_ids = array_values(array_unique($expanded));
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingProduct = null;
        $this->provider_id = '';
        $this->category_id = '';
        $this->brand = '';
        $this->supplier_code = '';
        $this->oem_code = '';
        $this->additional_oem_codes = '';
        $this->oversize = '';
        $this->fuel_type = '';
        $this->name = '';
        $this->specs_raw = '';
        $this->specs_radial = '';
        $this->specs_shape = '';
        $this->specs_bore = '';
        $this->specs_cylinders = '';
        $this->specs_length = '';
        $this->specs_comp_height = '';
        $this->specs_height_1 = '';
        $this->specs_height_2 = '';
        $this->specs_pin = '';
        $this->specs_circlip = '';
        $this->notes = '';
        $this->price = '';
        $this->is_active = true;
        $this->image = null;
        $this->existing_image = null;
        $this->isLoading = false;
        // vehicle compat
        $this->form_make = '';
        $this->form_model_ids = [];
        $this->form_engine_ids = [];
        $this->available_models = [];
        $this->available_engines = [];
        $this->vehicle_make = '';

        $this->oversizesData = [];
        foreach (array_keys($this->oversizeList) as $key) {
            $this->oversizesData[$key] = [
                'enabled' => $key === 'STD',
                'price' => '',
                'stock' => 10,
            ];
        }
    }

    public function editProduct($id)
    {
        $this->isLoading = true;
        $this->resetValidation();
        $p = Product::with('oversizes')->findOrFail($id);
        $this->editingProduct = $p;

        $this->provider_id = $p->provider_id;
        $this->category_id = $p->category_id;
        $this->brand = $p->brand;
        $this->supplier_code = $p->supplier_code;
        $this->oem_code = $p->oem_code;
        $this->additional_oem_codes = implode(', ', $p->additional_oem_codes ?? []);
        $this->oversize = $p->oversize ?? '';
        $this->fuel_type = $p->fuel_type ?? '';
        $this->name = $p->name;
        $specs = $p->specs ?? [];
        $this->specs_raw = is_array($specs['raw'] ?? null) ? implode(' ', $specs['raw']) : ($specs['raw'] ?? '');
        $this->specs_radial = is_array($specs['radial'] ?? null) ? implode(', ', $specs['radial']) : '';
        $this->specs_shape = $specs['shape'] ?? '';
        $this->specs_bore = $specs['bore'] ?? '';
        $this->specs_cylinders = $specs['cylinders'] ?? '';
        $this->specs_length = $specs['length'] ?? '';
        $this->specs_comp_height = $specs['comp_height'] ?? '';
        
        $heights = $specs['height'] ?? [];
        if (!is_array($heights)) $heights = [$heights];
        $this->specs_height_1 = $heights[0] ?? '';
        $this->specs_height_2 = $heights[1] ?? '';
        
        $this->specs_pin = $specs['pin'] ?? '';
        $this->specs_circlip = !empty($specs['circlip_required']);
        $this->notes = $p->notes;
        $this->price = $p->price;
        $this->is_active = $p->is_active;
        $this->existing_image = $p->image_path;
        $this->vehicle_make = $p->vehicle_make ?? '';
        $this->form_make = $p->vehicle_make ?? '';

        // Reset and load variants
        $this->oversizesData = [];
        foreach (array_keys($this->oversizeList) as $key) {
            $this->oversizesData[$key] = [
                'enabled' => false,
                'price' => '',
                'stock' => 0,
            ];
        }

        foreach ($p->oversizes as $variant) {
            $key = $variant->oversize ?: 'STD';
            if (isset($this->oversizesData[$key])) {
                $this->oversizesData[$key] = [
                    'enabled' => true,
                    'price' => (string) $variant->price,
                    'stock' => $variant->stock,
                ];
            }
        }

        // Restore: populate all dropdowns
        if ($this->form_make) {
            $make = Make::where('name', $this->form_make)->first();
            if ($make) {
                $this->available_models = CarModel::where('make_id', $make->id)
                    ->orderBy('name')
                    ->get(['id', 'name', 'start_year', 'end_year'])
                    ->map(fn($m) => [
                        'id' => $m->id,
                        'label' => $m->name . ($m->start_year ? ' (' . $m->start_year . ($m->end_year && $m->end_year !== $m->start_year ? '-' . $m->end_year : '') . ')' : ''),
                    ])->toArray();
            }
        }

        // Restore model IDs and engine IDs (source of truth: ID columns first, fallback to codes)
        if (!empty($p->compatible_model_ids)) {
            $this->form_model_ids = array_map('strval', (array) $p->compatible_model_ids);
        }

        if (!empty($p->compatible_engine_ids)) {
            $this->form_engine_ids = array_map('strval', (array) $p->compatible_engine_ids);
        } elseif (!empty($p->compatible_engines) && !empty($this->form_model_ids)) {
            // Fallback: derive engine IDs from stored codes
            $matchedEngines = Engine::whereIn('engine_code', $p->compatible_engines)->get();
            $this->form_engine_ids = $matchedEngines->pluck('id')->map(fn($id) => (string) $id)->toArray();
        }

        // Populate available engines for the selected models
        $this->loadAvailableEngines();

        $this->isLoading = false;
        $this->showModal = true;
    }

    public function saveProduct()
    {
        try {
            $this->validate([
                'provider_id' => 'required|exists:providers,id',
                'supplier_code' => 'required|string|max:255',
                'brand' => 'nullable|string|max:100',
                'oem_code' => 'nullable|string|max:2000',
                'name' => 'nullable|string|max:255',
                'image' => 'nullable|image|max:2048', // 2MB max
            ], [
                'provider_id.required' => 'El proveedor es obligatorio.',
                'supplier_code.required' => 'El código es obligatorio.',
                'image.image' => 'El archivo debe ser una imagen.',
                'image.max' => 'La imagen no debe pesar más de 2MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Por favor, completa los campos requeridos con datos válidos.']);
            throw $e;
        }

        $hasEnabled = false;
        foreach ($this->oversizesData as $key => $val) {
            if ($val['enabled'] ?? false) {
                $hasEnabled = true;
                if (!isset($val['price']) || $val['price'] === '' || !is_numeric($val['price']) || floatval($val['price']) < 0) {
                    $this->addError("oversizesData.{$key}.price", "El precio es requerido y debe ser un número válido.");
                    $this->dispatch('notify', ['type' => 'error', 'message' => "El precio para la medida {$key} es requerido y debe ser válido."]);
                    return;
                }
            }
        }

        if (!$hasEnabled) {
            $this->addError('oversizesData', 'Debes habilitar al menos una sobremedida.');
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Debes habilitar al menos una sobremedida (ej: STD).']);
            return;
        }

        // Build compatible_engines and compatible_vehicles FROM the DB selections
        $selectedEngines = Engine::whereIn('id', $this->form_engine_ids)->get();
        $engines = $selectedEngines->pluck('engine_code')->unique()->values()->toArray();

        $selectedModels = CarModel::whereIn('id', $this->form_model_ids)->get();
        $vehicles = $selectedModels->pluck('name')->unique()->filter()->values()->toArray();

        // Parse OEM codes: keep them all in oem_code if they fit within 255 chars.
        // Otherwise, split and put the remainder into additional_oem_codes.
        $rawOemCode = strtoupper(trim($this->oem_code));
        $primaryOem = null;
        $allAdditionalCodes = array_values(array_filter(array_map('trim', explode(',', $this->additional_oem_codes))));

        if (strlen($rawOemCode) <= 255) {
            $primaryOem = $rawOemCode ?: null;
        } else {
            // Split by comma
            $oemParts = array_values(array_filter(array_map('trim', explode(',', $rawOemCode))));
            $tempOem = '';
            $extraOems = [];
            foreach ($oemParts as $part) {
                if (strlen($tempOem . ($tempOem ? ', ' : '') . $part) <= 255) {
                    $tempOem .= ($tempOem ? ', ' : '') . $part;
                } else {
                    $extraOems[] = $part;
                }
            }
            $primaryOem = $tempOem ?: null;
            $allAdditionalCodes = array_values(array_unique(array_merge($extraOems, $allAdditionalCodes)));
        }

        // Parse specs
        $radialStr = (string) $this->specs_radial;
        $radial = $radialStr !== '' ? array_values(array_filter(array_map(
            fn($r) => trim($r),
            explode(',', $radialStr)
        ))) : [];
        
        $specs = [
            'raw' => strtoupper(trim((string)$this->specs_raw)),
            'radial' => !empty($radial) ? $radial : null,
            'shape' => trim((string)$this->specs_shape) ?: null,
            'bore' => trim((string)$this->specs_bore) ?: null,
            'cylinders' => trim((string)$this->specs_cylinders) ?: null,
            'length' => trim((string)$this->specs_length) ?: null,
            'comp_height' => trim((string)$this->specs_comp_height) ?: null,
            'height' => array_values(array_filter([trim((string)$this->specs_height_1), trim((string)$this->specs_height_2)])) ?: null,
            'pin' => trim((string)$this->specs_pin) ?: null,
            'circlip_required' => $this->specs_circlip ? 'required' : null,
        ];
        
        // Remove empty values from specs array so it saves cleanly or becomes null if fully empty
        $specs = array_filter($specs, fn($v) => $v !== null && $v !== '');

        // Determine price, oversize and is_active for backward compatibility on products table
        $legacyPrice = null;
        $legacyOversize = 'STD';
        $legacyIsActive = false;

        $stdVariant = $this->oversizesData['STD'] ?? null;
        if ($stdVariant && $stdVariant['enabled']) {
            $legacyPrice = floatval($stdVariant['price']);
            $legacyOversize = 'STD';
            $legacyIsActive = intval($stdVariant['stock']) > 0;
        } else {
            $firstEnabled = collect($this->oversizesData)->first(fn($v) => $v['enabled'] ?? false);
            if ($firstEnabled) {
                $legacyPrice = floatval($firstEnabled['price']);
                $legacyOversize = array_search($firstEnabled, $this->oversizesData);
                $legacyIsActive = intval($firstEnabled['stock']) > 0;
            }
        }

        $data = [
            'provider_id' => $this->provider_id,
            'category_id' => $this->category_id ?: null,
            'brand' => strtoupper(trim($this->brand)) ?: null,
            'vehicle_make' => strtoupper(trim($this->form_make)) ?: null,
            'supplier_code' => strtoupper(trim($this->supplier_code)),
            'oem_code' => $primaryOem,
            'additional_oem_codes' => $allAdditionalCodes ?: null,
            'oversize' => $legacyOversize,
            'fuel_type' => strtoupper(trim($this->fuel_type)) ?: null,
            'name' => trim($this->name) ?: null,
            'compatible_engines' => $engines ?: null,
            'compatible_vehicles' => !empty($vehicles) ? implode(', ', $vehicles) : null,
            'compatible_model_ids' => !empty($this->form_model_ids) ? array_map('intval', $this->form_model_ids) : null,
            'compatible_engine_ids' => !empty($this->form_engine_ids) ? array_map('intval', $this->form_engine_ids) : null,
            'specs' => !empty($specs) ? $specs : null,
            'notes' => trim($this->notes) ?: null,
            'price' => $legacyPrice,
            'is_active' => $legacyIsActive,
            'updated_by' => auth()->id(),
        ];

        // Handle Image Upload
        if ($this->image) {
            $imageName = 'product_' . time() . '_' . uniqid() . '.' . $this->image->getClientOriginalExtension();
            $path = $this->image->storeAs('products', $imageName, 'public');
            $data['image_path'] = $path;
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function() use ($data) {
                if ($this->editingProduct) {
                    $this->editingProduct->update($data);
                    $product = $this->editingProduct;
                } else {
                    $data['created_by'] = auth()->id();
                    $product = Product::create($data);
                }

                // Sync oversizes
                foreach ($this->oversizesData as $key => $val) {
                    if ($val['enabled'] ?? false) {
                        ProductOversize::updateOrCreate(
                            ['product_id' => $product->id, 'oversize' => $key],
                            [
                                'price' => floatval($val['price']),
                                'stock' => intval($val['stock'] ?? 0),
                                'is_active' => intval($val['stock'] ?? 0) > 0,
                            ]
                        );
                    } else {
                        ProductOversize::where('product_id', $product->id)->where('oversize', $key)->delete();
                    }
                }
            });

            $this->dispatch('notify', ['type' => 'success', 'message' => $this->editingProduct ? 'Producto actualizado correctamente.' : 'Producto creado correctamente.']);
            \Illuminate\Support\Facades\Cache::forget('brands_with_products');
            \Illuminate\Support\Facades\Cache::forget('all_makes_names');
            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->is_active = !$product->is_active;
        $product->save();
        \Illuminate\Support\Facades\Cache::forget('brands_with_products');
        $this->dispatch('notify', ['type' => 'info', 'message' => 'Estado actualizado.']);
    }

    public function confirmDelete($id)
    {
        $product = Product::findOrFail($id);
        $this->dispatch('swal:confirm-delete-product', [
            'id' => $id,
            'title' => '¿Eliminar producto?',
            'text' => "Se eliminará \"{$product->supplier_code}\". Esta acción no se puede deshacer.",
        ]);
    }

    #[On('delete-product-confirmed')]
    public function deleteProduct($id)
    {
        $id = is_array($id) ? ($id['id'] ?? null) : $id;
        if (!$id)
            return;

        try {
            Product::findOrFail($id)->delete();
            \Illuminate\Support\Facades\Cache::forget('brands_with_products');
            \Illuminate\Support\Facades\Cache::forget('all_makes_names');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Producto eliminado correctamente.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al eliminar el producto.']);
        }
    }

    public function scanFormCatalog()
    {
        $this->validate([
            'scannerImage' => 'required|image|max:5120',
        ], [
            'scannerImage.required' => 'Debes subir una captura del catálogo.',
            'scannerImage.max' => 'La imagen no debe pesar más de 5MB.',
        ]);

        // Allow up to 5 minutes for Ollama (runs on CPU, needs more time than Gemini)
        set_time_limit(300);

        try {
            $imageData = base64_encode(file_get_contents($this->scannerImage->getRealPath()));
            $mimeType  = $this->scannerImage->getMimeType();

            $prompt = <<<PROMPT
Eres un extractor experto de datos de catálogos técnicos de repuestos de motor (pistones, anillos, metales).

Analiza la imagen COMPLETA de la tabla del catálogo y extrae TODOS los datos visibles pertenecientes al producto principal.

La marca del vehículo ya fue seleccionada por el usuario: "{$this->form_make}".
Todos los modelos y motores pertenecen a "{$this->form_make}".

INSTRUCCIÓN CRÍTICA: Extrae solo los datos correspondientes al producto que se está consultando. No mezcles códigos OEM de columnas ajenas (ej: si consultas Pistones, no incluyas los OEM de Anillos).
NO utilices nombres o códigos de ejemplo; lee los códigos reales que aparecen en la foto de la tabla.

Devuelve un array JSON con EXACTAMENTE esta estructura (sin texto adicional, sin markdown):
[
  {
    "supplier_code": "<CODIGO_PROVEEDOR_ALFANUMERICO>",
    "brand": "<MARCA_REPUESTO_NPR_O_SWD_ETC>",
    "make": "{$this->form_make}",
    "models": ["<MODELO_VEHICULO_1>", "<MODELO_VEHICULO_2>"],
    "engines": ["<CODIGO_MOTOR_1>", "<CODIGO_MOTOR_2>"],
    "displacement": "<SOLO_NUMERO_CILINDRADA_CC>",
    "bore": "<DIAMETRO_MOTOR_BETA_EJ_74.0MM>",
    "heights": "<ALTURA_O_MEDIDAS_EJ_1.2X1.5X2.8_O_-2.7/-5.3>",
    "radial": "<MEDIDA_RADIAL_EJ_2.67/2.8/2.55>",
    "shape": "<FORMA_PERFIL_EJ_BF-IB/TUC/NIFF-S>",
    "oem_code": "<TODOS_LOS_CODIGOS_OEM_SEPARADOS_POR_COMA>",
    "catalog_code": "<CODIGO_CATALOGO_ADICIONAL_SI_EXISTE>",
    "notes": ""
  }
]

Reglas:
- "supplier_code": código del producto en el catálogo (ej: PTMA408308G, SWH-30433)
- "brand": marca del repuesto (NPR, NDC, ARCOMOTO, etc.)
- "make": SIEMPRE "{$this->form_make}"
- "models": lista de modelos de vehículos visibles en la fila.
- "engines": lista de códigos de motor visibles.
- "displacement": cilindrada en cc (solo número, ej: "1998")
- "oem_code": TODOS los códigos OEM originales del fabricante correspondientes a este producto, separados por coma. IMPORTANTE: Los OEMs de anillos u otras piezas NO deben incluirse.
- "catalog_code": Código de catálogo alternativo (ej: SDG30002ZZ). NUNCA pongas códigos OEM en este campo. Si no hay código de catálogo claro, déjalo vacío "".
- Si un campo no está visible en la imagen, usa "" o []
- Devuelve SOLO el JSON, sin texto adicional ni markdown
PROMPT;

            $backend = env('SCAN_BACKEND', 'gemini');
            $rawText = '';

            if ($backend === 'ollama') {
                // ── OLLAMA LOCAL ──────────────────────────────────────────────
                $ollamaUrl   = rtrim(env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'), '/') . '/api/generate';
                $ollamaModel = env('OLLAMA_VISION_MODEL', 'llava:7b');

                $payload = [
                    'model'  => $ollamaModel,
                    'prompt' => $prompt,
                    'images' => [$imageData],
                    'stream' => false,
                    'format' => 'json', // Force model to output valid JSON grammars
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
                curl_close($ch);

                if ($httpCode !== 200) {
                    $errDec = json_decode($response, true);
                    $msg = $errDec['error'] ?? "Error HTTP {$httpCode} desde Ollama";
                    throw new \Exception("Ollama: {$msg}");
                }

                $decoded = json_decode($response, true);
                $rawText = $decoded['response'] ?? '';

            } elseif ($backend === 'groq') {
                // ── GROQ CLOUD (Llama 3.2 Vision) ─────────────────────────────
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
                // ── GEMINI API ────────────────────────────────────────────────
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
                $url    = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

                $ch = curl_init($url);
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

                $decoded = json_decode($response, true);
                if ($httpCode !== 200) {
                    $msg = $decoded['error']['message'] ?? "Error HTTP {$httpCode}";
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

            if (!$data || empty($data)) {
                throw new \Exception("No se pudo interpretar la respuesta de la IA. Intenta con otra imagen o ajusta el encuadre.");
            }

            $row = $data[0];

            // 1. Brand & Supplier code
            $this->brand = strtoupper(trim($row['brand'] ?? 'NPR'));
            $rawCode = $row['supplier_code'] ?? '';
            $this->supplier_code = strtoupper(str_replace(['-', ' ', '_', '/'], '', $rawCode));

            // 2. OEM code & catalog code mapping
            $this->oem_code = strtoupper(trim($row['oem_code'] ?? ''));

            // Map catalog_code to additional_oem_codes
            $catCode = strtoupper(trim($row['catalog_code'] ?? ''));
            if ($catCode) {
                $existing = array_filter(array_map('trim', explode(',', $this->additional_oem_codes)));
                if (!in_array($catCode, $existing)) {
                    $existing[] = $catCode;
                }
                $this->additional_oem_codes = implode(', ', $existing);
            }

            // 3. Make & vehicle components creation
            $makeName = strtoupper(trim($row['make'] ?? ''));
            if ($makeName) {
                $make = Make::firstOrCreate(['name' => $makeName]);
                $this->form_make = $make->name;

                $this->all_makes = Make::orderBy('name')->pluck('name')->toArray();

                $this->isLoading = true;
                $this->available_models = CarModel::where('make_id', $make->id)
                    ->orderBy('name')
                    ->get(['id', 'name', 'start_year', 'end_year'])
                    ->map(fn($m) => [
                        'id'    => $m->id,
                        'label' => $m->name . ($m->start_year ? ' (' . $m->start_year . ($m->end_year && $m->end_year !== $m->start_year ? '-' . $m->end_year : '') . ')' : ''),
                    ])->toArray();
                $this->isLoading = false;

                // Resolve models
                $models = $row['models'] ?? [];
                if (is_string($models)) {
                    $models = array_filter(array_map('trim', explode(',', $models)));
                }

                $modelIds = [];
                foreach ($models as $modelName) {
                    $modelName = strtoupper(trim($modelName));
                    if (!$modelName) continue;
                    $model = CarModel::firstOrCreate([
                        'make_id' => $make->id,
                        'name'    => $modelName,
                    ]);
                    $modelIds[] = (string) $model->id;
                }
                $this->form_model_ids = $modelIds;

                $this->isLoading = true;
                $this->loadAvailableEngines();
                $this->isLoading = false;

                // Resolve engines — one entry per (car_model_id, engine_code)
                $engines = $row['engines'] ?? [];
                if (is_string($engines)) {
                    $engines = array_filter(array_map('trim', explode(',', $engines)));
                }

                $engineIds = [];
                foreach ($engines as $engCode) {
                    $engCode = strtoupper(trim($engCode));
                    if (!$engCode) continue;

                    foreach ($modelIds as $mId) {
                        $engine = Engine::firstOrCreate(
                            [
                                'car_model_id' => (int) $mId,
                                'engine_code'  => $engCode,
                            ],
                            [
                                'displacement' => trim(preg_replace('/(cc|c\.c\.|c\.c)/i', '', $row['displacement'] ?? '')) ?: null,
                                'fuel_type'    => $this->fuel_type ?: 'GASOLINA',
                            ]
                        );
                        $engineIds[] = (string) $engine->id;
                    }
                }
                $this->form_engine_ids = array_values(array_unique($engineIds));
            }

            // 4. Specs
            $this->specs_raw    = strtoupper(trim(($row['bore'] ?? '') . ' ' . ($row['heights'] ?? '')));
            $this->specs_radial = trim($row['radial'] ?? '');
            $this->specs_shape  = trim($row['shape'] ?? '');
            $this->notes        = trim($row['notes'] ?? '');

            // 5. Name description generator
            $modelsList  = is_array($models) ? $models : [];
            $this->name  = "Anillos " . $this->brand . " " . $this->form_make . " " . implode(', ', array_slice($modelsList, 0, 2)) . " " . $this->specs_raw;

            $this->scannerImage = null;

            $backendLabel = $backend === 'ollama' ? '(Ollama local)' : '(Gemini)';
            $this->dispatch('notify', ['type' => 'success', 'message' => "¡Datos importados con éxito! {$backendLabel}"]);

        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al escanear: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $query = Product::with(['provider', 'category', 'createdBy', 'updatedBy'])
            ->when($this->search, function ($q) {
                $s = $this->search;
                $q->where(function ($q2) use ($s) {
                    $q2->where('supplier_code', 'LIKE', "%$s%")
                        ->orWhere('oem_code', 'LIKE', "%$s%")
                        ->orWhere('brand', 'LIKE', "%$s%")
                        ->orWhere('vehicle_make', 'LIKE', "%$s%")
                        ->orWhere('compatible_vehicles', 'LIKE', "%$s%")
                        ->orWhere('name', 'LIKE', "%$s%");
                });
            })
            ->when($this->filterProvider, fn($q) => $q->where('provider_id', $this->filterProvider))
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->orderBy('vehicle_make')
            ->orderBy('brand')
            ->orderBy('supplier_code');

        $products = $query->paginate($this->perPage);
        $providers = Provider::orderBy('business_name')->get();
        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.product-management', compact('products', 'providers', 'categories'))
            ->layout('layouts.app');
    }
}
