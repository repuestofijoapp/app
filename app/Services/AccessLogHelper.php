<?php

namespace App\Services;

use Illuminate\Http\Request;

class AccessLogHelper
{
    /**
     * Resuelve nombre legible de acción y categoría desde un objeto Request entrante.
     */
    public static function resolveFromRequest(Request $request): array
    {
        $path   = trim($request->path(), '/');
        $method = strtoupper($request->method());

        // 1. Livewire updates
        if (str_starts_with($path, 'livewire/')) {
            return self::resolveLivewireAction($request);
        }

        // 2. Búsqueda API
        if ($path === 'api/search-products') {
            $q = $request->query('q');
            return [
                'action_name' => $q ? "Búsqueda rápida: '{$q}'" : "Autocompletado de repuestos",
                'category'    => 'catalogo',
            ];
        }

        // 3. Panel de Administración
        if (str_contains($path, 'Ayoro-sape-')) {
            return self::resolveAdminAction($path, $method);
        }

        // 4. Autenticación
        if ($path === 'login') {
            return [
                'action_name' => $method === 'POST' ? 'Intento de inicio de sesión' : 'Página de inicio de sesión',
                'category'    => 'auth',
            ];
        }
        if ($path === 'logout') {
            return [
                'action_name' => 'Cierre de sesión',
                'category'    => 'auth',
            ];
        }
        if (str_starts_with($path, 'auth/google')) {
            return [
                'action_name' => 'Autenticación con Google',
                'category'    => 'auth',
            ];
        }
        if ($path === 'onboarding') {
            return [
                'action_name' => 'Registro / Onboarding de usuario',
                'category'    => 'auth',
            ];
        }

        // 5. Portal B2B
        if (str_starts_with($path, 'b2b/')) {
            return [
                'action_name' => str_contains($path, 'acceso') ? 'B2B: Login de Proveedores' : 'B2B: Portal de Proveedores',
                'category'    => 'b2b',
            ];
        }
        if (str_starts_with($path, 'proveedor/confirmar')) {
            return [
                'action_name' => 'B2B: Confirmación de Stock',
                'category'    => 'b2b',
            ];
        }

        // 6. Páginas Legales y Reclamaciones
        if ($path === 'libro-de-reclamaciones') {
            return [
                'action_name' => $method === 'POST' ? 'Registro de reclamación formal' : 'Consulta Libro de Reclamaciones',
                'category'    => 'legal',
            ];
        }
        if ($path === 'privacidad') {
            return [
                'action_name' => 'Consulta Políticas de Privacidad',
                'category'    => 'legal',
            ];
        }
        if ($path === 'terminos') {
            return [
                'action_name' => 'Consulta Términos y Condiciones',
                'category'    => 'legal',
            ];
        }

        // 7. Catálogo Principal / Home
        if ($path === '' || $path === '/') {
            $q = $request->query('search') ?: $request->query('q');
            if ($q) {
                return [
                    'action_name' => "Búsqueda en catálogo: '{$q}'",
                    'category'    => 'catalogo',
                ];
            }
            return [
                'action_name' => 'Visualización de Catálogo Principal',
                'category'    => 'catalogo',
            ];
        }

        // Fallback
        return [
            'action_name' => "Consulta: /{$path}",
            'category'    => 'general',
        ];
    }

    /**
     * Resuelve acciones de rutas de Livewire analizando el cuerpo de la petición.
     */
    protected static function resolveLivewireAction(Request $request): array
    {
        try {
            $components = $request->json('components') ?? [];
            if (!empty($components) && is_array($components)) {
                $comp = $components[0] ?? [];
                $name = $comp['snapshot']['memo']['name'] ?? '';

                // Catálogo de búsqueda principal
                if (str_contains($name, 'main-search')) {
                    $calls = $comp['calls'] ?? [];
                    if (!empty($calls) && isset($calls[0]['method'])) {
                        $m = $calls[0]['method'];
                        if (str_contains(strtolower($m), 'search') || str_contains(strtolower($m), 'filter')) {
                            return ['action_name' => 'Buscador: Filtrado de repuestos', 'category' => 'catalogo'];
                        }
                        if (str_contains(strtolower($m), 'cart') || str_contains(strtolower($m), 'item')) {
                            return ['action_name' => 'Buscador: Interacción con carrito', 'category' => 'catalogo'];
                        }
                        return ['action_name' => "Buscador: Acción ({$m})", 'category' => 'catalogo'];
                    }
                    return ['action_name' => 'Interacción en Catálogo de Repuestos', 'category' => 'catalogo'];
                }

                // Componentes de Administración
                if (str_contains($name, 'admin.')) {
                    $clean = str_replace(['admin.', '-'], ['', ' '], $name);
                    return [
                        'action_name' => 'Admin: Interacción en ' . ucwords($clean),
                        'category'    => 'admin',
                    ];
                }

                return [
                    'action_name' => 'Interacción Livewire (' . ($name ?: 'componente') . ')',
                    'category'    => 'interaccion',
                ];
            }
        } catch (\Throwable) {
            // Ignorar y caer al fallback
        }

        return [
            'action_name' => 'Actualización interactiva en pantalla',
            'category'    => 'interaccion',
        ];
    }

    /**
     * Resuelve acciones del panel de control de administración.
     */
    protected static function resolveAdminAction(string $path, string $method): array
    {
        $segments = explode('/', $path);
        $section  = end($segments);

        $labels = [
            'dashboard'       => 'Panel de Control (Dashboard)',
            'profile'         => 'Perfil de Administrador',
            'users'           => 'Gestión de Usuarios',
            'providers'       => 'Gestión de Proveedores',
            'products'        => 'Catálogo de Repuestos',
            'pedidos'         => 'Gestión de Pedidos',
            'en-vivo'         => 'Monitor de Pedidos En Vivo',
            'vehiculos'       => 'Catálogo de Vehículos',
            'access-logs'     => 'Registro de Accesos y Actividad',
            'security-alerts' => 'Alertas de Seguridad',
            'reclamaciones'   => 'Libro de Reclamaciones',
            'diagnostico'     => 'Diagnóstico de Catálogo',
            'zettabot'        => 'Configuración ZettaBot',
            'configuracion'   => 'Configuración del Sistema',
            'soporte'         => 'Mesa de Ayuda y Soporte',
            'logs'            => 'Visor de Logs del Servidor',
        ];

        $name = $labels[$section] ?? ('Sección Admin: ' . ucfirst($section));

        return [
            'action_name' => "Admin: {$name}",
            'category'    => 'admin',
        ];
    }

    /**
     * Mapea un registro (incluso histórico con nulls) a datos visuales formateados.
     */
    public static function formatDisplay(?string $actionName, string $route, string $method, ?string $category = null): array
    {
        // Si no tiene action_name ni category en la BD, deducirlo al vuelo de la ruta
        if (empty($actionName) || empty($category)) {
            $deduced = self::deduceFromRoute($route, $method);
            $actionName = $actionName ?: $deduced['action_name'];
            $category   = $category ?: $deduced['category'];
        }

        $meta = self::getCategoryMeta($category);

        return [
            'action_name' => $actionName,
            'category'    => $category,
            'badge_label' => $meta['label'],
            'badge_bg'    => $meta['bg'],
            'badge_color' => $meta['color'],
            'icon'        => $meta['icon'],
        ];
    }

    /**
     * Deduce retroactivamente descripción para logs antiguos que no tenían action_name.
     */
    protected static function deduceFromRoute(string $route, string $method): array
    {
        $clean = trim($route, '/');

        if ($clean === '' || $clean === '/') {
            return ['action_name' => 'Visualización de Catálogo Principal', 'category' => 'catalogo'];
        }
        if (str_starts_with($clean, 'livewire/')) {
            return ['action_name' => 'Interacción interactiva (Livewire)', 'category' => 'interaccion'];
        }
        if ($clean === 'api/search-products') {
            return ['action_name' => 'Búsqueda rápida de repuestos', 'category' => 'catalogo'];
        }
        if (str_contains($clean, 'Ayoro-sape-')) {
            return self::resolveAdminAction($clean, $method);
        }
        if ($clean === 'login') {
            return ['action_name' => $method === 'POST' ? 'Intento de inicio de sesión' : 'Página de login', 'category' => 'auth'];
        }
        if ($clean === 'logout') {
            return ['action_name' => 'Cierre de sesión', 'category' => 'auth'];
        }
        if (str_starts_with($clean, 'b2b/')) {
            return ['action_name' => 'Portal Proveedores B2B', 'category' => 'b2b'];
        }
        if ($clean === 'libro-de-reclamaciones') {
            return ['action_name' => 'Libro de Reclamaciones', 'category' => 'legal'];
        }

        return ['action_name' => "Consulta: /{$clean}", 'category' => 'general'];
    }

    /**
     * Estilos visuales por categoría.
     */
    public static function getCategoryMeta(?string $category): array
    {
        return match ($category) {
            'catalogo'    => ['label' => 'Catálogo',    'bg' => 'rgba(56,189,248,0.15)',  'color' => '#38bdf8', 'icon' => 'fas fa-search'],
            'admin'       => ['label' => 'Admin',       'bg' => 'rgba(192,132,252,0.18)', 'color' => '#c084fc', 'icon' => 'fas fa-user-shield'],
            'auth'        => ['label' => 'Sesión',      'bg' => 'rgba(251,191,36,0.18)',  'color' => '#fbbf24', 'icon' => 'fas fa-key'],
            'b2b'         => ['label' => 'B2B',         'bg' => 'rgba(52,211,153,0.15)',  'color' => '#34d399', 'icon' => 'fas fa-building'],
            'legal'       => ['label' => 'Legal',       'bg' => 'rgba(248,113,113,0.15)', 'color' => '#f87171', 'icon' => 'fas fa-balance-scale'],
            'interaccion' => ['label' => 'Interacción', 'bg' => 'rgba(167,139,250,0.15)', 'color' => '#a78bfa', 'icon' => 'fas fa-bolt'],
            default       => ['label' => 'General',     'bg' => 'rgba(148,163,184,0.12)', 'color' => '#94a3b8', 'icon' => 'fas fa-globe'],
        };
    }
}
