<?php

namespace App\Http\Controllers\Admin;

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
        // Log the entire request for debugging
        Log::info('Admin login request received', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'headers' => $request->headers->all()
        ]);

        try {
            $credentials = $request->validate([
                'username' => ['required', 'string'],
                'password' => ['required'],
            ]);

            // For debugging
            Log::info('Admin login attempt', ['username' => $credentials['username']]);

            // Check if admin exists
            $admin = \App\Models\Admin::where('username', $credentials['username'])->first();
            if (!$admin) {
                Log::warning('Admin not found', ['username' => $credentials['username']]);
                return back()->withErrors([
                    'username' => 'No admin account found with this username.',
                ])->onlyInput('username');
            }

            Log::info('Admin found, attempting authentication', ['admin_id' => $admin->id]);

            // Try to authenticate
            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                Log::info('Admin login successful', ['username' => $credentials['username'], 'admin_id' => $admin->id]);

                // Check if this is an AJAX request
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('admin.dashboard')
                    ]);
                }

                // Force redirect to admin dashboard
                $dashboardUrl = route('admin.dashboard');
                Log::info('Attempting redirect to admin dashboard', [
                    'route' => $dashboardUrl,
                    'session_id' => $request->session()->getId(),
                    'auth_check_before_redirect' => Auth::guard('admin')->check(),
                    'user_id' => Auth::guard('admin')->id()
                ]);

                // Try different redirect approaches
                // Force session save before redirect
                $request->session()->save();

                // Method 1: Direct URL redirect with forced session save
                return redirect($dashboardUrl)->with('success', 'Login successful!');
            }

            Log::warning('Admin login failed - password mismatch', ['username' => $credentials['username']]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The provided credentials do not match our records.'
                ], 422);
            }

            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->onlyInput('username');

        } catch (\Exception $e) {
            Log::error('Admin login exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'username' => 'An error occurred during login. Please try again.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function logRequest(Request $request)
    {
        Log::info($request->all());
        return response()->json(['message' => 'Request logged successfully']);
    }

    // Add a test method to verify the controller is working
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Admin controller is working'
        ]);
    }
}
