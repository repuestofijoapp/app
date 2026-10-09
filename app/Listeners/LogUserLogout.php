<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\DB;

class LogUserLogout
{
    /**
     * Cierra la sesión abierta más reciente del usuario en user_sessions.
     */
    public function handle(Logout $event): void
    {
        try {
            $user    = $event->user;
            $request = request();

            if (!$user) return;

            $sessionId = $request->hasSession() ? $request->session()->getId() : null;

            // Buscar la sesión abierta (sin logged_out_at) más reciente de este usuario
            $query = DB::table('user_sessions')
                ->where('user_id', $user->id)
                ->whereNull('logged_out_at')
                ->orderByDesc('logged_in_at');

            if ($sessionId) {
                // Preferir la sesión que coincida con el session_id actual
                $match = (clone $query)->where('session_id', $sessionId)->first();
                if ($match) {
                    DB::table('user_sessions')->where('id', $match->id)->update([
                        'logged_out_at' => now(),
                        'updated_at'    => now(),
                    ]);
                    return;
                }
            }

            // Fallback: cerrar la sesión abierta más reciente
            $session = $query->first();
            if ($session) {
                DB::table('user_sessions')->where('id', $session->id)->update([
                    'logged_out_at' => now(),
                    'updated_at'    => now(),
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('LogUserLogout failed: ' . $e->getMessage());
        }
    }
}
