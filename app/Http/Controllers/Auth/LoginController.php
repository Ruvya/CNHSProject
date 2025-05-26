<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Log login attempts for debugging
        \Log::info('=== LOGIN ATTEMPT RECEIVED ===', [
            'timestamp' => now(),
            'role' => $request->input('role'),
            'all_data' => $request->all()
        ]);

        $role = $request->input('role');
        $rules = [
            'role' => 'required|in:admin,teacher,student,registrar',
            'password' => 'required',
        ];

        if ($role === 'admin') {
            $rules['username'] = 'required';
        } elseif ($role === 'teacher') {
            $rules['email'] = 'required|email';
        } elseif ($role === 'student') {
            $rules['student_id'] = 'required';
        } elseif ($role === 'registrar') {
            $rules['email'] = 'required|email';
        }

        $credentials = $request->validate($rules);

        // Note: Admin login now uses dedicated /admin/login route and Admin\AuthController
        if ($role === 'teacher') {
            if (Auth::guard('teacher')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                $request->session()->regenerate();
                return redirect()->intended(route('teacher.dashboard'));
            }
        } elseif ($role === 'student') {
            $credentials = $request->only('student_id', 'password');
            if (Auth::guard('student')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'));
            }
        } elseif ($role === 'registrar') {
            // Validate registrar credentials including secret
            $registrarRules = [
                'email' => 'required|email',
                'password' => 'required',
                'registrar_secret' => 'required'
            ];

            $registrarCredentials = $request->validate($registrarRules);

            // Check registrar secret first
            $secret = env('REGISTRAR_SECRET', 'letmein');
            if ($request->input('registrar_secret') !== $secret) {
                return back()->withErrors([
                    'registrar_secret' => 'Invalid registrar secret. Please use the dedicated registrar login for better security.',
                ])->onlyInput('email')->with('info', 'For enhanced security, please use the dedicated registrar login system.');
            }

            // Only use email and password for authentication
            $authCredentials = [
                'email' => $registrarCredentials['email'],
                'password' => $registrarCredentials['password']
            ];

            if (Auth::guard('registrar')->attempt($authCredentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('registrar.dashboard'));
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('teacher')->logout();
        Auth::guard('student')->logout();
        Auth::guard('registrar')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}