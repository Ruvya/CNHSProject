<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Show the password change form for new teachers
     */
    public function showChangeForm()
    {
        $teacher = Auth::guard('teacher')->user();

        // If password change is not required, redirect to dashboard
        if (!$teacher->password_change_required) {
            return redirect()->route('teacher.dashboard');
        }

        return view('teacher.auth.change-password', compact('teacher'));
    }

    /**
     * Handle the password change request
     */
    public function changePassword(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        // Validate the request
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $teacher->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ]);
        }

        // Update password and mark as changed
        $teacher->update([
            'password' => Hash::make($request->password),
            'password_change_required' => false,
            'password_changed_at' => now(),
        ]);

        // Log the password change
        \Log::info('Teacher password changed successfully', [
            'teacher_id' => $teacher->id,
            'teacher_email' => $teacher->email,
            'timestamp' => now()->toDateTimeString()
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Password changed successfully! Welcome to CNHS.');
    }
}
