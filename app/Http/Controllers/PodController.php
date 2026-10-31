<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoHito;
use App\Services\UbigeoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PodController extends Controller
{
    /**
     * Interfaz web móvil ligera para registrar hitos PoD (Cámara + GPS + QR)
     */
    public function show(string $token)
    {
        $pedido = Pedido::with(['customer', 'items.product', 'hitos'])
            ->where('delivery_token', $token)
            ->firstOrFail();

        $hitosRegistrados = $pedido->hitos->keyBy('tipo_hito');

        // Determinar el siguiente hito pendiente
        $siguienteHito = 'recojo_proveedor';
        if ($hitosRegistrados->has('recojo_proveedor')) {
            $siguienteHito = 'embalaje_hub';
        }
        if ($hitosRegistrados->has('embalaje_hub')) {
            $siguienteHito = 'entrega_motorizado';
        }
        if ($hitosRegistrados->has('entrega_motorizado')) {
            $siguienteHito = 'entrega_cliente';
        }
        if ($hitosRegistrados->has('entrega_cliente')) {
            $siguienteHito = 'completado';
        }

        return view('pod.show', [
            'pedido' => $pedido,
            'hitos' => $hitosRegistrados,
            'siguienteHito' => $siguienteHito,
        ]);
    }

    /**
     * Registrar evidencia de un hito logístico (foto + GPS + operador)
     */
    public function storeHito(Request $request, string $token)
    {
        $pedido = Pedido::where('delivery_token', $token)->firstOrFail();

        $request->validate([
            'tipo_hito' => 'required|in:recojo_proveedor,embalaje_hub,entrega_motorizado,entrega_cliente',
            'foto' => 'required|image|max:15360', // max 15MB
            'operador_nombre' => 'nullable|string|max:100',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Guardar foto en almacenamiento público
        $file = $request->file('foto');
        $filename = 'pod_' . $pedido->id . '_' . $request->tipo_hito . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('pod_evidencias', $filename, 'public');
        $fotoUrl = Storage::url($path);

        $lat = $request->filled('lat') ? floatval($request->lat) : null;
        $lng = $request->filled('lng') ? floatval($request->lng) : null;
        $distanciaMetros = null;
        $dentroDeRango = true;

        // Si es entrega final al cliente y el pedido tiene coordenadas pactadas, validar geocerca
        if ($request->tipo_hito === 'entrega_cliente' && $pedido->lat && $pedido->lng) {
            if (!$lat || !$lng) {
                return redirect()->back()
                    ->withInput()
                    ->with('error_geocerca', '⚠️ Para confirmar la entrega al cliente es obligatorio tener activada la ubicación GPS en tu dispositivo móvil.');
            }

            $geofence = UbigeoService::isWithinGeofence($pedido->lat, $pedido->lng, $lat, $lng, 200.0);
            $distanciaMetros = $geofence['distancia_metros'];
            $dentroDeRango = $geofence['dentro_de_rango'];

            // Bloqueo estricto si está fuera del radio de 200m
            if (!$dentroDeRango) {
                $isLocalBypass = app()->environment('local') && $request->boolean('bypass_test_geocerca');
                if (!$isLocalBypass) {
                    $distanciaTexto = $distanciaMetros >= 1000 
                        ? number_format($distanciaMetros / 1000, 1) . ' km' 
                        : round($distanciaMetros) . ' metros';

                    return redirect()->back()
                        ->withInput()
                        ->with('error_geocerca', "⚠️ Entrega bloqueada por ubicación: Te encuentras a {$distanciaTexto} del punto de entrega. El radio máximo permitido es de 200 metros. Por favor acércate a la dirección del destinatario para completar la entrega.")
                        ->with('distancia_actual', $distanciaMetros);
                }
            }
        }

        $operadorNombre = (auth()->check() && auth()->user()->role === \App\Enums\UserRole::Transporte)
            ? auth()->user()->name
            : 'Motorizado';

        PedidoHito::create([
            'pedido_id' => $pedido->id,
            'tipo_hito' => $request->tipo_hito,
            'foto_url' => $fotoUrl,
            'lat' => $lat,
            'lng' => $lng,
            'distancia_metros' => $distanciaMetros,
            'dentro_de_rango' => $dentroDeRango,
            'operador_nombre' => $operadorNombre,
            'operador_user_id' => auth()->id(),
            'observaciones' => $request->observaciones,
        ]);

        // Actualizar el estado del pedido automáticamente según el hito
        $nuevoEstado = match ($request->tipo_hito) {
            'recojo_proveedor' => ($pedido->status === 'pagado' ? 'en_preparacion' : $pedido->status),
            'embalaje_hub' => 'en_preparacion',
            'entrega_motorizado' => 'en_camino',
            'entrega_cliente' => 'entregado',
            default => $pedido->status,
        };

        if ($nuevoEstado !== $pedido->status) {
            $pedido->update(['status' => $nuevoEstado]);

            // Disparar correos según hito
            try {
                $mailService = app(\App\Services\NotificationMailService::class);
                if ($request->tipo_hito === 'entrega_motorizado') {
                    $mailService->sendOrderInRoute($pedido);
                } elseif ($request->tipo_hito === 'entrega_cliente') {
                    $mailService->sendOrderDelivered($pedido);
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error("Error email hito {$request->tipo_hito} pedido #{$pedido->id}: " . $e->getMessage());
            }
        }

        return redirect()->route('pod.show', $token)
            ->with('success', '¡Hito registrado correctamente con evidencia y GPS!');
    }

    /**
     * Vista de impresión de etiqueta con QR para el Hub Central
     */
    public function printLabel(int $id)
    {
        $pedido = Pedido::with(['customer', 'items.product'])->findOrFail($id);

        // Si no tiene delivery_token, generarlo
        if (empty($pedido->delivery_token)) {
            $pedido->update(['delivery_token' => Str::random(40)]);
        }

        $podUrl = route('pod.show', $pedido->delivery_token);
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=" . urlencode($podUrl);

        return view('pod.label', [
            'pedido' => $pedido,
            'podUrl' => $podUrl,
            'qrUrl' => $qrUrl,
        ]);
    }
}
