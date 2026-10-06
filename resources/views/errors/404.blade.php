<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repuesto no encontrado (404) | RepuestoFijo</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,400;1,700&family=Syne:wght@600;700;800&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
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

        /* ── AVATAR DE SESIÓN DEL USUARIO ── */
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

        /* ── PÍLDORA DE MI REPARACIÓN ── */
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

        /* ── BUSCADOR MÓVIL (FILA SECUNDARIA) ── */
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
            padding: 30px 16px 60px;
        }

        /* ── HERO 404: 2 COLUMNAS EN DESKTOP, APILADO EN MÓVIL (SIN FONDO AZUL) ── */
        .hero-404-layout {
            max-width: 980px;
            margin: 15px auto 45px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 48px;
            padding: 10px 16px;
        }

        .hero-404-media {
            flex: 0 0 440px;
            max-width: 440px;
            text-align: center;
        }

        .img-404 {
            width: 100%;
            max-width: 440px;
            height: auto;
            object-fit: contain;
            display: block;
        }

        .hero-404-content {
            flex: 1;
            text-align: left;
        }

        .badge-404 {
            font-family: 'Syne', sans-serif;
            font-size: 6rem;
            font-weight: 800;
            line-height: 0.95;
            color: var(--primary-red);
            letter-spacing: 2px;
            margin-bottom: 12px;
            font-style: italic;
            text-shadow: 0 2px 8px rgba(190, 60, 59, 0.25);
        }

        .title-404 {
            font-family: 'Syne', sans-serif;
            font-size: 2.3rem;
            font-weight: 800;
            color: var(--dark-blue);
            margin-bottom: 12px;
            line-height: 1.15;
            letter-spacing: -0.5px;
        }

        .desc-404 {
            color: #475569;
            font-size: 1.15rem;
            font-weight: 500;
            font-style: italic;
            line-height: 1.5;
        }

        /* ── SECCIÓN NOVEDADES ── */
        .featured-wrapper {
            margin-top: 20px;
            width: 100%;
        }

        .featured-title {
            text-align: center;
            color: #4B5563;
            font-family: 'Syne', sans-serif;
            letter-spacing: 1.5px;
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 26px;
            text-transform: uppercase;
        }

        .carousel-relative {
            position: relative;
            padding: 0 35px;
        }

        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border: 1px solid #dee2e6;
            background: #FFFFFF;
            color: #6c757d;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 5;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
        }

        .carousel-arrow:hover {
            background: #f8f9fa;
            color: #212529;
        }

        .carousel-arrow.left { left: 0; }
        .carousel-arrow.right { right: 0; }

        .featured-scroll-container {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
            padding: 10px 0;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .featured-scroll-container::-webkit-scrollbar {
            display: none;
        }

        .product-card {
            width: 240px;
            flex-shrink: 0;
            scroll-snap-align: start;
            background-color: #F8F9FB;
            border-radius: 6px;
            padding: 22px 18px;
            text-align: center;
            display: flex;
            flex-direction: column;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .product-img-box {
            height: 140px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img-box img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .product-divider {
            position: relative;
            margin-bottom: 14px;
        }

        .product-divider hr {
            border: 0;
            border-top: 1px solid rgba(108, 117, 125, 0.25);
            margin: 0;
        }

        .product-divider .dot {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 6px;
            height: 6px;
            background-color: #f97316;
            border-radius: 50%;
        }

        .product-name {
            font-size: 0.85rem;
            min-height: 3.6rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: #6C757D;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .btn-discover {
            margin-top: auto;
            width: 100%;
            padding: 10px 0;
            background-color: #132530;
            color: #FFFFFF;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 3px;
            text-decoration: none;
            display: block;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background 0.2s;
        }

        .btn-discover:hover {
            background-color: #1d3748;
            color: #FFFFFF;
        }

        /* ── BARRA DE NAVEGACIÓN INFERIOR MÓVIL (OFICIAL) ── */
        .mobile-bottom-nav {
            display: none;
        }

        /* ── ADAPTACIÓN MÓVIL EXACTA A LA CAPTURA ── */
        @media (max-width: 768px) {
            .header-container {
                justify-content: center;
                padding: 6px 16px;
            }

            .header-search-form { display: none; }
            .header-right-group { display: none; }
            .header-mobile-search { display: block; }

            .logo-box {
                text-align: center;
                width: 100%;
            }

            .logo-box img {
                margin: 0 auto;
                max-width: 150px;
                max-height: 40px;
            }

            .page-container {
                padding: 16px 12px 85px; /* Espacio para barra inferior */
            }

            .hero-404-layout {
                flex-direction: column;
                text-align: center;
                gap: 16px;
                margin: 10px auto 25px auto;
                padding: 10px 8px;
            }

            .hero-404-media {
                flex: none;
                max-width: 270px;
                margin: 0 auto;
            }

            .img-404 {
                max-width: 270px;
            }

            .hero-404-content {
                text-align: center;
            }

            .badge-404 {
                font-size: 4.2rem;
                margin-bottom: 4px;
            }

            .title-404 {
                font-size: 1.65rem;
                margin-bottom: 8px;
            }

            .desc-404 {
                font-size: 0.98rem;
                line-height: 1.45;
            }

            .carousel-relative { padding: 0 4px; }
            .carousel-arrow { display: none; }
            .product-card { width: 210px; padding: 16px 12px; }
            .product-img-box { height: 120px; }

            /* Barra inferior roja */
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

            {{-- Buscador OEM de escritorio --}}
            <form action="{{ route('home') }}" method="GET" class="header-search-form">
                <input type="text" name="oem" placeholder="Busca por código OEM..." autocomplete="off">
                <button type="submit">BUSCAR</button>
            </form>

            <div class="header-right-group">
                {{-- SESIÓN DEL USUARIO EN DESKTOP (AVATAR / FOTO) --}}
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

                {{-- INDICADOR DE MI REPARACIÓN / CARRITO EN DESKTOP --}}
                <a href="{{ route('home') }}" class="cart-indicator">
                    <div class="cart-circle">{{ $cartCount }}</div>
                    <div>
                        <div class="cart-text-small">Mi</div>
                        <div class="cart-text-bold">reparación</div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Buscador OEM Móvil --}}
        <div class="header-mobile-search">
            <form action="{{ route('home') }}" method="GET">
                <input type="text" name="oem" placeholder="Busca por código OEM..." autocomplete="off">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </header>

    {{-- CONTENEDOR DE LA PÁGINA (FONDO BLANCO) --}}
    <main class="page-container">
        {{-- HERO 404: 2 COLUMNAS EN DESKTOP, APILADO EN MÓVIL (SIN FONDO AZUL) --}}
        <div class="hero-404-layout">
            <div class="hero-404-media">
                <img src="{{ asset('images/404.jpg') }}" alt="404 - Repuesto no encontrado" class="img-404">
            </div>

            <div class="hero-404-content">
                <div class="badge-404">404</div>
                <h1 class="title-404">Repuesto no encontrado</h1>
                <p class="desc-404">Intenta de nuevo, antes que cerremos la ultima caja.</p>
            </div>
        </div>

        {{-- SECCIÓN NOVEDADES --}}
        @php
            $featuredProductsList = collect();
            try {
                $featuredProductsList = \App\Models\FeaturedProduct::getActive();
            } catch (\Exception $e) {
                // Silencioso en caso de error de BD
            }
        @endphp

        @if($featuredProductsList->count() > 0)
            <div class="featured-wrapper">
                <h4 class="featured-title">NOVEDADES</h4>

                <div class="carousel-relative">
                    {{-- Flecha Izquierda --}}
                    <button type="button"
                            class="carousel-arrow left"
                            onclick="document.getElementById('featured-products-container').scrollBy({left: -260, behavior: 'smooth'})"
                            aria-label="Anterior">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    {{-- Contenedor Scroll --}}
                    <div id="featured-products-container" class="featured-scroll-container">
                        @foreach($featuredProductsList as $fp)
                            <div class="product-card">
                                <div class="product-img-box">
                                    <img src="{{ ($fp->product && $fp->product->image_path) ? Storage::url($fp->product->image_path) : 'https://via.placeholder.com/150?text=Sin+Imagen' }}"
                                         alt="{{ $fp->product->name ?? 'Repuesto' }}">
                                </div>

                                <div class="product-divider">
                                    <hr>
                                    <div class="dot"></div>
                                </div>

                                <h6 class="product-name">
                                    {{ $fp->product->name ?? 'Producto Destacado' }}
                                </h6>

                                <a href="{{ route('home', ['featured' => $fp->product_id]) }}" class="btn-discover">
                                    Descubrir ahora
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- Flecha Derecha --}}
                    <button type="button"
                            class="carousel-arrow right"
                            onclick="document.getElementById('featured-products-container').scrollBy({left: 260, behavior: 'smooth'})"
                            aria-label="Siguiente">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        @endif
    </main>

    {{-- BARRA INFERIOR ROJA EN MÓVIL (IDÉNTICA A LA TIENDA) --}}
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
</body>
</html>
