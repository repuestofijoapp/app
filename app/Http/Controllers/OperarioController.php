<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Pedido;
use App\Models\PedidoHito;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OperarioController extends Controller
{
    /**
     * Vista móvil del operario interno: verificación de ítems + 3 pasos de evidencia.
     * Acceso por token único enviado por WhatsApp.
     */
    public function show(string $token)
    {
        $pedido = Pedido::with(['customer', 'items.product', 'hitos'])
            ->where('delivery_token', $token)
            ->firstOrFail();

        $hitosRegistrados = $pedido->hitos->keyBy('tipo_hito');

        // El operario gestiona sólo los 3 primeros hitos (proveedor → hub → motorizado)
        $siguienteHito = 'recojo_proveedor';
        if ($hitosRegistrados->has('recojo_proveedor')) {
            $siguienteHito = 'embalaje_hub';
        }
        if ($hitosRegistrados->has('embalaje_hub')) {
            $siguienteHito = 'entrega_motorizado';
        }
        if ($hitosRegistrados->has('entrega_motorizado')) {
            $siguienteHito = 'completado_operario'; // Operario terminó; motorizado toma el relevo
        }

        $motorizado = User::where('role', UserRole::Transporte)
            ->where('is_active', true)
            ->first();

        return view('operario.show', [
            'pedido'         => $pedido,
            'hitos'          => $hitosRegistrados,
            'siguienteHito'  => $siguienteHito,
            'token'          => $token,
            'motorizado'     => $motorizado,
        ]);
    }

    /**
     * Registrar uno de los 3 hitos del operario con foto + GPS.
     * Al completar el paso 3 (entrega_motorizado), notifica automáticamente al motorizado.
     */
    public function storeHito(Request $request, string $token)
    {
        $pedido = Pedido::with(['items.product'])
            ->where('delivery_token', $token)
            ->firstOrFail();

        if (!$request->filled('operador_nombre')) {
            $request->merge([
                'operador_nombre' => auth()->user()?->name ?? 'Operario Repuesto Fijo',
            ]);
        }

        $request->validate([
            'tipo_hito'       => 'required|in:recojo_proveedor,embalaje_hub,entrega_motorizado',
            'foto'            => 'required|image|max:15360',
            'operador_nombre' => 'nullable|string|max:100',
            'lat'             => 'nullable|numeric',
            'lng'             => 'nullable|numeric',
            'observaciones'   => 'nullable|string|max:500',
        ]);

        // Guardar foto en disco público
        $file     = $request->file('foto');
        $filename = 'operario_' . $pedido->id . '_' . $request->tipo_hito . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('pod_evidencias', $filename, 'public');
        $fotoUrl  = Storage::url($path);

        $lat = $request->filled('lat') ? floatval($request->lat) : null;
        $lng = $request->filled('lng') ? floatval($request->lng) : null;

        // Asignar 'Operario' como responsable (o nombre real solo si tiene rol operario)
        $operadorNombre = (auth()->check() && auth()->user()->role === \App\Enums\UserRole::Operario)
            ? auth()->user()->name
            : 'Operario';

        PedidoHito::updateOrCreate(
            ['pedido_id' => $pedido->id, 'tipo_hito' => $request->tipo_hito],
            [
                'foto_url'         => $fotoUrl,
                'lat'              => $lat,
                'lng'              => $lng,
                'operador_nombre'  => $operadorNombre,
                'operador_user_id' => auth()->id(),
                'observaciones'    => $request->observaciones,
            ]
        );

        // Actualizar estado del pedido
        $nuevoEstado = match ($request->tipo_hito) {
            'recojo_proveedor'   => 'en_preparacion',
            'embalaje_hub'       => 'en_preparacion',
            'entrega_motorizado' => 'en_camino',
            default              => $pedido->status,
        };

        if ($nuevoEstado !== $pedido->status) {
            $pedido->update(['status' => $nuevoEstado]);
        }

        // ── Paso 2 completado: Caja embalada lista en Hub ───────────
        // Notificar inmediatamente al motorizado para que se acerque al Hub a recoger
        $waSent = null;
        if ($request->tipo_hito === 'embalaje_hub') {
            $waSent = $this->notifyMotorizado($pedido);
        }

        return redirect()
            ->route('operario.show', $token)
            ->with('just_packed', $request->tipo_hito === 'embalaje_hub')
            ->with('wa_sent', $waSent);
    }

    /**
     * Vista de impresión de etiqueta con QR accesible para el operario mediante su token.
     */
    public function printLabel(string $token)
    {
        $pedido = Pedido::with(['customer', 'items.product'])
            ->where('delivery_token', $token)
            ->firstOrFail();

        if (empty($pedido->delivery_token)) {
            $pedido->update(['delivery_token' => Str::random(40)]);
        }

        $podUrl = route('pod.show', $pedido->delivery_token);
        $qrUrl  = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=" . urlencode($podUrl);

        return view('pod.label', [
            'pedido' => $pedido,
            'podUrl' => $podUrl,
            'qrUrl'  => $qrUrl,
        ]);
    }

    /**
     * Enviar WhatsApp al motorizado de confianza registrado.
     */
    private function notifyMotorizado(Pedido $pedido): bool
    {
        // Buscar motorizado asignado específicamente al pedido o el motorizado activo por defecto
        $motorizado = ($pedido->transporte_id ? User::find($pedido->transporte_id) : null)
            ?: User::where('role', UserRole::Transporte)
                ->where('is_active', true)
                ->first();

        if (!$motorizado || empty($motorizado->phone)) {
            Log::warning("OperarioController: No se encontró motorizado activo con teléfono registrado.");
            return false;
        }

        // Asegurarse de que el pedido tenga delivery_token
        if (empty($pedido->delivery_token)) {
            $pedido->update(['delivery_token' => Str::random(40)]);
        }

        try {
            $ws = new WhatsAppService();
            $res = $ws->sendMotorizadoReadyNotification(
                $motorizado->phone,
                $pedido,
                $pedido->delivery_token
            );
            if ($res) {
                Log::info("Motorizado notificado para pedido #{$pedido->id} → {$motorizado->phone}");
                return true;
            }
            Log::warning("OperarioController: Green API rechazó el envío a {$motorizado->phone}");
            return false;
        } catch (\Throwable $e) {
            Log::error("Error notificando motorizado pedido #{$pedido->id}: " . $e->getMessage());
            return false;
        }
    }
}
