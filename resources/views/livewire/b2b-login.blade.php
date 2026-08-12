<div>
    <style>
        :root {
            --bg-color: #0b1121;
            --card-bg: #131a2c;
            --input-bg: #182035;
            --border-color: #1e293b;
            --text-main: #ffffff;
            --text-muted: #94a3b8;
            --accent-red: #1da04f;
            --accent-hover: #15803d;
        }
        
        .login-page {
            min-height: 100vh;
            background-color: var(--bg-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            padding: 1rem;
        }

        .login-logo {
            max-width: 280px;
            margin-bottom: 2rem;
            filter: drop-shadow(0 0 10px rgba(185, 50, 55, 0.2));
        }

        .login-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            width: 100%;
            max-width: 480px;
            padding: 2.5rem 3rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .lock-icon-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #1da04fff;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            margin-right: 12px;
            vertical-align: middle;
        }

        .login-title {
            color: var(--text-main);
            font-size: 1.25rem;
            font-weight: 600;
            display: inline-block;
            vertical-align: middle;
            margin: 0;
        }

        .login-subtitle {
            color: var(--text-main);
            font-size: 0.95rem;
            margin-top: 1rem;
            margin-bottom: 0;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: var(--text-main);
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem 1.25rem;
            color: var(--text-main);
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        .form-input::placeholder {
            color: var(--text-muted);
        }

        .btn-submit {
            background-color: var(--accent-red);
            color: white;
            width: 100%;
            padding: 1rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            background-color: var(--accent-hover);
        }

        .btn-submit i {
            margin-left: 8px;
        }

        .footer-text {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 2rem;
            text-align: center;
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            text-align: center;
        }
    </style>

    <div class="login-page">
        <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo Logo" class="login-logo">
        
        <div class="login-card">
            <div class="login-header">
                <div>
                    <div class="lock-icon-container">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h1 class="login-title">Acceso B2B</h1>
                </div>
                <p class="login-subtitle">Ingresa tus credenciales para continuar</p>
            </div>

            @if (session()->has('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="login">
                <div class="form-group">
                    <label class="form-label">RUC</label>
                    <input type="text" wire:model="ruc" class="form-input" placeholder="Ej: 20123456789">
                    @error('ruc') <span class="text-danger mt-1 d-block" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">CONTRASEÑA</label>
                    <input type="password" wire:model="password" class="form-input" placeholder="••••••••">
                    @error('password') <span class="text-danger mt-1 d-block" style="font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>Ingresar <i class="fas fa-arrow-right"></i></span>
                    <span wire:loading><i class="fas fa-spinner fa-spin"></i> Validando...</span>
                </button>
            </form>
        </div>

        <div class="footer-text">
            RepuestoFijo &copy; {{ date('Y') }} &middot; Powered by ZettaThink
        </div>
    </div>
</div>
