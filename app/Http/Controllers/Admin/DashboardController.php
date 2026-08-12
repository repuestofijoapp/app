<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pedido;
use App\Models\Vehicle;
use App\Models\SecurityLog;
use App\Models\Incidencia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $yesterday = today()->subDay();

        $pedidosHoy = Pedido::whereDate('created_at', $today)->count();
        $pedidosAyer = Pedido::whereDate('created_at', $yesterday)->count();

        // Calculate percentage change
        $pedidosGrowth = 0;
        if ($pedidosAyer > 0) {
            $pedidosGrowth = round((($pedidosHoy - $pedidosAyer) / $pedidosAyer) * 100);
        } else if ($pedidosHoy > 0) {
            $pedidosGrowth = 100;
        }

        // Truly active orders: things moving right now
        $enVivoAhora = Pedido::whereIn('status', ['por_confirmar', 'en_preparacion', 'en_camino'])
            ->where('updated_at', '>=', now()->subHours(2))
            ->count();
            
        $ticketsSoporte = Incidencia::whereIn('status', ['abierta', 'en_revision'])->count();
        $alertasSeguridad = SecurityLog::where('created_at', '>=', now()->subDay())->count();

        // Pedidos en vivo list - Only truly active ones for the display
        $pedidosEnVivoList = Pedido::with('customer')
            ->whereIn('status', ['por_confirmar', 'en_preparacion', 'en_camino', 'pagado'])
            ->where(function($q) {
                // Keep 'pagado' only if it's very recent, others by status
                $q->where('status', '!=', 'pagado')
                  ->orWhere('updated_at', '>=', now()->subHours(1));
            })
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Resumen del dia
        $resumenCompletados = Pedido::whereDate('created_at', $today)->where('status', 'entregado')->count();
        $resumenCancelados = Pedido::whereDate('created_at', $today)->where('status', 'cancelado')->count();
        $ticketPromedio = Pedido::whereDate('created_at', $today)->whereNotIn('status', ['cancelado', 'pendiente', 'por_confirmar'])->avg('total') ?? 0;

        // Tickets list
        $recentTickets = Incidencia::with('customer')
            ->whereIn('status', ['abierta', 'en_revision'])
            ->latest()
            ->take(3)
            ->get();

        $recent_logs = SecurityLog::latest()->take(5)->get();

        $stats = [
            'pedidos_hoy' => $pedidosHoy,
            'pedidos_growth' => $pedidosGrowth,
            'en_vivo_ahora' => $enVivoAhora,
            'tickets_soporte' => $ticketsSoporte,
            'alertas_seguridad' => $alertasSeguridad,
            'resumen_completados' => $resumenCompletados,
            'resumen_cancelados' => $resumenCancelados,
            'ticket_promedio' => $ticketPromedio,
            'proveedor_activo' => 'Proveedor A', // Mock per screenshot
            'saldo_culqi' => 958, // Mock
        ];

        return view('admin.dashboard', compact('stats', 'pedidosEnVivoList', 'recentTickets', 'recent_logs'));
    }
}
