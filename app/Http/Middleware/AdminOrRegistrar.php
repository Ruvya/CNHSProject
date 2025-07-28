<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrRegistrar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check normal authentication
        if (Auth::guard('admin')->check() || Auth::guard('registrar')->check()) {
            return $next($request);
        }

        // TEMPORARY: Check for emergency session data
        if ($request->session()->has('registrar_authenticated') && $request->session()->get('registrar_authenticated')) {
            // Try to restore authentication from session
            $registrarId = $request->session()->get('registrar_id');
            if ($registrarId) {
                $registrar = \App\Models\Registrar::find($registrarId);
                if ($registrar) {
                    Auth::guard('registrar')->login($registrar);
                    return $next($request);
                }
            }
        }

        // TEMPORARY: Allow access if coming from force-registrar-access
        if ($request->headers->get('referer') && str_contains($request->headers->get('referer'), 'force-registrar-access')) {
            return $next($request);
        }

        // Redirect to login if neither admin nor registrar is authenticated
        return redirect()->route('login')->with('error', 'You must be an admin or registrar to access this page.');
    }
} 