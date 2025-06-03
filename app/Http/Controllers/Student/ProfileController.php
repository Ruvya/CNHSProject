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
        // Prevent students from editing their profiles - only registrars can edit
        return back()->with('error', 'Profile editing is restricted. Please contact the registrar\'s office for any changes to your information.');
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
        // Prevent students from completing profiles - only registrars can edit
        return redirect()->route('student.dashboard')
            ->with('error', 'Profile editing is restricted. Please contact the registrar\'s office for any changes to your information.');
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