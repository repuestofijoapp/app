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
    public string $notaCliente = '';
    public string $newStatus = '';

    // Modal Auditoría PoD (Sección 9)
    public bool $showAuditModal = false;
    public ?Incidencia $auditIncidencia = null;
    public array $auditHitos = [];

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
        $this->totalResueltas = Incidencia::whereIn('status', ['resuelta', 'cerrada'])->count();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(int $id): void
    {
        $this->selected = Incidencia::with(['pedido.hitos', 'customer', 'atendidaPor'])->findOrFail($id);
        $this->resolucion = $this->selected->resolucion ?? '';
        $this->notaCliente = $this->selected->nota_resolucion_cliente ?? '';
        $this->newStatus = $this->selected->status;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selected = null;
        $this->resolucion = '';
        $this->notaCliente = '';
    }

    public function openAudit(int $id): void
    {
        $this->auditIncidencia = Incidencia::with(['pedido.hitos.operador', 'customer'])->findOrFail($id);

        $hitosMap = [];
        if ($this->auditIncidencia->pedido && $this->auditIncidencia->pedido->hitos) {
            foreach ($this->auditIncidencia->pedido->hitos as $hito) {
                $hitosMap[$hito->tipo_hito] = $hito;
            }
        }
        $this->auditHitos = $hitosMap;
        $this->showAuditModal = true;
    }

    public function closeAudit(): void
    {
        $this->showAuditModal = false;
        $this->auditIncidencia = null;
        $this->auditHitos = [];
    }

    public function saveGestion(): void
    {
        $this->validate([
            'newStatus'   => 'required|in:abierta,en_revision,reenvio_camino,reembolsado,resuelta,rechazada,cerrada',
            'resolucion'  => 'nullable|string|max:1000',
            'notaCliente' => 'nullable|string|max:500',
        ]);

        $historial = $this->selected->historial_estados ?? [];
        if (!is_array($historial)) {
            $historial = [];
        }

        // Si cambió de estado o se añadió nota, agregar al historial auditado
        $cambioEstado = ($this->selected->status !== $this->newStatus);
        if ($cambioEstado || !empty($this->resolucion)) {
            $historial[] = [
                'status'     => $this->newStatus,
                'timestamp'  => now()->toIso8601String(),
                'changed_by' => auth()->id(),
                'user_name'  => auth()->user()?->name ?? 'Admin',
                'nota'       => $this->resolucion ?: ($cambioEstado ? 'Cambio de estado administrativo' : 'Actualización de resolución'),
            ];
        }

        $data = [
            'status'                  => $this->newStatus,
            'resolucion'              => $this->resolucion ?: null,
            'nota_resolucion_cliente' => $this->notaCliente ?: null,
            'historial_estados'       => $historial,
            'atendida_por'            => auth()->id(),
        ];

        if (in_array($this->newStatus, ['resuelta', 'rechazada', 'cerrada']) && !$this->selected->resolved_at) {
            $data['resolved_at'] = now();
        } elseif ($cambioEstado && in_array($this->newStatus, ['abierta', 'en_revision'])) {
            $data['resolved_at'] = null; // Reabierta
        }

        $this->selected->update($data);

        // Notificar por correo al cliente si se resolvió, rechazó o actualizó con nota
        if (in_array($this->newStatus, ['resuelta', 'rechazada', 'en_proceso'])) {
            try {
                app(\App\Services\NotificationMailService::class)->sendIncidentResolved(
                    $this->selected,
                    $this->newStatus,
                    $this->notaCliente
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Error enviando email resolucion incidencia: " . $e->getMessage());
            }
        }
        $this->refreshStats();
        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Incidencia actualizada correctamente.']);
    }

    public function render()
    {
        $incidencias = Incidencia::with(['pedido.hitos', 'customer'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->tipoFilter, fn($q) => $q->where('tipo', $this->tipoFilter))
            ->when($this->search, function ($q) {
                $term = trim($this->search);
                $q->where(function ($sub) use ($term) {
                    $sub->where('codigo', 'like', "%{$term}%")
                        ->orWhere('descripcion', 'like', "%{$term}%")
                        ->orWhereHas('customer', fn($u) => $u->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"))
                        ->orWhereHas('pedido', fn($p) => $p->where('id', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.soporte-management', compact('incidencias'));
    }
}
