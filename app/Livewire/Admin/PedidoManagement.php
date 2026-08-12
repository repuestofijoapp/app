<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;

class PedidoManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 25;
    public $selectedPedido = null;
    public $showDetailModal = false;

    protected $updatesQueryString = ['search', 'statusFilter'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openDetail($pedidoId)
    {
        $this->selectedPedido = Pedido::with(['customer', 'items.product', 'items.provider'])->find($pedidoId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPedido = null;
    }

    public function updateStatus($pedidoId, $newStatus)
    {
        $pedido = Pedido::find($pedidoId);
        if ($pedido) {
            $pedido->update(['status' => $newStatus]);
            $this->dispatch('notify', ['type' => 'success', 'message' => "Pedido #{$pedido->id} actualizado a {$newStatus}"]);
        }
    }

    public function sendInvoice($pedidoId)
    {
        // For now, just logic placeholder
        $pedido = Pedido::find($pedidoId);
        if ($pedido) {
            // Logic to send email would go here
            $this->dispatch('notify', ['type' => 'info', 'message' => "Factura del pedido #{$pedido->id} enviada al correo"]);
        }
    }

    public function render()
    {
        $query = Pedido::with(['customer'])
            ->when($this->search, function ($q) {
                $q->where('id', 'like', "%{$this->search}%")
                    ->orWhereHas('customer', function ($sq) {
                        $sq->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest();

        $stats = null;
        if (auth()->check() && auth()->user()->isAdmin()) {
            $paidPedidos = Pedido::with('items')->where('status', 'pagado')->get();
            $totalIngresos = 0;
            $totalEgresos = 0;
            foreach ($paidPedidos as $p) {
                $totalIngresos += $p->subtotal;
                foreach ($p->items as $item) {
                    $precioProveedor = round($item->precio_unitario / 1.10, 2);
                    $totalEgresos += ($precioProveedor * $item->cantidad);
                }
            }
            $stats = [
                'ingresos' => $totalIngresos,
                'egresos' => $totalEgresos,
                'ganancia' => $totalIngresos - $totalEgresos
            ];
        }

        return view('livewire.admin.pedido-management', [
            'pedidos' => $query->paginate($this->perPage),
            'stats' => $stats
        ]);
    }
}
