<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireBusinessInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user has DNI/RUC and business_name (if applicable)
        // This middleware would be applied to routes that require full registration
        // For MVP Phase 1, it's mentioned to only ask upon ZettaBot activation
        if ($user && ($user->ruc_dni === null || ($user->isMechanic() && $user->business_name === null))) {
            // Redirect to a profile completion page
            return redirect()->route('profile.complete')->with('warning', 'Please complete your business information to access this feature.');
        }

        return $next($request);
    }
}