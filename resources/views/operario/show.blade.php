<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>📦 Operario · Orden #{{ $pedido->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #0b0f19;
            --surface:  #131b2e;
            --surface2: #1a2540;
            --border:   rgba(255,255,255,0.08);
            --blue:     #3b82f6;
            --blue-dim: rgba(59,130,246,0.15);
            --blue-glow:rgba(59,130,246,0.35);
            --green:    #10b981;
            --orange:   #f97316;
            --text:     #f1f5f9;
            --muted:    #64748b;
            --footer-h: 148px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', -apple-system, sans-serif;
            min-height: 100vh;
            /* leave room for fixed footer */
            padding-bottom: calc(var(--footer-h) + 16px);
        }

        /* ── TOP HEADER ─────────────────────────────────── */
        .top-bar {
            background: rgba(19,27,46,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }
        .top-bar-brand {
            display: flex; align-items: center; gap: 8px;
        }
        .top-bar-icon {
            width: 30px; height: 30px; border-radius: 8px;
            background: var(--blue-dim); color: var(--blue);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
        }
        .top-bar-title { font-size: 0.82rem; font-weight: 700; }
        .top-bar-sub   { font-size: 0.6rem; color: var(--muted); }

        /* ── CARDS ──────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 12px;
        }

        /* ── ORDER HERO CARD ────────────────────────────── */
        .order-hero {
            background: linear-gradient(135deg, #0f1e3c 0%, #131b2e 100%);
            border: 1px solid rgba(59,130,246,0.25);
            border-radius: 16px;
            padding: 18px 16px 14px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .order-icon-wrap {
            width: 52px; height: 52px; border-radius: 14px;
            background: var(--blue-dim);
            border: 1.5px solid rgba(59,130,246,0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .order-num {
            font-size: 1.55rem;
            font-weight: 600;
            letter-spacing: -0.5px;
            line-height: 1;
            color: #fff;
        }
        .order-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
            background: var(--blue-dim);
            border: 1px solid rgba(59,130,246,0.35);
            color: var(--blue);
            padding: 3px 9px;
            border-radius: 99px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        /* ── CLIENT CARD ────────────────────────────────── */
        .card-label {
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
            display: flex; align-items: center; gap: 5px;
        }
        .client-name  { font-size: 1rem; font-weight: 800; margin-bottom: 3px; }
        .client-phone { font-size: 0.78rem; color: var(--muted); margin-bottom: 8px; }
        .address-box {
            background: var(--surface2);
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 0.8rem;
            line-height: 1.4;
        }
        .note-box {
            margin-top: 7px;
            padding: 6px 9px;
            border-radius: 8px;
            background: rgba(245,158,11,0.1);
            border: 1px dashed rgba(245,158,11,0.4);
            color: #fbbf24;
            font-size: 0.73rem;
            line-height: 1.4;
        }
        .timer-row {
            display: flex; align-items: center; gap: 6px;
            margin-top: 8px;
            font-size: 0.75rem;
            color: var(--muted);
        }
        .timer-val {
            color: var(--blue);
            font-weight: 800;
            font-size: 0.88rem;
            font-variant-numeric: tabular-nums;
        }

        /* ── ITEMS CHECKLIST ────────────────────────────── */
        .items-scroll {
            max-height: 230px;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: rgba(59,130,246,0.3) transparent;
        }
        .item-row {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 0;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            user-select: none;
            transition: opacity 0.2s;
        }
        .item-row:last-child { border-bottom: none; }
        .item-row.checked-row { opacity: 0.55; }
        .check-box {
            width: 26px; height: 26px;
            border-radius: 8px;
            border: 2px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: all 0.18s;
            background: transparent;
        }
        .check-box.on {
            background: var(--green);
            border-color: var(--green);
        }
        .check-box.on::after {
            content: '';
            width: 11px; height: 6px;
            border-left: 2.5px solid #fff;
            border-bottom: 2.5px solid #fff;
            transform: rotate(-45deg) translateY(-2px);
            display: block;
        }
        .item-info { flex: 1; min-width: 0; }
        .item-name  { font-size: 0.82rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .item-code  { font-size: 0.67rem; color: var(--muted); margin-top: 2px; }
        .item-qty   {
            background: var(--surface2);
            padding: 3px 7px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--blue);
            flex-shrink: 0;
        }
        .progress-bg {
            height: 5px;
            background: rgba(255,255,255,0.07);
            border-radius: 99px;
            overflow: hidden;
            margin-top: 8px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--blue), #60a5fa);
            border-radius: 99px;
            transition: width 0.35s ease;
        }
        .progress-label {
            font-size: 0.65rem;
            color: var(--muted);
            text-align: right;
            margin-top: 4px;
        }

        /* ── CURRENT STEP FORM ──────────────────────────── */
        .step-form-card {
            background: linear-gradient(160deg, rgba(59,130,246,0.07) 0%, var(--surface) 100%);
            border: 1px solid rgba(59,130,246,0.3);
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 12px;
        }
        .step-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--blue);
            color: #fff;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 800;
            margin-bottom: 12px;
        }
        .form-lbl {
            display: block;
            font-size: 0.74rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text);
        }
        .form-lbl span { color: var(--muted); font-weight: 400; }
        .f-input {
            width: 100%;
            background: var(--surface2);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 9px 12px;
            color: var(--text);
            font-size: 0.82rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
            margin-bottom: 10px;
        }
        .f-input:focus { border-color: var(--blue); }

        /* ── ACTIVE STEP BANNER ────────────────────────── */
        .active-step-card {
            background: var(--surface);
            border: 1px solid rgba(59,130,246,0.35);
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: transform 0.2s, border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }
        .active-step-card:hover {
            border-color: var(--blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 24px var(--blue-glow);
        }
        .step-pulse-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: var(--blue-dim);
            border: 1px solid rgba(59,130,246,0.4);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .btn-sm-open {
            background: var(--blue);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 15px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            white-space: nowrap;
        }

        /* ── POPUP OVERLAY ─────────────────────────────── */
        .step-popup-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: none;
            align-items: flex-end;
            justify-content: center;
            padding: 0;
        }
        .step-popup-overlay.active {
            display: flex;
        }
        .step-popup-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 520px;
            padding: 22px 20px 28px;
            box-shadow: 0 -10px 40px rgba(0,0,0,0.7);
            animation: slideUpPopup 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @media (min-width: 540px) {
            .step-popup-overlay {
                align-items: center;
                padding: 20px;
            }
            .step-popup-content {
                border-radius: 20px;
            }
        }
        @keyframes slideUpPopup {
            from { transform: translateY(100%); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .btn-popup-close {
            background: transparent;
            border: none;
            color: var(--muted);
            font-size: 1.6rem;
            line-height: 1;
            cursor: pointer;
            padding: 0 4px;
            transition: color 0.2s;
        }
        .btn-popup-close:hover { color: #fff; }

        /* ── BOTON CAPTURAR IMAGEN ── */
        .btn-capture-photo {
            width: 100%;
            background: linear-gradient(135deg, #1e3a8a, var(--blue));
            border: 1px solid rgba(59,130,246,0.4);
            color: #fff;
            padding: 14px 18px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            font-family: inherit;
            box-shadow: 0 4px 16px var(--blue-glow);
            transition: all 0.2s;
        }
        .btn-capture-photo:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59,130,246,0.6);
        }
        .btn-capture-photo.has-photo {
            background: var(--surface2);
            border-color: rgba(16,185,129,0.4);
            box-shadow: none;
        }
        .btn-confirm {
            width: 100%;
            background: linear-gradient(135deg, var(--blue), #2563eb);
            color: #fff;
            border: none;
            padding: 13px 20px;
            border-radius: 13px;
            font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            gap: 8px;
            font-size: 0.95rem;
            box-shadow: 0 8px 20px var(--blue-glow);
            cursor: pointer;
            font-family: inherit;
            transition: transform 0.15s, box-shadow 0.15s;
            margin-top: 4px;
        }
        .btn-confirm:active { transform: scale(0.97); }
        .btn-confirm:disabled { opacity: 0.45; cursor: not-allowed; box-shadow: none; }

        /* ── COMPLETED STATE ────────────────────────────── */
        .done-card {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.3);
            border-radius: 16px;
            padding: 28px 20px;
            text-align: center;
            margin-bottom: 12px;
        }
        .done-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: rgba(16,185,129,0.2); color: var(--green);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 26px; margin-bottom: 12px;
        }

        /* ── ALERT SUCCESS ──────────────────────────────── */
        .alert-ok {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.3);
            color: #34d399;
            border-radius: 12px;
            padding: 10px 13px;
            font-size: 0.8rem;
            margin-bottom: 12px;
            display: flex; align-items: center; gap: 7px;
        }

        /* ── FIXED BOTTOM FOOTER ────────────────────────── */
        .fixed-footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: rgba(13,18,32,0.97);
            backdrop-filter: blur(16px);
            border-top: 1px solid rgba(59,130,246,0.15);
            padding: 10px 16px 16px;
            z-index: 100;
        }
        /* Horizontal trazabilidad */
        .traza-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 10px;
        }
        .traza-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            flex: 1;
        }
        .traza-circle {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 0.8rem;
            transition: all 0.3s;
            border: 2px solid;
        }
        .traza-circle.done {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
            box-shadow: 0 0 12px var(--blue-glow);
        }
        .traza-circle.active {
            background: var(--blue-dim);
            border-color: var(--blue);
            color: var(--blue);
        }
        .traza-circle.pending {
            background: rgba(255,255,255,0.04);
            border-color: rgba(255,255,255,0.15);
            color: var(--muted);
        }
        .traza-label {
            font-size: 0.58rem;
            font-weight: 600;
            text-align: center;
            line-height: 1.2;
            max-width: 64px;
        }
        .traza-label.done   { color: var(--blue); }
        .traza-label.active { color: var(--blue); }
        .traza-label.pending{ color: var(--muted); }
        .traza-arrow {
            color: rgba(255,255,255,0.15);
            font-size: 0.75rem;
            margin: 0 2px;
            margin-bottom: 18px; /* align with circles */
            flex-shrink: 0;
        }
        .traza-arrow.done { color: var(--blue); }

        /* CTA button in footer */
        .btn-cta {
            width: 100%;
            padding: 12px 16px;
            border-radius: 13px;
            border: none;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex; align-items: center; justify-content: center;
            gap: 8px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
        }
        .btn-cta.inactive {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--muted);
            cursor: not-allowed;
        }
        .btn-cta.waiting {
            background: linear-gradient(135deg, #1d4ed8, var(--blue));
            color: #fff;
            box-shadow: 0 6px 20px var(--blue-glow);
            animation: pulse-blue 2s infinite;
        }
        .btn-cta.done-state {
            background: linear-gradient(135deg, #065f46, var(--green));
            color: #fff;
            box-shadow: 0 6px 16px rgba(16,185,129,0.35);
        }
        @keyframes pulse-blue {
            0%,100% { box-shadow: 0 6px 20px rgba(59,130,246,0.35); }
            50%      { box-shadow: 0 6px 28px rgba(59,130,246,0.6); }
        }
    </style>
</head>
<body>

    {{-- ── Top sticky header ─────────────────────────────── --}}
    <header class="top-bar">
        <div class="top-bar-brand">
            <div class="top-bar-icon">📦</div>
            <div>
                <div class="top-bar-title">Operario · Hub Central</div>
                <div class="top-bar-sub">Repuesto Fijo · Trazabilidad interna</div>
            </div>
        </div>
        <span style="font-size:0.65rem; color:var(--muted);">{{ now()->format('H:i') }}</span>
    </header>

    {{-- ── Main scrollable content ───────────────────────── --}}
    <main style="max-width:520px; margin:0 auto; padding:14px 12px;">

        {{-- Mensaje de éxito eliminado (confirmación se visualiza en los pasos) --}}

        {{-- 1. ORDER HERO CARD ──────────────────────────── --}}
        <div class="order-hero">
            <div class="order-icon-wrap">📦</div>
            <div>
                <div class="order-num">Pedido #{{ $pedido->id }}</div>
                <div class="order-status-badge">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--blue);display:inline-block;"></span>
                    {{ $pedido->status_label }}
                </div>
            </div>
        </div>

        {{-- 2. CUSTOMER INFO + TIMER ────────────────────── --}}
        <div class="card">
            <div class="card-label">📍 Destino de Entrega</div>
            <div class="client-name">{{ $pedido->customer?->name ?? 'Cliente' }}</div>
            <div class="address-box">
                @if($pedido->distrito)
                    <strong>{{ $pedido->distrito }}:</strong>
                @endif
                {{ $pedido->direccion }}
            </div>

            {{-- Timer --}}
            <div class="timer-row">
                <span>⏱</span>
                <span>Listo para recogida desde</span>
                <span class="timer-val" id="elapsedTimer">--:--</span>
            </div>
        </div>

        {{-- 3. ITEMS CHECKLIST ──────────────────────────── --}}
        @if($pedido->items->isNotEmpty())
        <div class="card">
            <div class="card-label" style="justify-content:space-between;">
                <span>🔩 Verificación de Ítems del Pedido</span>
                <span style="color:var(--blue); font-weight:700;">{{ $pedido->items->count() }} ítem{{ $pedido->items->count() > 1 ? 's' : '' }}</span>
            </div>

            <div class="items-scroll" id="itemList">
                @foreach($pedido->items as $idx => $item)
                    <div class="item-row" id="row{{ $idx }}" onclick="toggleItem({{ $idx }})">
                        <div class="check-box" id="chk{{ $idx }}"></div>

                        {{-- Miniatura del producto --}}
                        @if($item->product?->image_url)
                            <img src="{{ $item->product->image_url }}"
                                 alt="{{ $item->product->name }}"
                                 style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--border);flex-shrink:0;">
                        @else
                            <div style="width:44px;height:44px;border-radius:8px;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;">🔩</div>
                        @endif

                        <div class="item-info">
                            {{-- Código grande — lo más importante --}}
                            <div style="font-size:0.88rem;font-weight:800;color:#fff;letter-spacing:0.2px;">
                                {{ $item->product?->supplier_code ?? $item->product?->oem_code ?? 'Sin código' }}
                                @if($item->oversize)
                                    <span style="color:var(--orange);font-size:0.75rem;font-weight:700;"> · {{ $item->oversize }}</span>
                                @endif
                            </div>
                            {{-- Nombre como referencia visual (1 línea) --}}
                            <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ Str::limit($item->product?->name ?? '', 40) }}
                            </div>
                        </div>
                        <span class="item-qty">{{ $item->cantidad }}x</span>
                    </div>
                @endforeach
            </div>

            <div class="progress-bg"><div class="progress-fill" id="progFill" style="width:0%"></div></div>
            <div class="progress-label"><span id="chkCount">0</span> / {{ $pedido->items->count() }} verificados</div>
        </div>
        @endif

        {{-- CARD DE ETIQUETA LOGÍSTICA (Se oculta automáticamente una vez impresa) --}}
        @if($hitos->has('embalaje_hub'))
            {{-- Card para imprimir (se oculta en cuanto el operario imprime) --}}
            <div id="etiquetaCard" class="card" style="border: 1px solid rgba(59,130,246,0.35); background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(30,58,138,0.15)); margin-bottom: 14px;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:44px; height:44px; border-radius:12px; background:var(--blue-dim); border:1px solid rgba(59,130,246,0.4); display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">
                            🏷️
                        </div>
                        <div>
                            <div style="font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:0.8px; color:var(--blue);">
                                Caja Embalada · Hub
                            </div>
                            <div style="font-size:0.92rem; font-weight:800; color:#fff; margin-top:2px;">
                                Etiqueta Logística con QR
                            </div>
                            <div style="font-size:0.72rem; color:var(--muted); margin-top:2px;">
                                Imprime y pega la etiqueta con los datos del destinatario
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
                        <a href="{{ route('operario.label', $token) }}"
                           target="_blank"
                           class="btn-sm-open"
                           onclick="markLabelAsPrinted()"
                           style="text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            🖨️ Imprimir
                        </a>
                        <button type="button" onclick="markLabelAsPrinted()" style="background:none; border:none; color:var(--muted); font-size:0.65rem; cursor:pointer; text-decoration:underline; padding:0;">
                            Ya la imprimí (ocultar)
                        </button>
                    </div>
                </div>
            </div>

            {{-- Badge sutil cuando ya fue impresa (no ocupa espacio) --}}
            <div id="etiquetaImpresaBadge" style="display:none; align-items:center; justify-content:space-between; padding:8px 12px; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:10px; margin-bottom:12px;">
                <span style="font-size:0.75rem; color:var(--green); font-weight:700;">✅ Etiqueta impresa y pegada en la caja</span>
                <a href="{{ route('operario.label', $token) }}" target="_blank" style="font-size:0.7rem; color:var(--muted); text-decoration:underline;">
                    Reimprimir
                </a>
            </div>

            {{-- Fallback WhatsApp si Green API devolvió error de cuota o se requiere reenvío --}}
            @if(isset($motorizado) && $motorizado)
                @php
                    $mPhone = preg_replace('/[^0-9]/', '', $motorizado->phone);
                    $waText = "🛵 *¡PAQUETE LISTO EN HUB! · Pedido #{$pedido->id}*\n\n"
                            . "Acércate al Hub Central a recoger la caja lista para despacho.\n\n"
                            . "👤 *Destinatario:* " . ($pedido->customer?->name ?? 'Cliente') . "\n"
                            . "📍 *Dirección:* " . ($pedido->distrito ? "{$pedido->distrito}: " : '') . $pedido->direccion . "\n"
                            . "📞 *Contacto:* " . ($pedido->telefono_contacto ?: ($pedido->customer?->phone ?: 'No registrado')) . "\n"
                            . ($pedido->referencia ? "📝 *Instrucciones:* {$pedido->referencia}\n\n" : "\n")
                            . "📲 *Enlace de entrega (PoD):*\n"
                            . route('pod.show', $pedido->delivery_token);
                    $waLink = "https://wa.me/{$mPhone}?text=" . rawurlencode($waText);
                @endphp
                @if(session('wa_sent') === false)
                    <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 12px; padding: 12px 14px; margin-bottom: 14px;">
                        <div style="font-size: 0.8rem; font-weight: 800; color: #f87171;">
                            ⚠️ WhatsApp automático no entregado (Límite Green API)
                        </div>
                        <div style="font-size: 0.72rem; color: var(--muted); margin-top: 3px; line-height: 1.4;">
                            El número <strong>{{ $motorizado->phone }}</strong> no está entre los 3 números autorizados del plan gratuito de Green API.
                        </div>
                        <div style="margin-top: 8px;">
                            <a href="{{ $waLink }}" target="_blank" class="btn-sm-open" style="text-decoration:none; background:#25D366; color:#000; font-weight:800; display:inline-flex; align-items:center; gap:6px;">
                                💬 Enviar WhatsApp Directo al Motorizado
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        @endif

        {{-- 4. ACTIVE STEP BANNER or COMPLETED STATE ─────── --}}
        @php
            $stepDefs = [
                'recojo_proveedor'   => ['n' => 1, 'label' => 'Inspección en Proveedor',  'sub' => 'Foto del repuesto antes de salir del proveedor'],
                'embalaje_hub'       => ['n' => 2, 'label' => 'Embalaje en Hub + QR',      'sub' => 'Foto del paquete embalado con el sticker QR pegado'],
                'entrega_motorizado' => ['n' => 3, 'label' => 'Entrega al Motorizado',      'sub' => 'Foto al momento de entregar la caja lista al rider'],
            ];
        @endphp

        @if($siguienteHito !== 'completado_operario')
            @php $cur = $stepDefs[$siguienteHito]; @endphp
            <div class="active-step-card" onclick="openStepPopup()">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="step-pulse-icon">📸</div>
                        <div>
                            <div style="font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:0.8px; color:var(--blue);">
                                Paso {{ $cur['n'] }} de 3 · Siguiente Acción
                            </div>
                            <div style="font-size:0.95rem; font-weight:800; color:#fff; margin-top:2px;">
                                {{ $cur['label'] }}
                            </div>
                            <div style="font-size:0.72rem; color:var(--muted); margin-top:2px;">
                                {{ $cur['sub'] }}
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-sm-open">
                        Tomar Foto ›
                    </button>
                </div>
            </div>
        @else
            {{-- TRABAJO TERMINADO --}}
            <div class="done-card">
                <div class="done-icon">🎉</div>
                <h5 style="font-weight:700; margin-bottom:6px; color:#fff; font-size:1.15rem;">Pedido completado.</h5>
                <p style="font-size:0.82rem; color:var(--muted); line-height:1.5;">
                    Los 3 pasos de trazabilidad fueron registrados con éxito.
                </p>
            </div>
        @endif

    </main><!-- /main -->

    @if($siguienteHito !== 'completado_operario')
    {{-- ── POPUP DE REGISTRO DE PASO ─────────────────────── --}}
    <div class="step-popup-overlay" id="stepPopup" onclick="handlePopupOverlayClick(event)">
        <div class="step-popup-content" onclick="event.stopPropagation()">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <div>
                    <div class="step-badge" style="margin-bottom:4px;">
                        📸 Paso {{ $cur['n'] }} de 3 · {{ $cur['label'] }}
                    </div>
                    <p style="font-size:0.75rem; color:var(--muted); margin:0;">{{ $cur['sub'] }}</p>
                </div>
                <button type="button" class="btn-popup-close" onclick="closeStepPopup()" title="Cerrar">&times;</button>
            </div>

            <form action="{{ route('operario.hito', $token) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="opForm">
                @csrf
                <input type="hidden" name="tipo_hito" value="{{ $siguienteHito }}">
                <input type="hidden" name="operador_nombre" value="{{ auth()->user()?->name ?? 'Operario Repuesto Fijo' }}">
                <input type="hidden" name="lat" id="gps_lat" value="">
                <input type="hidden" name="lng" id="gps_lng" value="">

                {{-- Si es el Paso 2, opción de imprimir la etiqueta para pegarla en la caja --}}
                @if($siguienteHito === 'embalaje_hub')
                    <div style="margin-bottom: 14px; padding: 10px 14px; background: rgba(59,130,246,0.08); border: 1px dashed rgba(59,130,246,0.35); border-radius: 12px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                        <div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: #fff;">1. Generar e Imprimir Etiqueta</div>
                            <div style="font-size: 0.68rem; color: var(--muted);">Pégala en la caja con el QR antes de tomar la foto</div>
                        </div>
                        <a href="{{ route('operario.label', $token) }}" target="_blank" class="btn-sm-open" style="text-decoration: none; padding: 7px 12px; font-size: 0.75rem;">
                            🖨️ Ver Etiqueta
                        </a>
                    </div>
                @endif

                {{-- Capturar Imagen (input nativo de cámara oculto + botón estilizado) --}}
                <input type="file"
                       name="foto"
                       id="fotoInput"
                       accept="image/*"
                       capture="environment"
                       style="display:none;"
                       required
                       onchange="onPhotoSelected(this)">

                <div style="margin-bottom:14px;">
                    <button type="button"
                            class="btn-capture-photo"
                            id="btnTriggerPhoto"
                            onclick="document.getElementById('fotoInput').click()">
                        <span style="font-size:1.3rem;">📸</span>
                        <span id="btnPhotoLabel">Capturar Imagen</span>
                    </button>

                    {{-- Vista previa tras capturar --}}
                    <div id="photoPreviewWrap" style="display:none; align-items:center; gap:12px; background:var(--surface2); padding:10px 14px; border-radius:12px; border:1px solid rgba(16,185,129,0.3); margin-top:10px;">
                        <img id="photoPreviewImg" src="" alt="Vista previa" style="width:48px; height:48px; object-fit:cover; border-radius:8px; border:1px solid rgba(255,255,255,0.1);">
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:0.8rem; font-weight:700; color:var(--green);">✅ Imagen capturada</div>
                            <div id="photoFileName" style="font-size:0.68rem; color:var(--muted); text-overflow:ellipsis; overflow:hidden; white-space:nowrap;"></div>
                        </div>
                        <button type="button" onclick="document.getElementById('fotoInput').click()" style="background:none; border:none; color:var(--blue); font-size:0.75rem; font-weight:700; cursor:pointer; text-decoration:underline;">
                            Cambiar
                        </button>
                    </div>
                </div>

                {{-- Observaciones (opcional) --}}
                <div style="margin-bottom:16px;">
                    <label class="form-lbl" style="color:var(--muted); margin-bottom:6px;">Observaciones <span>(opcional)</span></label>
                    <textarea name="observaciones"
                              rows="2"
                              class="f-input"
                              placeholder="Ej: Producto en buen estado, embalado en caja reforzada…"
                              style="resize:none; margin-bottom:0;"></textarea>
                </div>

                {{-- Botón de Confirmación se Mantiene --}}
                <button type="submit" class="btn-confirm" id="submitBtn">
                    ✅ Confirmar Paso {{ $cur['n'] }}
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- ── FIXED FOOTER: trazabilidad + CTA ────────────── --}}
    @php
        $h1 = $hitos->has('recojo_proveedor');
        $h2 = $hitos->has('embalaje_hub');
        $h3 = $hitos->has('entrega_motorizado');

        $s1 = $h1 ? 'done'    : 'active';
        $s2 = $h1 ? ($h2 ? 'done' : 'active') : 'pending';
        $s3 = $h2 ? ($h3 ? 'done' : 'active') : 'pending';

        // Arrow colours
        $a1 = $h1 ? 'done' : '';
        $a2 = $h2 ? 'done' : '';

        // Footer button state
        if ($h3) {
            $ctaState = 'done-state';
            $ctaText  = '✅ Pedido completado';
        } elseif ($h2) {
            $ctaState = 'waiting';
            $ctaText  = '🛵 ⏳ Paquete Listo — Esperando Motorizado';
        } else {
            $ctaState = 'inactive';
            $ctaText  = '🛵 Paquete Listo — Esperando Motorizado';
        }
    @endphp

    <footer class="fixed-footer">

        {{-- Horizontal trazabilidad steps --}}
        <div class="traza-row">
            {{-- Step 1 --}}
            <div class="traza-step" @if($s1 === 'active') onclick="openStepPopup()" style="cursor:pointer;" @endif>
                <div class="traza-circle {{ $s1 }}">
                    @if($h1) ✓ @else 1 @endif
                </div>
                <div class="traza-label {{ $s1 }}">Inspección<br>Proveedor</div>
            </div>

            <div class="traza-arrow {{ $a1 }}">›</div>

            {{-- Step 2 --}}
            <div class="traza-step" @if($s2 === 'active') onclick="openStepPopup()" style="cursor:pointer;" @endif>
                <div class="traza-circle {{ $s2 }}">
                    @if($h2) ✓ @else 2 @endif
                </div>
                <div class="traza-label {{ $s2 }}">Embalaje<br>Hub + QR</div>
            </div>

            <div class="traza-arrow {{ $a2 }}">›</div>

            {{-- Step 3 --}}
            <div class="traza-step" @if($s3 === 'active') onclick="openStepPopup()" style="cursor:pointer;" @endif>
                <div class="traza-circle {{ $s3 }}">
                    @if($h3) ✓ @else 3 @endif
                </div>
                <div class="traza-label {{ $s3 }}">Entrega<br>Motorizado</div>
            </div>
        </div>

        {{-- CTA button --}}
        <button class="btn-cta {{ $ctaState }}"
                @if($siguienteHito !== 'completado_operario') onclick="openStepPopup()" @elseif($ctaState === 'inactive') disabled @endif>
            {{ $ctaText }}
        </button>

    </footer>

    <script>
        // ── Silent background GPS (invisible to operator) ──
        function silentGPS() {
            if (!navigator.geolocation) return;
            const lat = document.getElementById('gps_lat');
            const lng = document.getElementById('gps_lng');
            navigator.geolocation.getCurrentPosition(
                p => {
                    if (lat) lat.value = p.coords.latitude;
                    if (lng) lng.value = p.coords.longitude;
                },
                () => {},
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 }
            );
        }

        // ── Item checklist with localStorage persistence ──
        const storageKey = 'operario_items_pedido_{{ $pedido->id }}';
        const total = {{ $pedido->items->count() }};

        function loadSavedChecks() {
            let saved = [];
            try {
                saved = JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch(e) {
                saved = [];
            }
            saved.forEach(idx => {
                const box = document.getElementById('chk' + idx);
                const row = document.getElementById('row' + idx);
                if (box && row) {
                    box.classList.add('on');
                    row.classList.add('checked-row');
                }
            });
            updateProgress();
        }

        function toggleItem(i) {
            const box = document.getElementById('chk' + i);
            const row = document.getElementById('row' + i);
            if (!box || !row) return;
            box.classList.toggle('on');
            row.classList.toggle('checked-row', box.classList.contains('on'));

            // Persist indexes of checked items
            const checkedIndexes = [];
            for (let idx = 0; idx < total; idx++) {
                const b = document.getElementById('chk' + idx);
                if (b && b.classList.contains('on')) {
                    checkedIndexes.push(idx);
                }
            }
            try {
                localStorage.setItem(storageKey, JSON.stringify(checkedIndexes));
            } catch(e) {}

            updateProgress();
        }

        function updateProgress() {
            const done = document.querySelectorAll('.check-box.on').length;
            const pct  = total > 0 ? Math.round(done / total * 100) : 0;
            const fill = document.getElementById('progFill');
            const cnt  = document.getElementById('chkCount');
            if (fill) fill.style.width = pct + '%';
            if (cnt)  cnt.textContent  = done;
        }

        // ── Popup controls ───────────────────────────────
        function openStepPopup() {
            const pop = document.getElementById('stepPopup');
            if (pop) {
                pop.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            silentGPS();
        }

        function closeStepPopup() {
            const pop = document.getElementById('stepPopup');
            if (pop) {
                pop.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        function handlePopupOverlayClick(e) {
            if (e.target.id === 'stepPopup') {
                closeStepPopup();
            }
        }

        // ── Photo Selection & Preview ────────────────────
        function onPhotoSelected(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('photoPreviewImg');
                    const wrap = document.getElementById('photoPreviewWrap');
                    const lbl = document.getElementById('btnPhotoLabel');
                    const fname = document.getElementById('photoFileName');
                    const btn = document.getElementById('btnTriggerPhoto');
                    if (img) img.src = e.target.result;
                    if (fname) fname.textContent = file.name || 'foto_evidencia.jpg';
                    if (wrap) wrap.style.display = 'flex';
                    if (lbl) lbl.textContent = 'Volver a Capturar';
                    if (btn) btn.classList.add('has-photo');
                };
                reader.readAsDataURL(file);
            }
        }

        // ── Elapsed timer ────────────────────────────────
        @php
            $paidAt = $pedido->payment_confirmed_at
                ? $pedido->payment_confirmed_at->timestamp
                : $pedido->created_at->timestamp;
        @endphp
        const paidTimestamp = {{ $paidAt }};
        function updateTimer() {
            const el = document.getElementById('elapsedTimer');
            if (!el) return;
            const diff = Math.max(0, Math.floor(Date.now() / 1000) - paidTimestamp);
            const h    = Math.floor(diff / 3600);
            const m    = Math.floor((diff % 3600) / 60);
            const s    = diff % 60;
            el.textContent = (h > 0 ? String(h).padStart(2,'0') + ':' : '')
                           + String(m).padStart(2,'0') + ':'
                           + String(s).padStart(2,'0');
        }
        updateTimer();
        setInterval(updateTimer, 1000);

        // ── Etiqueta impresa persistence ─────────────────
        const labelStorageKey = 'operario_label_printed_{{ $pedido->id }}';

        function checkLabelPrinted() {
            if (localStorage.getItem(labelStorageKey) === 'true') {
                const card = document.getElementById('etiquetaCard');
                const badge = document.getElementById('etiquetaImpresaBadge');
                if (card) card.style.display = 'none';
                if (badge) badge.style.display = 'flex';
            }
        }

        function markLabelAsPrinted() {
            try {
                localStorage.setItem(labelStorageKey, 'true');
            } catch(e) {}
            setTimeout(() => {
                checkLabelPrinted();
            }, 300);
        }

        // ── Initialization & Submit ──────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            silentGPS();
            loadSavedChecks();
            checkLabelPrinted();

            const form = document.getElementById('opForm');
            if (form) {
                form.addEventListener('submit', () => {
                    const btn = document.getElementById('submitBtn');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '⏳ Subiendo evidencia…';
                    }
                });
            }
        });
    </script>

</body>
</html>
