<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $idInstance;
    protected $apiToken;
    protected $baseUrl;

    public function __construct()
    {
        $this->idInstance = env('GREEN_API_ID_INSTANCE');
        $this->apiToken = env('GREEN_API_TOKEN_INSTANCE');

        // Extract prefix from ID (first 4 digits) or use default
        $prefix = substr($this->idInstance, 0, 4);
        $host = $prefix ? "{$prefix}.api.greenapi.com" : "api.greenapi.com";
        $this->baseUrl = "https://{$host}/waInstance{$this->idInstance}";
    }

    /**
     * Check current instance status.
     * 
     * @return string 'online'|'offline'|'error'
     */
    public function getStatus()
    {
        if (empty($this->idInstance) || empty($this->apiToken)) {
            return 'error';
        }

        return cache()->remember('zettabot_status', 30, function () {
            try {
                $url = "{$this->baseUrl}/getStateInstance/{$this->apiToken}";
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

    /**
     * Send a standard text message.
     */
    public function sendMessage($chatId, $message)
    {
        // Clean number: remove non-digits
        $chatId = preg_replace('/[^0-9]/', '', $chatId);

        // Simple chat validation
        if (!str_contains($chatId, '@')) {
            $chatId .= '@c.us';
        }

        $url = "{$this->baseUrl}/sendMessage/{$this->apiToken}";
        Log::info("Enviando a Green API: {$url} | Chat: {$chatId}");

        try {
            $response = Http::post($url, [
                'chatId' => $chatId,
                'message' => $message,
                'linkPreview' => false,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Green API Error: Status {$response->status()} | " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsApp Service Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send message with buttons (Green API supported).
     */
    public function sendButtons($chatId, $message, $buttons, $footer = '')
    {
        $chatId = preg_replace('/[^0-9]/', '', $chatId);
        if (!str_contains($chatId, '@'))
            $chatId .= '@c.us';

        // Green API format for buttons
        $formattedButtons = [];
        foreach ($buttons as $index => $btn) {
            $formattedButtons[] = [
                'buttonId' => $btn['id'] ?? 'btn_' . $index,
                'buttonText' => [
                    'displayText' => $btn['text']
                ],
                'type' => 1
            ];
        }

        try {
            Log::info("Enviando sendButtons a Green API: {$chatId}");
            $response = Http::post("{$this->baseUrl}/sendButtons/{$this->apiToken}", [
                'chatId' => $chatId,
                'message' => $message,
                'footer' => $footer,
                'buttons' => $formattedButtons
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Green API sendButtons Error: Status {$response->status()} | " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("WhatsApp sendButtons Error: " . $e->getMessage());
            return false;
        }
    }

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
            $code = $item['product']['supplier_code'] ?? 'N/A';
            $qty = $item['qty'] ?? 1;
            $oversize = $item['product']['oversize'] ?? null;
            
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
        $orderStr = $orderNum ? " · #{$orderNum}" : "";
        $msg = "🚫 *Búsqueda Cancelada{$orderStr}*\nEl cliente ha cancelado la búsqueda de este pedido. Ya no es necesario que verifiques el stock. ¡Gracias!";
        return $this->sendMessage($chatId, $msg);
    }
}

