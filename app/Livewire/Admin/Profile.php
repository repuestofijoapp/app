<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $photo;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $user->name = $this->name;

        if ($this->photo) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = $this->photo->store('profile-photos', 'public');
        }

        $user->save();

        $this->reset('photo');

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Perfil actualizado correctamente.'
        ]);
    }

    public function updatePassword()
    {
        $this->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($this->password);
        $user->save();

        $this->password = '';
        $this->password_confirmation = '';

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Contraseña actualizada correctamente.'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.profile');
    }
}
