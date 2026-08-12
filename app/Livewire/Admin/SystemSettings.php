<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SystemSetting;

class SystemSettings extends Component
{
    public $enable_plate_search = true;

    public function mount()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Acceso denegado.');
        }

        $this->enable_plate_search = SystemSetting::getBool('enable_plate_search', true);
    }

    public function togglePlateSearch()
    {
        $this->enable_plate_search = !$this->enable_plate_search;
        SystemSetting::setBool('enable_plate_search', $this->enable_plate_search);

        // Flash message or dispatch event
        session()->flash('success', 'Configuración de búsqueda por placa actualizada.');
    }

    public function render()
    {
        return view('livewire.admin.system-settings')->layout('layouts.app');
    }
}
