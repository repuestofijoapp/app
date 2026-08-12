<?php

namespace App\Livewire\Admin;

use App\Models\Make;
use App\Models\CarModel;
use App\Models\Engine;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class VehicleManagement extends Component
{
    use WithPagination, WithFileUploads;

    public function paginationView()
    {
        return 'vendor.pagination.custom-repuestofijo';
    }

    // Active tab: 'makes' | 'models' | 'engines'
    public string $tab = 'makes';

    // ─── Filters ───
    public string $searchMake  = '';
    public string $searchModel = '';
    public string $searchEngine = '';
    public string $filterMakeForModels = '';
    public string $filterMakeForEngines = '';
    public string $filterModelForEngines = '';

    // ─── Modal state ───
    public bool   $showModal = false;
    public string $modalMode = 'make'; // 'make' | 'model' | 'engine'
    public ?int   $editingId = null;

    // ─── Make form ───
    public string $make_name = '';

    // ─── Model form ───
    public string $model_make_id  = '';
    public string $model_name     = '';
    public string $model_version  = '';
    public string $model_start_year = '';
    public string $model_end_year  = '';
    public string $model_image_current = ''; // existing saved image filename
    public string $model_selected_existing = ''; // pick from existing files
    public $model_image_upload = null; // new uploaded file

    // ─── Engine form ───
    public string $engine_car_model_id  = '';
    public string $engine_code          = '';
    public string $engine_displacement  = '';
    public string $engine_fuel_type     = 'GASOLINA';
    public string $engine_power         = '';

    // ─── Populated dropdowns ───
    public array $allMakes  = [];
    public array $allModels = [];

    public function mount(): void
    {
        $this->loadDropdowns();
    }

    private function loadDropdowns(): void
    {
        $this->allMakes  = Make::orderBy('name')->get(['id', 'name'])->toArray();
        $this->allModels = CarModel::with('make')->orderBy('name')->get()->map(fn($m) => [
            'id'    => $m->id,
            'label' => ($m->make->name ?? '?') . ' · ' . $m->name,
        ])->toArray();
    }

    // ─── Tab switching ───
    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    // ─── Opens ───
    public function openCreateMake(): void
    {
        $this->resetMakeForm();
        $this->editingId  = null;
        $this->modalMode  = 'make';
        $this->showModal  = true;
    }

    public function openCreateModel(): void
    {
        $this->resetModelForm();
        $this->editingId  = null;
        $this->modalMode  = 'model';
        $this->showModal  = true;
    }

    public function openCreateEngine(): void
    {
        $this->resetEngineForm();
        $this->editingId  = null;
        $this->modalMode  = 'engine';
        $this->showModal  = true;
    }

    public function editMake(int $id): void
    {
        $make = Make::findOrFail($id);
        $this->editingId  = $id;
        $this->make_name  = $make->name;
        $this->modalMode  = 'make';
        $this->showModal  = true;
    }

    public function editModel(int $id): void
    {
        $model = CarModel::findOrFail($id);
        $this->editingId                = $id;
        $this->model_make_id            = (string) $model->make_id;
        $this->model_name               = $model->name;
        $this->model_version            = $model->version_no ?? '';
        $this->model_start_year         = (string) ($model->start_year ?? '');
        $this->model_end_year           = (string) ($model->end_year ?? '');
        $this->model_image_current      = $model->image ?? '';
        $this->model_selected_existing  = $model->image ?? '';
        $this->model_image_upload       = null;
        $this->modalMode                = 'model';
        $this->showModal                = true;
    }

    public function editEngine(int $id): void
    {
        $engine = Engine::findOrFail($id);
        $this->editingId              = $id;
        $this->engine_car_model_id    = (string) $engine->car_model_id;
        $this->engine_code            = $engine->engine_code;
        $this->engine_displacement    = (string) ($engine->displacement ?? '');
        $this->engine_fuel_type       = $engine->fuel_type ?? 'GASOLINA';
        $this->engine_power           = (string) ($engine->engine_power ?? '');
        $this->modalMode              = 'engine';
        $this->showModal              = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetMakeForm();
        $this->resetModelForm();
        $this->resetEngineForm();
        $this->editingId = null;
    }

    // ─── Saves ───
    public function saveMake(): void
    {
        $this->validate([
            'make_name' => 'required|string|max:100',
        ], ['make_name.required' => 'El nombre de la marca es obligatorio.']);

        $name = strtoupper(trim($this->make_name));

        if ($this->editingId) {
            Make::findOrFail($this->editingId)->update(['name' => $name]);
            $this->dispatch('notify', ['type' => 'success', 'message' => "Marca '{$name}' actualizada."]);
        } else {
            Make::create(['name' => $name]);
            $this->dispatch('notify', ['type' => 'success', 'message' => "Marca '{$name}' creada."]);
        }

        $this->loadDropdowns();
        $this->closeModal();
    }

    public function saveModel(): void
    {
        $this->validate([
            'model_make_id'       => 'required|exists:makes,id',
            'model_name'          => 'required|string|max:100',
            'model_image_upload'  => 'nullable|image|max:2048',
        ], [
            'model_make_id.required' => 'Selecciona una marca.',
            'model_name.required'    => 'El nombre del modelo es obligatorio.',
        ]);

        // Determine final image value
        $imageName = $this->model_image_current; // default: keep existing

        if ($this->model_image_upload) {
            // New file uploaded → store in public/images/cars
            $extension = $this->model_image_upload->getClientOriginalExtension();
            $originalName = pathinfo($this->model_image_upload->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = $originalName . '.' . $extension;
            $this->model_image_upload->storeAs('', $filename, ['disk' => 'car_images']);
            $imageName = $filename;
        } elseif ($this->model_selected_existing !== '') {
            // Admin picked from existing images list
            $imageName = $this->model_selected_existing;
        }

        $data = [
            'make_id'    => $this->model_make_id,
            'name'       => strtoupper(trim($this->model_name)),
            'version_no' => trim($this->model_version) ?: null,
            'start_year' => $this->model_start_year ?: null,
            'end_year'   => $this->model_end_year ?: null,
            'image'      => $imageName ?: null,
        ];

        if ($this->editingId) {
            CarModel::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => "Modelo '{$data['name']}' actualizado."]);
        } else {
            CarModel::create($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => "Modelo '{$data['name']}' creado."]);
        }

        $this->loadDropdowns();
        $this->closeModal();
    }

    public function saveEngine(): void
    {
        $this->validate([
            'engine_car_model_id' => 'required|exists:car_models,id',
            'engine_code'         => 'required|string|max:100',
        ], [
            'engine_car_model_id.required' => 'Selecciona un modelo de vehículo.',
            'engine_code.required'         => 'El código de motor es obligatorio.',
        ]);

        $data = [
            'car_model_id' => $this->engine_car_model_id,
            'engine_code'  => strtoupper(trim($this->engine_code)),
            'displacement' => $this->engine_displacement ?: null,
            'fuel_type'    => $this->engine_fuel_type ?: 'GASOLINA',
            'engine_power' => $this->engine_power ?: null,
        ];

        if ($this->editingId) {
            Engine::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => "Motor '{$data['engine_code']}' actualizado."]);
        } else {
            Engine::create($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => "Motor '{$data['engine_code']}' creado."]);
        }

        $this->closeModal();
    }

    // ─── Deletes ───
    public function confirmDeleteMake(int $id): void
    {
        $make = Make::findOrFail($id);
        $this->dispatch('swal:confirm-delete-vehicle', [
            'id'    => $id,
            'type'  => 'make',
            'title' => '¿Eliminar marca?',
            'text'  => "Se eliminará \"{$make->name}\" y todos sus modelos y motores asociados. Esta acción no se puede deshacer.",
        ]);
    }

    public function confirmDeleteModel(int $id): void
    {
        $model = CarModel::with('make')->findOrFail($id);
        $this->dispatch('swal:confirm-delete-vehicle', [
            'id'    => $id,
            'type'  => 'model',
            'title' => '¿Eliminar modelo?',
            'text'  => "Se eliminará \"{$model->name}\" y sus motores asociados.",
        ]);
    }

    public function confirmDeleteEngine(int $id): void
    {
        $engine = Engine::findOrFail($id);
        $this->dispatch('swal:confirm-delete-vehicle', [
            'id'    => $id,
            'type'  => 'engine',
            'title' => '¿Eliminar motor?',
            'text'  => "Se eliminará el motor \"{$engine->engine_code}\".",
        ]);
    }

    #[On('delete-vehicle-confirmed')]
    public function deleteVehicleItem($id = null, $type = null): void
    {
        if (!$id || !$type) return;

        try {
            match ($type) {
                'make'   => Make::findOrFail($id)->delete(),
                'model'  => CarModel::findOrFail($id)->delete(),
                'engine' => Engine::findOrFail($id)->delete(),
            };
            $this->loadDropdowns();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Registro eliminado correctamente.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }

    // ─── Resets ───
    private function resetMakeForm(): void
    {
        $this->make_name = '';
    }

    private function resetModelForm(): void
    {
        $this->model_make_id            = '';
        $this->model_name               = '';
        $this->model_version            = '';
        $this->model_start_year         = '';
        $this->model_end_year           = '';
        $this->model_image_current      = '';
        $this->model_selected_existing  = '';
        $this->model_image_upload       = null;
    }

    private function resetEngineForm(): void
    {
        $this->engine_car_model_id = '';
        $this->engine_code         = '';
        $this->engine_displacement = '';
        $this->engine_fuel_type    = 'GASOLINA';
        $this->engine_power        = '';
    }

    public function render()
    {
        $makes = Make::when($this->searchMake, fn($q) => $q->where('name', 'LIKE', '%' . $this->searchMake . '%'))
            ->withCount(['carModels', 'carModels as engines_count' => fn($q) => $q])
            ->orderBy('name')
            ->paginate(20, ['*'], 'makesPage');

        $models = CarModel::with('make')
            ->when($this->searchModel, fn($q) => $q->where('name', 'LIKE', '%' . $this->searchModel . '%'))
            ->when($this->filterMakeForModels, fn($q) => $q->where('make_id', $this->filterMakeForModels))
            ->withCount('engines')
            ->orderBy('name')
            ->paginate(20, ['*'], 'modelsPage');

        $engines = Engine::with(['carModel.make'])
            ->when($this->searchEngine, fn($q) => $q->where('engine_code', 'LIKE', '%' . $this->searchEngine . '%'))
            ->when($this->filterModelForEngines, fn($q) => $q->where('car_model_id', $this->filterModelForEngines))
            ->when($this->filterMakeForEngines, function($q) {
                $q->whereHas('carModel', fn($q2) => $q2->whereHas('make', fn($q3) => $q3->where('id', $this->filterMakeForEngines)));
            })
            ->orderBy('engine_code')
            ->paginate(20, ['*'], 'enginesPage');

        return view('livewire.admin.vehicle-management', compact('makes', 'models', 'engines'))
            ->layout('layouts.app');
    }
}
