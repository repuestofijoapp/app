<div class="onboarding-container">
    <style>
        .onboarding-container {
            position: fixed;
            inset: 0;
            background: #020617;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            font-family: 'Syne', sans-serif;
            color: white;
            padding: 1rem;
            overflow-y: auto;
        }

        .bg-blobs {
            position: fixed;
            inset: 0;
            overflow: hidden;
            z-index: -1;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: move 20s infinite alternate;
        }

        .blob-1 { width: 400px; height: 400px; background: #3b82f6; top: -100px; left: -100px; }
        .blob-2 { width: 500px; height: 500px; background: #be3c3b; bottom: -150px; right: -150px; animation-delay: -5s; }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(50px, 100px) scale(1.1); }
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 56rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .progress-bar {
            height: 4px;
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #be3c3b);
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .title-gradient {
            background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 600 !important;
            font-size: 2.2rem;
            line-height: 1.1;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 2rem;
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Step 1: Roles ── */
        .options-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.875rem;
        }

        @media (min-width: 640px) {
            .options-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .option-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            width: 100%;
            padding: 1.25rem 1.25rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1.25rem;
            color: white;
            cursor: pointer;
            transition: all 0.25s;
            text-align: left;
        }

        .option-btn:hover {
            background: rgba(59,130,246,0.1);
            border-color: rgba(59,130,246,0.4);
            transform: translateX(6px);
        }

        .option-icon {
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            transition: all 0.25s;
        }

        .option-btn:hover .option-icon {
            background: rgba(59,130,246,0.2);
            color: #3b82f6;
        }

        .option-title {
            display: block;
            font-size: 1rem;
            font-weight: 600 !important;
            color: #3b82f6;
            margin-bottom: 2px;
        }

        .option-desc {
            font-size: 0.8rem;
            color: #475569;
            font-family: 'DM Sans', sans-serif;
        }

        /* ── Step 2: Comprobante ── */
        .receipt-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.875rem;
        }

        .receipt-card {
            padding: 1.5rem 1rem;
            background: rgba(255,255,255,0.03);
            border: 2px solid rgba(255,255,255,0.08);
            border-radius: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s;
        }

        .receipt-card:hover {
            border-color: rgba(59,130,246,0.3);
        }

        .receipt-card.active {
            background: rgba(59,130,246,0.1);
            border-color: #3b82f6;
            box-shadow: 0 0 20px rgba(59,130,246,0.15);
        }

        .receipt-card i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            display: block;
            color: #475569;
            transition: color 0.25s;
        }

        .receipt-card.active i { color: #3b82f6; }

        .receipt-card b {
            font-weight: 600 !important;
            font-size: 1.05rem;
        }

        /* ── Input área ── */
        .doc-section {
            margin-top: 1.75rem;
        }

        .doc-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 0.625rem;
            font-family: 'DM Sans', sans-serif;
        }

        /* Badge que aparece al auto-detectar el tipo */
        .doc-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 999px;
        }

        .doc-type-badge.dni { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .doc-type-badge.ce  { background: rgba(139,92,246,0.15);  color: #a78bfa; }

        .input-wrapper {
            position: relative;
        }

        .custom-input {
            width: 100%;
            background: rgba(0,0,0,0.3);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 15px 50px 15px 16px;
            color: white;
            font-size: 1.05rem;
            outline: none;
            transition: border-color 0.25s;
            font-family: 'DM Mono', 'Courier New', monospace;
            letter-spacing: 0.08em;
        }

        .custom-input:focus { border-color: #3b82f6; }
        .custom-input::placeholder { letter-spacing: 0.02em; color: #374151; font-family: 'DM Sans', sans-serif; }

        .input-spinner {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2.5px solid rgba(255,255,255,.2);
            border-radius: 50%;
            border-top-color: #3b82f6;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .doc-hint {
            margin-top: 0.5rem;
            font-size: 0.78rem;
            color: #334155;
            font-family: 'DM Sans', sans-serif;
        }

        .doc-error {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #f87171;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ── Nombre encontrado ── */
        .name-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            padding: 14px 16px;
            border-radius: 14px;
            margin-top: 1rem;
            animation: slideUp 0.35s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .name-badge-icon {
            width: 38px;
            height: 38px;
            background: rgba(16,185,129,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .name-badge-text small {
            display: block;
            color: #10b981;
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .name-badge-text strong {
            font-size: 0.98rem;
            font-weight: 700;
        }

        /* ── Rate limit warning ── */
        .rate-limit-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #475569;
            font-family: 'DM Sans', sans-serif;
            margin-top: 0.5rem;
        }

        .rate-limit-info.warning { color: #f59e0b; }

        /* ── Botón ── */
        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            padding: 17px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 1.25rem;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover { box-shadow: 0 10px 20px rgba(37,99,235,0.3); }
        .btn-primary:active { transform: scale(0.98); }

        /* ── Back button ── */
        .back-btn {
            background: none;
            border: none;
            color: #475569;
            cursor: pointer;
            padding: 8px;
            margin-left: -8px;
            border-radius: 8px;
            transition: color 0.2s;
            line-height: 1;
        }

        .back-btn:hover { color: #94a3b8; }

        /* ── Mobile ── */
        @media (max-width: 640px) {
            .glass-card {
                padding: 1.5rem;
                width: calc(100vw - 2rem);
            }
            .title-gradient { font-size: 1.8rem; }
        }
    </style>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="glass-card">

        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $step == 1 ? '50%' : '100%' }};"></div>
        </div>

        {{-- ══════════ STEP 1: Rol ══════════ --}}
        @if($step == 1)
            <h1 class="title-gradient">¡Hola, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
            <p class="subtitle">Para personalizar tu experiencia, ¿cuál es tu actividad?</p>

            <div class="options-grid">
                <button type="button" wire:click="setRole('mechanic')" class="option-btn">
                    <div class="option-icon"><i class="fas fa-wrench"></i></div>
                    <div>
                        <span class="option-title">Mecánico</span>
                        <span class="option-desc">Servicio independiente</span>
                    </div>
                </button>

                <button type="button" wire:click="setRole('workshop')" class="option-btn">
                    <div class="option-icon"><i class="fas fa-tools"></i></div>
                    <div>
                        <span class="option-title">Taller Automotriz</span>
                        <span class="option-desc">Local o taller establecido</span>
                    </div>
                </button>

                <button type="button" wire:click="setRole('store')" class="option-btn">
                    <div class="option-icon"><i class="fas fa-store"></i></div>
                    <div>
                        <span class="option-title">Tienda de Repuestos</span>
                        <span class="option-desc">Venta mayorista o minorista</span>
                    </div>
                </button>
            </div>

        {{-- ══════════ STEP 2: Comprobante + Documento ══════════ --}}
        @else
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1.5rem;">
                <button wire:click="$set('step', 1)" class="back-btn" title="Volver">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h2 style="font-size:1.35rem; margin:0; font-weight:700;">Datos de Facturación</h2>
            </div>

            {{-- Selector boleta / factura --}}
            <div class="receipt-grid">
                <div wire:click="setReceiptType('boleta')"
                     class="receipt-card {{ $receiptType == 'boleta' ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i>
                    <b>Boleta</b>
                    <p style="margin:4px 0 0; font-size:0.78rem; color:#475569; font-family:'DM Sans',sans-serif;">Persona natural</p>
                </div>

                <div wire:click="setReceiptType('factura')"
                     class="receipt-card {{ $receiptType == 'factura' ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <b>Factura</b>
                    <p style="margin:4px 0 0; font-size:0.78rem; color:#475569; font-family:'DM Sans',sans-serif;">Empresa o negocio</p>
                </div>
            </div>

            {{-- ──────── FACTURA → RUC ──────── --}}
            @if($receiptType === 'factura')
                <div class="doc-section">
                    <div class="doc-label">
                        <i class="fas fa-building" style="font-size:0.85rem;"></i>
                        Número de RUC
                    </div>
                    <div class="input-wrapper">
                        <input wire:model.live.debounce.400ms="ruc"
                               wire:keydown.enter.prevent="consultarRuc"
                               type="text"
                               inputmode="numeric"
                               maxlength="11"
                               class="custom-input"
                               placeholder="20XXXXXXXXX">
                        @if($isConsulting)
                            <div class="input-spinner"><div class="loading-spinner"></div></div>
                        @endif
                    </div>
                    @error('ruc')
                        <div class="doc-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                @if($businessName)
                    <div class="name-badge">
                        <div class="name-badge-icon"><i class="fas fa-check"></i></div>
                        <div class="name-badge-text">
                            <small>Razón Social</small>
                            <strong>{{ $businessName }}</strong>
                        </div>
                    </div>

                    <button wire:click="completeOnboarding" wire:loading.attr="disabled" class="btn-primary">
                        <i class="fas fa-check-circle"></i>
                        Completar Registro
                    </button>
                @endif

            {{-- ──────── BOLETA → DNI / CE (input unificado) ──────── --}}
            @elseif($receiptType === 'boleta')
                <div class="doc-section">
                    <div class="doc-label" style="justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-id-card" style="font-size:0.85rem;"></i>
                            <span>DNI o Carnet de Extranjería</span>
                        </div>

                        {{-- Badge dinámico alineado a la derecha --}}
                        @if($detectedDocType === 'dni')
                            <span class="doc-type-badge dni"><i class="fas fa-id-card"></i> DNI</span>
                        @elseif($detectedDocType === 'ce')
                            <span class="doc-type-badge ce"><i class="fas fa-passport"></i> Carnet</span>
                        @endif
                    </div>

                    <div class="input-wrapper">
                        <input wire:model.live.debounce.400ms="doc"
                               wire:blur="consultarDocManual"
                               wire:keydown.enter.prevent="consultarDocManual"
                               type="text"
                               maxlength="12"
                               class="custom-input"
                               placeholder="DNI (8 dígitos) o Carnet de Extranjería"
                               autocomplete="off">
                        @if($isConsulting)
                            <div class="input-spinner"><div class="loading-spinner"></div></div>
                        @endif
                    </div>

                    <p class="doc-hint">
                        <i class="fas fa-magic" style="color:#3b82f6; margin-right:4px;"></i>
                        El tipo de documento se detecta automáticamente.
                    </p>

                    @error('doc')
                        <div class="doc-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror

                    {{-- Aviso de intentos restantes (visible cuando quedan ≤ 3) --}}
                    @if($this->remainingAttempts <= 3 && $this->remainingAttempts > 0)
                        <div class="rate-limit-info warning">
                            <i class="fas fa-shield-alt"></i>
                            {{ $this->remainingAttempts }} consulta(s) restante(s) en esta hora.
                        </div>
                    @endif
                </div>

                @if($fullName)
                    <div class="name-badge">
                        <div class="name-badge-icon"><i class="fas fa-user-check"></i></div>
                        <div class="name-badge-text">
                            <small>Nombre encontrado</small>
                            <strong>{{ $fullName }}</strong>
                        </div>
                    </div>

                    <button wire:click="completeOnboarding" wire:loading.attr="disabled" class="btn-primary">
                        <i class="fas fa-check-circle"></i>
                        Completar Registro
                    </button>
                @endif
            @endif
        @endif

    </div>
</div>
