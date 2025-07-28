<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
            'username' => ['required', 'string', Rule::unique('admins')->ignore($admin->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
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
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
                Storage::disk('public')->delete($admin->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = 'admin_' . $admin->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('admin-profiles', $filename, 'public');
            $admin->profile_picture = $path;
        }

        // Handle profile picture removal
        if ($request->has('remove_profile_picture')) {
            if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
                Storage::disk('public')->delete($admin->profile_picture);
            }
            $admin->profile_picture = null;
        }

        // Update basic information
        $admin->name = $request->name;
        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->phone = $request->phone;
        $admin->address = $request->address;

        // Update password if provided
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->back()->with('success', 'Profile updated successfully! Your changes have been saved.');
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $admin = Auth::guard('admin')->user();

        // Delete old profile picture if exists
        if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
            Storage::disk('public')->delete($admin->profile_picture);
        }

        $file = $request->file('profile_picture');
        $filename = 'admin_' . $admin->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('admin-profiles', $filename, 'public');

        $admin->update(['profile_picture' => $path]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'profile_picture_url' => asset('storage/' . $path)
            ]);
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully');
    }

    public function removeProfilePicture(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture)) {
            Storage::disk('public')->delete($admin->profile_picture);
        }

        $admin->update(['profile_picture' => null]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Profile picture removed successfully');
    }
}