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
            to { transform: translate(50px, 100px) scale(1.1); }
        }

        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 58rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            margin-bottom: 30px;
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
            margin-bottom: 12px;
        }

        .subtitle {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-bottom: 32px;
            font-family: 'DM Sans', sans-serif;
        }

        .options {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .options {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .option-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 1.25rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            text-align: left;
            position: relative;
            overflow: hidden;
        }

        .option-btn:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.4);
            transform: translateX(10px);
        }

        .option-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 18px;
            transition: transform 0.3s;
        }

        .option-btn:hover .option-icon {
            transform: scale(1.1) rotate(5deg);
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .option-title {
            display: block;
            font-size: 1.1rem;
            font-weight: 600 !important;
            margin-bottom: 2px;
            color: #3b82f6;
        }

        .option-content span {
            font-size: 0.85rem;
            color: #64748b;
            font-family: 'DM Sans', sans-serif;
        }

        .receipt-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 20px;
        }

        .receipt-card {
            padding: 1.5rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 2px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .receipt-card.active {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);
        }

        .receipt-card i {
            font-size: 2rem;
            margin-bottom: 12px;
            display: block;
            color: #64748b;
        }

        .receipt-card.active i {
            color: #3b82f6;
        }
        
        .receipt-card b {
            font-weight: 600 !important;
            font-size: 1.125rem;
        }

        .input-group {
            margin-top: 24px;
        }

        .custom-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 16px;
            color: white;
            font-size: 1.1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .custom-input:focus {
            border-color: #3b82f6;
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-top: 24px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:active { transform: scale(0.98); }
        .btn-primary:hover { box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3); }

        .company-badge {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            padding: 16px;
            border-radius: 16px;
            margin-top: 16px;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* Mobile specific adjustments */
        @media (max-width: 768px) {
            .glass-card { 
                padding: 1.5rem; 
                width: calc(100vw - 2rem);
            }
            .title-gradient { font-size: 1.8rem; }
            .onboarding-container { padding: 1rem; }
        }
    </style>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="glass-card">
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $step == 1 ? '50%' : '100%' }}"></div>
        </div>

        @if($step == 1)
            <h1 class="title-gradient">¡Hola, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
            <p class="subtitle">Para personalizar tu experiencia,<br>¿cuál es tu actividad?</p>

            <div class="options">
                <button type="button" wire:click="setRole('mechanic')" class="option-btn">
                    <div class="option-icon"><i class="fas fa-wrench"></i></div>
                    <div class="option-content">
                        <span class="option-title">Mecánico Particular</span>
                        <span>Servicio independiente</span>
                    </div>
                </button>

                <button type="button" wire:click="setRole('workshop')" class="option-btn">
                    <div class="option-icon"><i class="fas fa-tools"></i></div>
                    <div class="option-content">
                        <span class="option-title">Taller Automotriz</span>
                        <span>Local o taller establecido</span>
                    </div>
                </button>

                <button type="button" wire:click="setRole('store')" class="option-btn">
                    <div class="option-icon"><i class="fas fa-store"></i></div>
                    <div class="option-content">
                        <span class="option-title">Tienda de Repuestos</span>
                        <span>Venta mayorista o minorista</span>
                    </div>
                </button>
            </div>
        @else
            <div style="display: flex; align-items: center; margin-bottom: 20px;">
                <button wire:click="$set('step', 1)" style="background: none; border: none; color: #64748b; cursor: pointer; padding: 10px; margin-left: -10px;">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h2 style="font-size: 1.4rem; margin: 0; font-weight: 700;">Preferencia de Pago</h2>
            </div>

            <p class="subtitle">¿Cómo facturamos tus pedidos?</p>

            <div class="receipt-grid">
                <div wire:click="setReceiptType('boleta')" class="receipt-card {{ $receiptType == 'boleta' ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i>
                    <b>Boleta</b>
                </div>

                <div wire:click="setReceiptType('factura')" class="receipt-card {{ $receiptType == 'factura' ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <b>Factura</b>
                </div>
            </div>

            @if($receiptType === 'factura')
                <div class="input-group">
                    <label style="color: #64748b; font-size: 0.85rem; display: block; margin-bottom: 8px;">NÚMERO DE RUC</label>
                    <div style="position: relative;">
                        <input wire:model.live="ruc" type="text" maxlength="11" class="custom-input" placeholder="20XXXXXXXXX">
                        @if($isConsultingRuc)
                            <div style="position: absolute; right: 15px; top: 15px;"><div class="loading-spinner"></div></div>
                        @endif
                    </div>
                    @error('ruc') <span style="color: #f87171; font-size: 0.8rem; margin-top: 8px; display: block;">{{ $message }}</span> @enderror
                </div>

                @if($businessName)
                    <div class="company-badge">
                        <small style="color: #10b981; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Razón Social</small>
                        <div style="color: white; font-weight: 700; margin-top: 4px;">{{ $businessName }}</div>
                    </div>

                    <button wire:click="completeOnboarding" class="btn-primary">
                        Completar Registro
                    </button>
                @endif
            @elseif($receiptType === 'boleta')
                <div class="input-group">
                    <label style="color: #64748b; font-size: 0.85rem; display: block; margin-bottom: 8px;">NÚMERO DE DNI</label>
                    <div style="position: relative;">
                        <input wire:model.live="dni" type="text" maxlength="8" class="custom-input" placeholder="XXXXXXXX">
                        @if($isConsultingRuc)
                            <div style="position: absolute; right: 15px; top: 15px;"><div class="loading-spinner"></div></div>
                        @endif
                    </div>
                    @error('dni') <span style="color: #f87171; font-size: 0.8rem; margin-top: 8px; display: block;">{{ $message }}</span> @enderror
                </div>

                @if($fullName)
                    <div class="company-badge">
                        <small style="color: #10b981; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Nombre Completo</small>
                        <div style="color: white; font-weight: 700; margin-top: 4px;">{{ $fullName }}</div>
                    </div>

                    <button wire:click="completeOnboarding" class="btn-primary">
                        Completar Registro
                    </button>
                @endif
            @endif
        @endif
    </div>
</div>
