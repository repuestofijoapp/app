<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Confirmar Stock · RepuestoFijo</title>
    <meta property="og:title" content="Confirmar Stock · RepuestoFijo">
    <meta property="og:description" content="Verifica y confirma disponibilidad y precios para este pedido.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: #151d30;
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --border-color: #1e293b;
            --input-bg: #1e293b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 16px;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin-top: 10px;
            margin-bottom: 40px;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        header {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .logo span {
            color: var(--accent-color);
        }

        .badge-pedido {
            background-color: var(--border-color);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 9999px;
            display: inline-block;
            margin-top: 8px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-description {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        /* Error and Success States */
        .status-card {
            text-align: center;
            padding: 40px 24px;
        }

        .status-icon {
            font-size: 64px;
            margin-bottom: 16px;
            display: block;
        }

        .status-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .status-message {
            color: var(--text-secondary);
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .btn {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 14px 24px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: var(--accent-hover);
        }

        .btn-success {
            background-color: var(--success-color);
        }

        .btn-success:hover {
            opacity: 0.9;
        }

        /* Product Confirmation List */
        .product-card {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px;
            padding: 16px;
            border: 1px solid var(--border-color);
            margin-bottom: 14px;
            transition: border-color 0.2s ease, background-color 0.2s ease;
        }

        .product-card.has-stock {
            border-color: rgba(16, 185, 129, 0.3);
            background: rgba(16, 185, 129, 0.03);
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .product-info {
            flex-grow: 1;
            padding-right: 12px;
        }

        .product-code {
            font-family: monospace;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: 0.5px;
        }

        .product-desc {
            color: var(--text-secondary);
            font-size: 13px;
            margin-top: 2px;
        }

        .product-measure {
            background-color: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 4px;
        }

        /* Custom Switch */
        .switch-container {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .switch-input {
            display: none;
        }

        .switch-label {
            width: 48px;
            height: 26px;
            background-color: #374151;
            border-radius: 9999px;
            position: relative;
            transition: background-color 0.2s ease;
        }

        .switch-label::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: white;
            top: 3px;
            left: 3px;
            transition: transform 0.2s ease;
        }

        .switch-input:checked + .switch-label {
            background-color: var(--success-color);
        }

        .switch-input:checked + .switch-label::after {
            transform: translateX(22px);
        }

        /* Stock Fields (shown when tengo stock is true) */
        .stock-fields {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0, 1, 0, 1);
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 12px;
        }

        .product-card.has-stock .stock-fields {
            max-height: 200px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px dashed var(--border-color);
            transition: max-height 0.3s cubic-bezier(1, 0, 1, 0);
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        /* Numeric inputs & Price */
        .qty-selector {
            display: flex;
            align-items: center;
            background-color: var(--input-bg);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            height: 42px;
        }

        .qty-btn {
            width: 36px;
            height: 100%;
            border: none;
            background: transparent;
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:active {
            opacity: 0.6;
        }

        .qty-input {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
            text-align: center;
            color: var(--text-primary);
            font-size: 15px;
            font-weight: 600;
            outline: none;
            -moz-appearance: textfield;
        }

        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .price-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .price-symbol {
            position: absolute;
            left: 12px;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 15px;
        }

        .price-input {
            width: 100%;
            height: 42px;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding-left: 28px;
            padding-right: 12px;
            color: var(--text-primary);
            font-size: 15px;
            font-weight: 600;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .price-input:focus {
            border-color: var(--accent-color);
        }

        /* Summary panel */
        .summary-panel {
            background-color: rgba(255, 255, 255, 0.01);
            border-radius: 12px;
            padding: 16px;
            border: 1px solid var(--border-color);
            margin-top: 20px;
            margin-bottom: 24px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .summary-row.total {
            border-top: 1px solid var(--border-color);
            padding-top: 8px;
            margin-bottom: 0;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        .summary-total-val {
            color: var(--success-color);
        }

        /* Footer styling */
        footer {
            text-align: center;
            color: var(--text-secondary);
            font-size: 12px;
            margin-top: auto;
            padding-top: 40px;
        }

        /* Responsive fixes */
        @media (max-width: 480px) {
            body {
                padding: 12px;
            }
            .card {
                padding: 18px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <header>
            <div style="margin-bottom: 15px;">
                <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo" style="max-height: 45px; width: auto;">
            </div>
            <div>
                @if(isset($pedido))
                    <span class="badge-pedido">Pedido #{{ $pedido->id }} · Confirmación de Stock</span>
                @else
                    <span class="badge-pedido">Confirmación de Stock</span>
                @endif
            </div>
        </header>

        <!-- ERROR STATE -->
        @if(isset($error))
            <div class="card status-card">
                <span class="status-icon">⚠️</span>
                <h2 class="status-title">Solicitud Expirada o Inválida</h2>
                <p class="status-message">{{ $error }}</p>
                <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 20px;">
                    Si crees que esto es un error, por favor comunícate directamente con RepuestoFijo.
                </div>
            </div>

        <!-- SUCCESS STATE -->
        @elseif(isset($success))
            <div class="card status-card">
                <span class="status-icon">✅</span>
                <h2 class="status-title">¡Información Registrada!</h2>
                <p class="status-message">
                    Muchas gracias. Hemos procesado tu confirmación de stock y precios exitosamente. El taller mecánico recibirá tu respuesta en tiempo real.
                </p>
                <div style="padding: 16px; background: rgba(16, 185, 129, 0.05); border-radius: 12px; border: 1px dashed rgba(16, 185, 129, 0.2); margin-bottom: 24px; text-align: left;">
                    <div style="font-weight: 600; font-size: 13px; color: var(--success-color); margin-bottom: 4px;">Resumen:</div>
                    <div style="font-size: 13px; color: var(--text-secondary); display: flex; justify-content: space-between;">
                        <span>Estado:</span>
                        <span style="font-weight: 500; color: var(--text-primary)">
                            {{ $query->status === 'confirmed' ? 'Stock Confirmado' : 'Sin Stock' }}
                        </span>
                    </div>
                    @if($query->status === 'confirmed')
                    <div style="font-size: 13px; color: var(--text-secondary); display: flex; justify-content: space-between; margin-top: 4px;">
                        <span>Total cotizado:</span>
                        <span style="font-weight: 600; color: var(--success-color)">
                            S/ {{ number_format($query->price, 2) }}
                        </span>
                    </div>
                    @endif
                </div>
                <div style="font-size: 13px; color: var(--text-secondary);">
                    Ya puedes cerrar esta ventana.
                </div>
            </div>

        <!-- FORM STATE -->
        @else
            <form action="{{ route('provider.confirm.submit', $query->confirmation_token) }}" method="POST" id="confirmForm">
                @csrf
                <div class="card">
                    <h3 class="card-title">Hola, {{ $provider->name ?? 'Proveedor' }}</h3>
                    <p class="card-description">
                        Por favor, indícanos qué repuestos tienes listos para entrega inmediata, su cantidad disponible y el precio unitario.
                    </p>

                    <div class="products-list">
                        @foreach($items as $item)
                            @php
                                $code = $item['product']['supplier_code'] ?? 'N/A';
                                $name = $item['product']['name'] ?? 'Repuesto';
                                $qty = $item['qty'] ?? 1;
                                $oversize = $item['product']['oversize'] ?? null;
                                $idx = $loop->index;
                                $uid = $idx . '-' . $code;
                            @endphp
                            <div class="product-card" id="card-{{ $uid }}">
                                <div class="product-header">
                                    <div class="product-info">
                                        <div class="product-code">
                                            {{ $code }}
                                            @if(!empty($oversize))
                                                <span class="product-measure" style="margin-left:6px; font-size:0.8em; font-weight:700; color:#f59e0b; background:rgba(245,158,11,0.12); padding:1px 6px; border-radius:4px;">{{ $oversize }}</span>
                                            @endif
                                        </div>
                                        <div class="product-desc">{{ $name }}</div>
                                    </div>
                                    <div class="switch-container">
                                        <input type="checkbox" name="items[{{ $idx }}][tengo]" value="1" id="tengo-{{ $uid }}" class="switch-input" onchange="toggleProductStock('{{ $uid }}')">
                                        <label for="tengo-{{ $uid }}" class="switch-label"></label>
                                    </div>
                                </div>

                                <div class="stock-fields">
                                    <div class="field-group">
                                        <label class="field-label">Disponibles (Max: {{ $qty }})</label>
                                        <div class="qty-selector">
                                            <button type="button" class="qty-btn" onclick="adjustQty('{{ $uid }}', -1)">−</button>
                                            <input type="number" name="items[{{ $idx }}][qty]" id="qty-{{ $uid }}" value="{{ $qty }}" min="1" max="{{ $qty }}" readonly class="qty-input" onchange="calculateTotals()">
                                            <button type="button" class="qty-btn" onclick="adjustQty('{{ $uid }}', 1, {{ $qty }})">+</button>
                                        </div>
                                    </div>
                                    <div class="field-group">
                                        <label class="field-label">Precio Unit. (S/.)</label>
                                        <div class="price-input-wrapper">
                                            <span class="price-symbol">S/</span>
                                            <input type="number" name="items[{{ $idx }}][price]" id="price-{{ $uid }}" step="0.01" min="0" placeholder="0.00" class="price-input" oninput="calculateTotals()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-panel">
                        <div class="summary-row">
                            <span>Productos Seleccionados:</span>
                            <span id="summary-items-count">0</span>
                        </div>
                        <div class="summary-row total">
                            <span>Cotización Total:</span>
                            <span class="summary-total-val">S/ <span id="summary-total">0.00</span></span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success" id="btnSubmit">
                        Enviar Confirmación
                    </button>
                </div>
            </form>
        @endif

        <footer>
            &copy; 2026 RepuestoFijo. Todos los derechos reservados.
        </footer>
    </div>

    <script>
        function toggleProductStock(uid) {
            const card = document.getElementById('card-' + uid);
            const checkbox = document.getElementById('tengo-' + uid);
            const priceInput = document.getElementById('price-' + uid);
            
            if (checkbox.checked) {
                card.classList.add('has-stock');
                priceInput.required = true;
            } else {
                card.classList.remove('has-stock');
                priceInput.required = false;
                priceInput.value = '';
            }
            calculateTotals();
        }

        function adjustQty(uid, amount, max = 999) {
            const qtyInput = document.getElementById('qty-' + uid);
            let current = parseInt(qtyInput.value) || 0;
            current += amount;
            if (current < 1) current = 1;
            if (current > max) current = max;
            qtyInput.value = current;
            calculateTotals();
        }

        function calculateTotals() {
            let total = 0;
            let count = 0;

            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                const uid = card.id.replace('card-', '');
                const checkbox = document.getElementById('tengo-' + uid);
                
                if (checkbox && checkbox.checked) {
                    const qty = parseInt(document.getElementById('qty-' + uid).value) || 0;
                    const price = parseFloat(document.getElementById('price-' + uid).value) || 0;
                    total += qty * price;
                    count++;
                }
            });

            const countEl = document.getElementById('summary-items-count');
            const totalEl = document.getElementById('summary-total');
            const btnSubmit = document.getElementById('btnSubmit');

            if (countEl) countEl.innerText = count;
            if (totalEl) totalEl.innerText = total.toFixed(2);

            if (btnSubmit) {
                if (count === 0) {
                    btnSubmit.innerText = "No tengo stock (Rechazar)";
                    btnSubmit.classList.remove('btn-success');
                    btnSubmit.style.backgroundColor = 'var(--danger-color)';
                } else {
                    btnSubmit.innerText = "Enviar Confirmación (" + count + " " + (count === 1 ? "producto" : "productos") + ")";
                    btnSubmit.classList.add('btn-success');
                    btnSubmit.style.backgroundColor = '';
                }
            }
        }

        // Initialize total text
        document.addEventListener('DOMContentLoaded', () => {
            calculateTotals();
        });
    </script>
</body>
</html>
