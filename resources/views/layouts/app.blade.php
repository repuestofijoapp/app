<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#132530">
    <meta name="description" content="RepuestoFijo — La solución integral para repuestos de autos en Lima, Perú. Buscamos en todos los proveedores, consolidamos tu pedido y lo entregamos en tu taller el mismo día.">
    <link rel="canonical" href="{{ url()->current() }}" />
    <title>RepuestoFijo</title>

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Force SweetAlert2 above all modals */
        .swal2-container {
            z-index: 99999 !important;
        }

        /* ─── Variables ─── */
        :root {
            --bg: #0A0E1A;
            --surface: #111827;
            --surface2: #1A2235;
            --surface3: #0E1525;
            --border: rgba(255, 255, 255, 0.07);
            --accent-red: #BE3C3B;
            --accent-red-glow: rgba(190, 60, 59, 0.25);
            --green: #00D68F;
            --red: #FF3B5C;
            --blue: #3B82F6;
            --text: #F0F4FF;
            --muted: #6B7A99;
            --sidebar-w: 265px;
            --sidebar-mini: 68px;
            --topbar-h: 56px;
            --transition: 0.3s cubic-bezier(.4, 0, .2, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            overflow-x: hidden;
            background-image:
                radial-gradient(ellipse at 15% 15%, rgba(59, 130, 246, .08) 0%, transparent 55%),
                radial-gradient(ellipse at 85% 85%, rgba(255, 107, 43, .06) 0%, transparent 55%);
            background-attachment: fixed;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Syne', sans-serif;
        }

        /* ─── Layout shell ─── */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ══════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed;
            inset: 0 auto 0 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: width var(--transition), transform var(--transition);
            overflow: hidden;
        }

        /* ── collapsed (desktop only) ── */
        .sidebar.is-collapsed {
            width: var(--sidebar-mini);
        }

        /* ─ Branding ─ */
        .sb-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1rem 1.25rem 1.25rem;
            border-bottom: 1px solid var(--border);
            min-height: 70px;
            flex-shrink: 0;
        }

        .sb-logo {
            max-height: 40px;
            width: auto;
            flex-shrink: 0;
            transition: opacity var(--transition), max-width var(--transition);
            max-width: 160px;
        }

        .sidebar.is-collapsed .sb-logo {
            opacity: 0;
            max-width: 0;
            pointer-events: none;
        }

        /* Collapse arrow btn (desktop) */
        .sb-collapse-btn {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, .05);
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: all .2s;
        }

        .sb-collapse-btn:hover {
            color: #fff;
            background: var(--accent-red-glow);
            border-color: var(--accent-red);
        }

        .sb-collapse-btn i {
            font-size: .8rem;
            transition: transform var(--transition);
        }

        .sidebar.is-collapsed .sb-collapse-btn i {
            transform: rotate(180deg);
        }

        /* ─ User card (below brand) ─ */
        .sb-user {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .85rem 1.25rem;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
            overflow: hidden;
            transition: padding var(--transition);
        }

        .sidebar.is-collapsed .sb-user {
            justify-content: center;
            padding: .85rem;
        }

        .sb-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-size: cover;
            flex-shrink: 0;
            border: 2px solid var(--border);
        }

        .sb-user-info {
            overflow: hidden;
            flex: 1;
        }

        .sb-user-name {
            font-family: 'Syne', sans-serif;
            font-size: .82rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sb-user-role {
            font-size: .7rem;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sb-user-info,
        .collapse-hide {
            transition: opacity var(--transition), max-width var(--transition);
            max-width: 200px;
        }

        .sidebar.is-collapsed .sb-user-info,
        .sidebar.is-collapsed .collapse-hide {
            opacity: 0;
            max-width: 0;
            pointer-events: none;
            overflow: hidden;
        }

        /* ─ Scrollable nav area ─ */
        .sb-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: .5rem 0 1rem;
            scrollbar-width: thin;
            scrollbar-color: var(--surface2) transparent;
        }

        .sb-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sb-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sb-nav::-webkit-scrollbar-thumb {
            background: var(--surface2);
            border-radius: 4px;
        }

        /* Section label */
        .sb-section {
            padding: .9rem 1.25rem .35rem;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            font-family: 'Syne', sans-serif;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--transition);
        }

        .sidebar.is-collapsed .sb-section {
            opacity: 0;
            padding: .5rem 0;
        }

        /* Menu link */
        .sb-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .62rem 1.25rem;
            color: var(--muted);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 600;
            font-family: 'Syne', sans-serif;
            white-space: nowrap;
            border-left: 3px solid transparent;
            position: relative;
            transition: color .2s, background .2s, border-color .2s, padding .2s;
        }

        .sb-link:hover {
            color: #fff;
            background: rgba(190, 60, 59, .06);
            border-left-color: var(--accent-red);
        }

        .sb-link.active {
            color: #fff;
            background: rgba(190, 60, 59, .1);
            border-left-color: var(--accent-red);
        }

        .sb-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            transition: color .2s;
        }

        .sb-link.active i,
        .sb-link:hover i {
            color: var(--accent-red);
        }

        /* Collapsed menu: icon-only + tooltip */
        .sidebar.is-collapsed .sb-link {
            justify-content: center;
            padding: .7rem;
            border-left: none;
            border-radius: 10px;
            margin: 2px 8px;
        }

        .sidebar.is-collapsed .sb-link:hover {
            background: var(--accent-red-glow);
        }

        .sidebar.is-collapsed .sb-link.active {
            background: var(--accent-red-glow);
        }

        .sb-link-label {
            flex: 1;
            transition: opacity var(--transition), max-width var(--transition);
            max-width: 200px;
            overflow: hidden;
        }

        .sidebar.is-collapsed .sb-link-label {
            opacity: 0;
            max-width: 0;
            pointer-events: none;
        }

        /* ─ Sidebar Submenu ─ */
        .sb-submenu {
            background: rgba(0, 0, 0, 0.15);
            padding: 0.25rem 0;
            margin: 0;
            overflow: hidden;
            border-left: 3px solid rgba(255, 255, 255, 0.03);
        }

        .sidebar.is-collapsed .sb-submenu {
            display: none !important;
        }

        .sb-submenu .sb-link {
            padding-left: 3rem;
            font-size: 0.82rem;
            background: transparent;
            border-left: none;
        }

        .sb-submenu .sb-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .sb-submenu .sb-link.active {
            color: var(--accent-red);
            background: rgba(190, 60, 59, 0.05);
        }

        .sb-dropdown-toggle i.fa-chevron-down {
            font-size: 0.65rem;
            transition: transform var(--transition);
        }

        .sb-dropdown-toggle.is-active i.fa-chevron-down {
            transform: rotate(180deg);
        }

        /* ─ Animations ─ */
        @keyframes sb-pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 214, 143, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(0, 214, 143, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 214, 143, 0);
            }
        }

        @keyframes sb-pulse-yellow {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(255, 193, 7, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
            }
        }

        .sb-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-left: auto;
        }

        .sb-status-dot.active {
            background-color: #00d68f;
            animation: sb-pulse-green 2s infinite;
        }

        .sb-status-dot.offline {
            background-color: #ffc107;
            animation: sb-pulse-yellow 2s infinite;
        }

        .sb-status-dot.error {
            background-color: #ff4444;
        }

        /* Tooltip on collapsed hover */
        .sidebar.is-collapsed .sb-link::after {
            content: attr(data-label);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--surface2);
            color: var(--text);
            padding: 5px 12px;
            border-radius: 8px;
            font-size: .78rem;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            white-space: nowrap;
            border: 1px solid var(--border);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .5);
            z-index: 2000;
            pointer-events: none;
            opacity: 0;
            transition: opacity .15s;
        }

        .sidebar.is-collapsed .sb-link:hover::after {
            opacity: 1;
        }

        /* ─ Sidebar footer (logout) ─ */
        .sb-footer {
            padding: .75rem 1rem;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .btn-logout {
            width: 100%;
            padding: .6rem .75rem;
            border-radius: 10px;
            background: rgba(255, 59, 92, .08);
            color: var(--red);
            border: 1px solid rgba(255, 59, 92, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            font-weight: 700;
            font-family: 'Syne', sans-serif;
            font-size: .82rem;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            transition: all .2s;
        }

        .btn-logout:hover {
            background: var(--red);
            color: #fff;
            border-color: var(--red);
        }

        .btn-logout i {
            flex-shrink: 0;
        }

        /* ══════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════ */
        .main-content {
            flex: 1;
            min-width: 0;
            padding: 2rem;
            transition: margin-left var(--transition);
            @if(auth()->check() && !request()->routeIs('login', 'home'))
                margin-left: var(--sidebar-w);
            @else margin-left: 0;
            @endif
        }

        .main-content.is-expanded {
            margin-left: var(--sidebar-mini);
        }

        /* ══════════════════════════════════════
           FLOATING ARROW BUTTON  (mobile toggle)
        ══════════════════════════════════════ */
        .sidebar-arrow {
            display: none;
            /* hidden on desktop */
            position: fixed;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            z-index: 1200;
            width: 28px;
            height: 52px;
            background: var(--accent-red);
            border: none;
            border-radius: 0 10px 10px 0;
            color: #fff;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            box-shadow: 4px 0 18px rgba(190, 60, 59, .45);
            transition: left var(--transition), background .2s;
        }

        .sidebar-arrow:hover {
            background: #a02e2d;
        }

        .sidebar-arrow i {
            transition: transform var(--transition);
        }

        /* When open: arrow moves with sidebar & flips */
        .sidebar-arrow.is-open {
            left: var(--sidebar-w);
        }

        .sidebar-arrow.is-open i {
            transform: rotate(180deg);
        }

        /* ── Overlay ── */
        .sb-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .65);
            backdrop-filter: blur(3px);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all var(--transition);
        }

        .sb-overlay.is-active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* ══════════════════════════════════════
           CARDS / UTIL
        ══════════════════════════════════════ */
        .card-glass {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .4);
        }

        /* ══════════════════════════════════════
           RESPONSIVE  ≤ 768px
        ══════════════════════════════════════ */
        @media (max-width: 768px) {

            /* Sidebar hidden off-canvas */
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-w) !important;
            }

            .sidebar.is-mobile-open {
                transform: translateX(0);
            }

            /* No desktop collapse btn on mobile */
            .sb-collapse-btn {
                display: none;
            }

            /* Arrow button visible */
            .sidebar-arrow {
                display: flex;
            }

            /* Overlay visible */

            /* Content: full width, no top bar needed */
            .main-content {
                margin-left: 0 !important;
                padding: 1.25rem;
            }
        }
    </style>
    @livewireStyles
</head>

<body>

    @if(auth()->check() && !request()->routeIs('login', 'home'))
        {{-- ── Floating arrow (mobile) ── --}}
        <button class="sidebar-arrow" id="sidebarArrow" aria-label="Abrir / cerrar menú">
            <i class="fas fa-chevron-right"></i>
        </button>

        {{-- ── Overlay ── --}}
        <div class="sb-overlay" id="sbOverlay"></div>
    @endif

    <div class="app-shell">

        {{-- ══════════════════════
        SIDEBAR
        ══════════════════════ --}}
        @if(auth()->check() && !request()->routeIs('login', 'home'))
            <aside class="sidebar" id="sidebar">

                {{-- Brand --}}
                <div class="sb-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo" class="sb-logo">
                    <button class="sb-collapse-btn" id="sbCollapseBtn" title="Colapsar barra">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>

                {{-- User card --}}
                <div class="sb-user">
                    <div class="sb-avatar"
                        style="background-image:url('{{ auth()->user()->profile_photo_path ? Storage::url(auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'Admin') . '&color=7F9CF5&background=1A2235&bold=true&size=80' }}');">
                    </div>
                    <div class="sb-user-info">
                        <div class="sb-user-name">{{ auth()->user()->name ?? 'Administrador' }}</div>
                        <div class="sb-user-role">{{ auth()->user()->email ?? 'admin@prueba.com' }}</div>
                        @if(auth()->check() && auth()->user()->canAccessDashboard())
                            <a href="{{ route('admin.profile', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                class="sb-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}"
                                style="padding: 0; margin-top: 8px; font-size: .875rem; border: none; background: none; gap: .5rem; @if(!request()->routeIs('admin.profile')) color: var(--muted); @endif"
                                data-label="Mi Perfil">
                                <i class="fas fa-user-circle"
                                    style="font-size: 1rem; width: auto; @if(request()->routeIs('admin.profile')) color: var(--accent-red); @endif"></i>
                                <span class="sb-link-label" style="font-family: 'Syne', sans-serif; font-weight: 600;">Mi
                                    Perfil</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="sb-nav">

                    {{-- ══ GENERAL ══ --}}
                    <div class="sb-section">General</div>

                    <a href="{{ route('admin.dashboard', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                        class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-label="Dashboard">
                        <i class="fas fa-th-large"></i>
                        <span class="sb-link-label">Dashboard</span>
                    </a>

                    {{-- Repuestos --}}
                    <a href="{{ route('admin.products', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                        class="sb-link {{ request()->routeIs('admin.products') ? 'active' : '' }}" data-label="Repuestos">
                        <i class="fas fa-cogs"></i>
                        <span class="sb-link-label">Repuestos</span>
                    </a>

                    {{-- Operaciones Group (Desplegable) --}}
                    <div
                        x-data="{ open: {{ request()->routeIs('admin.en-vivo', 'admin.pedidos', 'admin.soporte') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="sb-link sb-dropdown-toggle" :class="open ? 'is-active' : ''"
                            style="width: 100%; border: none; background: transparent; cursor: pointer; justify-content: flex-start; text-align: left;">
                            <i class="fas fa-tasks"></i>
                            <span class="sb-link-label">Operaciones</span>
                            <i class="fas fa-chevron-down ms-auto collapse-hide"
                                :style="open ? 'transform: rotate(180deg);' : ''"
                                style="font-size: 0.7rem; transition: transform 0.3s ease;"></i>
                        </button>

                        <div x-show="open" x-transition.opacity.duration.300ms class="sb-submenu">
                            <a href="{{ route('admin.en-vivo', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                class="sb-link {{ request()->routeIs('admin.en-vivo') ? 'active' : '' }}"
                                data-label="En Vivo">
                                <i class="fas fa-broadcast-tower" style="font-size: 0.8rem;"></i>
                                <span class="sb-link-label">En Vivo</span>
                                @if(request()->routeIs('admin.en-vivo'))
                                    <span class="sb-status-dot active collapse-hide" title="Escuchando"></span>
                                @endif
                            </a>

                            <a href="{{ route('admin.pedidos', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                class="sb-link {{ request()->routeIs('admin.pedidos') ? 'active' : '' }}"
                                data-label="Pedidos">
                                <i class="fas fa-file-invoice" style="font-size: 0.8rem;"></i>
                                <span class="sb-link-label">Pedidos</span>
                            </a>

                            <a href="{{ route('admin.soporte', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                class="sb-link {{ request()->routeIs('admin.soporte') ? 'active' : '' }}"
                                data-label="Soporte">
                                <i class="fas fa-headset" style="font-size: 0.8rem;"></i>
                                <span class="sb-link-label">Soporte</span>
                            </a>
                        </div>
                    </div>

                    {{-- Reportes --}}
                    <a href="#" class="sb-link" data-label="Reportes">
                        <i class="fas fa-chart-line"></i>
                        <span class="sb-link-label">Reportes</span>
                    </a>

                    {{-- ══ ADMINISTRACIÓN ══ --}}
                    @if(auth()->user()->canAccessDashboard())
                        <div class="sb-section" style="margin-top: 1.5rem;">Administración</div>

                        {{-- Usuarios --}}
                        <a href="{{ route('admin.users', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                            class="sb-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" data-label="Usuarios">
                            <i class="fas fa-users"></i>
                            <span class="sb-link-label">Usuarios</span>
                        </a>

                        @if(auth()->user()->isAdmin())
                            {{-- ZettaBot: Solo administradores --}}
                            <a href="{{ route('admin.zettabot', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                class="sb-link {{ request()->routeIs('admin.zettabot') ? 'active' : '' }}" data-label="ZettaBot">
                                <i class="fas fa-robot"></i>
                                <span class="sb-link-label">ZettaBot</span>
                                @php
                                    $whatsapp = resolve(\App\Services\WhatsAppService::class);
                                    $botStatus = $whatsapp->getStatus();
                                @endphp
                                <span class="sb-status-dot 
                                                                                                            @if($botStatus === 'online') active 
                                                                                                            @elseif($botStatus === 'offline') offline 
                                                                                                            @else error @endif 
                                                                                                            collapse-hide"
                                    title="ZettaBot: {{ ucfirst($botStatus) }}"></span>
                            </a>

                            {{-- Proveedores: solo admins --}}
                            <a href="{{ route('admin.providers', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                class="sb-link {{ request()->routeIs('admin.providers') ? 'active' : '' }}"
                                data-label="Proveedores">
                                <i class="fas fa-warehouse"></i>
                                <span class="sb-link-label">Proveedores</span>
                            </a>

                            {{-- Vehículos: solo admins --}}
                            <a href="{{ route('admin.vehicles', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                class="sb-link {{ request()->routeIs('admin.vehicles') ? 'active' : '' }}"
                                data-label="Vehículos">
                                <i class="fas fa-car"></i>
                                <span class="sb-link-label">Vehículos</span>
                            </a>


                            {{-- Seguridad Group (Desplegable) --}}
                            <div
                                x-data="{ open: {{ request()->routeIs('admin.access-logs', 'admin.logs') ? 'true' : 'false' }} }">
                                <button @click="open = !open" class="sb-link sb-dropdown-toggle" :class="open ? 'is-active' : ''"
                                    style="width: 100%; border: none; background: transparent; cursor: pointer; justify-content: flex-start; text-align: left;">
                                    <i class="fas fa-shield-alt"></i>
                                    <span class="sb-link-label">Seguridad</span>
                                    <i class="fas fa-chevron-down ms-auto collapse-hide"
                                        :style="open ? 'transform: rotate(180deg);' : ''"
                                        style="font-size: 0.7rem; transition: transform 0.3s ease;"></i>
                                </button>

                                <div x-show="open" x-transition.opacity.duration.300ms class="sb-submenu">
                                    <a href="{{ route('admin.security-alerts', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                        class="sb-link {{ request()->routeIs('admin.security-alerts') ? 'active' : '' }}"
                                        data-label="Alertas">
                                        <i class="fas fa-shield-alt" style="font-size: 0.8rem;"></i>
                                        <span class="sb-link-label">Alertas</span>
                                    </a>

                                    <a href="{{ route('admin.access-logs', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                        class="sb-link {{ request()->routeIs('admin.access-logs') ? 'active' : '' }}"
                                        data-label="Reg. Accesos">
                                        <i class="fas fa-list-ol" style="font-size: 0.8rem;"></i>
                                        <span class="sb-link-label">Reg. Accesos</span>
                                    </a>

                                    <a href="{{ route('admin.logs', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                        class="sb-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}"
                                        data-label="Errores Sistema">
                                        <i class="fas fa-bug" style="font-size: 0.8rem;"></i>
                                        <span class="sb-link-label">Errores Sistema</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Configuración --}}
                            <a href="{{ route('admin.system-settings', ['secret' => env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026')]) }}"
                                class="sb-link {{ request()->routeIs('admin.system-settings') ? 'active' : '' }}"
                                data-label="Configuración">
                                <i class="fas fa-cog"></i>
                                <span class="sb-link-label">Configuración</span>
                            </a>
                        @endif
                    @endif

                </nav>

                {{-- Footer / logout --}}
                <div class="sb-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="collapse-hide">Cerrar Sesión</span>
                        </button>
                    </form>
                </div>

            </aside>
        @endif

        {{-- ══════════════════════
        MAIN CONTENT
        ══════════════════════ --}}
        <main class="main-content" id="mainContent">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

    </div>{{-- /.app-shell --}}

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        /* ─── Toast notifications ─── */
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false,
            timer: 3000, timerProgressBar: true,
            didOpen: t => {
                t.addEventListener('mouseenter', Swal.stopTimer);
                t.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        window.addEventListener('notify', e => {
            const d = e.detail[0] || e.detail;
            const msg = typeof d === 'string' ? d : (d.message || 'Notificación');
            const type = d.type || 'info';
            Toast.fire({ icon: type, title: msg });
        });

        /* Trigger notifications from flash session - IMMEDIATE */
        @if(session()->has('notify'))
            (function () {
                const data = @json(session('notify'));
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('notify', { detail: data }));
                    }, 100);
                });
            })();
        @endif

        /* ─── Sidebar behaviour ─── */
        (function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const collapseBtn = document.getElementById('sbCollapseBtn');
            const arrow = document.getElementById('sidebarArrow');
            const overlay = document.getElementById('sbOverlay');
            if (!sidebar) return;

            const KEY = 'sb_collapsed';
            const mobile = () => window.innerWidth <= 768;

            /* ── Desktop collapse ── */
            function setCollapsed(val) {
                sidebar.classList.toggle('is-collapsed', val);
                mainContent && mainContent.classList.toggle('is-expanded', val);
                localStorage.setItem(KEY, val);
            }

            if (!mobile()) {
                setCollapsed(localStorage.getItem(KEY) === 'true');
            }

            collapseBtn && collapseBtn.addEventListener('click', () => {
                setCollapsed(!sidebar.classList.contains('is-collapsed'));
            });

            /* ── Mobile: floating arrow ── */
            function openMobile() {
                sidebar.classList.add('is-mobile-open');
                overlay && overlay.classList.add('is-active');
                arrow && arrow.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }
            function closeMobile() {
                sidebar.classList.remove('is-mobile-open');
                overlay && overlay.classList.remove('is-active');
                arrow && arrow.classList.remove('is-open');
                document.body.style.overflow = '';
            }

            arrow && arrow.addEventListener('click', () => sidebar.classList.contains('is-mobile-open') ? closeMobile() : openMobile());
            overlay && overlay.addEventListener('click', closeMobile);

            sidebar.querySelectorAll('.sb-link').forEach(l =>
                l.addEventListener('click', () => { if (mobile()) closeMobile(); })
            );

            window.addEventListener('resize', () => {
                if (!mobile()) {
                    closeMobile();
                    setCollapsed(localStorage.getItem(KEY) === 'true');
                } else {
                    sidebar.classList.remove('is-collapsed');
                    mainContent && mainContent.classList.remove('is-expanded');
                }
            });
        })();
    </script>
    @livewireScripts

    {{-- Culqi Checkout v4 - fuera del componente Livewire para que no se destruya con los polls --}}
    <script src="https://checkout.culqi.com/js/v4"></script>
    <script>
        // Se inicializa cuando Culqi carga
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Culqi === 'undefined') return;
            Culqi.publicKey = '{{ config('services.culqi.public_key', env('CULQI_PUBLIC_KEY')) }}';
        });

        function initiateCulqiPayment(amount, email, orderId) {
            if (typeof Culqi === 'undefined') {
                // Si Culqi no existe en el objeto global, re-iniciar el tag o intentar cargarlo
                const existingScript = document.querySelector('script[src*="checkout.culqi.com/js/v4"]');
                if (existingScript) {
                    existingScript.remove();
                }
                const script = document.createElement('script');
                script.src = "https://checkout.culqi.com/js/v4?t=" + new Date().getTime();
                script.onload = function() {
                    if (typeof Culqi !== 'undefined') {
                        Culqi.publicKey = '{{ env('CULQI_PUBLIC_KEY') }}';
                        initiateCulqiPayment(amount, email, orderId);
                    } else {
                        alert('La pasarela de pago no está disponible temporalmente. Inténtalo de nuevo.');
                    }
                };
                document.body.appendChild(script);
                return;
            }

            console.log("Iniciando Culqi:", { amount, email, orderId });

            Culqi.publicKey = '{{ env('CULQI_PUBLIC_KEY') }}';

            const amountInCents = Math.round(parseFloat(amount) * 100);

            Culqi.settings({
                title: 'RepuestoFijo',
                currency: 'PEN',
                amount: amountInCents,
                description: 'Pedido #' + orderId
            });

            Culqi.options({
                lang: 'es',
                installments: false,
                modal: true,
                style: {
                    logo: '{{ asset('images/logo.png') }}'
                }
            });

            window.currentCulqiEmail = email;
            Culqi.open();
        }

        // Callback obligatorio que Culqi llama cuando el usuario completa o cancela el pago
        window.culqi = function() {
            if (Culqi.token) {
                const token = Culqi.token.id;
                const email = window.currentCulqiEmail || Culqi.token.email;
                console.log("Token Culqi recibido:", token);

                // Cerrar el modal de Culqi
                if (typeof Culqi !== 'undefined' && typeof Culqi.close === 'function') {
                    Culqi.close();
                }

                // ── MOSTRAR ANIMACIÓN DE PROCESAMIENTO INMEDIATAMENTE Y LLAMAR A LIVEWIRE ──
                window.dispatchEvent(new CustomEvent('process-payment', {
                    detail: { token: token, email: email }
                }));
            } else {
                console.error("Error Culqi:", Culqi.error);
                const msg = (Culqi.error && Culqi.error.user_message) ? Culqi.error.user_message : 'Error al procesar la tarjeta. Intenta de nuevo.';
                alert(msg);
            }
        };
    </script>

    {{-- OVERLAY DE PROCESAMIENTO DE PAGO PREMIUM --}}
    <div x-data="{ showOverlay: false }"
         x-on:process-payment.window="showOverlay = true"
         x-on:payment-finished.window="setTimeout(() => { showOverlay = false; }, 400)"
         x-show="showOverlay"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="position-fixed top-0 start-0 w-100 h-100"
         style="background: #000; z-index: 999999999;"
         x-cloak>

        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; min-height: 100vh !important;">
            <div class="rf-pay-anim-wrapper">
                <div class="rf-isotipo-wrap">
                    <div class="rf-logo-glow-ring"></div>
                    <img src="{{ asset('images/logo.png') }}" alt="RepuestoFijo" class="rf-real-logo">
                </div>
                <p class="rf-tagline">Estamos procesando tu pago&hellip;</p>
                <div class="rf-progress-track">
                    <div class="rf-progress-bar"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
    [x-cloak] {
        display: none !important;
    }
    .rf-pay-anim-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
        user-select: none;
    }
    .rf-isotipo-wrap {
        position: relative;
        width: 100%;
        max-width: 260px;
        height: auto;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 20px;
    }
    .rf-logo-glow-ring {
        position: absolute;
        width: 120%;
        height: 120%;
        background: radial-gradient(ellipse, rgba(204,0,0,0.4) 0%, transparent 70%);
        animation: rfGlowPulse 2s infinite ease-in-out;
        pointer-events: none;
    }
    .rf-real-logo {
        position: relative;
        z-index: 1;
        width: 100%;
        height: auto;
        max-height: 80px;
        object-fit: contain;
        animation: rfLogoPulse 2s infinite ease-in-out;
        filter: drop-shadow(0 0 18px rgba(204,0,0,0.5));
    }
    @keyframes rfGlowPulse {
        0%,100% { opacity: 0.4; transform: scale(0.95); }
        50%      { opacity: 0.8;   transform: scale(1.05); }
    }
    @keyframes rfLogoPulse {
        0%,100% { filter: drop-shadow(0 0 12px rgba(204,0,0,0.4)); transform: scale(1); }
        50%      { filter: drop-shadow(0 0 24px rgba(204,0,0,0.8)); transform: scale(1.03); }
    }
    .rf-tagline {
        font-family: 'DM Sans', sans-serif;
        color: rgba(255,255,255,0.7);
        font-size: 0.85rem;
        letter-spacing: 0.06em;
        margin: 0 0 20px;
        text-transform: uppercase;
        text-align: center;
    }
    .rf-progress-track {
        width: 200px;
        height: 2px;
        background: rgba(255,255,255,0.12);
        border-radius: 99px;
        overflow: hidden;
    }
    .rf-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #CC0000, #ff4444);
        border-radius: 99px;
        animation: rfProgress 3s infinite linear;
    }
    @keyframes rfProgress {
        0%    { width: 0%; margin-left: 0%; }
        80%   { width: 100%; margin-left: 0%; }
        100%  { width: 0%; margin-left: 100%; }
    }
    </style>
</body>

</html>