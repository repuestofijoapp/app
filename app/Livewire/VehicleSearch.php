<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use App\Services\VehicleDataService;

class VehicleSearch extends Component
{
    public $search = '';
    public $searchType = 'auto'; // 'auto', 'plate', 'oem'
    public $vehicle = null;
    public $isSearching = false;
    public $searchResults = [];

    protected $rules = [
        'search' => 'required|string|min:3|max:20',
    ];

    protected $vehicleDataService;

    public function __construct()
    {
        $this->vehicleDataService = app(VehicleDataService::class);
    }

    public function updatedSearch()
    {
        $this->validateOnly('search');
        $this->detectSearchType();
        $this->searchVehicle();
    }

    public function detectSearchType()
    {
        $search = trim(strtoupper($this->search));

        // Detect plate format (Peruvian plates: ABC-123 or ABC123)
        if (preg_match('/^[A-Z]{3}-?\d{3}$/', $search)) {
            $this->searchType = 'plate';
        }
        // Detect OEM code format (typically alphanumeric with dashes or slashes)
        elseif (preg_match('/^[A-Z0-9\-\/\.]+$/', $search) && strlen($search) >= 6) {
            $this->searchType = 'oem';
        }
        else {
            $this->searchType = 'auto';
        }
    }

    public function searchVehicle()
    {
        if (strlen($this->search) < 3) {
            $this->vehicle = null;
            $this->searchResults = [];
            return;
        }

        $this->isSearching = true;

        if ($this->searchType === 'plate' || $this->searchType === 'auto') {
            // Use VehicleDataService to search
            $vehicle = $this->vehicleDataService->searchVehicleByPlate($this->search);

            if ($vehicle) {
                $this->vehicle = $vehicle;
                $this->searchResults = [$vehicle];
            } else {
                // No results found
                $this->vehicle = null;
                $this->searchResults = [];
            }
        } elseif ($this->searchType === 'oem') {
            // Search OEM products
            $this->searchOemProducts();
        }

        $this->isSearching = false;
    }

    public function searchOemProducts()
    {
        // TODO: Implement OEM product search using OemProduct model
        // For now, search by OEM code in the database
        $this->searchResults = [];
    }

    public function selectVehicle($vehicleId)
    {
        $this->vehicle = Vehicle::find($vehicleId);
        $this->search = $this->vehicle->plate;

        // Emitir evento para actualizar otros componentes
        $this->dispatch('vehicleSelected', $vehicleId);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->vehicle = null;
        $this->searchResults = [];
        $this->searchType = 'auto';
    }

    public function render()
    {
        return view('livewire.vehicle-search');
    }
}