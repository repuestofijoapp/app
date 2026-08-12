<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlacklistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $isBlacklisted = \App\Models\BlacklistIp::where('ip_address', $ip)
            ->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        })
            ->exists();

        if ($isBlacklisted) {
            abort(403, 'Acceso denegado por motivos de seguridad. Tu IP ha sido añadida a la lista negra.');
        }

        return $next($request);
    }
}
