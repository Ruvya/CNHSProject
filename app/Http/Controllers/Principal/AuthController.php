<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Debug logging
        \Log::info('Principal Login Attempt', [
            'email' => $credentials['email'],
            'has_password' => !empty($credentials['password'])
        ]);

        // Check if principal exists
        $principal = \App\Models\Principal::where('email', $credentials['email'])->first();
        
        if (!$principal) {
            \Log::info('Principal not found', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'No principal account found with this email.',
            ])->onlyInput('email');
        }

        // Try to authenticate
        if (Auth::guard('principal')->attempt($credentials)) {
            $request->session()->regenerate();
            
            \Log::info('Principal login successful', [
                'email' => $credentials['email'],
                'principal_id' => $principal->id
            ]);
            
            return redirect()->intended(route('principal.dashboard'));
        }

        \Log::info('Principal login failed - invalid password', ['email' => $credentials['email']]);
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('principal')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('Principal Logged Out', [
            'session_id' => session()->getId()
        ]);
        
        return redirect()->route('principal.login');
    }
} 