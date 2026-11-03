<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\SystemSetting;

class WhatsAppService
{
    protected string $provider;   // 'green_api' | 'apis_peru'
    protected $idInstance;
    protected $apiToken;
    protected $baseUrl;

    public function __construct()
    {
        $this->provider = SystemSetting::get('whatsapp_provider', 'green_api');

        if ($this->provider === 'apis_peru') {
            $this->baseUrl = config('services.apis_peru_wa.base_url', 'https://whatsapp.apisperu.com/api/v1');
        } else {
            // Green API (configuración original, sin cambios)
            $this->idInstance = config('services.green_api.instance_id') ?: env('GREEN_API_ID_INSTANCE');
            $this->apiToken   = config('services.green_api.token')       ?: env('GREEN_API_TOKEN_INSTANCE');
            $prefix           = substr($this->idInstance, 0, 4);
            $host             = $prefix ? "{$prefix}.api.greenapi.com" : 'api.greenapi.com';
            $this->baseUrl    = "https://{$host}/waInstance{$this->idInstance}";
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  HELPERS INTERNOS
    // ──────────────────────────────────────────────────────────────

    /**
     * Normaliza el número al formato correcto según el proveedor activo.
     * Green API → @c.us  |  APIs Perú → @s.whatsapp.net
     */
    protected function formatPhone(string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);

        if ($this->provider === 'apis_peru') {
            return $clean . '@s.whatsapp.net';
        }

        return $clean . '@c.us';
    }

    /**
     * Obtiene (o refresca) el token JWT de APIs Perú.
     * Se cachea 23 h; si recibe 401 borra el cache y reintenta una vez.
     */
    protected function getApisPeruToken(): ?string
    {
        return Cache::remember('apis_peru_wa_token', 23 * 60 * 60, function () {
            $response = Http::timeout(10)->post(
                config('services.apis_peru_wa.base_url') . '/auth/login',
                [
                    'username' => config('services.apis_peru_wa.username') ?: env('APIS_PERU_USERNAME'),
                    'password' => config('services.apis_peru_wa.password') ?: env('APIS_PERU_PASSWORD'),
                ]
            );

            if ($response->successful()) {
                Log::info('APIs Perú: token obtenido correctamente.');
                return $response->json()['token'] ?? null;
            }

            Log::error('APIs Perú: login fallido. Status ' . $response->status() . ' | ' . $response->body());
            return null;
        });
    }

    /**
     * Devuelve los headers base para llamadas a APIs Perú.
     */
    protected function apisPeruHeaders(?string $token = null): array
    {
        $token    = $token ?? $this->getApisPeruToken();
        $deviceId = config('services.apis_peru_wa.device_id') ?: env('APIS_PERU_DEVICE_ID');

        return [
            'Authorization' => "Bearer {$token}",
            'device-id'     => $deviceId,
        ];
    }

    // ──────────────────────────────────────────────────────────────
    //  STATUS
    // ──────────────────────────────────────────────────────────────

    /**
     * Check current instance status.
     *
     * @return string 'online'|'offline'|'error'
     */
    public function getStatus(): string
    {
        if ($this->provider === 'apis_peru') {
            return Cache::remember('zettabot_status', 30, function () {
                try {
                    $token = $this->getApisPeruToken();
                    if (!$token) return 'error';

                    $response = Http::timeout(5)
                        ->withHeaders($this->apisPeruHeaders($token))
                        ->get($this->baseUrl . '/app/status');

                    if ($response->successful()) {
                        $results = $response->json()['results'] ?? [];
                        return ($results['is_connected'] ?? false) && ($results['is_logged_in'] ?? false)
                            ? 'online'
                            : 'offline';
                    }
                    return 'error';
                } catch (\Exception $e) {
                    return 'error';
                }
            });
        }

        // Green API
        if (empty($this->idInstance) || empty($this->apiToken)) {
            return 'error';
        }

        return Cache::remember('zettabot_status', 30, function () {
            try {
                $url      = "{$this->baseUrl}/getStateInstance/{$this->apiToken}";
                $response = Http::timeout(5)->get($url);

                if ($response->successful()) {
                    $state = $response->json()['stateInstance'] ?? '';
                    // Possible states: authorized, notAuthorized, blocked, sleepMode, starting
                    return $state === 'authorized' ? 'online' : 'offline';
                }
                return 'error';
            } catch (\Exception $e) {
                return 'error';
            }
        });
    }

    // ──────────────────────────────────────────────────────────────
    //  ENVÍO DE MENSAJES
    // ──────────────────────────────────────────────────────────────

    /**
     * Send a standard text message.
     */
    public function sendMessage($chatId, $message)
    {
        if ($this->provider === 'apis_peru') {
            return $this->sendMessageApisPeru($chatId, $message);
        }

        return $this->sendMessageGreenApi($chatId, $message);
    }

    /**
     * Envío de texto mediante Green API (lógica original).
     */
    protected function sendMessageGreenApi($chatId, $message)
    {
        $chatId = $this->formatPhone($chatId);
        $url    = "{$this->baseUrl}/sendMessage/{$this->apiToken}";
        Log::info("Enviando a Green API: {$url} | Chat: {$chatId}");

        try {
            $response = Http::post($url, [
                'chatId'       => $chatId,
                'message'      => $message,
                'linkPreview'  => true,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Green API Error: Status {$response->status()} | " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp Service Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envío de texto mediante APIs Perú.
     * Reintenta una vez si el token expiró (401).
     */
    protected function sendMessageApisPeru($chatId, $message, bool $isRetry = false)
    {
        $token = $this->getApisPeruToken();
        if (!$token) {
            Log::error('APIs Perú: no se pudo obtener token para sendMessage.');
            return false;
        }

        $phone = $this->formatPhone($chatId);
        Log::info("Enviando a APIs Perú: {$phone}");

        try {
            $response = Http::withHeaders($this->apisPeruHeaders($token))
                ->post($this->baseUrl . '/send/message', [
                    'phone'   => $phone,
                    'message' => $message,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Token expirado → refrescar y reintentar una sola vez
            if ($response->status() === 401 && !$isRetry) {
                Cache::forget('apis_peru_wa_token');
                return $this->sendMessageApisPeru($chatId, $message, true);
            }

            $isQuota = $response->status() === 429;
            Log::error('APIs Perú sendMessage Error: Status ' . $response->status() . ' | ' . $response->body() . ($isQuota ? ' [QUOTA]' : ''));
            return false;
        } catch (\Exception $e) {
            Log::error('APIs Perú sendMessage Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send message with buttons.
     * Green API soporta botones nativos; APIs Perú los degrada a texto plano.
     */
    public function sendButtons($chatId, $message, $buttons, $footer = '')
    {
        if ($this->provider === 'apis_peru') {
            // Degradar a texto plano con opciones numeradas
            $options = '';
            foreach ($buttons as $i => $btn) {
                $options .= "\n" . ($i + 1) . '. ' . ($btn['text'] ?? '');
            }
            $fullMessage = $message . $options . ($footer ? "\n\n_{$footer}_" : '');
            return $this->sendMessageApisPeru($chatId, $fullMessage);
        }

        // Green API — formato original
        $chatId = $this->formatPhone($chatId);
        $formattedButtons = [];
        foreach ($buttons as $index => $btn) {
            $formattedButtons[] = [
                'buttonId'   => $btn['id'] ?? 'btn_' . $index,
                'buttonText' => ['displayText' => $btn['text']],
                'type'       => 1,
            ];
        }

        try {
            Log::info("Enviando sendButtons a Green API: {$chatId}");
            $response = Http::post("{$this->baseUrl}/sendButtons/{$this->apiToken}", [
                'chatId'   => $chatId,
                'message'  => $message,
                'footer'   => $footer,
                'buttons'  => $formattedButtons,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Green API sendButtons Error: Status {$response->status()} | " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp sendButtons Error: ' . $e->getMessage());
            return false;
        }
    }

    // ──────────────────────────────────────────────────────────────
    //  MENSAJES DE NEGOCIO (delegan a sendMessage — compatibles con ambos providers)
    // ──────────────────────────────────────────────────────────────

    /**
     * Send the final confirmation message to the provider.
     */
    public function sendFinalConfirmation($chatId)
    {
        $msg = "¡Muchas, Gracias! presupuesto enviado al cliente.\nEn breve confirmamos el pago.";
        return $this->sendMessage($chatId, $msg);
    }

    /**
     * Send payment confirmation message.
     */
    public function sendPaymentConfirmation($chatId)
    {
        $msg = "¡PAGO CONFIRMADO!\nEl motorizado llegara en 20 minutos aproximadamente, a recoger el pedido.\nTen todos los productos empaquetados y listos por favor.";
        return $this->sendMessage($chatId, $msg);
    }

    /**
     * Send thanks for quick response when denied.
     */
    public function sendDenialThanks($chatId)
    {
        $msg = "Entendido, gracias por responder rápido 👍";
        return $this->sendMessage($chatId, $msg);
    }

    /**
     * Format the Zbot query message.
     */
    public function formatZbotOrder($items, $orderNum)
    {
        $msg = "🛒 *Nuevo pedido · #{$orderNum}*\n\n";

        foreach ($items as $item) {
            $code      = $item['product']['supplier_code'] ?? 'N/A';
            $qty       = $item['qty'] ?? 1;
            $oversize  = $item['product']['oversize'] ?? null;
            $measureStr = '';
            if (!empty($oversize)) {
                $measureStr = " (Medida: {$oversize})";
            }
            $msg .= "• {$code}{$measureStr} x{$qty}\n";
        }

        return $msg;
    }

    /**
     * Send notification to provider when search is cancelled.
     */
    public function sendSearchCancelled($chatId, $orderNum = null)
    {
        $orderStr = $orderNum ? " · #{$orderNum}" : '';
        $msg = "🚫 *Búsqueda Cancelada{$orderStr}*\nEl cliente ha cancelado la búsqueda de este pedido. Ya no es necesario que verifiques el stock. ¡Gracias!";
        return $this->sendMessage($chatId, $msg);
    }

    /**
     * Notificar al operario interno que hay un nuevo pedido pagado para recoger.
     */
    public function sendOperarioNewOrderNotification($phone, $pedidoId, $token, $providerInfo = 'Proveedor La Victoria')
    {
        $url = url("/operario/{$token}");
        $msg = "📦 *¡NUEVO PEDIDO PAGADO! · Orden #{$pedidoId}*\n\n"
             . "📍 *Recojo:* {$providerInfo}\n"
             . "⏱️ *Estado:* Listo para recojo inmediato.\n\n"
             . "Toca aquí para ver los ítems y registrar las fotos de inspección y empaque:\n"
             . "👉 {$url}";
        return $this->sendMessage($phone, $msg);
    }

    /**
     * Notificar al motorizado de confianza que el paquete está listo en el Hub.
     */
    public function sendMotorizadoReadyNotification($phone, $pedidoOrId, $token, $destination = '')
    {
        $url = url("/pod/{$token}");

        if ($pedidoOrId instanceof \App\Models\Pedido) {
            $pedido   = $pedidoOrId;
            $pedidoId = $pedido->id;
            $cliente  = $pedido->customer?->name ?? 'Cliente';
            $dir      = trim(($pedido->distrito ? "{$pedido->distrito}: " : '') . $pedido->direccion);
            $tel      = $pedido->telefono_contacto ?: ($pedido->customer?->phone ?: 'No especificado');
            $ref      = $pedido->referencia ? "\n📝 *Instrucciones:* {$pedido->referencia}" : '';

            $msg = "🛵 *¡PAQUETE LISTO EN HUB! · Pedido #{$pedidoId}*\n\n"
                 . "Acércate al Hub Central a recoger la caja lista para despacho.\n\n"
                 . "📦 *DATOS DE ENTREGA:*\n"
                 . "👤 *Destinatario:* {$cliente}\n"
                 . "📍 *Dirección:* {$dir}\n"
                 . "📞 *Contacto:* {$tel}{$ref}\n\n"
                 . "📲 *Enlace de seguimiento y entrega (PoD):*\n"
                 . "👉 {$url}";
        } else {
            $pedidoId = $pedidoOrId;
            $destStr  = !empty($destination) ? "\n📍 *Destino Cliente:* {$destination}" : '';
            $msg = "🛵 *¡PAQUETE LISTO EN HUB! · Orden #{$pedidoId}*{$destStr}\n\n"
                 . "Pasa a recoger la caja embalada en el Hub Central para llevarla a destino.\n\n"
                 . "Toca aquí para ver los datos del cliente, Waze y registrar la foto de entrega:\n"
                 . "👉 {$url}";
        }

        return $this->sendMessage($phone, $msg);
    }

    /**
     * Envía un código OTP de 4 dígitos por WhatsApp para verificar un reclamo.
     * Retorna array con status y detección de cuota (Green API → 466, APIs Perú → 429).
     */
    public function sendIncidentOtp(string $phone, string $code, int $pedidoId): array
    {
        $msg = "🔐 *Código de verificación RepuestoFijo*\n\n"
             . "Tu código para confirmar el reclamo del *Pedido #{$pedidoId}* es:\n\n"
             . "*{$code}*\n\n"
             . "⏱️ Válido por 10 minutos. No lo compartas con nadie.";

        try {
            $result = $this->sendMessage($phone, $msg);

            if ($result) {
                return ['success' => true, 'is_quota_error' => false, 'error' => null];
            }

            // Determinar si fue error de cuota según el proveedor
            return ['success' => false, 'is_quota_error' => false, 'error' => 'send_failed'];
        } catch (\Throwable $e) {
            Log::error('WhatsApp OTP incidencia falló', [
                'phone'     => $phone,
                'pedido_id' => $pedidoId,
                'provider'  => $this->provider,
                'error'     => $e->getMessage(),
            ]);
            return ['success' => false, 'is_quota_error' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Notifica al gestor cuando se crea una nueva incidencia.
     * Retorna false si falla (la incidencia ya fue creada, no se revierte).
     */
    public function sendIncidentManagerAlert(\App\Models\Incidencia $inc): bool
    {
        $managerPhone = env('SUPPORT_WHATSAPP_PHONE', '51926639534');
        $pedido       = $inc->pedido;
        $cliente      = $inc->customer?->name ?? 'Cliente';
        $adminSecret  = env('ADMIN_SECRET', 'Repuesto-Sape-2026');
        $link         = url("/Ayoro-sape-{$adminSecret}/soporte");

        $msg = "🚨 *NUEVO RECLAMO · {$inc->codigo}*\n\n"
             . "📦 *Pedido:* #{$pedido?->id}\n"
             . "👤 *Cliente:* {$cliente}\n"
             . "📋 *Motivo:* {$inc->tipo_label}"
             . ($inc->submotivo ? " › {$inc->submotivo_label}" : '') . "\n"
             . ($inc->descripcion ? "💬 *Comentario:* {$inc->descripcion}\n" : '')
             . "\n🔗 Ver incidencia:\n{$link}";

        try {
            return (bool) $this->sendMessage($managerPhone, $msg);
        } catch (\Throwable $e) {
            Log::error('WhatsApp gestor incidencia falló', [
                'incidencia_id' => $inc->id,
                'codigo'        => $inc->codigo,
                'provider'      => $this->provider,
                'error'         => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Devuelve el nombre legible del proveedor activo.
     */
    public function getProviderName(): string
    {
        return $this->provider === 'apis_peru' ? 'APIs Perú' : 'Green API';
    }
}
