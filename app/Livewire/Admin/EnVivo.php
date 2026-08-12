<?php

namespace App\Livewire\Admin;

use App\Models\ZbotQuery;
use App\Services\WhatsAppService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class EnVivo extends Component
{
    // Polling activo: refresca cada 8 segundos
    public $totalActivos = 0;
    public $totalExpirados = 0;
    public $totalHoy = 0;
    public $quotaWarning = false;

    // Para el modal de confirmación manual
    public $confirmingQueryId = null;
    public $manualPrice = '';
    public $manualError = '';

    public function openManualConfirm($queryId)
    {
        $this->confirmingQueryId = $queryId;
        $this->manualPrice = '';
        $this->manualError = '';
    }

    public function cancelManualConfirm()
    {
        $this->confirmingQueryId = null;
        $this->manualPrice = '';
        $this->manualError = '';
    }

    /**
     * Confirmación manual del precio cuando Green API tiene quota exceeded
     * y la respuesta del proveedor no llega por webhook.
     */
    public function manualConfirm()
    {
        $price = trim($this->manualPrice);

        if (!$this->confirmingQueryId || empty($price) || !is_numeric(str_replace(',', '.', $price))) {
            $this->manualError = 'Introduce un precio válido (solo números, ej: 150 o 75.50).';
            return;
        }

        $query = ZbotQuery::find($this->confirmingQueryId);
        if (!$query || $query->status !== 'waiting') {
            $this->manualError = 'Esta consulta ya no está activa.';
            return;
        }

        $query->update([
            'status'        => 'confirmed',
            'current_step'  => 'completed',
            'price'         => str_replace(',', '.', $price),
            'response_text' => 'Confirmado manualmente por admin. Precio: ' . $price,
        ]);

        // Intentar enviar confirmación al proveedor (si la cuota lo permite)
        try {
            $ws = new WhatsAppService();
            $ws->sendFinalConfirmation($query->chat_id);
        } catch (\Exception $e) {
            Log::warning('No se pudo enviar confirmación WS al proveedor (posible quota): ' . $e->getMessage());
        }

        Log::info("Admin confirmó manualmente ZbotQuery #{$query->id} con precio: {$price}");

        $this->confirmingQueryId = null;
        $this->manualPrice = '';
        $this->manualError = '';

        session()->flash('envivo_success', "✅ Consulta #{$query->id} confirmada con precio S/ {$price}");
    }

    public function render()
    {
        $now = now();

        // Detectar si hay advertencia de quota en logs recientes (últimos 30 min)
        $logFile = storage_path('logs/laravel.log');
        $this->quotaWarning = false;
        if (file_exists($logFile)) {
            $tail = '';
            $handle = fopen($logFile, 'r');
            if ($handle) {
                fseek($handle, -15000, SEEK_END);
                $tail = fread($handle, 15000);
                fclose($handle);
            }
            if (str_contains($tail, 'QUOTA EXCEEDED')) {
                // Check if it's within last 30 minutes
                preg_match_all('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*QUOTA EXCEEDED/m', $tail, $matches);
                foreach (($matches[1] ?? []) as $dateStr) {
                    try {
                        $logTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateStr);
                        if ($logTime->diffInMinutes(now()) <= 30) {
                            $this->quotaWarning = true;
                            break;
                        }
                    } catch (\Exception $e) {}
                }
            }
        }

        // Consultas activas: status=waiting y aún no expiradas
        $activas = ZbotQuery::with('provider')
            ->where('status', 'waiting')
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', $now);
            })
            ->latest()
            ->get();

        // Estadísticas rápidas
        $this->totalActivos = $activas->count();
        $this->totalExpirados = ZbotQuery::where('status', 'expired')
            ->whereDate('created_at', today())
            ->count();
        $this->totalHoy = ZbotQuery::whereDate('created_at', today())->count();

        return view('livewire.admin.en-vivo', [
            'activas' => $activas,
        ]);
    }
}
