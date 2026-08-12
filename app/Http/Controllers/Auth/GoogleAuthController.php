<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\UserRole;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update existing user with Google ID if not already set
                if (empty($user->google_id)) {
                    $user->google_id = $googleUser->getId();
                }
                // Always update avatar to keep it fresh
                $user->profile_photo_path = $googleUser->getAvatar();
                $user->save();
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(16)), // Placeholder password
                    'profile_photo_path' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'role' => UserRole::Mechanic, // Default role
                    'is_active' => true, // New users are active by default
                ]);
            }

            // check if user is blocked
            if (!$user->is_active) {
                return redirect('/')->with('account_blocked', 'Tu cuenta ha sido bloqueada. Por favor, comunícate con soporte técnico: [EMAIL] para más información.');
            }

            Auth::login($user);
            session()->regenerate();

            // Guardamos el ID de esta nueva sesión como la activa
            $user->update(['last_session_id' => session()->getId()]);

            // Persistir aceptación de términos si viene de la sesión
            if (session('privacy_policy_accepted')) {
                $user->update(['privacy_policy_accepted_at' => now()]);
                session()->forget('privacy_policy_accepted');
            }

            // Redirect based on role
            if ($user->isAdmin() || $user->isManager()) {
                $secret = env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026');
                return redirect()->route('admin.dashboard', ['secret' => $secret])->with('notify', [
                    'type' => 'success',
                    'message' => "Bienvenid@, {$user->name}. Acceso administrativo concedido."
                ]);
            }

            // mechanic, transporte y cualquier otro rol van al home
            return redirect()->route('home')->with('notify', [
                'type' => 'success',
                'message' => "Bienvenid@, {$user->name}, sesión iniciada correctamente"
            ]);

        } catch (\Exception $e) {
            // Log the error
            return redirect('/')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}