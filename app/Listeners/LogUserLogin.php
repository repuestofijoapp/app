<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;

class LogUserLogin
{
    /**
     * Registra el inicio de sesión en user_sessions.
     */
    public function handle(Login $event): void
    {
        try {
            $user    = $event->user;
            $request = request();

            DB::table('user_sessions')->insert([
                'user_id'      => $user->id,
                'session_id'   => $request->session()->getId(),
                'ip'           => $request->ip(),
                'user_agent'   => $request->userAgent(),
                'logged_in_at' => now(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        } catch (\Throwable $e) {
            // No interrumpir el login si falla el log
            \Illuminate\Support\Facades\Log::warning('LogUserLogin failed: ' . $e->getMessage());
        }
    }
}
