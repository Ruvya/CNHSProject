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

    protected function getActingUser()
    {
        if (\Auth::guard('admin')->check()) {
            return ['user' => \Auth::guard('admin')->user(), 'type' => 'admin'];
        } elseif (\Auth::guard('registrar')->check()) {
            return ['user' => \Auth::guard('registrar')->user(), 'type' => 'registrar'];
        }
        return ['user' => null, 'type' => null];
    }

    public function update(Request $request)
    {
        $acting = $this->getActingUser();
        $user = $acting['user'];
        $type = $acting['type'];

        // Validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:' . ($type === 'admin' ? 'admins' : 'registrars') . ',email,' . $user->id,
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
            if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = 'registrar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('registrar-profiles', $filename, 'public');
            $user->profile_picture = $path;
        }

        // Handle profile picture removal
        if ($request->has('remove_profile_picture')) {
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = null;
        }

        // Update basic information
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = \Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully! Your changes have been saved.');
    }
}
