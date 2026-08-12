<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SecurityLog;
use App\Models\BlacklistIp;

class AdminSecurityMiddleware
{
    /**
     * Handle an incoming request.
     * Validates the secret URL param and auto-blocks IPs with 3+ failed attempts.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secret         = env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026');
        $providedSecret = $request->route('secret');
        $ip             = $request->ip();

        if (!$providedSecret || $providedSecret !== $secret) {
            SecurityLog::create([
                'ip_address' => $ip,
                'event_type' => 'invalid_admin_secret',
                'details'    => [
                    'path'            => $request->path(),
                    'provided_secret' => $providedSecret,
                    'method'          => $request->method(),
                ],
                'user_id'    => auth()->id(),
            ]);

            // Auto-block after 3 failed attempts within 1 hour
            $this->maybeAutoBlock($ip, 'Auto-bloqueado: 3+ intentos con clave inválida en 1 hora');

            abort(404); // Pretend it doesn't exist
        }

        // Must be logged in as admin or manager
        if (!auth()->check() || !auth()->user()->canAccessDashboard()) {
            SecurityLog::create([
                'ip_address' => $ip,
                'event_type' => 'unauthorized_admin_access_attempt',
                'details'    => [
                    'path'       => $request->path(),
                    'user_email' => auth()->user() ? auth()->user()->email : 'guest',
                ],
                'user_id'    => auth()->id(),
            ]);

            $this->maybeAutoBlock($ip, 'Auto-bloqueado: acceso no autorizado al panel de administrador');

            return redirect('/')->with('error', 'Acceso restringido.');
        }

        return $next($request);
    }

    /**
     * Auto-block the IP if it has 3+ failed attempts in the last hour.
     * Blocks for 24 hours. Skips if already blocked.
     */
    private function maybeAutoBlock(string $ip, string $reason): void
    {
        // Already blocked? Skip
        if (BlacklistIp::where('ip_address', $ip)->active()->exists()) {
            return;
        }

        $recentAttempts = SecurityLog::where('ip_address', $ip)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($recentAttempts >= 3) {
            BlacklistIp::updateOrCreate(
                ['ip_address' => $ip],
                [
                    'reason'     => $reason,
                    'expires_at' => now()->addHours(24),
                    'blocked_by' => null, // null = sistema automático
                ]
            );
        }
    }
}
