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
        \Illuminate\Support\Facades\DB::table('access_logs')->insert([
            'ip'         => $request->ip(),
            'route'      => $request->path(),
            'method'     => $request->method(),
            'user_agent' => $request->userAgent(),
            'user_id'    => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $next($request);
    }
}
