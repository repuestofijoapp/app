<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Vehicle;

class CategoryGrid extends Component
{
    public $vehicleId;
    public $selectedCategory = null;
    public $searchTerm = '';

    protected $listeners = ['vehicleSelected' => 'setVehicle'];

    public function setVehicle($vehicleId)
    {
        $this->vehicleId = $vehicleId;
        $this->selectedCategory = null;
        $this->searchTerm = '';
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategory = Category::find($categoryId);

        // Emitir evento para que otros componentes sepan que se seleccionó una categoría
        $this->dispatch('categorySelected', $categoryId);
    }

    public function clearSelection()
    {
        $this->selectedCategory = null;
        $this->dispatch('categoryCleared');
    }

    public function getParentCategoriesProperty()
    {
        $query = Category::whereNull('parent_id')->orderBy('order');

        if (!empty($this->searchTerm)) {
            $query->where('name', 'LIKE', '%' . $this->searchTerm . '%');
        }

        return $query->get();
    }

    public function updatedSearchTerm()
    {
        // Reset selection when searching
        $this->selectedCategory = null;
    }

    public function render()
    {
        return view('livewire.category-grid');
    }
}