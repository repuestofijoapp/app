<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'vendor.pagination.custom-repuestofijo';
    }

    public $name;
    public $email;
    public $password;
    public $role = 'mechanic';
    public $ruc_dni;
    public $business_name;
    public $showModal = false;
    public $editingUser = null;

    public $search = '';
    public $perPage = 25;

    public $showAddressModal = false;
    public $selectedUserAddresses = [];
    public $selectedUserName = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->editingUser ? $this->editingUser->id : 'NULL'),
            'password' => $this->editingUser ? 'nullable|min:8' : 'required|min:8',
            'role' => 'required',
            'ruc_dni' => 'nullable|string|max:11',
            'business_name' => 'nullable|string|max:255',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->reset(['name', 'email', 'password', 'role', 'ruc_dni', 'business_name', 'editingUser', 'showModal']);
        $this->role = auth()->user()->isAdmin() ? 'manager' : 'mechanic';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function editUser($id)
    {
        $user = User::find($id);
        $currentUser = auth()->user();

        // Security check: Managers can only edit Gmail users (non-admin, non-manager)
        $canEdit = $currentUser->isAdmin() ? (!$user->isAdmin()) : (!$user->isAdmin() && !$user->isManager());

        if ($user && $canEdit) {
            $this->editingUser = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role->value;
            $this->ruc_dni = $user->ruc_dni;
            $this->business_name = $user->business_name;
            $this->password = '';
            $this->showModal = true;
        } else {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No tienes permiso para editar este usuario.']);
        }
    }

    public function saveUser()
    {
        $this->validate();

        // Security check for role assignment: Managers can't assign Admin or Manager roles
        if (!auth()->user()->isAdmin() && in_array($this->role, ['admin', 'manager'])) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No tienes permiso para asignar este rol.']);
            return;
        }

        if ($this->editingUser) {
            $this->editingUser->name = $this->name;
            $this->editingUser->email = $this->email;
            $this->editingUser->role = $this->role;
            $this->editingUser->ruc_dni = $this->ruc_dni;
            $this->editingUser->business_name = $this->business_name;
            if (!empty($this->password)) {
                $this->editingUser->password = Hash::make($this->password);
            }
            $this->editingUser->save();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Usuario actualizado correctamente.']);
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'ruc_dni' => $this->ruc_dni,
                'business_name' => $this->business_name,
                'is_active' => true,
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Usuario creado correctamente.']);
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $user = User::find($id);
        if (!$user)
            return;

        $currentUser = auth()->user();
        // Managers can only delete Gmail users
        $canDelete = $currentUser->isAdmin() ? (!$user->isAdmin()) : (!$user->isAdmin() && !$user->isManager());

        if (!$canDelete) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No tienes permiso para eliminar a este usuario.']);
            return;
        }

        $this->dispatch('swal:confirm-delete', [
            'id' => $id,
            'title' => "¿Eliminar a {$user->name}?",
            'text' => 'El usuario será desactivado del sistema (Soft Delete).',
        ]);
    }

    #[On('delete-confirmed')]
    public function deleteUser($id)
    {
        $user = User::find($id);
        $currentUser = auth()->user();
        $canDelete = $currentUser->isAdmin() ? (!$user->isAdmin()) : (!$user->isAdmin() && !$user->isManager());

        if ($user && $canDelete) {
            $user->delete(); // Soft delete
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Usuario eliminado correctamente.']);
        }
    }

    public function confirmBlock($id)
    {
        $user = User::find($id);
        if (!$user)
            return;

        $currentUser = auth()->user();
        $canBlock = $currentUser->isAdmin() ? (!$user->isAdmin()) : (!$user->isAdmin() && !$user->isManager());

        if (!$canBlock) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No tienes permiso para bloquear a este usuario.']);
            return;
        }

        $action = $user->is_active ? 'Bloquear' : 'Desbloquear';
        $text = $user->is_active ? "El usuario {$user->name} no podrá acceder al sistema." : "El usuario {$user->name} recuperará el acceso al sistema.";

        $this->dispatch('swal:confirm-block', [
            'id' => $id,
            'title' => "¿{$action} a {$user->name}?",
            'text' => $text,
        ]);
    }

    #[On('block-confirmed')]
    public function toggleBlock($id)
    {
        $user = User::find($id);
        $currentUser = auth()->user();
        $canBlock = $currentUser->isAdmin() ? (!$user->isAdmin()) : (!$user->isAdmin() && !$user->isManager());

        if ($user && $canBlock) {
            $user->is_active = !$user->is_active;

            if (!$user->is_active) {
                $user->blocked_at = now();
                $user->blocked_by = $currentUser->id;
            } else {
                $user->blocked_at = null;
                $user->blocked_by = null;
            }

            $user->save();
            $status = $user->is_active ? 'desbloqueado' : 'bloqueado';
            $this->dispatch('notify', ['type' => 'success', 'message' => "Usuario {$status} correctamente."]);
        }
    }

    public function openAddressModal($userId)
    {
        $user = User::findOrFail($userId);
        $this->selectedUserAddresses = $user->getSavedAddresses();
        $this->selectedUserName = $user->name;
        $this->showAddressModal = true;
    }

    public function closeAddressModal()
    {
        $this->showAddressModal = false;
        $this->selectedUserAddresses = [];
        $this->selectedUserName = '';
    }

    public function render()
    {
        // Mostrar todos los usuarios salvo el propio admin logueado
        $query = User::with(['blockedBy']);
        $currentUser = auth()->user();

        // Si es manager, solo puede ver mechanics y transportes (no admins/managers)
        if ($currentUser->role === UserRole::Manager) {
            $query->whereNotIn('role', [UserRole::Admin->value, UserRole::Manager->value]);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.admin.user-management', [
            'users' => $users
        ]);
    }
}