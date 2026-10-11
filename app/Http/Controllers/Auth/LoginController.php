<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Verificar cuenta activa
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta ha sido bloqueada. Contacta al administrador si crees que es un error.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Guardamos el ID de esta sesión como la "Sesión Maestra"
            $user->update(['last_session_id' => $request->session()->getId()]);

            // Sincronizar carrito multidispositivo (Mi Reparación)
            $sessionCart = $request->session()->get('repair_list_session') ?: [];
            $dbCart = $user->cart_data ?? [];
            if (is_string($dbCart)) {
                $dbCart = json_decode($dbCart, true) ?: [];
            }

            if (!empty($sessionCart) && !empty($dbCart)) {
                $mergedCart = array_merge($dbCart, $sessionCart);
                $user->cart_data = $mergedCart;
                $user->save();
                \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update([
                    'cart_data' => json_encode($mergedCart),
                ]);
                $request->session()->put('repair_list_session', $mergedCart);
            } elseif (!empty($sessionCart)) {
                $user->cart_data = $sessionCart;
                $user->save();
                \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update([
                    'cart_data' => json_encode($sessionCart),
                ]);
            } elseif (!empty($dbCart)) {
                $request->session()->put('repair_list_session', $dbCart);
            }

            // Cerramos otras sesiones para el driver web
            Auth::logoutOtherDevices($request->password);

            // ── Redirect basado en rol ────────────────────────────────────
            $notify = ['type' => 'success', 'message' => 'Sesión iniciada. Sesiones previas cerradas por seguridad.'];

            if ($user->role === UserRole::Admin || $user->role === UserRole::Manager) {
                $secret = env('ADMIN_URL_SECRET', 'Repuesto-Sape-2026');
                return redirect()
                    ->intended(route('admin.dashboard', ['secret' => $secret]))
                    ->with('notify', $notify);
            }

            if ($user->role === UserRole::Transporte) {
                return redirect()->intended(route('home'))->with('notify', $notify);
            }

            // mechanic y cualquier otro rol van al home (búsqueda pública)
            // Si venía de la página de búsqueda, la sesión de Livewire se restaura
            // automáticamente al volver al home (ver search_state en sesión)
            return redirect()->intended(route('home'))->with('notify', $notify);
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }
}
