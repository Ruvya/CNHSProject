<?php

namespace App\Http\Controllers\Principal;

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
        $principal = Auth::guard('principal')->user();
        return view('Principal.profile.index', compact('principal'));
    }

    public function update(Request $request)
    {
        $principal = Auth::guard('principal')->user();

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('principals')->ignore($principal->id)],
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'education' => 'nullable|string|max:500',
            'years_of_experience' => 'nullable|integer|min:0|max:50',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:Male,Female,Other',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_number' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'facebook' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $request->validate($rules);

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!$request->filled('current_password')) {
                return back()->withErrors(['current_password' => 'Current password is required to change password.']);
            }
            
            if (!Hash::check($request->current_password, $principal->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($principal->profile_picture && Storage::disk('public')->exists($principal->profile_picture)) {
                Storage::disk('public')->delete($principal->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = 'principal_' . $principal->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('principal-profiles', $filename, 'public');
            $principal->profile_picture = $path;
        }

        // Handle profile picture removal
        if ($request->has('remove_profile_picture')) {
            if ($principal->profile_picture && Storage::disk('public')->exists($principal->profile_picture)) {
                Storage::disk('public')->delete($principal->profile_picture);
            }
            $principal->profile_picture = null;
        }

        // Update basic information
        $principal->name = $request->name;
        $principal->email = $request->email;
        $principal->contact_number = $request->contact_number;
        $principal->address = $request->address;
        $principal->position = $request->position ?? 'Principal';
        $principal->bio = $request->bio;
        $principal->education = $request->education;
        $principal->years_of_experience = $request->years_of_experience;
        $principal->date_of_birth = $request->date_of_birth;
        $principal->gender = $request->gender;
        $principal->emergency_contact_name = $request->emergency_contact_name;
        $principal->emergency_contact_number = $request->emergency_contact_number;
        $principal->emergency_contact_relationship = $request->emergency_contact_relationship;
        $principal->facebook = $request->facebook;
        $principal->linkedin = $request->linkedin;
        $principal->twitter = $request->twitter;

        // Update password if provided
        if ($request->filled('password')) {
            $principal->password = Hash::make($request->password);
        }

        $principal->save();

        return redirect()->route('principal.profile')
            ->with('success', 'Profile updated successfully! Your changes have been saved.');
    }

    public function uploadProfilePicture(Request $request)
    {
        try {
            $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $principal = Auth::guard('principal')->user();

            // Log the upload attempt
            \Log::info('Profile picture upload attempt', [
                'principal_id' => $principal->id,
                'principal_name' => $principal->name,
                'file_size' => $request->file('profile_picture')->getSize(),
                'file_type' => $request->file('profile_picture')->getMimeType()
            ]);

            // Delete old profile picture if exists
            if ($principal->profile_picture && Storage::disk('public')->exists($principal->profile_picture)) {
                Storage::disk('public')->delete($principal->profile_picture);
                \Log::info('Deleted old profile picture', ['old_path' => $principal->profile_picture]);
            }

            $file = $request->file('profile_picture');
            $filename = 'principal_' . $principal->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store the file
            $path = $file->storeAs('principal-profiles', $filename, 'public');

            // Verify the file was stored
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception('File was not stored successfully');
            }

            // Update principal record
            $principal->update([
                'profile_picture' => $path
            ]);

            // Verify the database was updated
            $updatedPrincipal = $principal->fresh();
            if ($updatedPrincipal->profile_picture !== $path) {
                throw new \Exception('Database was not updated successfully');
            }

            \Log::info('Profile picture uploaded successfully', [
                'principal_id' => $principal->id,
                'new_path' => $path,
                'full_url' => asset('storage/' . $path)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully!',
                'profile_picture_url' => asset('storage/' . $path),
                'debug_info' => [
                    'path' => $path,
                    'filename' => $filename,
                    'storage_exists' => Storage::disk('public')->exists($path),
                    'principal_id' => $principal->id
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Profile picture validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Profile picture upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'principal_id' => Auth::guard('principal')->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeProfilePicture()
    {
        $principal = Auth::guard('principal')->user();

        if ($principal->profile_picture && Storage::disk('public')->exists($principal->profile_picture)) {
            Storage::disk('public')->delete($principal->profile_picture);
        }

        $principal->update([
            'profile_picture' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile picture removed successfully!',
            'profile_picture_url' => asset('images/default-avatar.png')
        ]);
    }

    /**
     * Get current profile picture info for debugging
     */
    public function getProfilePictureInfo()
    {
        $principal = Auth::guard('principal')->user();

        $info = [
            'principal_id' => $principal->id,
            'principal_name' => $principal->name,
            'profile_picture_path' => $principal->profile_picture,
            'profile_picture_url' => $principal->profile_picture ? asset('storage/' . $principal->profile_picture) : null,
            'file_exists' => $principal->profile_picture ? Storage::disk('public')->exists($principal->profile_picture) : false,
            'storage_path' => $principal->profile_picture ? storage_path('app/public/' . $principal->profile_picture) : null,
            'public_path' => $principal->profile_picture ? public_path('storage/' . $principal->profile_picture) : null,
        ];

        return response()->json($info);
    }
}
