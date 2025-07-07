<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Registrar;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('registrar.auth.login');
    }

    public function login(Request $request)
    {
        if ($request->input('step') === 'otp') {
            return $this->verifyOtp($request);
        }

        // Validate initial credentials - SIMPLIFIED
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => 'required|in:admin,teacher,student,registrar',
        ]);

        // Debug: Check if user exists and password is correct
        $user = \App\Models\Registrar::where('email', $credentials['email'])->first();
        $passwordCorrect = $user ? \Hash::check($credentials['password'], $user->password) : false;

        \Log::info('Registrar Login Attempt', [
            'email' => $credentials['email'],
            'user_exists' => $user ? 'yes' : 'no',
            'user_id' => $user ? $user->id : 'none',
            'password_correct' => $passwordCorrect,
            'table' => $user ? $user->getTable() : 'none'
        ]);

        // Try authentication
        if ($user && $passwordCorrect) {
            // Manual login since Auth::attempt might have issues
            Auth::guard('registrar')->login($user);
            $request->session()->regenerate();
            $request->session()->save(); // Force session save

            // Verify login was successful
            $authCheck = Auth::guard('registrar')->check();
            $authUser = Auth::guard('registrar')->user();

            \Log::info('Registrar Login Success - Manual', [
                'email' => $credentials['email'],
                'auth_check' => $authCheck,
                'auth_user_id' => $authUser ? $authUser->id : null,
                'session_id' => $request->session()->getId()
            ]);

            if (!$authCheck) {
                \Log::error('Registrar Login Failed - Auth check failed after manual login', [
                    'email' => $credentials['email']
                ]);
                return back()->withErrors(['email' => 'Authentication failed. Please try again.']);
            }

            // For now, skip OTP and go directly to dashboard for testing
            return redirect()->intended(route('registrar.dashboard'))
                ->with('success', 'Login successful!');
        }

        \Log::info('Registrar Login Failed', [
            'email' => $credentials['email'],
            'user_exists' => $user ? 'yes' : 'no',
            'password_correct' => $passwordCorrect
        ]);

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Invalid email or password. Please check your credentials.',
            ]);
    }

    protected function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        if ($request->input('otp') !== session('registrar_otp')) {
            return back()->withErrors([
                'otp' => 'Invalid OTP code. Please try again.'
            ]);
        }

        // Clear sensitive session data
        session()->forget(['registrar_otp', 'registrar_email']);

        // Regenerate session for security
        $request->session()->regenerate();

        return redirect()->intended(route('registrar.dashboard'));
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