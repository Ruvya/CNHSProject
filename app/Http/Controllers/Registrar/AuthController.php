<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Models\Subject;
use Illuminate\Support\Collection;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('registrar.auth.login');
    }

    public function login(Request $request)
    {
        $step = $request->input('step', 'credentials');
        if ($step === 'credentials') {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                'registrar_secret' => ['required'],
            ]);

            // Check registrar secret
            $secret = env('REGISTRAR_SECRET', 'letmein');
            if ($request->input('registrar_secret') !== $secret) {
                return back()->withErrors(['registrar_secret' => 'Invalid registrar secret.'])->onlyInput('email');
            }

            // Only use email and password for authentication
            $authCredentials = [
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ];

            if (Auth::guard('registrar')->attempt($authCredentials)) {
                // Generate OTP
                $otp = rand(100000, 999999);
                session(['registrar_otp' => $otp, 'registrar_email' => $credentials['email'], 'registrar_password' => $credentials['password']]);
                return redirect()->back()->with('otp', $otp)->with('step', 'otp');
            }
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        } else if ($step === 'otp') {
            $request->validate(['otp' => 'required']);
            if ($request->input('otp') == session('registrar_otp')) {
                // Log in the registrar with only email and password
                $authCredentials = [
                    'email' => session('registrar_email'),
                    'password' => session('registrar_password')
                ];

                Auth::guard('registrar')->attempt($authCredentials);
                $request->session()->regenerate();
                session()->forget(['registrar_otp', 'registrar_email', 'registrar_password']);
                return redirect()->intended(route('registrar.dashboard'));
            } else {
                return back()->withErrors(['otp' => 'Invalid OTP'])->with('step', 'otp')->with('otp', session('registrar_otp'));
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('registrar')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function subjects()
    {
        $subjects = collect(\App\Models\Subject::all());
        return view('registrar.subjects', compact('subjects'));
    }
}