<?php

namespace App\Livewire\Admin;

use App\Models\Provider;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProviderManagement extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.pagination.custom-repuestofijo';
    }

    // Search and filters
    public $search = '';
    public $perPage = 25;

    // Modal state
    public $showModal = false;
    public $editingProvider = null;

    // Form fields
    public $business_name;
    public $ruc;
    public $specialty;
    public $whatsapp_number;
    public $phone;
    public $contact_email;
    public $address;
    public $city;
    public $country = 'Perú';
    public $bank_account_number;
    public $new_portal_password = '';

    // Autocomplete for cities
    public $cityResults = [];
    public $peruCities = [
        'Amazonas',
        'Áncash',
        'Apurímac',
        'Arequipa',
        'Ayacucho',
        'Cajamarca',
        'Callao',
        'Cusco',
        'Huancavelica',
        'Huánuco',
        'Ica',
        'Junín',
        'La Libertad',
        'Lambayeque',
        'Lima',
        'Loreto',
        'Madre de Dios',
        'Moquegua',
        'Pasco',
        'Piura',
        'Puno',
        'San Martín',
        'Tacna',
        'Tumbes',
        'Ucayali',
        'Abancay',
        'Andahuaylas',
        'Barranca',
        'Cajamarca',
        'Cañete',
        'Chachapoyas',
        'Chiclayo',
        'Chimbote',
        'Chincha Alta',
        'Cusco',
        'Huacho',
        'Huancayo',
        'Huánuco',
        'Huaraz',
        'Ica',
        'Iquitos',
        'Juliaca',
        'Moquegua',
        'Moyobamba',
        'Pisco',
        'Piura',
        'Pucallpa',
        'Puerto Maldonado',
        'Puno',
        'Sullana',
        'Tacna',
        'Talara',
        'Tarapoto',
        'Tarma',
        'Trujillo',
        'Tumbes'
    ];

    public function updatedCity()
    {
        if (strlen($this->city) >= 2) {
            $this->cityResults = array_filter($this->peruCities, function ($c) {
                return str_contains(strtolower($c), strtolower($this->city));
            });
            // Sort by relevance (starts with first)
            usort($this->cityResults, function ($a, $b) {
                $startsA = str_starts_with(strtolower($a), strtolower($this->city));
                $startsB = str_starts_with(strtolower($b), strtolower($this->city));
                if ($startsA && !$startsB)
                    return -1;
                if (!$startsA && $startsB)
                    return 1;
                return strcmp($a, $b);
            });
            $this->cityResults = array_slice($this->cityResults, 0, 8); // Limit to 8
        } else {
            $this->cityResults = [];
        }
    }

    public function selectCity($cityName)
    {
        $this->city = $cityName;
        $this->cityResults = [];
    }

    public function updatingSearch()
    {
        $this->resetPage();
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
        $this->editingProvider = null;
        $this->business_name = '';
        $this->ruc = '';
        $this->specialty = '';
        $this->whatsapp_number = '';
        $this->phone = '';
        $this->contact_email = '';
        $this->address = '';
        $this->city = '';
        $this->country = 'Perú';
        $this->bank_account_number = '';
        $this->new_portal_password = '';
        $this->cityResults = [];
    }

    public function editProvider($id)
    {
        $this->resetValidation();
        $this->editingProvider = Provider::findOrFail($id);

        $this->business_name = $this->editingProvider->business_name;
        $this->ruc = $this->editingProvider->ruc;
        $this->specialty = $this->editingProvider->specialty;
        $this->whatsapp_number = $this->editingProvider->whatsapp_number;
        $this->phone = $this->editingProvider->phone;
        $this->contact_email = $this->editingProvider->contact_email;
        $this->address = $this->editingProvider->address;
        $this->city = $this->editingProvider->city;
        $this->country = $this->editingProvider->country;
        $this->bank_account_number = $this->editingProvider->bank_account_number;
        $this->new_portal_password = '';

        $this->showModal = true;
    }

    public function saveProvider()
    {
        \Illuminate\Support\Facades\Log::info('saveProvider hit', $this->all());
        $rules = [
            'business_name' => 'required|string|max:255',
            'ruc' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'new_portal_password' => 'nullable|string|min:6',
        ];

        $this->validate($rules);

        try {
            if ($this->editingProvider) {
                // Update Provider
                $updateData = [
                    'business_name' => $this->business_name,
                    'ruc' => $this->ruc,
                    'specialty' => $this->specialty,
                    'whatsapp_number' => $this->whatsapp_number,
                    'phone' => $this->phone,
                    'contact_email' => $this->contact_email,
                    'address' => $this->address,
                    'city' => $this->city,
                    'country' => $this->country,
                    'bank_account_number' => $this->bank_account_number,
                ];

                // Only update password if a new one was provided
                if (!empty($this->new_portal_password)) {
                    $updateData['portal_password'] = Hash::make($this->new_portal_password);
                }

                $this->editingProvider->update($updateData);

                $message = 'Proveedor actualizado correctamente.';
                if (!empty($this->new_portal_password)) {
                    $message .= ' Contraseña del portal B2B actualizada.';
                }

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => $message
                ]);
            } else {
                // Create Provider
                Provider::create([
                    'business_name' => $this->business_name,
                    'ruc' => $this->ruc,
                    'specialty' => $this->specialty,
                    'whatsapp_number' => $this->whatsapp_number,
                    'phone' => $this->phone,
                    'contact_email' => $this->contact_email,
                    'address' => $this->address,
                    'city' => $this->city,
                    'country' => $this->country,
                    'bank_account_number' => $this->bank_account_number,
                    'is_active' => true,
                ]);

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Proveedor creado correctamente.'
                ]);
            }

            $this->closeModal();
            $this->resetPage();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al guardar el proveedor: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($id)
    {
        $provider = Provider::findOrFail($id);
        $provider->is_active = !$provider->is_active;
        $provider->save();

        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'Estado del proveedor actualizado.'
        ]);
    }

    public function confirmDelete($id)
    {
        $provider = Provider::findOrFail($id);

        $this->dispatch('swal:confirm-delete-provider', [
            'id' => $id,
            'title' => '¿Eliminar proveedor?',
            'text' => "Se eliminará a '{$provider->business_name}'. Esta acción no se puede deshacer."
        ]);
    }

    #[On('delete-provider-confirmed')]
    public function deleteProvider($id)
    {
        $id = is_array($id) ? ($id['id'] ?? null) : $id;
        if (!$id)
            return;

        try {
            $provider = Provider::findOrFail($id);

            $provider->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Proveedor eliminado correctamente.'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al eliminar el proveedor.'
            ]);
        }
    }

    public function render()
    {
        $providers = Provider::where(function ($query) {
            $query->where('business_name', 'like', '%' . $this->search . '%')
                ->orWhere('ruc', 'like', '%' . $this->search . '%')
                ->orWhere('city', 'like', '%' . $this->search . '%')
                ->orWhere('contact_email', 'like', '%' . $this->search . '%');
        })
            ->orderBy('business_name')
            ->paginate($this->perPage);

        return view('livewire.admin.provider-management', [
            'providers' => $providers
        ])->layout('layouts.app');
    }
}
