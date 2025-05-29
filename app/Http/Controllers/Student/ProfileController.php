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
        $dropdownOptions = $this->getDropdownOptions();
        return view('student.profile', compact('student', 'dropdownOptions'));
    }

    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:students,email,' . $student->id,
            'password' => 'nullable|string|min:8|confirmed',
            'grade_level' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'emergency_name' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:50',
            'emergency_relationship' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'advisor' => 'nullable|string|max:100',
            'track' => 'nullable|string|max:100',
            'strand' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'province' => 'nullable|string|max:100',
            'municipality' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'permanent_address' => 'nullable|string|max:255',
            'social_media' => 'nullable|string|max:255',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:255',
            'lrn' => 'nullable|string|max:255',
        ]);

        // Prepare update data
        $updateData = $request->only([
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'grade_level',
            'gender',
            'contact_number',
            'address',
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
            'parent_name',
            'parent_contact',
            'lrn',
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $student->update($updateData);

        return back()->with('success', 'Profile updated successfully!');
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
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');

        // Update student record
        $student->update([
            'profile_picture' => $path
        ]);

        return redirect()->back()->with('success', 'Profile picture updated successfully');
    }

    /**
     * Show the profile completion form for temporary accounts
     */
    public function showCompleteForm()
    {
        $student = Auth::guard('student')->user();

        // Redirect if not a temporary account or already completed
        if (!$student->is_temporary_account || $student->profile_completed) {
            return redirect()->route('student.dashboard');
        }

        $dropdownOptions = $this->getDropdownOptions();
        return view('student.profile.complete', compact('student', 'dropdownOptions'));
    }

    /**
     * Complete the profile for temporary accounts
     */
    public function completeProfile(Request $request)
    {
        $student = Auth::guard('student')->user();

        // Ensure this is a temporary account that hasn't been completed
        if (!$student->is_temporary_account || $student->profile_completed) {
            return redirect()->route('student.dashboard');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . $student->id,
            'password' => 'nullable|string|min:8|confirmed',
            'grade_level' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:255',
            'track' => 'nullable|string|max:255',
            'strand' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'advisor' => 'nullable|string|max:255',
            'lrn' => 'nullable|string|max:255',
        ]);

        // Prepare update data
        $updateData = $request->only([
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'grade_level',
            'gender',
            'contact_number',
            'address',
            'parent_name',
            'parent_contact',
            'track',
            'strand',
            'section',
            'advisor',
            'lrn'
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        // Mark profile as completed
        $updateData['profile_completed'] = true;
        $updateData['is_temporary_account'] = false;

        // Update the student record
        $student->update($updateData);

        return redirect()->route('student.dashboard')
            ->with('success', 'Welcome to CNHS! Your profile has been completed successfully.');
    }

    /**
     * Get dropdown options for profile forms
     */
    private function getDropdownOptions()
    {
        // Get all active teachers for adviser dropdown
        $teachers = \App\Models\Teacher::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->pluck('name', 'name')
            ->toArray();

        return [
            'grade_levels' => [
                'Grade 11' => 'Grade 11',
                'Grade 12' => 'Grade 12',
            ],
            'tracks' => [
                'Academic Track' => 'Academic Track',
                'Technical-Vocational-Livelihood Track' => 'Technical-Vocational-Livelihood Track',
                'Sports Track' => 'Sports Track',
                'Arts and Design Track' => 'Arts and Design Track',
            ],
            'strands' => [
                // Academic Track Strands
                'STEM' => 'STEM (Science, Technology, Engineering, and Mathematics)',
                'HUMSS' => 'HUMSS (Humanities and Social Sciences)',
                'ABM' => 'ABM (Accountancy, Business, and Management)',
                'GAS' => 'GAS (General Academic Strand)',

                // TVL Track Strands
                'TVL-AFA' => 'TVL-AFA (Agri-Fishery Arts)',
                'TVL-HE' => 'TVL-HE (Home Economics)',
                'TVL-IA' => 'TVL-IA (Industrial Arts)',
                'TVL-ICT' => 'TVL-ICT (Information and Communications Technology)',

                // Sports Track
                'Sports' => 'Sports',

                // Arts and Design Track
                'Creative Writing' => 'Creative Writing',
                'Visual Arts' => 'Visual Arts',
                'Performing Arts' => 'Performing Arts',
                'Media Arts' => 'Media Arts',
            ],
            'sections' => [
                'Einstein' => 'Einstein',
                'Newton' => 'Newton',
                'Darwin' => 'Darwin',
                'Curie' => 'Curie',
                'Tesla' => 'Tesla',
                'Galileo' => 'Galileo',
                'Hawking' => 'Hawking',
                'Pasteur' => 'Pasteur',
            ],
            'genders' => [
                'Male' => 'Male',
                'Female' => 'Female',
            ],
            'relationships' => [
                'Parent' => 'Parent',
                'Guardian' => 'Guardian',
                'Sibling' => 'Sibling',
                'Relative' => 'Relative',
                'Family Friend' => 'Family Friend',
                'Other' => 'Other',
            ],
            'teachers' => $teachers,
        ];
    }
}