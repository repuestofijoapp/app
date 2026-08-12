<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && 
            !$user->onboarding_completed_at && 
            !in_array($user->role, [\App\Enums\UserRole::Admin, \App\Enums\UserRole::Manager]) &&
            !$request->routeIs('onboarding') && 
            !$request->is('logout') && 
            !$request->ajax() &&
            !$request->hasHeader('X-Livewire') &&
            !$request->is('livewire/*')) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}
