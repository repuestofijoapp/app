<?php

namespace App\Http\Controllers\Api\WhatsApp;

use App\Http\Controllers\Controller;
use App\Models\ZbotQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GreenApiWebhookController extends Controller
{
    /**
     * Handle the Green API incoming webhook.
     */
    public function handle(Request $request)
    {
        $data = $request->all();
        $type = $data['typeWebhook'] ?? '';

        $totalQueries = ZbotQuery::count();
        Log::info("Green API Webhook [{$type}] - Total Queries: {$totalQueries}");

        if ($type === 'incomingMessageReceived') {
            $this->processMessage($data);
        } elseif ($type === 'quotaExceeded') {
            // Green API quota exceeded — outgoing/incoming messages may be blocked
            // Providers' responses will NOT arrive as webhooks while quota is exceeded
            Log::warning('⚠️  GREEN API QUOTA EXCEEDED: La instancia ha superado el límite de mensajes del plan. Las respuestas de los proveedores NO podrán procesarse hasta que se restablezca la cuota. Verifica el panel de Green API.', [
                'instance' => $data['instanceData'] ?? [],
                'timestamp' => now()->toDateTimeString(),
            ]);
        } elseif ($type === 'statusInstanceChanged') {
            $state = $data['stateInstance'] ?? 'unknown';
            Log::info("Green API estado de instancia cambiado: {$state}");
        } else {
            Log::info("Green API Webhook tipo no manejado: [{$type}]");
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Logic for conversation state machine.
     */
    private function processMessage($data)
    {
        $sender = $data['senderData']['chatId'] ?? '';
        $text = $data['messageData']['textMessageData']['textMessage'] ?? '';

        Log::info("Webhook Message - Raw Sender: {$sender} | Raw Text: '{$text}'");

        $text = strtoupper(trim($text));
        $ws = new \App\Services\WhatsAppService();

        // Check if it's a button click response
        if (isset($data['messageData']['buttonsResponseMessageData'])) {
            $text = strtoupper($data['messageData']['buttonsResponseMessageData']['buttonId'] ?? $text);
        }

        if (empty($sender) || empty($text))
            return;

        $cleanSender = preg_replace('/[^0-9]/', '', $sender) . '@c.us';

        // Find latest query that isn't completed or expired
        $query = ZbotQuery::where('chat_id', $cleanSender)
            ->where('current_step', '!=', 'completed')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$query) {
            Log::warning("No active zbot_query found for: {$cleanSender}");
            return;
        }

        if ($query->current_step === 'initial') {
            // Check for positive response
            // Check for positive response (Text, Button ID or Number 1)
            if (str_contains($text, 'SI') || str_contains($text, 'BTN_YES') || str_contains($text, 'TENGO') || $text === '1' || str_contains($text, '1️⃣') || preg_match('/^1\s+\d+/', $text)) {

                // Check if they already sent the price (e.g., "1 150")
                $parts = preg_split('/\s+/', $text);
                $potentialPrice = null;
                foreach ($parts as $p) {
                    $cleanP = preg_replace('/[^0-9.]/', '', $p);
                    if (is_numeric($cleanP) && $cleanP != '1') {
                        $potentialPrice = $cleanP;
                        break;
                    }
                }

                if ($potentialPrice) {
                    $query->update([
                        'status' => 'confirmed',
                        'current_step' => 'completed',
                        'price' => $potentialPrice,
                        'response_text' => "Confirmado con precio: " . $potentialPrice
                    ]);
                    $ws->sendFinalConfirmation($sender);
                } else {
                    $query->update(['current_step' => 'asking_price']);
                    $itemCount = count($query->items_json);
                    if ($itemCount === 1) {
                        $ws->sendMessage($sender, "¡Excelente! Confirma el PRECIO de este producto por favor. Solo envía el número (Ej: 150).");
                    } else {
                        $ws->sendMessage($sender, "¡Excelente! Confirma los PRECIOS de estos *{$itemCount}* productos en orden por favor.");
                    }
                }
            }
            // Check for negative response (Text, Button ID or Number 2)
            elseif (str_contains($text, 'NO') || str_contains($text, 'BTN_NO') || str_contains($text, 'STOCK') || $text === '2' || str_contains($text, '2️⃣')) {
                $query->update([
                    'status' => 'denied',
                    'current_step' => 'completed',
                    'response_text' => $text
                ]);
                $ws->sendDenialThanks($sender);
            }
        } elseif ($query->current_step === 'asking_price') {
            // Anything sent now is considered the price
            $query->update([
                'status' => 'confirmed',
                'current_step' => 'completed',
                'price' => $text,
                'response_text' => "Precio: " . $text
            ]);
            $ws->sendFinalConfirmation($sender);
        }
    }
}
