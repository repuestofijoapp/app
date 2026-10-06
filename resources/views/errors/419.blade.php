<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizando sesión | RepuestoFijo</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-red: #BE3C3B;
            --dark-blue: #132530;
            --light-gray: #F9FAFB;
            --text-dark: #333333;
            --text-muted: #6C757D;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #FFFFFF;
            color: var(--text-dark);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ── HEADER OFICIAL REPUESTOFIJO ── */
        .bg-header-custom {
            background-color: #132530;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1020;
        }

        .header-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .logo-box a {
            display: inline-block;
            text-decoration: none;
        }

        .logo-box img {
            max-width: 180px;
            max-height: 44px;
            object-fit: contain;
            display: block;
        }

        .header-search-form {
            flex: 1;
            max-width: 580px;
            display: flex;
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: #FFFFFF;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .header-search-form input {
            flex: 1;
            height: 46px;
            border: none;
            padding: 0 16px;
            font-size: 0.95rem;
            outline: none;
            color: #333;
            font-family: 'DM Sans', sans-serif;
        }

        .header-search-form button {
            background-color: #0d6efd;
            border: none;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0 24px;
            letter-spacing: 0.8px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .header-search-form button:hover {
            background-color: #0b5ed7;
        }

        .header-right-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar-btn {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .user-avatar-btn:hover {
            transform: scale(1.05);
        }

        .user-avatar-img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
        }

        .user-avatar-placeholder {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 1.1rem;
        }

        .cart-indicator {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: 4px 18px 4px 4px;
            height: 46px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .cart-indicator:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        .cart-circle {
            width: 36px;
            height: 36px;
            background-color: var(--primary-red);
            color: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            margin-right: 8px;
            box-shadow: 0 2px 6px rgba(190, 60, 59, 0.4);
        }

        .cart-text-small {
            font-size: 0.62rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.65);
            line-height: 1;
        }

        .cart-text-bold {
            font-size: 0.85rem;
            font-weight: 700;
            color: #FFFFFF;
            line-height: 1.1;
        }

        .header-mobile-search {
            display: none;
            padding: 8px 16px 4px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .header-mobile-search form {
            display: flex;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: #FFFFFF;
        }

        .header-mobile-search input {
            flex: 1;
            height: 42px;
            border: none;
            padding: 0 14px;
            font-size: 0.9rem;
            outline: none;
        }

        .header-mobile-search button {
            background-color: var(--primary-red);
            border: none;
            color: #FFFFFF;
            padding: 0 18px;
            font-size: 1rem;
            cursor: pointer;
        }

        /* ── CONTENIDO PRINCIPAL (FONDO BLANCO) ── */
        .page-container {
            flex: 1;
            max-width: 1240px;
            width: 100%;
            margin: 0 auto;
            padding: 50px 16px 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-419 {
            background-color: #FFFFFF;
            border-radius: 20px;
            padding: 42px 34px;
            max-width: 560px;
            width: 100%;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.07);
            border: 1px solid #E2E8F0;
        }

        .icon-box {
            width: 72px;
            height: 72px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: rgba(190, 60, 59, 0.1);
            border: 1.5px solid rgba(190, 60, 59, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-red);
            font-size: 1.9rem;
            position: relative;
        }

        .spinner-ring {
            position: absolute;
            inset: -4px;
            border: 2.5px solid transparent;
            border-top-color: var(--primary-red);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .title-419 {
            font-family: 'Syne', sans-serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: #132530;
            margin-bottom: 12px;
        }

        .desc-419 {
            color: #64748B;
            font-size: 0.98rem;
            line-height: 1.6;
            margin-bottom: 26px;
            max-width: 460px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: var(--primary-red);
            color: #FFFFFF;
            padding: 13px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(190, 60, 59, 0.3);
        }

        .btn-action:hover {
            background-color: #a82b2b;
            transform: translateY(-2px);
            color: #FFFFFF;
        }

        .mobile-bottom-nav {
            display: none;
        }

        @media (max-width: 768px) {
            .header-container {
                justify-content: center;
                padding: 6px 16px;
            }
            .header-search-form { display: none; }
            .header-right-group { display: none; }
            .header-mobile-search { display: block; }
            .logo-box { text-align: center; width: 100%; }
            .logo-box img { margin: 0 auto; max-width: 150px; max-height: 40px; }

            .page-container { padding: 30px 16px 85px; }
            .card-419 { padding: 30px 18px; }
            .title-419 { font-size: 1.35rem; }

            .mobile-bottom-nav {
                display: flex;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: 65px;
                background-color: #BE3C3B !important;
                z-index: 1030;
                justify-content: space-around;
                align-items: center;
                border-top: 1px solid rgba(255, 255, 255, 0.15);
                box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.15);
            }

            .mobile-bottom-nav .nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: #FFFFFF;
                text-decoration: none;
                font-size: 0.68rem;
                gap: 3px;
                position: relative;
            }

            .mobile-bottom-nav .nav-item i {
                font-size: 1.25rem;
            }

            .mobile-bottom-nav .nav-user-img {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                object-fit: cover;
                border: 1.5px solid rgba(255, 255, 255, 0.7);
            }

            .mobile-bottom-nav .cart-pill-badge {
                position: absolute;
                top: -3px;
                right: -6px;
                background: #FFFFFF;
                color: #BE3C3B;
                font-weight: 700;
                font-size: 0.62rem;
                border-radius: 999px;
                padding: 1px 6px;
                border: 1px solid #BE3C3B;
            }
        }
    </style>
</head>
<body>
    @php
        $cartCount = 0;
        if (session()->has('repair_list_session')) {
            $cartCount = array_sum(array_column(session('repair_list_session'), 'qty'));
        } elseif (auth()->check() && !empty(auth()->user()->cart_data)) {
            $cartCount = array_sum(array_column(auth()->user()->cart_data, 'qty'));
        }
    @endphp

    {{-- HEADER OFICIAL DE REPUESTOFIJO --}}
    <header class="bg-header-custom">
        <div class="header-container">
            <div class="logo-box">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo">
                </a>
            </div>

            <form action="{{ route('home') }}" method="GET" class="header-search-form">
                <input type="text" name="oem" placeholder="Busca por código OEM..." autocomplete="off">
                <button type="submit">BUSCAR</button>
            </form>

            <div class="header-right-group">
                @auth
                    <a href="{{ route('dashboard') }}" class="user-avatar-btn" title="{{ auth()->user()->name ?? 'Mi Perfil' }}">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ auth()->user()->profile_photo_path }}" alt="User" class="user-avatar-img">
                        @else
                            <div class="user-avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </a>
                @endauth

                <a href="{{ route('home') }}" class="cart-indicator">
                    <div class="cart-circle">{{ $cartCount }}</div>
                    <div>
                        <div class="cart-text-small">Mi</div>
                        <div class="cart-text-bold">reparación</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="header-mobile-search">
            <form action="{{ route('home') }}" method="GET">
                <input type="text" name="oem" placeholder="Busca por código OEM..." autocomplete="off">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </header>

    {{-- CONTENEDOR DE LA PÁGINA (FONDO BLANCO) --}}
    <main class="page-container">
        <div class="card-419">
            <div class="icon-box">
                <div class="spinner-ring"></div>
                <i class="fas fa-sync-alt"></i>
            </div>

            <h1 id="title-text" class="title-419">Actualizando conexión</h1>
            <p id="msg-text" class="desc-419">Estamos renovando tu sesión de forma segura. Te redirigiremos automáticamente en un momento...</p>

            <a href="{{ route('home') }}" id="btn-action" class="btn-action">
                <i class="fas fa-arrow-right"></i>
                <span>Continuar navegando</span>
            </a>
        </div>
    </main>

    {{-- BARRA INFERIOR ROJA EN MÓVIL (OFICIAL) --}}
    <nav class="mobile-bottom-nav">
        <a href="{{ route('home') }}" class="nav-item">
            <i class="fas fa-th-large"></i>
            <span>Inicio</span>
        </a>

        <a href="{{ route('home') }}" class="nav-item">
            <i class="fas fa-box"></i>
            <span>Mi Reparación</span>
            @if($cartCount > 0)
                <span class="cart-pill-badge">{{ $cartCount }}</span>
            @endif
        </a>

        <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="nav-item">
            @auth
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ auth()->user()->profile_photo_path }}" alt="User" class="nav-user-img">
                @else
                    <i class="fas fa-user-circle"></i>
                @endif
                <span>Cuenta</span>
            @else
                <i class="fas fa-user"></i>
                <span>Ingresar</span>
            @endauth
        </a>
    </nav>

    <script>
        const reloadKey = 'repuestofijo_csrf_auto_reload';
        const hasReloaded = sessionStorage.getItem(reloadKey);

        if (!hasReloaded) {
            sessionStorage.setItem(reloadKey, '1');
            setTimeout(function () {
                if (document.referrer && document.referrer.includes(window.location.host) && !document.referrer.includes('/logout')) {
                    window.location.href = document.referrer;
                } else {
                    window.location.href = "{{ route('home') }}";
                }
            }, 1000);
        } else {
            sessionStorage.removeItem(reloadKey);
            document.getElementById('title-text').innerText = 'Tu sesión ha expirado';
            document.getElementById('msg-text').innerText = 'Por seguridad tu token ha vencido tras un período de inactividad. Haz clic abajo para continuar cotizando.';
            const spinner = document.querySelector('.spinner-ring');
            if (spinner) spinner.style.display = 'none';
        }
    </script>
</body>
</html>
