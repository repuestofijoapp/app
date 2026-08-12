<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . "/../routes/web.php",
        commands: __DIR__ . "/../routes/console.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Confiar en todos los proxies (ngrok, Cloudflare, etc.)
        // Necesario para que HTTPS, URLs generadas y sesiones funcionen correctamente
        $middleware->trustProxies(at: '*');

        $middleware->web(prepend: [
            \App\Http\Middleware\BlacklistMiddleware::class,
        ]);

        $middleware->web(append: [
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \App\Http\Middleware\EnsureSingleSession::class,
            \App\Http\Middleware\CheckOnboarding::class,
            \App\Http\Middleware\LogAccess::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'webhooks/green-api',
            'webhooks/culqi',  // Culqi envía webhooks sin CSRF token
            'proveedor/confirmar/*', // Confirmación de stock sin CSRF
        ]);

        $middleware->alias([
            "admin.security" => \App\Http\Middleware\AdminSecurityMiddleware::class,
            "provider.auth" => \App\Http\Middleware\ProviderAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
