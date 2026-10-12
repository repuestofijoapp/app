@php
    // Build a keyed map: repairListId (pid) => confirmed item data
    $confirmedItemsRaw = $this->getConfirmedItems();
    $confirmedByPid = [];
    foreach ($confirmedItemsRaw as $ci) {
        if (isset($ci['repair_list_id'])) {
            $confirmedByPid[$ci['repair_list_id']] = $ci;
        }
    }
@endphp
@if(count($confirmedByPid) > 0)
    @php
        $unconfirmedItems = [];
        foreach ($this->repairList as $pid => $rItem) {
            $match = $confirmedByPid[$pid] ?? null;
            if (!$match || ($match['qty'] ?? 0) <= 0) {
                $unconfirmedItems[] = $rItem;
            }
        }
        $totalItems = count($this->repairList);
        $confirmedCount = $totalItems - count($unconfirmedItems);
        $unconfirmedCount = count($unconfirmedItems);
        $hasPartial = $unconfirmedCount > 0;
    @endphp

    {{-- === STATUS BANNER === --}}
    @if($hasPartial)
        <div class="rounded-3 p-3 mb-3 text-start d-flex align-items-start gap-3"
            style="background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%); border: 1.5px solid #f59e0b;">
            <div style="font-size: 26px; line-height:1;">⚠️</div>
            <div>
                <div class="fw-bold" style="color:#92400e; font-size:13px;">Stock parcialmente disponible</div>
                <div class="mt-1" style="color:#a16207; font-size:12px; line-height:1.5;">
                    Encontramos <strong>{{ $confirmedCount }} de {{ $totalItems }}</strong> repuesto(s) en stock.
                    Puedes pagar los que están disponibles ahora y contactarnos para los restantes.
                </div>
            </div>
        </div>
    @else
        <div class="rounded-3 p-3 mb-3 text-start d-flex align-items-start gap-3"
            style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border: 1.5px solid #22c55e;">
            <div style="font-size: 26px; line-height:1;">✅</div>
            <div>
                <div class="fw-bold" style="color:#14532d; font-size:13px;">¡Todo en stock y listo!</div>
                <div class="mt-1" style="color:#166534; font-size:12px; line-height:1.5;">
                    Los <strong>{{ $confirmedCount }}</strong> repuesto(s) solicitados están confirmados y disponibles para entrega.
                </div>
            </div>
        </div>
    @endif

    {{-- === CARRITO EDITABLE POST-CONFIRMACIÓN === --}}
    <div class="rounded-3 border overflow-hidden mb-3" style="border-color:#e5e7eb;">
        <div class="px-3 py-2 d-flex align-items-center justify-content-between"
            style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
            <span class="fw-bold text-dark"
                style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">
                🛒 Tu pedido
            </span>
        </div>
        <div class="px-3 py-2 bg-white">
            @php $totalCalculated = 0; @endphp
            @foreach($this->repairList as $pid => $rItem)
                @php
                    $oem = strtoupper(trim($rItem['product']['supplier_code'] ?? $rItem['product']['oem_code'] ?? ''));
                    $match = $confirmedByPid[$pid] ?? null;

                    // Raw confirmed qty from provider (before user overrides)
                    $rawConfirmedQty = $match ? ($match['qty'] ?? 0) : 0;
                    $requestedQty = $rItem['qty'] ?? 1;
                    // Detect if client has excluded this item using the product ID as key
                    $isExcluded = ($confirmedOverrides[$pid]['excluded'] ?? false);
                    // Effective qty: 0 if excluded, user override if set, otherwise provider qty
                    $effectiveQty = $isExcluded
                        ? 0
                        : (isset($confirmedOverrides[$pid]['qty'])
                            ? (int) $confirmedOverrides[$pid]['qty']
                            : $rawConfirmedQty);
                    $confirmedQty = $rawConfirmedQty; // for badge logic
                    $isFull = $confirmedQty >= $requestedQty;
                    $isPartial = $confirmedQty < $requestedQty && $confirmedQty > 0;
                    $isNone = $confirmedQty == 0;
                    $unitPrice = $match ? $match['price'] : 0;
                    $effectiveSubtotal = $unitPrice * $effectiveQty;
                    $totalCalculated += $effectiveSubtotal;

                    // Oversize label from the cart snapshot (the one the user actually chose)
                    $selectedOversize = $rItem['product']['oversize'] ?? null;
                    if ($selectedOversize && $selectedOversize !== 'STD') {
                        $oversizeLabel = '+' . $selectedOversize;
                    } elseif ($selectedOversize === 'STD') {
                        $oversizeLabel = 'STD';
                    } else {
                        $oversizeLabel = null;
                    }
                @endphp
                <div class="py-3 {{ !$loop->first ? 'border-top border-light-subtle' : '' }}"
                    style="{{ $isExcluded ? 'opacity:0.45;' : '' }} transition: opacity .2s;">
                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">

                        {{-- Producto info --}}
                        <div class="flex-grow-1" style="min-width: 250px;">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="fw-bold fs-6"
                                    style="color:#1d4ed8; {{ $isExcluded ? 'text-decoration:line-through;' : '' }}">{{ $oem }}</span>

                                @if($oversizeLabel)
                                    <span class="badge rounded-pill px-2"
                                        style="background:rgba(59,130,246,.15);color:#1d4ed8;font-size:10px;font-weight:700;">{{ $oversizeLabel }}</span>
                                @endif

                                @if($isFull && !$isExcluded)
                                    <span class="badge rounded-pill px-2"
                                        style="background:#dcfce7;color:#15803d;font-size:10px;">✅ Disponible</span>
                                @elseif($isPartial && !$isExcluded)
                                    <span class="badge rounded-pill px-2"
                                        style="background:#fff7ed;color:#c2410c;font-size:10px;">⚠️ Parcial</span>
                                @elseif($isNone)
                                    <span class="badge rounded-pill px-2"
                                        style="background:#fee2e2;color:#991b1b;font-size:10px;">❌ Sin stock</span>
                                @endif
                                @if($isExcluded)
                                    <span class="badge rounded-pill px-2"
                                        style="background:#f3f4f6;color:#6b7280;font-size:10px;">Quitado</span>
                                @endif
                            </div>
                            <div class="text-secondary small text-truncate"
                                style="max-width: 450px;">{{ $rItem['product']['name'] }}
                            </div>
                            @if($confirmedQty > 0 && !$isExcluded)
                                <div class="text-muted small">S/
                                    {{ number_format($unitPrice, 2) }} c/u
                                </div>
                            @endif
                        </div>

                        {{-- Controles y precio --}}
                        <div class="d-flex align-items-center gap-3">
                            {{-- Controles de cantidad (solo si hay stock y no excluido) --}}
                            @if($confirmedQty > 0 && !$isExcluded)
                                <div class="d-flex align-items-center gap-1"
                                    style="background:#f3f4f6; border-radius:20px; padding:3px 8px;">
                                    <button type="button"
                                        wire:click="updateConfirmedQty('{{ $pid }}', -1)"
                                        class="btn btn-sm p-0 border-0 text-muted"
                                        style="width:24px;height:24px;line-height:1;font-size:16px;border-radius:50%;background:none;"
                                        {{ $effectiveQty <= 1 ? 'disabled' : '' }}>−</button>
                                    <span class="fw-bold text-dark"
                                        style="min-width:24px;text-align:center;font-size:14px;">{{ $effectiveQty }}</span>
                                    <button type="button"
                                        wire:click="updateConfirmedQty('{{ $pid }}', 1)"
                                        class="btn btn-sm p-0 border-0 text-muted"
                                        style="width:24px;height:24px;line-height:1;font-size:16px;border-radius:50%;background:none;"
                                        {{ $effectiveQty >= $confirmedQty ? 'disabled' : '' }}>+</button>
                                </div>
                            @endif

                            {{-- Subtotal --}}
                            @if($confirmedQty > 0 && !$isExcluded)
                                <div class="fw-bold text-dark text-end fs-6"
                                    style="min-width:90px;">
                                    S/ {{ number_format($effectiveSubtotal, 2) }}
                                </div>
                            @elseif($isNone)
                                <div class="text-muted text-end small" style="min-width:90px;">—
                                </div>
                            @endif

                            {{-- Botón Quitar / Restaurar y Eliminar definitivamente --}}
                            <div class="d-flex align-items-center gap-2">
                                @if($isExcluded)
                                    <button type="button"
                                        wire:click="restoreConfirmedItem('{{ $pid }}')"
                                        class="btn btn-sm rounded-pill px-3 py-1 border-0"
                                        style="font-size:11px; background:#dcfce7; color:#15803d; white-space:nowrap;">
                                        ↩ Restaurar
                                    </button>
                                @else
                                    <button type="button"
                                        wire:click="removeConfirmedItem('{{ $pid }}')"
                                        class="btn btn-sm rounded-pill px-3 py-1 border-0"
                                        title="Excluir este repuesto del pago (ej. precio muy alto)"
                                        style="font-size:11px; background:#fee2e2; color:#991b1b; white-space:nowrap;">
                                        × Quitar
                                    </button>
                                @endif

                                <button type="button"
                                    wire:click="deleteFromConfirmed('{{ $pid }}')"
                                    class="btn btn-sm p-1 border-0 text-muted"
                                    title="Eliminar repuesto definitivamente de tu pedido (agregado por error)"
                                    style="background:none; font-size:14px; line-height:1;"
                                    onclick="confirm('¿Eliminar este repuesto definitivamente de tu pedido?') || event.stopImmediatePropagation()">
                                    <i class="fas fa-trash-alt text-danger opacity-75"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- === TOTALS === --}}
    @php
        $deliveryCost = $this->estimatedDeliveryCost ?? 0;
        $finalTotal = $totalCalculated + ($this->deliveryType !== 'pickup' ? $deliveryCost : 0);
        $subtotalGravado = round($finalTotal / 1.18, 2);
        $igvAmount = round($finalTotal - $subtotalGravado, 2);
    @endphp
    <div class="rounded-3 p-3 mb-3"
        style="background:#f9fafb; border:1.5px solid #e5e7eb;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted" style="font-size:13px;">Subtotal repuestos (Incluye IGV)</span>
            <span class="fw-bold text-dark" style="font-size:13px;">S/ {{ number_format($totalCalculated, 2) }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted" style="font-size:13px;">Comisión de servicio</span>
            <div class="text-end">
                <span class="text-muted"
                    style="text-decoration: line-through; font-size:12px; margin-right: 5px;">S/ 15.00</span>
                <span class="text-success fw-bold" style="font-size:13px;">S/ 0.00</span>
                <span class="d-block text-success"
                    style="font-size:10px; margin-top:-2px;">¡No cobramos comisión por ahora!</span>
            </div>
        </div>
        @if($this->deliveryType !== 'pickup')
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted" style="font-size:13px;">Costo de envío</span>
                <span class="text-dark" style="font-size:13px;">S/ {{ number_format($deliveryCost, 2) }}</span>
            </div>
        @endif
        <div class="d-flex justify-content-between align-items-center pt-2"
            style="border-top:1.5px solid #e5e7eb;">
            <span class="fw-bold text-dark" style="font-size:15px;">Total a pagar</span>
            <span class="fw-bold" style="font-size:20px; color:#dc2626;">S/ {{ number_format($finalTotal, 2) }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light"
            style="font-size: 11px; opacity: 0.85;">
            <span class="text-muted">Op. Gravada: S/ {{ number_format($subtotalGravado, 2) }}</span>
            <span class="text-muted">IGV (18%): S/ {{ number_format($igvAmount, 2) }}</span>
        </div>
    </div>

    {{-- === TRUST SIGNALS === --}}
    <div class="d-flex justify-content-center gap-4 mb-3 flex-wrap">
        <div class="d-flex align-items-center gap-1 text-muted" style="font-size:11px;">
            <i class="fas fa-shield-alt text-success"></i>
            <span>Pago seguro SSL</span>
        </div>
        <div class="d-flex align-items-center gap-1 text-muted" style="font-size:11px;">
            <i class="fas fa-undo text-primary"></i>
            <span>Garantía de entrega</span>
        </div>
        <div class="d-flex align-items-center gap-1 text-muted" style="font-size:11px;">
            <i class="fab fa-cc-visa text-dark"></i>
            <span>Visa / Mastercard</span>
        </div>
    </div>

    {{-- === CTA BUTTON === --}}
    @php
        $allExcluded = $totalCalculated <= 0;
        $userEmail = auth()->user()?->email ?? 'cliente@repuestofijo.com';
        $currentOrderId = $this->lastOrderId ?? ($lastOrderId ?? session('current_pedido_id', ''));
    @endphp
    @if($allExcluded)
        <div class="rounded-3 p-3 mb-3 text-center"
            style="background:#fef9c3; border:1.5px solid #fbbf24;">
            <span style="font-size:13px; color:#92400e;">⚠️ Has quitado todos los productos.
                Restaura al menos uno para continuar.</span>
        </div>
    @endif
    <button type="button"
        class="btn w-100 fw-bold rounded-3 shadow-sm position-relative overflow-hidden"
        style="padding: 14px 24px; font-size:15px; background: {{ $allExcluded ? 'linear-gradient(135deg,#9ca3af,#6b7280)' : 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)' }}; color:white; border:none; transition: all .2s ease; {{ $allExcluded ? 'cursor:not-allowed;opacity:.7;' : '' }}"
        @if(!$allExcluded)
            onclick="initiateCulqiPayment({{ (float)$finalTotal }}, '{{ $userEmail }}', '{{ $currentOrderId }}')"
            onmouseover="this.style.opacity='0.92';this.style.transform='translateY(-1px)';"
            onmouseout="this.style.opacity='1';this.style.transform='translateY(0)';"
        @else
            disabled
        @endif>
        <i class="fas fa-lock me-2" style="font-size:13px;"></i>
        @if($allExcluded)
            Carrito vacío
        @else
            PAGAR S/ {{ number_format($finalTotal, 2) }} →
        @endif
    </button>
    <p class="text-center text-muted mt-2 mb-0" style="font-size:11px;">
        Al confirmar aceptas nuestros <a href="#" class="text-muted">Términos de Servicio</a>.
    </p>

@else
    {{-- === NO STOCK STATE === --}}
    <div class="rounded-3 p-3 mb-3 text-center"
        style="background:#f9fafb; border: 1.5px dashed #d1d5db;">
        <div style="font-size:36px; margin-bottom:8px;">😕</div>
        <div class="fw-bold text-dark mb-1" style="font-size:14px;">Sin stock disponible en este momento</div>
        <div class="text-muted" style="font-size:12px;">
            Ningún proveedor confirmó stock para los repuestos solicitados. Puedes volver a buscar o contactarnos.
        </div>
    </div>
    <div class="mb-4">
        <div class="text-muted x-small fw-bold text-uppercase mb-2 text-center">
            PRODUCTOS QUE TE PUEDEN INTERESAR</div>
        <div class="row g-2">
            @foreach($this->getRecommendedProducts() as $rec)
                <div class="col-6">
                    <div class="card p-2 border-light shadow-sm text-center h-100 cursor-pointer hover-scale"
                        wire:click="trackProductView({{ $rec->id }}); openDetails({{ $rec->id }})">
                        <img src="{{ $rec->image_url }}" class="img-fluid mb-2"
                            style="height: 60px; object-fit: contain;">
                        <div class="x-small fw-bold text-dark text-truncate">
                            {{ $rec->name }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="d-flex flex-column gap-2">
        <button type="button"
            class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm"
            style="background:#1d4ed8; border:none;"
            wire:click="reopenCart">
            <i class="fas fa-shopping-cart me-2"></i> VER Y EDITAR MI LISTA DE REPUESTOS
        </button>
        <button type="button"
            class="btn btn-outline-secondary w-100 py-2 fw-medium rounded-3"
            wire:click="resetToHome">
            VOLVER A BUSCAR 🔍
        </button>
    </div>
@endif

@if(collect($this->getZbotProviders())->contains('result', '⚠ No responde'))
    <div class="text-center mt-3 x-small text-muted">
        <i class="fas fa-info-circle me-1"></i> Estamos trabajando para mejorar los tiempos de respuesta de nuestros proveedores.
    </div>
@endif
