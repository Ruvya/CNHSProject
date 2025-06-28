<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipalAuthController extends Controller
{
    // Show the principal login form
    public function showLoginForm()
    {
        return view('principal.auth.login');
    }

    // Handle principal login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('principal')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('principal.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials or account not found.',
        ])->withInput($request->only('email'));
    }

    // Handle principal logout
    public function logout(Request $request)
    {
        Auth::guard('principal')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
} 