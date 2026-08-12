<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Enums\UserRole;
use App\Services\RucService;
use Livewire\Component;

class Onboarding extends Component
{
    public $step = 1;
    public $role = '';
    public $receiptType = '';
    public $ruc = '';
    public $dni = '';
    public $businessName = '';
    public $fullName = '';
    public $isConsultingRuc = false;

    protected $rules = [
        'role' => 'required',
        'receiptType' => 'required_if:step,2',
        'ruc' => 'required_if:receiptType,factura|digits:11',
        'dni' => 'required_if:receiptType,boleta|digits:8',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->onboarding_completed_at || 
            auth()->user()->canAccessDashboard()) {
            return redirect()->route('home');
        }
    }

    public function setRole($role)
    {
        $this->role = $role;
        $this->step = 2;
    }

    public function setReceiptType($type)
    {
        $this->receiptType = $type;
        $this->ruc = '';
        $this->dni = '';
        $this->businessName = '';
        $this->fullName = '';
        $this->resetValidation();
    }

    public function updatedRuc($value)
    {
        if (strlen($value) === 11) {
            $this->consultRuc();
        } else {
            $this->businessName = '';
        }
    }

    public function updatedDni($value)
    {
        if (strlen($value) === 8) {
            $this->consultDni();
        } else {
            $this->fullName = '';
        }
    }

    public function consultRuc()
    {
        $this->validate(['ruc' => 'required|digits:11']);
        
        $this->isConsultingRuc = true;
        try {
            $service = new RucService();
            $data = $service->consultRuc($this->ruc);
            
            if ($data) {
                $this->businessName = $data['nombre_o_razon_social'] ?? $data['nombre'] ?? '';
            } else {
                $this->addError('ruc', 'No se encontró el RUC o hubo un error en la consulta.');
            }
        } catch (\Exception $e) {
            $this->addError('ruc', 'Error al consultar la API.');
        } finally {
            $this->isConsultingRuc = false;
        }
    }

    public function consultDni()
    {
        $this->validate(['dni' => 'required|digits:8']);
        
        $this->isConsultingRuc = true;
        try {
            $service = new RucService();
            $data = $service->consultDni($this->dni);
            
            if ($data) {
                $this->fullName = $data['nombre_completo'] ?? '';
            } else {
                $this->addError('dni', 'No se encontró el DNI o hubo un error en la consulta.');
            }
        } catch (\Exception $e) {
            $this->addError('dni', 'Error al consultar la API.');
        } finally {
            $this->isConsultingRuc = false;
        }
    }

    public function completeOnboarding()
    {
        if ($this->receiptType === 'factura') {
            $this->validate([
                'ruc' => 'required|digits:11',
                'businessName' => 'required'
            ]);
        } elseif ($this->receiptType === 'boleta') {
            $this->validate([
                'dni' => 'required|digits:8',
                'fullName' => 'required'
            ]);
        }

        $user = auth()->user();
        
        // Map roles
        $newRole = match($this->role) {
            'workshop' => UserRole::Workshop,
            'store' => UserRole::Store,
            default => UserRole::Mechanic,
        };

        $user->update([
            'role' => $newRole,
            'receipt_type' => $this->receiptType,
            'ruc_dni' => ($this->receiptType === 'factura') ? $this->ruc : (($this->receiptType === 'boleta') ? $this->dni : $user->ruc_dni),
            'business_name' => ($this->receiptType === 'factura') ? $this->businessName : (($this->receiptType === 'boleta') ? $this->fullName : $user->business_name),
            'onboarding_completed_at' => now(),
        ]);

        return redirect()->route('home')->with('notify', [
            'type' => 'success',
            'message' => 'Perfil completado correctamente. ¡Bienvenido!'
        ]);
    }

    public function render()
    {
        return view('livewire.auth.onboarding')
            ->layout('layouts.app');
    }
}
