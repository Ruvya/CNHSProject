<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.profile', compact('teacher'));
    }

    public function update(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Add password validation if password fields are filled
        if ($request->filled('password') || $request->filled('current_password')) {
            $rules['current_password'] = 'required';
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $teacher->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($teacher->profile_picture && Storage::disk('public')->exists($teacher->profile_picture)) {
                Storage::disk('public')->delete($teacher->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = 'teacher_' . $teacher->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store the original file
            $path = $file->storeAs('teacher-profiles', $filename, 'public');

            // Optional: Resize/compress the image if Intervention Image is available
            try {
                $fullPath = storage_path('app/public/' . $path);
                if (class_exists('Intervention\Image\Facades\Image')) {
                    $image = Image::make($fullPath);
                    $image->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $image->save($fullPath, 80); // 80% quality
                }
            } catch (\Exception $e) {
                // If image processing fails, continue with original file
            }

            $teacher->profile_picture = $path;
        }

        // Handle profile picture removal
        if ($request->has('remove_profile_picture')) {
            if ($teacher->profile_picture && Storage::disk('public')->exists($teacher->profile_picture)) {
                Storage::disk('public')->delete($teacher->profile_picture);
            }
            $teacher->profile_picture = null;
        }

        // Update basic information
        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->contact_number = $request->contact_number;
        $teacher->address = $request->address;

        // Update password if provided
        if ($request->filled('password')) {
            $teacher->password = Hash::make($request->password);
        }

        $teacher->save();

        return redirect()->route('teacher.profile')
            ->with('success', 'Profile updated successfully! Your changes have been saved.');
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $teacher = Auth::guard('teacher')->user();

        // Delete old profile picture if exists
        if ($teacher->profile_picture && Storage::disk('public')->exists($teacher->profile_picture)) {
            Storage::disk('public')->delete($teacher->profile_picture);
        }

        $file = $request->file('profile_picture');
        $filename = 'teacher_' . $teacher->id . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Store the file
        $path = $file->storeAs('teacher-profiles', $filename, 'public');

        // Optional: Resize/compress the image
        try {
            $fullPath = storage_path('app/public/' . $path);
            if (class_exists('Intervention\Image\Facades\Image')) {
                $image = Image::make($fullPath);
                $image->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $image->save($fullPath, 80); // 80% quality
            }
        } catch (\Exception $e) {
            // If image processing fails, continue with original file
        }

        // Update teacher record
        $teacher->update([
            'profile_picture' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully',
            'image_url' => asset('storage/' . $path)
        ]);
    }
}