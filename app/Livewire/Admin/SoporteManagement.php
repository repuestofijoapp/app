<?php

namespace App\Livewire\Admin;

use App\Models\Incidencia;
use Livewire\Component;
use Livewire\WithPagination;

class SoporteManagement extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public string $tipoFilter = '';
    public string $search = '';
    public int $perPage = 25;

    // Modal detalle/gestión
    public bool $showModal = false;
    public ?Incidencia $selected = null;
    public string $resolucion = '';
    public string $newStatus = '';

    // Stats
    public int $totalAbiertas = 0;
    public int $totalEnRevision = 0;
    public int $totalResueltas = 0;

    public function mount(): void
    {
        $this->refreshStats();
    }

    public function refreshStats(): void
    {
        $this->totalAbiertas = Incidencia::where('status', 'abierta')->count();
        $this->totalEnRevision = Incidencia::where('status', 'en_revision')->count();
        $this->totalResueltas = Incidencia::where('status', 'resuelta')->count();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(int $id): void
    {
        $this->selected = Incidencia::with(['pedido', 'customer', 'atendidaPor'])->findOrFail($id);
        $this->resolucion = $this->selected->resolucion ?? '';
        $this->newStatus = $this->selected->status;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selected = null;
        $this->resolucion = '';
    }

    public function saveGestion(): void
    {
        $this->validate([
            'newStatus' => 'required|in:abierta,en_revision,resuelta,cerrada',
            'resolucion' => 'nullable|string|max:1000',
        ]);

        $data = [
            'status' => $this->newStatus,
            'resolucion' => $this->resolucion,
            'atendida_por' => auth()->id(),
        ];

        if (in_array($this->newStatus, ['resuelta', 'cerrada']) && !$this->selected->resolved_at) {
            $data['resolved_at'] = now();
        }

        $this->selected->update($data);
        $this->refreshStats();
        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Incidencia actualizada.']);
    }

    public function render()
    {
        $incidencias = Incidencia::with(['pedido', 'customer'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->tipoFilter, fn($q) => $q->where('tipo', $this->tipoFilter))
            ->when($this->search, fn($q) => $q->whereHas(
                'customer',
                fn($u) =>
                $u->where('name', 'like', "%{$this->search}%")
            )->orWhereHas(
                    'pedido',
                    fn($p) =>
                    $p->where('id', 'like', "%{$this->search}%")
                ))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.soporte-management', compact('incidencias'));
    }
}
