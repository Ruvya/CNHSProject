<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherYearlyRecord;
use App\Models\Subject;
use Illuminate\Http\Request;

class TeacherYearlyRecordController extends Controller
{
    /**
     * Display yearly records for a specific teacher
     */
    public function index(Teacher $teacher)
    {
        $records = $teacher->yearlyRecords()->orderBy('school_year', 'desc')->get();
        return view('registrar.teachers.yearly-records', compact('teacher', 'records'));
    }

    /**
     * Show the form for creating a new yearly record
     */
    public function create(Teacher $teacher)
    {
        // Get available subjects for dropdown
        $subjects = Subject::orderBy('name')->get();
        
        // Get current school year
        $currentSchoolYear = $this->getCurrentSchoolYear();
        
        return view('registrar.teachers.yearly-records-create', compact('teacher', 'subjects', 'currentSchoolYear'));
    }

    /**
     * Store a newly created yearly record
     */
    public function store(Request $request, Teacher $teacher)
    {
        $request->validate([
            'school_year' => 'required|string|max:9', // e.g., 2023-2024
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'subjects_taught' => 'nullable|array',
            'grade_levels_handled' => 'nullable|array',
            'advisory_section' => 'nullable|string|max:255',
            'total_students' => 'nullable|integer|min:0',
            'teaching_load' => 'nullable|numeric|min:0|max:999.99',
            'employment_status' => 'required|string|in:regular,substitute,part-time',
            'status' => 'required|string|in:active,inactive,transferred,resigned',
            'notes' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Check if record already exists for this school year
        $existingRecord = $teacher->yearlyRecords()->where('school_year', $request->school_year)->first();
        if ($existingRecord) {
            return back()->withErrors(['school_year' => 'A record for this school year already exists.'])->withInput();
        }

        $teacher->yearlyRecords()->create($request->all());

        return redirect()->route('registrar.teachers.yearly-records.index', $teacher)
            ->with('success', 'Teacher yearly record created successfully.');
    }

    /**
     * Show the form for editing a yearly record
     */
    public function edit(Teacher $teacher, TeacherYearlyRecord $yearlyRecord)
    {
        // Get available subjects for dropdown
        $subjects = Subject::orderBy('name')->get();
        
        return view('registrar.teachers.yearly-records-edit', compact('teacher', 'yearlyRecord', 'subjects'));
    }

    /**
     * Update the specified yearly record
     */
    public function update(Request $request, Teacher $teacher, TeacherYearlyRecord $yearlyRecord)
    {
        $request->validate([
            'school_year' => 'required|string|max:9',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'subjects_taught' => 'nullable|array',
            'grade_levels_handled' => 'nullable|array',
            'advisory_section' => 'nullable|string|max:255',
            'total_students' => 'nullable|integer|min:0',
            'teaching_load' => 'nullable|numeric|min:0|max:999.99',
            'employment_status' => 'required|string|in:regular,substitute,part-time',
            'status' => 'required|string|in:active,inactive,transferred,resigned',
            'notes' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Check if school year is being changed and if it conflicts
        if ($request->school_year !== $yearlyRecord->school_year) {
            $existingRecord = $teacher->yearlyRecords()
                ->where('school_year', $request->school_year)
                ->where('id', '!=', $yearlyRecord->id)
                ->first();
            if ($existingRecord) {
                return back()->withErrors(['school_year' => 'A record for this school year already exists.'])->withInput();
            }
        }

        $yearlyRecord->update($request->all());

        return redirect()->route('registrar.teachers.yearly-records.index', $teacher)
            ->with('success', 'Teacher yearly record updated successfully.');
    }

    /**
     * Remove the specified yearly record
     */
    public function destroy(Teacher $teacher, TeacherYearlyRecord $yearlyRecord)
    {
        $yearlyRecord->delete();

        return redirect()->route('registrar.teachers.yearly-records.index', $teacher)
            ->with('success', 'Teacher yearly record deleted successfully.');
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        // School year typically starts in June/July
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }
}
