<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Provider;

class B2bLogin extends Component
{
    public $ruc;
    public $password;

    public function login()
    {
        $this->validate([
            'ruc' => 'required',
            'password' => 'required',
        ]);

        $provider = Provider::where('ruc', $this->ruc)->first();

        // Check if provider exists and password is correct
        if ($provider && Hash::check($this->password, $provider->portal_password)) {
            Auth::guard('provider')->login($provider);
            session()->regenerate();
            return redirect()->route('b2b.portal');
        }

        session()->flash('error', 'Credenciales incorrectas o acceso no autorizado.');
    }

    public function render()
    {
        return view('livewire.b2b-login')->layout('layouts.b2b');
    }
}
