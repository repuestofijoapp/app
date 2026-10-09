<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $action = \App\Services\AccessLogHelper::resolveFromRequest($request);

            \Illuminate\Support\Facades\DB::table('access_logs')->insert([
                'ip'          => $request->ip(),
                'route'       => $request->path(),
                'action_name' => $action['action_name'] ?? null,
                'category'    => $action['category'] ?? null,
                'method'      => $request->method(),
                'user_agent'  => $request->userAgent(),
                'user_id'     => auth()->id(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // Silencioso para no interrumpir el flujo si la tabla está en migración o falla el log
        }

        return $next($request);
    }
}
