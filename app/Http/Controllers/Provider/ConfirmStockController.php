<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ZbotQuery;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConfirmStockController extends Controller
{
    /**
     * Show the confirmation form to the provider.
     */
    public function show($token)
    {
        $query = ZbotQuery::where('confirmation_token', $token)->first();

        if (!$query) {
            return view('provider.confirm-stock', [
                'error' => 'La solicitud de búsqueda no existe o el enlace es inválido.'
            ]);
        }

        // Check if query is already completed or expired
        if ($query->current_step === 'completed' || $query->status === 'expired' || ($query->expires_at && $query->expires_at->isPast())) {
            return view('provider.confirm-stock', [
                'error' => 'Esta solicitud de búsqueda ya ha expirado o fue respondida previamente.',
                'query' => $query
            ]);
        }

        $pedido = $query->pedido;
        $provider = $query->provider;

        return view('provider.confirm-stock', [
            'query' => $query,
            'pedido' => $pedido,
            'provider' => $provider,
            'items' => $query->items_json
        ]);
    }

    /**
     * Process the confirmation form submission.
     */
    public function submit(Request $request, $token)
    {
        $query = ZbotQuery::where('confirmation_token', $token)->first();

        if (!$query) {
            return back()->with('error', 'La solicitud de búsqueda no existe.');
        }

        if ($query->current_step === 'completed' || $query->status === 'expired' || ($query->expires_at && $query->expires_at->isPast())) {
            return back()->with('error', 'Esta búsqueda ya expiró o fue completada.');
        }

        $itemsInput = $request->input('items', []);
        $confirmedItems = [];
        $totalPrice = 0;
        $hasAnyStock = false;

        // items_json format:
        // array of items. Each item has:
        // 'product' => ['supplier_code' => '...', 'oversize' => '...'], 'qty' => 2
        // We now match items by positional index to support duplicate supplier_codes with different oversize
        foreach ($query->items_json as $idx => $item) {
            $code = $item['product']['supplier_code'] ?? 'N/A';
            $input = $itemsInput[$idx] ?? null;

            if ($input && isset($input['tengo']) && $input['tengo'] == '1') {
                $qtyConfirmed = isset($input['qty']) ? intval($input['qty']) : 0;
                $priceUnit = isset($input['price']) ? floatval($input['price']) : 0.0;

                // Clamp qty to original requested qty
                $maxQty = $item['qty'] ?? 1;
                if ($qtyConfirmed > $maxQty) {
                    $qtyConfirmed = $maxQty;
                }
                if ($qtyConfirmed < 0) {
                    $qtyConfirmed = 0;
                }

                if ($qtyConfirmed > 0 && $priceUnit >= 0) {
                    $subtotal = $qtyConfirmed * $priceUnit;
                    $totalPrice += $subtotal;
                    $hasAnyStock = true;

                    $confirmedItems[] = [
                        'oem_code' => $code,
                        'description' => $item['product']['name'] ?? ($item['product']['supplier_code'] ?? 'Producto'),
                        'qty_requested' => $maxQty,
                        'qty_confirmed' => $qtyConfirmed,
                        'price_unit' => $priceUnit,
                        'subtotal' => $subtotal,
                        'measure' => $item['product']['oversize'] ?? null,
                    ];
                }
            }
        }

        $ws = new WhatsAppService();

        if ($hasAnyStock) {
            $query->update([
                'status' => 'confirmed',
                'current_step' => 'completed',
                'price' => $totalPrice,
                'items_confirmed_json' => $confirmedItems,
                'response_text' => 'Confirmado vía Web'
            ]);

            // Notify provider via WhatsApp
            $ws->sendFinalConfirmation($query->chat_id);
        } else {
            $query->update([
                'status' => 'denied',
                'current_step' => 'completed',
                'price' => 0,
                'items_confirmed_json' => [],
                'response_text' => 'Sin stock vía Web'
            ]);

            // Notify provider via WhatsApp
            $ws->sendDenialThanks($query->chat_id);
        }

        return view('provider.confirm-stock', [
            'success' => true,
            'query' => $query
        ]);
    }
}
