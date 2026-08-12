<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = $request->session()->getId();

            // Si el ID de la sesión actual no coincide con el último ID guardado en el usuario
            // significa que el usuario ha iniciado sesión en otro lugar.
            if ($user->last_session_id && $user->last_session_id !== $currentSessionId) {
                $isAdmin = $user->isAdmin() || $user->isManager();

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $redirectUrl = $isAdmin ? route('login') : '/';

                return redirect($redirectUrl)->with('notify', [
                    'type' => 'warning',
                    'message' => 'Sesión cerrada: Se ha iniciado sesión desde otro dispositivo.'
                ]);
            }
        }

        return $next($request);
    }
}
