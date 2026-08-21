<?php

namespace App\Livewire\SearchComponents;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductOversize;
use App\Models\Vehicle;
use App\Models\Make;
use App\Models\CarModel;
use App\Models\Engine;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\ZbotQuery;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class MainSearch extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $oemSearch = '';
    public $plateSearch = '';

    public function searchFeaturedProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->oemSearch = $product->name; // search by product name exactly
            $this->performSearch('oem');
        }
    }

    public function getZbotProviders()
    {
        $providerIds = collect($this->repairList)->pluck('product.provider_id')->unique()->toArray();
        $providers = \App\Models\Provider::whereIn('id', $providerIds)->get();

        if ($providers->isEmpty()) {
            // Fallback for demo if no providers found
            return [
                ['id' => 'p1', 'name' => 'Proveedor A', 'icon' => '🏪', 'state' => 'waiting', 'result' => 'Esperando', 'depends_on_zbot' => true],
            ];
        }

        $icons = ['🏪', '🏬', '🔩', '📦', '🏣'];
        return $providers->map(function ($p, $index) use ($icons) {
            // Check if ALL products of this provider in the repairList have a price AND provider does not require ZettaBot
            $providerProductsInCart = collect($this->repairList)->filter(fn($item) => $item['product']['provider_id'] == $p->id);
            $allHavePrice = $providerProductsInCart->every(fn($item) => ($item['product']['price'] ?? 0) > 0);
            $dependsOnZbot = $p->requires_zbot || !$allHavePrice;

            $state = 'waiting';
            $result = 'Esperando';
            $zbotPrice = null;

            if (!$dependsOnZbot) {
                // Pre-priced directly confirmed
                $state = 'confirmed';
                $zbotPrice = $providerProductsInCart->sum(fn($item) => ($item['product']['price'] ?? 0) * $item['qty']);
                $result = '✓ Confirmado';
            } else {
                // Find current active query
                $startTime = $this->zbotSearchStartTime ?? now()->subMinutes(10);
                $q = ZbotQuery::where('provider_id', $p->id)
                    ->where('created_at', '>=', $startTime)
                    ->latest()
                    ->first();

                if ($q) {
                    // Handle timeout (no response after 9 minutes)
                    if ($q->status === 'waiting' && $q->expires_at && $q->expires_at->isPast()) {
                        $q->update(['status' => 'expired']);
                        // Final message to provider
                        $ws = new WhatsAppService();
                        $ws->sendMessage($q->chat_id, "Sin respuesta en el tiempo límite. El pedido será redirigido a otro proveedor.");
                    }

                    if ($q->status === 'confirmed') {
                        $state = 'confirmed';
                        $zbotPrice = $q->price;
                        $result = '✓ Confirmado';
                    } elseif ($q->status === 'denied') {
                        $state = 'denied';
                        $result = '✗ Sin stock';
                    } elseif ($q->status === 'expired') {
                        $state = 'denied';
                        $result = '⚠ No responde';
                    } elseif ($q->status === 'waiting') {
                        $state = 'asking';
                        $result = 'Consultando...';
                    }
                }
            }

            return [
                'id' => 'p' . ($index + 1),
                'real_id' => $p->id,
                'name' => 'Proveedor ' . ($index + 1),
                'icon' => $icons[$index % count($icons)],
                'state' => $state,
                'result' => $result,
                // zbot_price (costo del proveedor) NO se expone al cliente - solo para uso interno
                'depends_on_zbot' => $dependsOnZbot
            ];
        })->values()->toArray();
    }

    /**
     * Returns true if EVERY product in the repairList has a pre-set price
     * AND its provider does NOT require ZettaBot confirmation.
     * In this case we can skip the WhatsApp flow and go straight to payment.
     */
    public function isOrderFullyPrePriced(): bool
    {
        if (empty($this->repairList)) return false;

        foreach ($this->repairList as $item) {
            $product = $item['product'];
            $hasPrice = isset($product['price']) && floatval($product['price']) > 0;
            if (!$hasPrice) return false;

            $provider = \App\Models\Provider::find($product['provider_id'] ?? null);
            if (!$provider || $provider->requires_zbot) return false;
        }

        return true;
    }

    public $triedProviderIds = [];
    public $searchingForAlternatives = false;


    public function checkZbotResponses()
    {
        if (!$this->isSearching)
            return;

        $startTime = $this->zbotSearchStartTime ?? now()->subMinutes(10);
        $ws = new WhatsAppService();

        // 1. Get current queries
        $currentQueries = ZbotQuery::where('created_at', '>=', $startTime)->get();

        // 2. Handle Reminders and Timeouts for 'waiting' queries
        foreach ($currentQueries->where('status', 'waiting') as $wq) {
            $secondsElapsed = $wq->created_at->diffInSeconds(now());
            \Illuminate\Support\Facades\Log::info("Zbot Query ID {$wq->id} - Elapsed: {$secondsElapsed}s | Reminders: {$wq->reminders_sent}");

            // Reminders (3m = 180s, 6m = 360s, 9m = 540s)
            if ($secondsElapsed >= 180 && $wq->reminders_sent == 0) {
                $ws->sendMessage($wq->chat_id, "⏰ *Recordatorio:* Hola, ¿pudiste revisar este pedido? Seguimos a la espera de tu confirmación.");
                $wq->update(['reminders_sent' => 1]);
            } elseif ($secondsElapsed >= 360 && $wq->reminders_sent == 1) {
                $ws->sendMessage($wq->chat_id, "⏳ *Segundo Recordatorio:* ¡Importante! El cliente sigue esperando. Por favor confirma si tienes stock.");
                $wq->update(['reminders_sent' => 2]);
            } elseif ($secondsElapsed >= 540 && $wq->reminders_sent == 2) {
                $ws->sendMessage($wq->chat_id, "Sin respuesta en el tiempo límite. El pedido será redirigido a otro proveedor.");
                $wq->update(['status' => 'expired', 'reminders_sent' => 3]);
            }
        }

        // 3. Detect failed queries (denied or expired) to search for alternatives
        $failedQueries = $currentQueries->whereIn('status', ['denied', 'expired']);
        $alternativeRequests = []; // Group by provider to avoid multiple messages

        foreach ($failedQueries as $fq) {
            if (in_array($fq->provider_id, $this->triedProviderIds))
                continue;

            $this->triedProviderIds[] = $fq->provider_id;

            $items = $fq->items_json ?? [];
            foreach ($items as $item) {
                $pid = $item['product']['id'] ?? null;
                $originalProduct = Product::find($pid);
                if (!$originalProduct)
                    continue;

                // Find alternative product strictly by OEM or supplier code
                $alternative = Product::where(function ($q) use ($originalProduct) {
                    if (!empty($originalProduct->oem_code)) {
                        $q->where('oem_code', $originalProduct->oem_code);
                    }
                    if (!empty($originalProduct->supplier_code)) {
                        $q->orWhere('supplier_code', $originalProduct->supplier_code);
                    }
                })
                    ->where('provider_id', '!=', $fq->provider_id)
                    ->whereNotIn('provider_id', $this->triedProviderIds)
                    ->active()
                    ->with('provider')
                    ->first();

                if ($alternative && $alternative->provider && $alternative->provider->whatsapp_number) {
                    $altPid = $alternative->provider_id;
                    $alternativeRequests[$altPid]['provider'] = $alternative->provider;
                    $alternativeRequests[$altPid]['items'][] = $item;
                }
            }
        }

        // Trigger alternative searches grouped by provider
        foreach ($alternativeRequests as $req) {
            $this->triggerAlternativeSearch($req['provider'], $req['items']);
            $this->searchingForAlternatives = true;
        }

        $providers = $this->getZbotProviders();
        $this->dispatch('zbot-updated', ['providers' => $providers]);
    }

    private function triggerAlternativeSearch($provider, $items)
    {
        $ws = new WhatsAppService();
        \Illuminate\Support\Facades\Log::info("Intentando alternativa con Proveedor {$provider->id}");

        $token = (string) \Illuminate\Support\Str::uuid();
        $link = url("/proveedor/confirmar/{$token}");

        $orderMsg = $ws->formatZbotOrder($items, rand(1000, 9999));
        $menuMsg = "{$orderMsg}\n\n*🔗 Confirma tu stock y precios aquí:*\n{$link}\n\nO si prefieres, responde por WhatsApp:\n1️⃣ - ✅ Sí tengo todo el stock\n2️⃣ - ❌ No tengo stock";

        $res = $ws->sendMessage($provider->whatsapp_number, $menuMsg);

        if ($res) {
            $cleanNumber = preg_replace('/[^0-9]/', '', $provider->whatsapp_number);
            ZbotQuery::create([
                'provider_id' => $provider->id,
                'chat_id' => $cleanNumber . '@c.us',
                'message_id' => $res['idMessage'] ?? null,
                'status' => 'waiting',
                'items_json' => $items,
                'expires_at' => now()->addMinutes(9),
                'reminders_sent' => 0,
                'confirmation_token' => $token,
            ]);
            $this->triedProviderIds[] = $provider->id;
        }
    }

    public function getRecommendedProducts()
    {
        if (empty($this->repairList))
            return collect();

        $firstItem = collect($this->repairList)->first();
        $catId = $firstItem['product']['category_id'] ?? null;

        return Product::where('category_id', $catId)
            ->whereNotIn('id', array_keys($this->repairList))
            ->active()
            ->with('provider')
            ->limit(4)
            ->get();
    }

    public function getConfirmedRepuestosTotal()
    {
        $items = $this->getConfirmedItems();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    public function getConfirmedItems()
    {
        $confirmed = [];
        $startTime = $this->zbotSearchStartTime ?? now()->subMinutes(15);

        // Group manually to preserve original string/integer keys ($repairListId)
        $grouped = [];
        foreach ($this->repairList as $repairListId => $item) {
            $providerId = $item['product']['provider_id'] ?? null;
            if ($providerId) {
                $grouped[$providerId][$repairListId] = $item;
            }
        }

        foreach ($grouped as $providerId => $items) {
            $provider = \App\Models\Provider::find($providerId);
            $requiresZbot = $provider ? $provider->requires_zbot : true;

            $q = ZbotQuery::with('provider')->where('provider_id', $providerId)
                ->where('status', 'confirmed')
                ->where('created_at', '>=', $startTime)
                ->latest()
                ->first();

            foreach ($items as $repairListId => $item) {
                $product = $item['product'];
                $qty = $item['qty'];
                $hasPrice = isset($product['price']) && $product['price'] > 0;

                // Look up override keyed by repairListId (the product ID in repairList)
                $override = $this->confirmedOverrides[$repairListId] ?? null;
                // Skip excluded items
                if ($override && ($override['excluded'] ?? false)) continue;

                if ($hasPrice && !$requiresZbot) {
                    // Direct confirmation, no ZettaBot, no markup, but we add 18% IGV
                    $unitPriceWithTax = round(floatval($product['price']) * 1.18, 2);
                    $oem = $product['supplier_code'] ?? 'N/A';
                    $finalQty = ($override && isset($override['qty']) && $override['qty'] > 0)
                        ? min((int)$override['qty'], $qty)
                        : $qty;
                    $confirmed[$repairListId] = [
                        'oem_code' => $oem,
                        'product_id' => $product['id'],
                        'repair_list_id' => $repairListId,
                        'description' => $product['name'] ?? 'Producto',
                        'qty' => $finalQty,
                        'price' => $unitPriceWithTax,
                        'subtotal' => $unitPriceWithTax * $finalQty,
                        'provider_name' => $provider->business_name ?? $provider->name ?? 'Proveedor',
                    ];
                } elseif ($q) {
                    if (!empty($q->items_confirmed_json) && is_array($q->items_confirmed_json)) {
                        foreach ($q->items_confirmed_json as $cItem) {
                            $itemOversize = $cItem['measure'] ?? 'STD';
                            $productOversize = $product['oversize'] ?? 'STD';
                            
                            if (($cItem['oem_code'] ?? '') === ($product['supplier_code'] ?? '') && $itemOversize === $productOversize) {
                                $originalPrice = $cItem['price_unit'] ?? 0;
                                $unitPriceWithMarkup = round($originalPrice * 1.10 * 1.18, 2);
                                $qtyConfirmed = $cItem['qty_confirmed'] ?? 0;
                                
                                $finalQty = ($override && isset($override['qty']) && $override['qty'] > 0)
                                    ? min((int)$override['qty'], $qtyConfirmed)
                                    : $qtyConfirmed;
                                $confirmed[$repairListId] = [
                                    'oem_code' => $cItem['oem_code'] ?? 'N/A',
                                    'product_id' => $product['id'],
                                    'repair_list_id' => $repairListId,
                                    'description' => $cItem['description'] ?? 'Producto',
                                    'qty' => $finalQty,
                                    'price' => $unitPriceWithMarkup,
                                    'subtotal' => $unitPriceWithMarkup * $finalQty,
                                    'provider_name' => $q->provider->business_name ?? $q->provider->name ?? 'Proveedor',
                                ];
                                break;
                            }
                        }
                    } elseif ($q->price) {
                        $normalizedText = str_replace(',', '.', $q->price);
                        $cleanText = preg_replace('/[^0-9. ]/', ' ', $normalizedText);
                        $pricesFound = array_map('floatval', preg_split('/\s+/', trim($cleanText)));
                        
                        $itemsRequested = $q->items_json ?? [];
                        foreach ($itemsRequested as $index => $reqItem) {
                            if (($reqItem['product']['supplier_code'] ?? '') === ($product['supplier_code'] ?? '')) {
                                $originalPrice = $pricesFound[$index] ?? end($pricesFound) ?? 0;
                                $unitPriceWithMarkup = round($originalPrice * 1.10 * 1.18, 2);
                                
                                $finalQty2 = ($override && isset($override['qty']) && $override['qty'] > 0)
                                    ? min((int)$override['qty'], $qty)
                                    : $qty;
                                $confirmed[$repairListId] = [
                                    'oem_code' => $product['supplier_code'] ?? 'N/A',
                                    'product_id' => $product['id'],
                                    'repair_list_id' => $repairListId,
                                    'description' => $product['name'] ?? 'Producto',
                                    'qty' => $finalQty2,
                                    'price' => $unitPriceWithMarkup,
                                    'subtotal' => $unitPriceWithMarkup * $finalQty2,
                                    'provider_name' => $q->provider->business_name ?? $q->provider->name ?? 'Proveedor',
                                ];
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $confirmed;
    }

    public function getConfirmedFinalTotal()
    {
        $repuestos = $this->getConfirmedRepuestosTotal();
        if ($repuestos <= 0)
            return 0;
        // Use the estimated delivery cost set during delivery confirmation (Lima or province)
        $deliveryCost = $this->estimatedDeliveryCost ?? 0;
        return $repuestos + $deliveryCost;
    }

    /** Actualiza la cantidad de un item confirmado (cliente reduce/aumenta cantidad) */
    public function updateConfirmedQty(string $pid, int $delta)
    {
        $overrides = $this->confirmedOverrides;
        
        // Find maximum confirmed qty first from getConfirmedItems
        $items = $this->getConfirmedItems();
        $rItem = $this->repairList[$pid] ?? null;
        $oem = $rItem ? strtoupper(trim($rItem['product']['supplier_code'] ?? $rItem['product']['oem_code'] ?? '')) : '';
        $productModel = $rItem ? \App\Models\Product::find($rItem['product']['id']) : null;
        $oversize = $productModel ? $productModel->oversize : 'STD';
        
        $match = collect($items)->first(function($c) use ($oem, $oversize) {
            if (strtoupper(trim($c['oem_code'])) !== $oem) return false;
            if (!empty($c['product_id'])) {
                $dbProd = \App\Models\Product::find($c['product_id']);
                $cOversize = $dbProd ? $dbProd->oversize : 'STD';
                return $cOversize === $oversize;
            }
            return true;
        });

        // The maximum qty the supplier confirmed is the limit
        $maxQty = $match ? $match['qty'] : 1;
        
        // However, since getConfirmedItems dynamically includes the current overrides, 
        // we must find the raw/initial confirmed quantity before overrides were applied.
        // Let's get the raw quantity from the provider's query response.
        $rawQty = 1;
        if ($rItem && isset($rItem['product']['provider_id'])) {
            $providerId = $rItem['product']['provider_id'];
            $q = ZbotQuery::where('provider_id', $providerId)
                ->where('status', 'confirmed')
                ->where('created_at', '>=', $this->zbotSearchStartTime ?? now()->subMinutes(15))
                ->latest()
                ->first();
            if ($q && !empty($q->items_confirmed_json) && is_array($q->items_confirmed_json)) {
                foreach ($q->items_confirmed_json as $cItem) {
                    $itemOversize = $cItem['measure'] ?? 'STD';
                    if (($cItem['oem_code'] ?? '') === ($rItem['product']['supplier_code'] ?? '') && $itemOversize === $oversize) {
                        $rawQty = $cItem['qty_confirmed'] ?? 0;
                        break;
                    }
                }
            } else {
                $rawQty = $rItem['qty']; // fallback
            }
        }

        $current = $overrides[$pid]['qty'] ?? $rawQty;
        $newQty = min(max(1, (int)$current + $delta), $rawQty);
        
        $overrides[$pid] = array_merge($overrides[$pid] ?? [], ['qty' => $newQty, 'excluded' => false]);
        $this->confirmedOverrides = $overrides;
    }

    /** Excluye un item del carrito (cliente no quiere ese producto) */
    public function removeConfirmedItem(string $pid)
    {
        $overrides = $this->confirmedOverrides;
        $overrides[$pid] = array_merge($overrides[$pid] ?? [], ['excluded' => true]);
        $this->confirmedOverrides = $overrides;
    }

    /** Restaura un item excluido */
    public function restoreConfirmedItem(string $pid)
    {
        $overrides = $this->confirmedOverrides;
        $overrides[$pid] = array_merge($overrides[$pid] ?? [], ['excluded' => false]);
        $this->confirmedOverrides = $overrides;
    }

    public $isPro = false;
    public $vehicle = null;
    public $oemResult = null;

    public $categories = [];
    public $compatibleCategoryIds = [];
    public $selectedCategory = null;
    public $selectedSubcategory = null;
    public $selectedProductForDetails = null;
    public $showDetailsModal = false;
    public $selectedProductDisplacements = []; // cilindradas del producto en el modal

    public $viewState = 'initial'; // initial, vehicle_found, subcategories, products_list, oem_found, repair_summary
    public $searchType = ''; // plate, manual, oem

    // Manual Filter properties (cascading selects)
    public $selectedBrand = '';
    public $selectedModel = '';
    public $selectedEngine = '';
    public $brands = [];
    public $models = [];
    public $engines = [];
    public $priorityBrands = [];
    public $alphabeticalBrands = [];
    public $brandsWithProducts = []; // brands that have active products in DB

    // Livewire-native dropdown open state
    public $brandDropdownOpen = false;
    public $modelDropdownOpen = false;
    public $engineDropdownOpen = false;

    protected $recognizedBrands = [
        'TOYOTA',
        'NISSAN',
        'HONDA',
        'MITSUBISHI',
        'SUZUKI',
        'MAZDA',
        'SUBARU',
        'ISUZU',
        'HYUNDAI',
        'KIA',
        'SSANGYONG',
        'CHEVROLET',
        'FORD',
        'JEEP',
        'DODGE',
        'RAM'
    ];

    // Navigation and Results
    public $availableBrands = [];
    public $currentBrand = null;

    // Lead Capture
    public $showLeadForm = false;
    public $phone = '';
    public $verificationCode = '';
    public $isVerified = false;
    public $pendingAction = null;

    public $recentlyViewed = [];
    public $repairList = [];
    public $confirmedOverrides = []; // ['OEM_CODE' => ['qty' => N, 'excluded' => true/false]]
    public $showAddedPopup = false;
    public $showIdentityPopup = false;
    public $identityInput = '';
    public $lastAddedProduct = null;
    public $showSuccessPopup = false;
    public $isSearching = false;
    public $zbotSearchStartTime = null;
    public $showLoginModal = false;
    public $loginBlockMessage = '';
    public $showUserOrderDetailModal = false;
    public $userSelectedOrder = null;
    public $showOrdersDrawer = false;
    public $showPrivacyModal = false;
    public $showIncidentModal = false;
    public $selectedOrderIdForIncident = null;
    public $drawerSubState = 'menu'; // 'menu' | 'orders' | 'addresses' | 'profile'

    public function openPrivacyModal()
    {
        $this->showPrivacyModal = true;
    }

    public function closePrivacyModal()
    {
        $this->showPrivacyModal = false;
    }

    public function acceptPrivacyPolicy()
    {
        if (auth()->check()) {
            auth()->user()->update([
                'privacy_policy_accepted_at' => now()
            ]);
        }

        // También guardamos en sesión para usuarios no logueados
        session(['privacy_policy_accepted' => true]);

        $this->showPrivacyModal = false;
        
        $this->dispatch('privacy-policy-accepted');
    }

    // Address Edit
    public $showAddressEditModal = false;
    public $editingAddressIndex = null;
    public $addressEditData = [
        'type' => 'lima',
        'label' => '',
        'address' => '',
        'district' => '',
        'city' => '',
        'agency' => '',
    ];

    // Manual search state: engine object to carry into category/product search
    public $selectedEngineObj = null;
    public $lastOrderId = null;

    // ── Delivery Address Modal ──────────────────────────────────────────
    public $showDeliveryModal = false;
    public $deliveryType = 'lima';        // 'lima' | 'province'
    public $deliveryAddress = '';         // calle / referencia (Lima)
    public $deliveryDistrict = '';        // distrito (Lima)
    public $deliveryAgency = '';          // agencia (provincia)
    public $deliveryCity = '';            // ciudad destino (provincia)
    public $estimatedDeliveryCost = 0;
    public array $savedAddresses = [];    // direcciones guardadas del usuario

    // Distritos de Lima con tarifa estimada (zona → S/.)
    public array $limaZones = [
        'Cercana' => ['districts' => ['La Victoria', 'Lince', 'Breña', 'Rímac', 'El Agustino', 'San Luis', 'Cercado de Lima', 'Barrios Altos'], 'cost' => 10],
        'Media' => ['districts' => ['Miraflores', 'San Isidro', 'Surquillo', 'Barranco', 'Chorrillos', 'San Borja', 'Jesús María', 'Pueblo Libre', 'Magdalena del Mar', 'San Miguel'], 'cost' => 15],
        'Alejada' => ['districts' => ['Surco', 'La Molina', 'Ate', 'Santa Anita', 'San Juan de Lurigancho', 'Comas', 'Los Olivos', 'San Martín de Porres', 'Independencia', 'Carabayllo', 'Villa El Salvador', 'Villa María del Triunfo', 'Lurlurín'], 'cost' => 22],
    ];

    // Agencias de envío a provincias con costo hasta la agencia en Lima
    public array $shippingAgencies = [
        'Shalom Express' => 12,
        'CIVA' => 10,
        'Flores' => 10,
        'Marvisur' => 12,
        'Cruz del Sur' => 15,
        'Oltursa' => 15,
        'Tepsa' => 12,
        'Movil Tours' => 12,
        'GHL' => 10,
        'Otra agencia' => 15,
    ];

    // Datos del destinatario (province only)
    public $recipientName = '';
    public $recipientDni = '';
    public $recipientPhone = '';
    public $recipientAddress = ''; // referencia de domicilio (opcional, para el destinatario)

    // Contraseña de recojo generada por el sistema al confirmar el pedido
    public $pickupPassword = '';

    // Search params for pagination
    public $searchContext = [];

    public function resetToHome()
    {
        $this->oemSearch = '';
        $this->plateSearch = '';
        $this->vehicle = null;
        $this->selectedCategory = null;
        $this->selectedSubcategory = null;
        $this->viewState = 'initial';
        $this->selectedBrand = '';
        $this->selectedModel = '';
        $this->selectedEngine = '';
        $this->selectedEngineObj = null;
        $this->searchType = '';
        $this->isSearching = false;
        $this->searchContext = [];
        $this->resetPage();
        $this->loadInitialFilters();
        $this->savePersistedSearchState();
    }

    public function toggleRepairSummary()
    {
        if ($this->viewState === 'repair_summary') {
            $this->viewState = ($this->vehicle || $this->selectedEngineObj) ? 'vehicle_found' : 'initial';
        } else {
            $this->viewState = 'repair_summary';
        }
    }

    public function clearVerification()
    {
        // Solo permitir resetear si el admin lo requiere (esto elimina el phone guardado)
        if (auth()->check() && auth()->user()->hasVerifiedPhone()) {
            auth()->user()->update(['phone' => null, 'phone_verified_at' => null]);
        }
        session()->forget(['user_phone', 'wa_verification_code']);
        $this->isVerified = false;
        $this->phone = '';
        $this->verificationCode = '';
        $this->dispatch('notify', ['type' => 'info', 'message' => 'Número de WhatsApp desvinculado.']);
    }

    public function clearManualSearch()
    {
        $this->selectedBrand = '';
        $this->selectedModel = '';
        $this->selectedEngine = '';
        $this->selectedEngineObj = null;
        $this->models = [];
        $this->engines = [];
        if ($this->searchType === 'manual') {
            $this->resetStates();
        }
        $this->searchContext = [];
        $this->resetPage();
    }

    public function mount()
    {
        $categories = Category::whereNull('parent_id')
            ->orderBy('order')
            ->with(['children.children'])
            ->get();
            
        $allowedSubcategories = $this->getAllowedSubcategories();
        $allowedParents = $this->getAllowedParentCategories();

        $this->categories = $categories->filter(function($parent) use ($allowedSubcategories, $allowedParents) {
            if (!in_array($parent->name, $allowedParents)) {
                return false;
            }
            // Flatten 3-level: include grandchildren alongside direct children
            $flatChildren = $this->buildFlatChildrenForParent($parent, $allowedSubcategories, []);
            $parent->setRelation('children', $flatChildren);
            return $flatChildren->count() > 0;
        })->values();
        $this->isPro = true;
        $this->loadInitialFilters();
        $this->loadRecentlyViewed();

        // ★ RESTAURAR ESTADO DE BÚSqueda si el usuario acabó de iniciar sesión
        if (session()->has('search_state_snapshot')) {
            $snap = session()->pull('search_state_snapshot');
            foreach ($snap as $key => $value) {
                if (property_exists($this, $key)) {
                    if ($key === 'selectedEngineObj' && $value) {
                        $this->selectedEngineObj = \App\Models\Engine::find($value);
                    } elseif ($key === 'selectedCategory' && $value) {
                        $this->selectedCategory = \App\Models\Category::find($value);
                    } elseif ($key === 'selectedSubcategory' && $value) {
                        $this->selectedSubcategory = \App\Models\Category::find($value);
                    } else {
                        $this->$key = $value;
                    }
                }
            }
        }

        // Detect account block from Google Auth redirect
        if (session()->has('account_blocked')) {
            $this->loginBlockMessage = session('account_blocked');
            $this->showLoginModal = true;
        }

        // Si el usuario tiene WhatsApp verificado en su cuenta, no pedir de nuevo
        if (auth()->check() && auth()->user()->hasVerifiedPhone()) {
            $this->isVerified = true;
            $this->phone = auth()->user()->phone;
        } elseif (session()->has('user_phone')) {
            $this->isVerified = true;
            $this->phone = session('user_phone');
        }

        // Restablecer/cargar la lista de reparación (carrito) persistida si existe
        if (session()->has('repair_list_session')) {
            $this->repairList = session('repair_list_session');
        }

        // Cargar estado de búsqueda persistido en la sesión para evitar pérdida en F5
        if (session()->has('persisted_search_state')) {
            $pState = session('persisted_search_state');
            foreach ($pState as $key => $value) {
                if (property_exists($this, $key)) {
                    if ($key === 'selectedEngineObj' && $value) {
                        $this->selectedEngineObj = is_array($value) ? $value : \App\Models\Engine::find($value);
                    } elseif ($key === 'selectedCategory' && $value) {
                        $this->selectedCategory = \App\Models\Category::find($value);
                    } elseif ($key === 'selectedSubcategory' && $value) {
                        $this->selectedSubcategory = \App\Models\Category::find($value);
                    } else {
                        $this->$key = $value;
                    }
                }
            }
        }

        // ★ RESTAURAR ESTADO DE BÚSqueda si el usuario acabó de iniciar sesión
        if (session()->has('search_state_snapshot')) {
            $snap = session()->pull('search_state_snapshot');
            foreach ($snap as $key => $value) {
                if (property_exists($this, $key)) {
                    if ($key === 'selectedEngineObj' && $value) {
                        $this->selectedEngineObj = is_array($value) ? $value : \App\Models\Engine::find($value);
                    } elseif ($key === 'selectedCategory' && $value) {
                        $this->selectedCategory = \App\Models\Category::find($value);
                    } elseif ($key === 'selectedSubcategory' && $value) {
                        $this->selectedSubcategory = \App\Models\Category::find($value);
                    } else {
                        $this->$key = $value;
                    }
                }
            }
            // Al restaurar desde snapshot, guardar en el estado persistido
            $this->savePersistedSearchState();
        }
    }

    /**
     * Guarda los campos importantes del estado de búsqueda en sesión
     * para que se puedan restaurar después de iniciar sesión.
     */
    private function saveSearchState(): void
    {
        $statesToSave = [
            // Filtros de vehículo
            'selectedBrand' => $this->selectedBrand,
            'selectedModel' => $this->selectedModel,
            'selectedEngine' => $this->selectedEngine,
            'selectedEngineObj' => $this->selectedEngineObj ? $this->selectedEngineObj->id : null,
            // Guardar listas para evitar recargas innecesarias
            'models' => $this->models,
            'engines' => $this->engines,
            // Búsqueda activa
            'viewState' => $this->viewState,
            'searchType' => $this->searchType,
            'oemSearch' => $this->oemSearch,
            'plateSearch' => $this->plateSearch,
            // Lista de reparación
            'repairList' => $this->repairList,
            // Categoría seleccionada (IDs para recargar)
            'selectedCategory' => $this->selectedCategory?->id ?? null,
            'selectedSubcategory' => $this->selectedSubcategory?->id ?? null,
            // Contexto de búsqueda
            'searchContext' => $this->searchContext,
        ];

        session()->put('search_state_snapshot', $statesToSave);

        // Log para debug
        \Illuminate\Support\Facades\Log::info('Search state saved before login redirect', [
            'viewState' => $this->viewState,
            'repairItems' => count($this->repairList),
        ]);
    }

    /**
     * Guarda el estado actual en la sesión para que sobreviva a recargas F5 o recargas del navegador.
     */
    private function savePersistedSearchState(): void
    {
        $statesToSave = [
            'selectedBrand' => $this->selectedBrand,
            'selectedModel' => $this->selectedModel,
            'selectedEngine' => $this->selectedEngine,
            'selectedEngineObj' => $this->selectedEngineObj,
            'models' => $this->models,
            'engines' => $this->engines,
            'viewState' => $this->viewState,
            'searchType' => $this->searchType,
            'oemSearch' => $this->oemSearch,
            'plateSearch' => $this->plateSearch,
            'selectedCategory' => $this->selectedCategory?->id ?? null,
            'selectedSubcategory' => $this->selectedSubcategory?->id ?? null,
            'searchContext' => $this->searchContext,
            'isSearching' => $this->isSearching,
            'zbotSearchStartTime' => $this->zbotSearchStartTime,
            'lastOrderId' => $this->lastOrderId,
            'triedProviderIds' => $this->triedProviderIds,
            'searchingForAlternatives' => $this->searchingForAlternatives,
            'deliveryType' => $this->deliveryType,
            'deliveryAddress' => $this->deliveryAddress,
            'deliveryDistrict' => $this->deliveryDistrict,
            'deliveryAgency' => $this->deliveryAgency,
            'deliveryCity' => $this->deliveryCity,
            'estimatedDeliveryCost' => $this->estimatedDeliveryCost,
        ];

        session()->put('persisted_search_state', $statesToSave);
        session()->put('repair_list_session', $this->repairList);
    }

    /**
     * Guarda el estado de búsqueda y redirige a Google OAuth.
     * Se llama desde el botón del modal de login.
     */
    public function loginWithGoogle(): void
    {
        // Al continuar con Google se asume aceptación de la política por contrato visual
        session(['privacy_policy_accepted' => true]);

        $this->saveSearchState();
        $this->redirect(route('google.auth'));
    }

    public function loadInitialFilters()
    {
        // Cache the expensive JOIN across makes/models/engines/products for 30 minutes.
        // Invalidated automatically when a product is saved via ProductManagement.
        $cached = Cache::remember('brands_with_products', 1800, function () {
            return \DB::table('makes')
                ->where(function ($q) {
                    $q->whereExists(function ($sub) {
                        $sub->select(\DB::raw(1))
                            ->from('car_models')
                            ->join('engines', 'engines.car_model_id', '=', 'car_models.id')
                            ->join('products', function ($pJoin) {
                                $pJoin->whereRaw('products.compatible_engines LIKE CONCAT(\'%"\', engines.engine_code, \'"%\')')
                                    ->where('products.is_active', true);
                            })
                            ->whereColumn('car_models.make_id', 'makes.id');
                    })
                        ->orWhereExists(function ($sub) {
                            $sub->select(\DB::raw(1))
                                ->from('car_models')
                                ->join('products', function ($pJoin) {
                                    $pJoin->whereRaw('FIND_IN_SET(car_models.name, REPLACE(products.compatible_vehicles, \', \', \',\')) > 0')
                                        ->where('products.is_active', true);
                                })
                                ->whereColumn('car_models.make_id', 'makes.id');
                        });
                })
                ->pluck('name')
                ->toArray();
        });

        $this->brandsWithProducts = $cached;

        // Priority list = ALL recognized brands that exist in DB (coloured blue/red in view)
        $allBrandsInDb = Cache::remember('all_makes_names', 3600, fn() => Make::pluck('name')->toArray());
        $this->priorityBrands = array_values(array_intersect($this->recognizedBrands, $allBrandsInDb));

        // Alphabetical section: only brands WITH products that are NOT already in the priority list
        $this->alphabeticalBrands = array_values(array_diff($cached, $this->recognizedBrands));
        sort($this->alphabeticalBrands);

        $this->brands = $cached;
    }

    public function selectBrand(string $brand)
    {
        $this->selectedBrand = $brand;
        $this->selectedModel = '';
        $this->selectedEngine = '';
        $this->models = [];
        $this->engines = [];
        $this->brandDropdownOpen = false;
        $this->modelDropdownOpen = false;
        $this->engineDropdownOpen = false;
        $this->updatedSelectedBrand($brand);
    }

    public function selectModel(string $modelId)
    {
        $this->selectedModel = $modelId;
        $this->selectedEngine = '';
        $this->engines = [];
        $this->modelDropdownOpen = false;
        $this->engineDropdownOpen = false;
        $this->updatedSelectedModel($modelId);
    }

    public function selectEngine(string $engineId)
    {
        $this->selectedEngine = $engineId;
        $this->engineDropdownOpen = false;
    }

    public function updatedSelectedBrand($value)
    {
        $make = Make::where('name', $value)->first();
        if (!$make) {
            $this->models = [];
            return;
        }

        $this->models = CarModel::where('make_id', $make->id)
            ->where(function ($query) {
                // Models whose name appears in compatible_vehicles
                $query->whereExists(function ($sub) {
                    $sub->select(\DB::raw(1))
                        ->from('products')
                        ->where('is_active', true)
                        ->whereRaw('products.compatible_vehicles LIKE CONCAT("% ", car_models.name, "%") OR products.compatible_vehicles LIKE CONCAT(car_models.name, "%")');
                })
                    // Models whose engines are referenced by products (LIKE '%"code"%' exact match)
                    ->orWhereExists(function ($sub) {
                    $sub->select(\DB::raw(1))
                        ->from('engines')
                        ->join('products', function ($pJoin) {
                            $pJoin->whereRaw('products.compatible_engines LIKE CONCAT(\'%"\', engines.engine_code, \'"%\')')
                                ->where('products.is_active', true);
                        })
                        ->whereColumn('engines.car_model_id', 'car_models.id');
                });
            })
            ->get()
            ->map(function ($v) {
                $label = mb_convert_case($v->name, MB_CASE_TITLE, "UTF-8");
                if ($v->version_no)
                    $label .= " ({$v->version_no})";
                if ($v->start_year) {
                    $years = $v->start_year == $v->end_year ? $v->start_year : "{$v->start_year} - " . ($v->end_year ?: '...');
                    $label .= " ($years)";
                }
                return ['id' => $v->id, 'label' => $label];
            })
            ->sortBy('label')
            ->values()
            ->toArray();

        $this->selectedModel = '';
        $this->selectedEngine = '';
        $this->engines = [];
    }

    public function updatedSelectedModel($value)
    {
        if (!$value)
            return;

        // Get all engines for the selected model id
        $allEngines = Engine::where('car_model_id', $value)->get();

        // Filter: only show engines that have at least one product linked,
        // either by engine_code match OR by compatible_model_ids containing this model
        $filtered = $allEngines->filter(function ($engine) use ($value) {
            // Check 1: product with this engine code in compatible_engines
            $byCode = \DB::table('products')
                ->where('is_active', true)
                ->where('compatible_engines', 'LIKE', '%"' . $engine->engine_code . '"%')
                ->exists();
            if ($byCode)
                return true;

            // Check 2: product associated to this model by compatible_model_ids
            $byModelId = \DB::table('products')
                ->where('is_active', true)
                ->whereRaw('JSON_CONTAINS(compatible_model_ids, ?)', [(string) $value])
                ->exists();
            return $byModelId;
        });

        $this->engines = $filtered->map(function ($v) {
            $power = $v->engine_power;
            if ($power && str_contains($power, '@')) {
                $power = preg_replace('/(\d+)\s*@\s*(\d+)/', '$1 HP @ $2 RPM', $power);
            } elseif ($power && is_numeric($power)) {
                $power = $power . " HP";
            }
            $powerLabel = $power ? " ({$power})" : "";
            $displacement = $v->displacement ? " {$v->displacement}CC" : "";
            $fuel = $v->fuel_type ? " [" . mb_convert_case($v->fuel_type, MB_CASE_TITLE, "UTF-8") . "]" : "";
            $code = $v->engine_code ?? '';
            return ['id' => $v->id, 'label' => $code . $displacement . $powerLabel . $fuel];
        })->values()->toArray();

        $this->selectedEngine = '';
    }

    public function performManualSearch()
    {
        if (!auth()->check()) {
            $this->saveSearchState(); // guardar selección de marca/modelo/motor
            $this->showLoginModal = true;
            return;
        }

        if (!$this->selectedBrand || !$this->selectedModel || !$this->selectedEngine) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Por favor, complete todos los pasos.']);
            return;
        }

        $engine = Engine::with('carModel.make')->find($this->selectedEngine);
        if (!$engine) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Motor no encontrado.']);
            return;
        }

        $engineCode = strtoupper($engine->engine_code ?? '');
        $modelName = strtoupper($engine->carModel->name ?? '');
        $makeName = strtoupper($engine->carModel->make->name ?? '');

        $this->selectedEngineObj = [
            'model' => $engine->carModel->name,
            'engine_code' => $engine->engine_code,
            'brand' => $engine->carModel->make->name,
            'label' => $makeName . ' ' . $modelName . ($engineCode ? ' (' . $engineCode . ')' : ''),
        ];

        $this->searchContext = [
            'type' => 'manual',
            'engine_code' => $engineCode,
            'model_name' => $modelName,
        ];

        $this->searchType = 'manual';
        $this->viewState = 'products_list';
        $this->resetPage();
        $this->savePersistedSearchState();
    }

    public function loadRecentlyViewed()
    {
        $ids = session()->get('recently_viewed_products', []);
        if (!empty($ids)) {
            $products = Product::whereIn('id', $ids)->with('provider')->get()->keyBy('id');
            $this->recentlyViewed = collect($ids)->map(fn($id) => $products->get($id))->filter()->take(4)->all();
        }
    }

    public function trackProductView($productId)
    {
        $ids = session()->get('recently_viewed_products', []);
        if (($key = array_search($productId, $ids)) !== false) {
            unset($ids[$key]);
        }
        array_unshift($ids, $productId);
        $ids = array_slice($ids, 0, 4);
        session()->put('recently_viewed_products', $ids);
        $this->loadRecentlyViewed();
    }

    public function performSearch($type = 'plate')
    {
        if (!auth()->check()) {
            $this->saveSearchState(); // guardar búsqueda por placa/OEM en curso
            $this->showLoginModal = true;
            return;
        }

        $this->resetStates();

        if ($type === 'plate') {
            $term = strtoupper(trim($this->plateSearch));
            $term = str_replace('-', '', $term);
            if (empty($term))
                return;

            $vehicle = Vehicle::where('plate', $term)->first();
            if ($vehicle) {
                $this->vehicle = $vehicle;
                $this->searchType = 'plate';
                $this->viewState = 'vehicle_found';
                $this->loadCompatibleCategoriesForVehicle($vehicle);
                $this->savePersistedSearchState();
                return;
            }

            $this->dispatch('notify', ['type' => 'error', 'message' => 'Placa no encontrada en el sistema.']);

        } elseif ($type === 'oem') {
            $term = strtoupper(trim($this->oemSearch));
            if (empty($term))
                return;

            // Search in products table by supplier_code, oem_code, or additional_oem_codes
            $query = Product::where(function ($q) use ($term) {
                $q->where('supplier_code', 'LIKE', '%' . $term . '%')
                    ->orWhere('oem_code', 'LIKE', '%' . $term . '%')
                    ->orWhere('brand', 'LIKE', '%' . $term . '%') // Added brand search as requested
                    ->orWhere('name', 'LIKE', '%' . $term . '%')
                    ->orWhereJsonContains('additional_oem_codes', $term);
            })->with(['provider', 'category'])->active();

            if ($query->count() > 0) {
                $this->oemResult = $query->first();
                $this->searchContext = [
                    'type' => 'oem',
                    'term' => $term
                ];
                $this->searchType = 'oem';
                $this->viewState = 'products_list';
                $this->trackProductView($this->oemResult->id);
                $this->resetPage();
                $this->savePersistedSearchState();
                return;
            }

            $this->dispatch('notify', ['type' => 'error', 'message' => 'Código no encontrado. Intente con código del proveedor o código OEM.']);
        }
    }

    public function initiateLeadCapture($action)
    {
        $this->pendingAction = $action;
        $this->showLeadForm = true;
    }

    public function sendVerificationCode()
    {
        $this->validate(['phone' => 'required|digits_between:9,13']);

        // Limpiar y normalizar: agregar prefijo Perú (51) si es de 9 dígitos
        $clean = preg_replace('/[^0-9]/', '', $this->phone);
        if (strlen($clean) === 9) {
            $clean = '51' . $clean;
        }
        $this->phone = $clean;

        $code = rand(1000, 9999);
        session()->put('wa_verification_code', $code);
        session()->put('wa_phone_pending', $clean); // guardar el número limpio

        $service = new \App\Services\WhatsAppService();
        $service->sendMessage($clean, "✅ Tu código de verificación para RepuestoFijo es: *{$code}*\n\nEste código expira en 10 minutos.");
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Código enviado a WhatsApp 👌']);
    }

    public function verifyCode()
    {
        $storedCode = session()->get('wa_verification_code');
        $isValid = ($storedCode && $this->verificationCode == $storedCode)
            || $this->verificationCode === '1234'; // fallback desarrollo

        if ($isValid) {
            $verifiedPhone = session()->get('wa_phone_pending', $this->phone);

            $this->isVerified = true;
            $this->showLeadForm = false;
            $this->phone = $verifiedPhone;

            // Guardar en sesión (compatibilidad)
            session()->put('user_phone', $verifiedPhone);

            // ★ Persistir en la cuenta del usuario (solo verifica UNA VEZ)
            if (auth()->check()) {
                auth()->user()->saveVerifiedPhone($verifiedPhone);
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => '✅ Número verificado y vinculado a tu cuenta. No te pediremos esto de nuevo.',
                ]);
            } else {
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => '✅ Número verificado correctamente.',
                ]);
            }

            session()->forget('wa_verification_code');

            // Ejecutar la acción pendiente
            $action = $this->pendingAction;
            if ($action) {
                if ($action['type'] === 'selectBrand') {
                    $this->selectBrandForProducts($action['brand']);
                } elseif ($action['type'] === 'oem') {
                    $this->performSearch('oem');
                } elseif ($action['type'] === 'final_confirmation') {
                    $this->processFinalOrder();
                }
            }
        } else {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Código incorrecto. Inténtalo de nuevo.']);
        }
    }

    private function resetStates()
    {
        $this->vehicle = null;
        $this->selectedEngineObj = null;
        $this->oemResult = null;
        $this->selectedCategory = null;
        $this->selectedSubcategory = null;
        $this->searchContext = [];
        $this->compatibleCategoryIds = [];
        $this->viewState = 'initial';
        $this->searchType = '';
        $this->resetPage();
        $this->savePersistedSearchState();
    }

    // Load categories from the PRODUCTS table for a plate-searched vehicle
    private function loadCompatibleCategoriesForVehicle($vehicle)
    {
        $model = strtoupper($vehicle->model);
        $engineCode = $vehicle->engine_code ? strtoupper($vehicle->engine_code) : null;
        $brand = $vehicle->make ? strtoupper($vehicle->make) : null;

        $categoryIds = Product::getCompatibleCategoryIds($model, $engineCode, $brand);
        $this->compatibleCategoryIds = $categoryIds;
        $this->loadCategoriesFromIds($categoryIds);
    }

    private function loadCompatibleCategoriesForEngine(string $brand, string $model, ?string $engineCode)
    {
        $categoryIds = Product::getCompatibleCategoryIds(strtoupper($model), $engineCode ? strtoupper($engineCode) : null, strtoupper($brand));
        $this->compatibleCategoryIds = $categoryIds;
        $this->loadCategoriesFromIds($categoryIds);
    }

    private function getAllowedSubcategories()
    {
        // These are subcategory names allowed to show (level-2 categories)
        return [
            'Pistones',
            'Anillos',
            // Filtros
            'Filtro de Aire',
            'Filtro de Aceite',
            'Filtro de Combustible',
            // All Metales de Motor subcategories
            'Metales de Biela',
            'Metales de Bancada',
            'Separadores de Bancada',
            'Metales de Levas',
            'Metal compensador',
            'Bocina de biela',
        ];
    }

    private function getAllowedParentCategories()
    {
        // These are the parent category names allowed to appear (level-1 categories)
        return [
            'Motor y Componentes Internos',
            'Metales de Motor',
            'Filtros y Mantenimiento',
        ];
    }

    /**
     * Build a flat list of leaf subcategories for display,
     * supporting up to 3 levels deep (root > mid > leaf).
     * Products in a 3rd-level category (e.g. Metales de Biela) appear
     * alongside 2nd-level ones (e.g. Pistones, Anillos) in the UI.
     */
    private function buildFlatChildrenForParent(Category $parent, array $allowedSubcategories, array $categoryIds = []): \Illuminate\Support\Collection
    {
        $flat = collect();

        // Direct children
        $directChildren = $parent->children ?? collect();
        foreach ($directChildren as $child) {
            if (in_array($child->name, $allowedSubcategories)) {
                // Only include if it's in compatible categoryIds (or no filter)
                if (empty($categoryIds) || in_array($child->id, $categoryIds)) {
                    $flat->push($child);
                }
            } else {
                // This child is a mid-level (like Metales de Motor); include its grandchildren
                $grandchildren = Category::where('parent_id', $child->id)
                    ->whereIn('name', $allowedSubcategories)
                    ->when(!empty($categoryIds), fn($q) => $q->whereIn('id', $categoryIds))
                    ->get();
                foreach ($grandchildren as $gc) {
                    $flat->push($gc);
                }
            }
        }

        return $flat->unique('id')->values();
    }

    private function loadCategoriesFromIds(array $categoryIds)
    {
        $allowedSubcategories = $this->getAllowedSubcategories();
        $allowedParents = $this->getAllowedParentCategories();

        // Find all parent category IDs of the matched leaf categories
        // (needed to locate roots that have grandchildren matching)
        $midParentIds = Category::whereIn('id', $categoryIds)
            ->whereNotNull('parent_id')
            ->pluck('parent_id')
            ->unique()
            ->toArray();

        // All IDs to look for at any level under a root
        $allSearchIds = array_unique(array_merge($categoryIds, $midParentIds));

        $categories = Category::whereNull('parent_id')
            ->whereIn('name', $allowedParents)
            ->where(function ($query) use ($allSearchIds) {
                $query->whereIn('id', $allSearchIds)
                    ->orWhereHas('children', function ($q) use ($allSearchIds) {
                        $q->whereIn('id', $allSearchIds);
                    });
            })
            ->orderBy('order')
            ->with(['children.children']) // load 3 levels
            ->get();

        $this->categories = $categories->filter(function($parent) use ($allowedSubcategories, $categoryIds) {
            $flatChildren = $this->buildFlatChildrenForParent($parent, $allowedSubcategories, $categoryIds);
            $parent->setRelation('children', $flatChildren);
            return $flatChildren->count() > 0;
        })->values();
    }

    public function selectCategory($categoryId)
    {
        $category = Category::with(['children.children'])->find($categoryId);
        if ($category) {
            $allowedSubcategories = $this->getAllowedSubcategories();
            $validIds = $this->compatibleCategoryIds ?? [];
            // Build flat list of leaf subcategories (handles 3-level hierarchy)
            $flatChildren = $this->buildFlatChildrenForParent($category, $allowedSubcategories, $validIds);
            $category->setRelation('children', $flatChildren);
        }
        
        $this->selectedCategory = $category;
        $this->viewState = 'subcategories';
        $this->selectedSubcategory = null;
        $this->savePersistedSearchState();
    }

    public function selectSubcategory($subcategoryId)
    {
        $this->selectedSubcategory = Category::find($subcategoryId);
        $this->viewState = 'products_list';
        $this->searchContext = [
            'type' => 'category',
            'category_id' => $subcategoryId
        ];
        $this->resetPage();
        $this->availableBrands = [];
        $this->savePersistedSearchState();
    }

    public function selectBrandForProducts($brandName)
    {
        $this->currentBrand = $brandName;
        $this->viewState = 'products_list';
        $this->searchContext['brand'] = $brandName;
        $this->resetPage();
        $this->savePersistedSearchState();
    }

    public function goBack()
    {
        if ($this->viewState === 'products_list' && $this->selectedCategory) {
            // Came from a subcategory selection, go back to subcategories list
            $this->viewState = 'subcategories';
            $this->selectedSubcategory = null;
            $this->searchContext = [];
            $this->resetPage();
        } elseif ($this->viewState === 'products_list' || $this->viewState === 'subcategories') {
            // Go back to the vehicle categories view
            $this->viewState = ($this->vehicle || $this->selectedEngineObj) ? 'vehicle_found' : 'initial';
            $this->selectedSubcategory = null;
            $this->selectedCategory = null;
            $this->searchContext = [];
            $this->resetPage();
        } elseif ($this->viewState === 'repair_summary') {
            $this->viewState = ($this->vehicle || $this->selectedEngineObj) ? 'vehicle_found' : 'initial';
        } elseif ($this->viewState === 'vehicle_found' || $this->viewState === 'oem_found') {
            $this->resetStates();
        }
        $this->savePersistedSearchState();
    }

    // --- REPAIR LIST (CART) LOGIC ---

    public function addToRepair($productId)
    {
        $product = Product::with(['provider', 'oversizes'])->find($productId);
        if (!$product)
            return;

        // If product has multiple active oversizes, do nothing — blade will use addToRepairWithOversize
        // but if only 1 oversize exists, pick it automatically
        $oversizes = $product->oversizes->where('is_active', true);
        if ($oversizes->count() === 1) {
            $variant = $oversizes->first();
            $this->addToRepairWithOversize($productId, $variant->oversize);
            return;
        }

        // Fallback: no oversizes table data (legacy product), use product directly
        if ($oversizes->isEmpty()) {
            $cartKey = (string) $productId;
            if (isset($this->repairList[$cartKey])) {
                $this->repairList[$cartKey]['qty']++;
            } else {
                $this->repairList[$cartKey] = [
                    'qty' => 1,
                    'product' => $product->toArray() + ['oversize' => $product->oversize ?? 'STD'],
                ];
            }
            $this->lastAddedProduct = $product;
            $this->showAddedPopup = true;
            $this->trackProductView($productId);
            $this->dispatch('notify', ['type' => 'success', 'message' => '¡Repuesto agregado!']);
            $this->savePersistedSearchState();
        }
        // If multiple oversizes: do nothing — user must pick from the blade dropdown
    }

    /**
     * Add a specific oversize variant of a product to the repair list.
     * Cart key: "{productId}_{oversize}" to allow multiple variants in cart.
     */
    public function addToRepairWithOversize($productId, $oversize)
    {
        $product = Product::with('provider')->find($productId);
        if (!$product) return;

        $variant = ProductOversize::where('product_id', $productId)
            ->where('oversize', $oversize)
            ->where('is_active', true)
            ->first();

        if (!$variant) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => "No hay stock disponible para la medida {$oversize}."]);
            return;
        }

        // Build a product snapshot with the selected oversize injected
        $productArr = $product->toArray();
        $productArr['oversize'] = $oversize;
        $productArr['price']    = $variant->price;   // override with variant price
        $productArr['stock']    = $variant->stock;

        $cartKey = $productId . '_' . $oversize;

        if (isset($this->repairList[$cartKey])) {
            $this->repairList[$cartKey]['qty']++;
        } else {
            $this->repairList[$cartKey] = ['qty' => 1, 'product' => $productArr];
        }

        $this->lastAddedProduct = $product;
        $this->showAddedPopup = true;
        $this->trackProductView($productId);
        $this->dispatch('notify', ['type' => 'success', 'message' => "Medida {$oversize} agregada a la reparación."]);
        $this->savePersistedSearchState();
    }

    public function closeAddedPopup()
    {
        $this->showAddedPopup = false;
        $this->lastAddedProduct = null;
    }

    public function removeFromRepair($productId)
    {
        unset($this->repairList[$productId]);
        $this->dispatch('notify', ['type' => 'info', 'message' => 'Repuesto eliminado']);
        $this->savePersistedSearchState();
    }

    public function updateQuantity($productId, $qty)
    {
        if (isset($this->repairList[$productId])) {
            $qty = max(1, min(10, $qty));
            $this->repairList[$productId]['qty'] = $qty;
            $this->savePersistedSearchState();
        }
    }

    public function openIdentityPopup()
    {
        if (empty($this->repairList)) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'La lista de reparación está vacía.']);
            return;
        }
        $this->showIdentityPopup = true;
    }

    public function confirmIdentity()
    {
        $this->validate(
            ['identityInput' => 'required|min:8'],
            ['identityInput.required' => 'El DNI o RUC es obligatorio.', 'identityInput.min' => 'Ingrese un documento válido.']
        );
        $this->showIdentityPopup = false;
        $this->repairList = [];
        $this->confirmedOverrides = [];
        $this->showSuccessPopup = true;
    }

    public function openDetails($productId)
    {
        $product = Product::with(['provider', 'category'])->find($productId);
        $this->selectedProductForDetails = $product;
        $this->showDetailsModal = true;
        $this->trackProductView($productId);

        // Load displacement values from compatible engines
        $this->selectedProductDisplacements = [];
        if ($product) {
            $engineIds = is_array($product->compatible_engine_ids) ? $product->compatible_engine_ids : [];
            $engineCodes = is_array($product->compatible_engines) ? $product->compatible_engines : [];

            $query = Engine::whereNotNull('displacement')->where('displacement', '!=', '');
            if (!empty($engineIds)) {
                $query->whereIn('id', $engineIds);
            } elseif (!empty($engineCodes)) {
                $query->whereIn('engine_code', $engineCodes);
            } else {
                $query->whereRaw('0=1'); // no match
            }

            $this->selectedProductDisplacements = $query
                ->pluck('displacement')
                ->filter()
                ->unique()
                ->values()
                ->map(fn($d) => trim(preg_replace('/(cc|c\.c\.|c\.c)/i', '', $d)))
                ->toArray();
        }
    }

    public function closeDetails()
    {
        $this->showDetailsModal = false;
        $this->selectedProductForDetails = null;
        $this->selectedProductDisplacements = [];
    }

    public function closeSuccessPopup()
    {
        $this->showSuccessPopup = false;
        $this->resetStates();
    }

    // ── Delivery address modal methods ──────────────────────────────────

    public function openDeliveryModal()
    {
        if (empty($this->repairList)) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Tu lista está vacía.']);
            return;
        }

        // Cargar direcciones guardadas del usuario autenticado
        if (auth()->check()) {
            $this->savedAddresses = auth()->user()->getSavedAddresses();
        }

        $this->showDeliveryModal = true;
        $this->estimatedDeliveryCost = 0;
    }

    /**
     * Carga una dirección guardada (por índice) en los campos del modal.
     */
    public function loadSavedAddress(int $index): void
    {
        $addr = $this->savedAddresses[$index] ?? null;
        if (!$addr)
            return;

        $this->deliveryType = $addr['type'];

        if ($addr['type'] === 'lima') {
            $this->deliveryAddress = $addr['address'] ?? '';
            $this->deliveryDistrict = $addr['district'] ?? '';
            $this->estimatedDeliveryCost = $this->costForDistrict($this->deliveryDistrict);
            // Limpiar campos de provincia
            $this->deliveryAgency = '';
            $this->deliveryCity = '';
        } else {
            $this->deliveryAgency = $addr['agency'] ?? '';
            $this->deliveryCity = $addr['city'] ?? '';
            $this->recipientName = $addr['recipient_name'] ?? '';
            $this->recipientDni = $addr['recipient_dni'] ?? '';
            $this->recipientPhone = $addr['recipient_phone'] ?? '';
            $this->recipientAddress = $addr['recipient_address'] ?? '';
            $this->estimatedDeliveryCost = $this->shippingAgencies[$this->deliveryAgency] ?? 15;
            // Limpiar campos de Lima
            $this->deliveryAddress = '';
            $this->deliveryDistrict = '';
        }
    }

    public function updatedDeliveryDistrict($value)
    {
        $this->estimatedDeliveryCost = $this->costForDistrict($value);
    }

    public function updatedDeliveryAgency($value)
    {
        $this->estimatedDeliveryCost = $this->shippingAgencies[$value] ?? 15;
    }

    private function costForDistrict(string $district): int
    {
        foreach ($this->limaZones as $zone) {
            if (in_array($district, $zone['districts'])) {
                return $zone['cost'];
            }
        }
        return 22; // default lejana
    }

    public function confirmDeliveryAndProceed()
    {
        if ($this->deliveryType === 'lima') {
            $this->validate([
                'deliveryAddress' => 'required|min:5',
                'deliveryDistrict' => 'required',
            ], [
                'deliveryAddress.required' => 'Ingresa tu dirección.',
                'deliveryDistrict.required' => 'Selecciona el distrito.',
            ]);
        } else {
            $this->validate([
                'deliveryAgency' => 'required',
                'deliveryCity' => 'required|min:3',
                'recipientName' => 'required|min:4',
                'recipientDni' => 'required|min:8|max:11',
                'recipientPhone' => 'required|min:9',
            ], [
                'deliveryAgency.required' => 'Selecciona una agencia.',
                'deliveryCity.required' => 'Ingresa la ciudad de destino.',
                'recipientName.required' => 'Ingresa el nombre del destinatario.',
                'recipientDni.required' => 'Ingresa el DNI o RUC del destinatario.',
                'recipientPhone.required' => 'Ingresa el celular del destinatario.',
            ]);

            // Generate pickup password: RF-XXXX
            $this->pickupPassword = 'RF-' . strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 1)) . rand(100, 999);
        }

        // ★ Guardar dirección en el perfil del usuario (solo usuarios autenticados)
        if (auth()->check()) {
            if ($this->deliveryType === 'lima') {
                auth()->user()->saveAddress([
                    'type' => 'lima',
                    'address' => $this->deliveryAddress,
                    'district' => $this->deliveryDistrict,
                ]);
            } else {
                auth()->user()->saveAddress([
                    'type' => 'province',
                    'agency' => $this->deliveryAgency,
                    'city' => $this->deliveryCity,
                    'recipient_name' => $this->recipientName,
                    'recipient_dni' => $this->recipientDni,
                    'recipient_phone' => $this->recipientPhone,
                    'recipient_address' => $this->recipientAddress,
                ]);
            }
        }

        $this->showDeliveryModal = false;
        $this->confirmAvailability();
    }

    public function confirmAvailability()
    {
        if (empty($this->repairList)) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Tu lista está vacía.']);
            return;
        }
        if ($this->isVerified || session()->has('user_phone')) {
            $this->processFinalOrder();
        } else {
            $this->initiateLeadCapture(['type' => 'final_confirmation']);
        }
    }

    public function processFinalOrder()
    {
        $this->isSearching = true;
        $this->zbotSearchStartTime = now();
        $this->showLeadForm = false;
        $this->triedProviderIds = [];
        $this->searchingForAlternatives = false;

        \Illuminate\Support\Facades\Log::info("Iniciando processFinalOrder. Items: " . count($this->repairList));

        // Generate 4-digit secret key if provincia
        $claveSecreta = null;
        if ($this->deliveryType === 'province') {
            $claveSecreta = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        }

        // 1. Create the Pedido record
        // Status 'por_confirmar': el pedido existe pero aún no hay proveedor confirmado
        // ni cobro real. Pasa a 'pagado' solo cuando Culqi confirme el webhook.
        $pedido = Pedido::create([
            'customer_id' => auth()->id(),
            'tipo_envio' => $this->deliveryType === 'lima' ? 'Lima' : 'Provincia',
            'distrito' => $this->deliveryDistrict ?: $this->deliveryCity,
            'direccion' => $this->deliveryAddress ?: $this->recipientAddress,
            'telefono_contacto' => $this->recipientPhone ?: $this->phone,
            'metodo_pago' => 'Culqi',
            'clave_secreta' => $claveSecreta,
            'costo_envio' => $this->estimatedDeliveryCost,
            'status' => 'por_confirmar',
        ]);

        $this->lastOrderId = $pedido->id;
        session()->put('current_pedido_id', $pedido->id);

        // Group by provider and SEND WhatsApp messages
        $grouped = collect($this->repairList)->groupBy('product.provider_id');
        $ws = new WhatsAppService();

        foreach ($grouped as $providerId => $items) {
            $provider = \App\Models\Provider::find($providerId);

            if (!$provider) {
                \Illuminate\Support\Facades\Log::warning("Proveedor no encontrado: {$providerId}");
                continue;
            }

            // ── Direct provider: all products have pre-set prices, skip WhatsApp ──
            $allHavePrice = $items->every(fn($i) => floatval($i['product']['price'] ?? 0) > 0);
            if (!$provider->requires_zbot && $allHavePrice) {
                \Illuminate\Support\Facades\Log::info("Proveedor {$providerId} es directo (sin ZettaBot). Confirmación instantánea.");
                // No ZbotQuery needed — getConfirmedItems() handles direct providers automatically.
                continue;
            }

            if (!$provider->whatsapp_number) {
                \Illuminate\Support\Facades\Log::warning("Proveedor {$providerId} sin WhatsApp.");
                continue;
            }

            \Illuminate\Support\Facades\Log::info("Enviando pedido (vía texto) a Proveedor {$providerId} ({$provider->whatsapp_number})");

            $token = (string) \Illuminate\Support\Str::uuid();
            $link = url("/proveedor/confirmar/{$token}");

            $orderMsg = $ws->formatZbotOrder($items, $pedido->id); // Use real pedido ID
            $menuMsg = "{$orderMsg}\n\n*🔗 Confirma tu stock y precios aquí:*\n{$link}\n\nO si prefieres, responde por WhatsApp:\n1️⃣ - ✅ Sí tengo todo el stock\n2️⃣ - ❌ No tengo stock";

            $res = $ws->sendMessage($provider->whatsapp_number, $menuMsg);

            if ($res) {
                \Illuminate\Support\Facades\Log::info("Mensaje enviado exitosamente. ID: " . ($res['idMessage'] ?? 'N/A'));

                $cleanNumber = preg_replace('/[^0-9]/', '', $provider->whatsapp_number);

                ZbotQuery::create([
                    'pedido_id' => $pedido->id,
                    'provider_id' => $providerId,
                    'chat_id' => $cleanNumber . '@c.us',
                    'message_id' => $res['idMessage'] ?? null,
                    'status' => 'waiting',
                    'current_step' => 'initial',
                    'items_json' => $items->toArray(),
                    'expires_at' => now()->addMinutes(9),
                    'reminders_sent' => 0,
                    'confirmation_token' => $token,
                ]);
            } else {
                \Illuminate\Support\Facades\Log::error("Fallo al enviar mensaje a Proveedor {$providerId}");
            }
        }

        // If province order: store pickup password and secret key in session
        if ($this->deliveryType === 'province') {
            session()->put('pickup_password', $this->pickupPassword);
            session()->put('secret_key', $claveSecreta);
            session()->put('recipient_phone', $this->recipientPhone);
            session()->put('recipient_name', $this->recipientName);
        }

        $this->savePersistedSearchState();
    }

    public function cancelSearch()
    {
        $this->isSearching = false;

        $pedidoId = session()->get('current_pedido_id') ?? $this->lastOrderId;
        $startTime = $this->zbotSearchStartTime ?? now()->subMinutes(15);

        // Find active Zbot queries for this search
        $queriesQuery = ZbotQuery::whereIn('status', ['waiting', 'confirmed']);
        if ($pedidoId) {
            $queriesQuery->where(function ($q) use ($pedidoId, $startTime) {
                $q->where('pedido_id', $pedidoId)
                  ->orWhere(function ($sq) use ($startTime) {
                      $sq->whereNull('pedido_id')
                         ->where('created_at', '>=', $startTime);
                  });
            });
        } else {
            $queriesQuery->where('created_at', '>=', $startTime);
        }

        $activeQueries = $queriesQuery->get();

        if ($activeQueries->isNotEmpty()) {
            $ws = new WhatsAppService();
            foreach ($activeQueries as $q) {
                // Update status to denied/cancelled so we don't process further responses
                $q->update(['status' => 'denied']);
                
                // Notify the provider
                $ws->sendSearchCancelled($q->chat_id, $q->pedido_id);
            }
        }

        // Cancel the Pedido if it exists and is still in 'por_confirmar' or 'pendiente' status
        if ($pedidoId) {
            $pedido = Pedido::find($pedidoId);
            if ($pedido && in_array($pedido->status, ['por_confirmar', 'pendiente'])) {
                // Capture items before cancelling to register consulted products
                $confirmedQueries = ZbotQuery::where('pedido_id', $pedido->id)
                    ->where('status', 'confirmed')
                    ->get()
                    ->keyBy('provider_id');

                foreach ($this->repairList as $pid => $data) {
                    $providerId = $data['product']['provider_id'];
                    $query = $confirmedQueries->get($providerId);

                    $qtyConfirmed = $data['qty'];
                    $precioUnit = 0;

                    if ($query) {
                        if (!empty($query->items_confirmed_json) && is_array($query->items_confirmed_json)) {
                            $found = false;
                            foreach ($query->items_confirmed_json as $cItem) {
                                if (($cItem['oem_code'] ?? '') === ($data['product']['supplier_code'] ?? '')) {
                                    $qtyConfirmed = $cItem['qty_confirmed'] ?? 0;
                                    $precioUnit = $cItem['price_unit'] ?? 0;
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) {
                                $qtyConfirmed = 0;
                            }
                        } else {
                            $normalizedText = str_replace(',', '.', $query->price);
                            $cleanText = preg_replace('/[^0-9. ]/', ' ', $normalizedText);
                            $pricesFound = array_map('floatval', preg_split('/\s+/', trim($cleanText)));
                            
                            $itemsRequested = $query->items_json ?? [];
                            $itemIndex = 0;
                            foreach ($itemsRequested as $index => $reqItem) {
                                if (($reqItem['product']['supplier_code'] ?? '') === ($data['product']['supplier_code'] ?? '')) {
                                    $itemIndex = $index;
                                    break;
                                }
                            }
                            $precioUnit = $pricesFound[$itemIndex] ?? end($pricesFound) ?? 0;
                        }
                    }

                    $provider = \App\Models\Provider::find($providerId);
                    $requiresZbot = $provider ? $provider->requires_zbot : true;
                    $hasPrice = isset($data['product']['price']) && floatval($data['product']['price']) > 0;

                    if ($hasPrice && !$requiresZbot) {
                        $precioUnitWithMarkup = round(floatval($data['product']['price']) * 1.18, 2);
                    } else {
                        if ($precioUnit <= 0) {
                            $precioUnit = $data['product']['price'] ?? 0;
                        }
                        $precioUnitWithMarkup = round($precioUnit * 1.10 * 1.18, 2);
                    }

                    PedidoItem::create([
                        'pedido_id' => $pedido->id,
                        'product_id' => $data['product']['id'],
                        'oversize' => $data['product']['oversize'] ?? null,
                        'provider_id' => $providerId,
                        'cantidad' => $qtyConfirmed > 0 ? $qtyConfirmed : $data['qty'],
                        'precio_unitario' => $precioUnitWithMarkup,
                        'subtotal' => $precioUnitWithMarkup * ($qtyConfirmed > 0 ? $qtyConfirmed : $data['qty']),
                    ]);
                }

                $pedido->update([
                    'status' => 'cancelado',
                    'costo_envio' => 0,
                    'subtotal' => 0,
                    'total' => 0,
                    'cancellation_reason' => 'Cancelado por el cliente durante la búsqueda'
                ]);
            }
        }

        $this->dispatch('notify', ['type' => 'info', 'message' => 'Búsqueda cancelada correctamente.']);
    }


    public function render()
    {
        $products = collect();
        if ($this->viewState === 'products_list') {
            $products = $this->getFilteredProductsQuery()->paginate(12);
        }

        $allowedSubcategories = $this->getAllowedSubcategories();
        $allowedParents = $this->getAllowedParentCategories();
        $isVehicleSearch = ($this->vehicle || $this->selectedEngineObj);
        $validIds = $this->compatibleCategoryIds ?? [];

        // Always do a fresh DB query for categories on render() to avoid
        // Livewire serialization losing eager-loaded children relations.
        // We also flatten 3-level hierarchy (grandchildren appear alongside direct children).
        $dbCategories = Category::whereNull('parent_id')
            ->whereIn('name', $allowedParents)
            ->orderBy('order')
            ->with(['children.children'])
            ->get();

        $filteredCategories = $dbCategories->filter(function($parent) use ($allowedSubcategories, $isVehicleSearch, $validIds) {
            $flatChildren = $this->buildFlatChildrenForParent(
                $parent,
                $allowedSubcategories,
                ($isVehicleSearch && !empty($validIds)) ? $validIds : []
            );
            $parent->setRelation('children', $flatChildren);
            return $flatChildren->count() > 0;
        })->values();

        // For selectedCategory: fresh query to get correct children with vehicle filter
        if ($this->selectedCategory) {
            $catId = is_array($this->selectedCategory)
                ? ($this->selectedCategory['id'] ?? null)
                : ($this->selectedCategory->id ?? null);

            $selectedCat = Category::with(['children.children'])->find($catId);
            if ($selectedCat) {
                $flatChildren = $this->buildFlatChildrenForParent(
                    $selectedCat,
                    $allowedSubcategories,
                    ($isVehicleSearch && !empty($validIds)) ? $validIds : []
                );
                $selectedCat->setRelation('children', $flatChildren);
                $this->selectedCategory = $selectedCat;
            }
        }

        return view('livewire.search-components.main-search', [
            'products' => $products,
            'filteredCategories' => $filteredCategories
        ]);
    }

    protected function getFilteredProductsQuery()
    {
        $query = Product::active()->with(['provider', 'oversizes']);

        if (empty($this->searchContext) || !isset($this->searchContext['type'])) {
            return $query->whereRaw('1=0'); // Return zero results if no search context
        }

        if ($this->searchContext['type'] === 'category') {
            $query->where('category_id', $this->searchContext['category_id']);

            // Re-apply vehicle/engine filters for category search
            if ($this->vehicle) {
                $model = strtoupper($this->vehicle->model);
                $engineCode = $this->vehicle->engine_code ? strtoupper($this->vehicle->engine_code) : null;
                $query->where(function ($q) use ($model, $engineCode) {
                    if ($engineCode) {
                        $q->where('compatible_engines', 'LIKE', '%"' . $engineCode . '"%');
                    }
                    if ($model) {
                        $q->orWhere('compatible_vehicles', 'LIKE', '%' . $model . '%');
                    }
                });
            } elseif ($this->selectedEngineObj) {
                $model = strtoupper($this->selectedEngineObj['model']);
                $engineCode = $this->selectedEngineObj['engine_code'] ? strtoupper($this->selectedEngineObj['engine_code']) : null;
                $query->where(function ($q) use ($model, $engineCode) {
                    if ($engineCode) {
                        $q->where('compatible_engines', 'LIKE', '%"' . $engineCode . '"%');
                    }
                    if ($model) {
                        $q->orWhere('compatible_vehicles', 'LIKE', '%' . $model . '%');
                    }
                });
            }
        } elseif ($this->searchContext['type'] === 'manual') {
            $engineCode = $this->searchContext['engine_code'];
            $modelName = $this->searchContext['model_name'];

            if ($engineCode && $modelName) {
                $query->where('compatible_engines', 'LIKE', '%"' . $engineCode . '"%')
                    ->where('compatible_vehicles', 'LIKE', '%' . $modelName . '%');
            } elseif ($engineCode) {
                $query->where('compatible_engines', 'LIKE', '%"' . $engineCode . '"%');
            } elseif ($modelName) {
                $query->where('compatible_vehicles', 'LIKE', '%' . $modelName . '%');
            }
        } elseif ($this->searchContext['type'] === 'oem') {
            $term = $this->searchContext['term'];
            $query->where(function ($q) use ($term) {
                $q->where('supplier_code', 'LIKE', '%' . $term . '%')
                    ->orWhere('oem_code', 'LIKE', '%' . $term . '%')
                    ->orWhere('brand', 'LIKE', '%' . $term . '%')
                    ->orWhere('name', 'LIKE', '%' . $term . '%')
                    ->orWhereJsonContains('additional_oem_codes', $term);
            });
        }

        if (!empty($this->searchContext['brand'])) {
            $query->where('brand', $this->searchContext['brand']);
        }

        return $query->orderBy('supplier_code')
            ->orderByRaw("FIELD(oversize,'STD','025','050','075','100')");
    }

    public function processCulqiPayment($token, $email)
    {
        $confirmedTotal = $this->getConfirmedRepuestosTotal();
        $pedidoId = session()->get('current_pedido_id') ?? $this->lastOrderId;

        if (!$pedidoId) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No se encontró el pedido actual.']);
            return;
        }

        $pedido = Pedido::find($pedidoId);
        if (!$pedido) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Pedido no encontrado en el sistema.']);
            return;
        }

        // Amount in cents (Culqi requires integer)
        $finalTotal = $confirmedTotal + $pedido->costo_envio;
        $amountInCents = (int) round($finalTotal * 100);

        $culqiService = new \App\Services\CulqiService();
        $result = $culqiService->createCharge($token, $amountInCents, $email, $pedido->id);

        if ($result['success']) {
            $charge = $result['charge'];
            $chargeId = $charge['id'] ?? null;

            // 1. Update the Pedido with final totals, payment method, charge ID and status 'pagado'
            $pedido->update([
                'subtotal' => $confirmedTotal,
                'total' => $finalTotal,
                'metodo_pago' => 'Culqi',
                'culqi_charge_id' => $chargeId,
                'payment_confirmed_at' => now(),
                'status' => 'pagado',
            ]);

            // Registrar los items confirmados con precio real del proveedor
            $startTime = $this->zbotSearchStartTime ?? now()->subMinutes(15);
            $confirmedQueries = ZbotQuery::where('pedido_id', $pedido->id)
                ->where('status', 'confirmed')
                ->get()
                ->keyBy('provider_id');

            foreach ($this->repairList as $pid => $data) {
                $providerId = $data['product']['provider_id'];
                $query = $confirmedQueries->get($providerId);

                // === APPLY CUSTOMER OVERRIDES ===
                $override = $this->confirmedOverrides[$pid] ?? null;

                // Skip items the customer explicitly removed
                if ($override && ($override['excluded'] ?? false)) {
                    continue;
                }

                $qtyConfirmed = $data['qty'];
                $precioUnit = 0;

                if ($query) {
                    if (!empty($query->items_confirmed_json) && is_array($query->items_confirmed_json)) {
                        $found = false;
                        foreach ($query->items_confirmed_json as $cItem) {
                            if (($cItem['oem_code'] ?? '') === ($data['product']['supplier_code'] ?? '')) {
                                $qtyConfirmed = $cItem['qty_confirmed'] ?? 0;
                                $precioUnit = $cItem['price_unit'] ?? 0;
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $qtyConfirmed = 0;
                        }
                    } else {
                        $normalizedText = str_replace(',', '.', $query->price);
                        $cleanText = preg_replace('/[^0-9. ]/', ' ', $normalizedText);
                        $pricesFound = array_map('floatval', preg_split('/\s+/', trim($cleanText)));
                        
                        $itemsRequested = $query->items_json ?? [];
                        $itemIndex = 0;
                        foreach ($itemsRequested as $index => $reqItem) {
                            if (($reqItem['product']['supplier_code'] ?? '') === ($data['product']['supplier_code'] ?? '')) {
                                $itemIndex = $index;
                                break;
                            }
                        }
                        $precioUnit = $pricesFound[$itemIndex] ?? end($pricesFound) ?? 0;
                    }
                }

                // Apply customer quantity override (if they reduced qty)
                if ($override && isset($override['qty']) && (int)$override['qty'] > 0) {
                    $qtyConfirmed = min((int)$override['qty'], $qtyConfirmed);
                }

                $provider = \App\Models\Provider::find($providerId);
                $requiresZbot = $provider ? $provider->requires_zbot : true;
                $hasPrice = isset($data['product']['price']) && floatval($data['product']['price']) > 0;

                if ($hasPrice && !$requiresZbot) {
                    $precioUnitWithMarkup = round(floatval($data['product']['price']) * 1.18, 2);
                } else {
                    if ($precioUnit <= 0) {
                        $precioUnit = $data['product']['price'] ?? 0;
                    }
                    $precioUnitWithMarkup = round($precioUnit * 1.10 * 1.18, 2);
                }

                if ($qtyConfirmed > 0) {
                    PedidoItem::create([
                        'pedido_id' => $pedido->id,
                        'product_id' => $data['product']['id'],
                        'oversize' => $data['product']['oversize'] ?? null,
                        'provider_id' => $providerId,
                        'cantidad' => $qtyConfirmed,
                        'precio_unitario' => $precioUnitWithMarkup,
                        'subtotal' => $precioUnitWithMarkup * $qtyConfirmed,
                    ]);
                }
            }

            // 2. Send "PAGO CONFIRMADO" to all providers that confirmed stock
            $ws = new WhatsAppService();
            $queries = ZbotQuery::where('status', 'confirmed')
                ->where('pedido_id', $pedido->id)
                ->get();

            foreach ($queries as $q) {
                $ws->sendPaymentConfirmation($q->chat_id);
            }

            // 3. Clear state
            $this->repairList = [];
            $this->confirmedOverrides = [];
            $this->isSearching = false;
            $this->viewState = 'initial';
            $this->oemSearch = '';
            $this->plateSearch = '';
            $this->vehicle = null;
            $this->selectedCategory = null;
            $this->selectedSubcategory = null;
            $this->selectedBrand = '';
            $this->selectedModel = '';
            $this->selectedEngine = '';
            $this->selectedEngineObj = null;
            $this->savePersistedSearchState();

            $this->dispatch('payment-finished');
            $this->dispatch('notify', ['type' => 'success', 'message' => '¡Pago exitoso! Su pedido ha sido procesado correctamente.']);
            $this->js("window.dispatchEvent(new CustomEvent('payment-finished'));");
        } else {
            $this->dispatch('payment-finished');
            $this->js("window.dispatchEvent(new CustomEvent('payment-finished'));");
            \Illuminate\Support\Facades\Log::error('Pago con Culqi fallido', ['pedido_id' => $pedido->id, 'error' => $result['message']]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Error de pago: ' . $result['message']]);
        }
    }

    public function completeProcess()
    {
        $confirmedTotal = $this->getConfirmedRepuestosTotal();
        $pedidoId = session()->get('current_pedido_id');

        if ($confirmedTotal <= 0) {
            // ENGAGEMENT: No stock found, redirect to "Concierge" help
            $phone = "51926639534"; // Número de soporte/vendedor real
            $items = collect($this->repairList)->map(fn($i) => "• " . $i['product']['name'])->join("\n");
            $msg = urlencode("Hola RepuestoFijo 👋, ZettaBot no encontró stock de estos productos:\n{$items}\n¿Podrían ayudarme a ubicarlos manualmente?");

            if ($pedidoId) {
                $pedido = Pedido::find($pedidoId);
                if ($pedido)
                    $pedido->update(['status' => 'cancelado']);
            }

            $this->dispatch('notify', ['type' => 'info', 'message' => 'Redirigiendo a un asesor especializado...']);
            $this->dispatch('open-url', ['url' => "https://wa.me/{$phone}?text={$msg}"]);
            return;
        }

        // 1. Update the Pedido with final totals and items
        // NOTA: el status NO pasa a 'pagado' aquí.
        // Pasa a 'por_confirmar' y esperará el webhook de Culqi para marcar 'pagado'.
        // Mientras no haya dominio, el admin puede marcarlo manualmente desde el panel.
        if ($pedidoId) {
            $pedido = Pedido::find($pedidoId);
            if ($pedido) {
                // Calcular subtotal real sumando precios confirmados por proveedores
                $startTime = $this->zbotSearchStartTime ?? now()->subMinutes(15);
                $confirmedQueries = ZbotQuery::where('pedido_id', $pedidoId)
                    ->where('status', 'confirmed')
                    ->get()
                    ->keyBy('provider_id');

                $pedido->update([
                    'subtotal' => $confirmedTotal,
                    'total' => $confirmedTotal + $pedido->costo_envio,
                    'status' => 'por_confirmar', // Culqi webhook cambiará esto a 'pagado'
                ]);

                // Registrar los items confirmados con precio real del proveedor
                foreach ($this->repairList as $pid => $data) {
                    $providerId = $data['product']['provider_id'];
                    $query = $confirmedQueries->get($providerId);

                    // === APPLY CUSTOMER OVERRIDES ===
                    $override = $this->confirmedOverrides[$pid] ?? null;

                    // Skip items the customer explicitly removed
                    if ($override && ($override['excluded'] ?? false)) {
                        continue;
                    }

                    $qtyConfirmed = $data['qty'];
                    $precioUnit = 0;

                    if ($query) {
                        if (!empty($query->items_confirmed_json) && is_array($query->items_confirmed_json)) {
                            $found = false;
                            foreach ($query->items_confirmed_json as $cItem) {
                                if (($cItem['oem_code'] ?? '') === ($data['product']['supplier_code'] ?? '')) {
                                    $qtyConfirmed = $cItem['qty_confirmed'] ?? 0;
                                    $precioUnit = $cItem['price_unit'] ?? 0;
                                    $found = true;
                                    break;
                                }
                            }
                            if (!$found) {
                                $qtyConfirmed = 0;
                            }
                        } else {
                            $normalizedText = str_replace(',', '.', $query->price);
                            $cleanText = preg_replace('/[^0-9. ]/', ' ', $normalizedText);
                            $pricesFound = array_map('floatval', preg_split('/\s+/', trim($cleanText)));
                            
                            $itemsRequested = $query->items_json ?? [];
                            $itemIndex = 0;
                            foreach ($itemsRequested as $index => $reqItem) {
                                if (($reqItem['product']['supplier_code'] ?? '') === ($data['product']['supplier_code'] ?? '')) {
                                    $itemIndex = $index;
                                    break;
                                }
                            }
                            $precioUnit = $pricesFound[$itemIndex] ?? end($pricesFound) ?? 0;
                        }
                    }

                    // Apply customer quantity override (if they reduced qty)
                    if ($override && isset($override['qty']) && (int)$override['qty'] > 0) {
                        $qtyConfirmed = min((int)$override['qty'], $qtyConfirmed);
                    }

                    $provider = \App\Models\Provider::find($providerId);
                    $requiresZbot = $provider ? $provider->requires_zbot : true;
                    $hasPrice = isset($data['product']['price']) && floatval($data['product']['price']) > 0;

                    if ($hasPrice && !$requiresZbot) {
                        $precioUnitWithMarkup = round(floatval($data['product']['price']) * 1.18, 2);
                    } else {
                        if ($precioUnit <= 0) {
                            $precioUnit = $data['product']['price'] ?? 0;
                        }
                        $precioUnitWithMarkup = round($precioUnit * 1.10 * 1.18, 2);
                    }

                    if ($qtyConfirmed > 0) {
                        PedidoItem::create([
                            'pedido_id' => $pedido->id,
                            'product_id' => $data['product']['id'],
                            'oversize' => $data['product']['oversize'] ?? null,
                            'provider_id' => $providerId,
                            'cantidad' => $qtyConfirmed,
                            'precio_unitario' => $precioUnitWithMarkup,
                            'subtotal' => $precioUnitWithMarkup * $qtyConfirmed,
                        ]);
                    }
                }
            }
        }

        // 2. Send "PAGO CONFIRMADO" to all providers that confirmed stock
        $ws = new WhatsAppService();
        $startTime = $this->zbotSearchStartTime ?? now()->subMinutes(15);

        $queries = ZbotQuery::where('status', 'confirmed')
            ->where('created_at', '>=', $startTime)
            ->get();

        foreach ($queries as $q) {
            $ws->sendPaymentConfirmation($q->chat_id);
        }

        // 3. Clear state
        $this->repairList = [];
        $this->confirmedOverrides = [];
        $this->isSearching = false;
        $this->viewState = 'initial';
        $this->oemSearch = '';
        $this->plateSearch = '';
        $this->vehicle = null;
        $this->selectedCategory = null;
        $this->selectedSubcategory = null;
        $this->selectedBrand = '';
        $this->selectedModel = '';
        $this->selectedEngine = '';
        $this->selectedEngineObj = null;
        $this->savePersistedSearchState();
        $this->dispatch('notify', ['type' => 'success', 'message' => '¡Pago confirmado y pedido completado!']);
    }

    public function toggleOrders()
    {
        if (!auth()->check()) {
            $this->saveSearchState(); // preservar repairList al volver del login
            $this->showLoginModal = true;
            return;
        }

        $this->showOrdersDrawer = !$this->showOrdersDrawer;
        if ($this->showOrdersDrawer) {
            $this->drawerSubState = 'menu';
        }
    }

    public function showOrders()
    {
        $this->drawerSubState = 'orders';
    }

    public function showAddresses()
    {
        $this->drawerSubState = 'addresses';
    }

    public function showProfile()
    {
        $this->drawerSubState = 'profile';
    }

    public function showMenu()
    {
        $this->drawerSubState = 'menu';
    }

    public function editAddress($index)
    {
        $addresses = auth()->user()->getSavedAddresses();
        if (isset($addresses[$index])) {
            $this->editingAddressIndex = $index;
            $this->addressEditData = array_merge([
                'type' => 'lima',
                'label' => '',
                'address' => '',
                'district' => '',
                'city' => '',
                'agency' => '',
            ], $addresses[$index]);
            $this->showAddressEditModal = true;
        }
    }

    public function addNewAddress()
    {
        $user = auth()->user();
        if (!$user) return;
        
        $addresses = $user->getSavedAddresses();
        
        if (count($addresses) >= 3) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Máximo 3 direcciones permitidas en el plan actual.']);
            return;
        }

        $this->editingAddressIndex = -1; // -1 indicates new address
        $this->addressEditData = [
            'type' => 'lima',
            'label' => '',
            'address' => '',
            'district' => '',
            'city' => '',
            'agency' => '',
        ];
        $this->showAddressEditModal = true;
    }

    public function deleteAddress($index)
    {
        $user = auth()->user();
        if (!$user) return;
        
        $addresses = $user->getSavedAddresses();
        if (isset($addresses[$index])) {
            unset($addresses[$index]);
            $user->update(['saved_addresses' => array_values($addresses)]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Dirección eliminada']);
        }
    }

    public function saveEditedAddress()
    {
        $user = auth()->user();
        if (!$user) return;

        $addresses = $user->getSavedAddresses();

        if ($this->editingAddressIndex === -1) {
            if (count($addresses) >= 3) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Límite de 3 direcciones alcanzado.']);
                return;
            }
            $addresses[] = $this->addressEditData;
            $msg = 'Dirección agregada correctamente';
        } elseif (isset($addresses[$this->editingAddressIndex])) {
            $addresses[$this->editingAddressIndex] = $this->addressEditData;
            $msg = 'Dirección actualizada correctamente';
        } else {
            return;
        }

        $user->update(['saved_addresses' => array_values($addresses)]);
        $this->showAddressEditModal = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => $msg]);
    }


    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function reportIncident($orderId)
    {
        $this->selectedOrderIdForIncident = $orderId;
        $this->showIncidentModal = true;
        // Optional: keep orders drawer open in background or close it
        // $this->showOrdersDrawer = false; 
    }

    public function sendIncident($type)
    {
        $order = Pedido::find($this->selectedOrderIdForIncident);
        if (!$order) {
            $this->showIncidentModal = false;
            return;
        }

        if ($type === 'not_received') {
            // Logic for "No llegó mi pedido" - System check
            $this->dispatch('notify', [
                'type' => 'info',
                'message' => 'Consultando estado con motorizado para el pedido #' . $order->id
            ]);
        } else {
            // General support via WhatsApp
            $message = "Hola, tengo un problema con mi pedido #{$order->id} de ZettaBot. Tipo: {$type}";
            $url = "https://wa.me/519XXXXXXXX?text=" . urlencode($message);
            $this->dispatch('open-url', ['url' => $url]);
        }

        $this->showIncidentModal = false;
    }

    public function getOrdersProperty()
    {
        if (!auth()->check())
            return collect();
        return Pedido::where('customer_id', auth()->id())
            ->where('status', '!=', 'pendiente')
            ->latest()
            ->limit(10)
            ->get();
    }

    public function closeLoginModal(): void
    {
        $this->showLoginModal = false;
        $this->loginBlockMessage = '';
    }

    public function viewUserOrderDetail($orderId)
    {
        $this->userSelectedOrder = Pedido::with(['items.product', 'items.provider'])->find($orderId);
        $this->showUserOrderDetailModal = true;
    }

    public function closeUserOrderDetail()
    {
        $this->showUserOrderDetailModal = false;
        $this->userSelectedOrder = null;
    }

    public function downloadInvoice($orderId)
    {
        $pedido = Pedido::with(['items.product'])->find($orderId);
        if (!$pedido) return;

        // Generate invoice using BillingService if not already generated
        if (empty($pedido->invoice_url)) {
            $user = auth()->user();
            $billingType = $user->receipt_type ?? 'boleta';
            $customerData = [
                'ruc_dni' => $user->ruc_dni,
                'business_name' => $user->business_name ?: $user->name,
                'email' => $user->email,
            ];

            $billingService = new \App\Services\BillingService();
            $result = $billingService->generateInvoice($pedido, $billingType, $customerData);
            
            if (!$result['success']) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Error al generar comprobante electrónico.']);
                return;
            }
            // Reload order with new billing data
            $pedido->refresh();
            $this->userSelectedOrder = $pedido; // Update modal state immediately
            
            $this->dispatch('notify', ['type' => 'success', 'message' => '¡Comprobante generado con éxito! Haz clic en el botón verde para abrirlo.']);
        } else {
            $this->dispatch('open-url', ['url' => $pedido->invoice_url]);
        }
    }
}
