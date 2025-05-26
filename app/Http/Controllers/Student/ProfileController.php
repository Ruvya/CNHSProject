<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'emergency_name' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:50',
            'emergency_relationship' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'advisor' => 'nullable|string|max:100',
            'track' => 'nullable|string|max:100',
            'strand' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:100',
            'municipality' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'permanent_address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'social_media' => 'nullable|string|max:255',
        ]);

        $student->update($request->only([
            'advisor',
            'track',
            'strand',
            'province',
            'municipality',
            'barangay',
            'permanent_address',
            'phone',
            'social_media',
            'emergency_name',
            'emergency_phone',
            'emergency_relationship',
            'section',
            'email',
        ]));

        return back()->with('success', 'Profile updated!');
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $student = Auth::guard('student')->user();

        // Delete old profile picture if exists
        if ($student->profile_picture) {
            Storage::delete($student->profile_picture);
        }

        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile-pictures', 'public');
        
        // Update student record
        $student->update([
            'profile_picture' => $path
        ]);

        return redirect()->back()->with('success', 'Profile picture updated successfully');
    }
} 