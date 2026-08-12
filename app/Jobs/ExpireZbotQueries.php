<?php

namespace App\Jobs;

use App\Models\Pedido;
use App\Models\ZbotQuery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpireZbotQueries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = now();

        // 1. Buscar todas las ZbotQuery que expiraron y siguen en 'waiting'
        $expiredQueries = ZbotQuery::where('status', 'waiting')
            ->where('expires_at', '<', $now)
            ->get();

        if ($expiredQueries->isEmpty()) {
            return;
        }

        Log::info("[ExpireZbotQueries] Procesando {$expiredQueries->count()} consultas expiradas.");

        // Agrupar por pedido_id para evaluar qué pedidos cancelar
        $pedidoIds = $expiredQueries->pluck('pedido_id')->filter()->unique();

        // 2. Marcar las queries como 'expired'
        ZbotQuery::whereIn('id', $expiredQueries->pluck('id'))
            ->update([
                'status' => 'expired',
                'current_step' => 'completed',
            ]);

        Log::info("[ExpireZbotQueries] {$expiredQueries->count()} queries marcadas como expired.");

        // 3. Para cada pedido afectado, verificar si tiene alguna query 'confirmed'
        foreach ($pedidoIds as $pedidoId) {
            $pedido = Pedido::find($pedidoId);
            if (!$pedido) {
                continue;
            }

            // Si el pedido ya está en un estado final, no tocar
            if (in_array($pedido->status, ['pagado', 'en_preparacion', 'en_camino', 'entregado', 'cancelado'])) {
                continue;
            }

            // Verificar si al menos UNA query de este pedido fue confirmada
            $hasConfirmed = ZbotQuery::where('pedido_id', $pedidoId)
                ->where('status', 'confirmed')
                ->exists();

            if (!$hasConfirmed) {
                // Sin ningún proveedor con stock → cancelar pedido
                $pedido->update([
                    'status' => 'cancelado',
                    'cancellation_reason' => 'Sin proveedor disponible — timeout de ZettaBot.',
                ]);

                Log::info("[ExpireZbotQueries] Pedido #{$pedidoId} cancelado (sin stock en ningún proveedor).");
            } else {
                // Al menos un proveedor confirmó → mantener en 'por_confirmar'
                // El cliente aún puede completar el pago
                Log::info("[ExpireZbotQueries] Pedido #{$pedidoId} mantiene por_confirmar (hay proveedores con stock).");
            }
        }
    }
}
