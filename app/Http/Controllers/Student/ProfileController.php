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
        $file = $request->file('profile_picture');
        $filename = 'student_' . $student->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('student-profiles', $filename, 'public');

        // Update student record
        $student->profile_picture = $path;
        $student->save();

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

        // Get tracks from database
        $tracks = \App\Models\Track::active()
            ->orderBy('order')
            ->orderBy('name')
            ->pluck('name', 'name')
            ->toArray();

        // Get clusters from database
        $clusters = \App\Models\Cluster::active()
            ->with('track')
            ->orderBy('order')
            ->orderBy('name')
            ->get()
            ->pluck('name', 'name')
            ->toArray();

        return [
            'grade_levels' => [
                'Grade 11' => 'Grade 11',
                'Grade 12' => 'Grade 12',
            ],
            'tracks' => $tracks ?: [],
            'strands' => $clusters ?: [],
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