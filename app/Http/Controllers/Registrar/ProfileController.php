<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('registrar.profile');
    }

    public function update(Request $request)
    {
        $registrar = Auth::guard('registrar')->user();

        // Validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:registrars,email,' . $registrar->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        // Add password validation if password fields are filled
        if ($request->filled('password') || $request->filled('current_password')) {
            $rules['current_password'] = 'required';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $registrar->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($registrar->profile_picture && Storage::disk('public')->exists($registrar->profile_picture)) {
                Storage::disk('public')->delete($registrar->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $registrar->profile_picture = $path;
        }

        // Handle profile picture removal
        if ($request->has('remove_profile_picture')) {
            if ($registrar->profile_picture && Storage::disk('public')->exists($registrar->profile_picture)) {
                Storage::disk('public')->delete($registrar->profile_picture);
            }
            $registrar->profile_picture = null;
        }

        // Update basic information
        $registrar->first_name = $request->first_name;
        $registrar->last_name = $request->last_name;
        $registrar->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $registrar->password = Hash::make($request->password);
        }

        $registrar->save();

        return redirect()->back()->with('success', 'Profile updated successfully! Your changes have been saved.');
    }
}
