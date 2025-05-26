<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectOffering;
use App\Models\SubjectSchedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectOfferingController extends Controller
{
    /**
     * Display a listing of subject offerings
     */
    public function index(Request $request)
    {
        $registrar = Auth::guard('registrar')->user();

        // Get filter parameters
        $schoolYear = $request->get('school_year', $this->getCurrentSchoolYear());
        $grading = $request->get('grading');
        $gradeLevel = $request->get('grade_level');

        // Build query for offerings
        $offeringsQuery = SubjectOffering::with(['subject', 'teacher', 'schedules'])
            ->where('registrar_id', $registrar->id)
            ->where('school_year', $schoolYear);

        if ($grading) {
            $offeringsQuery->where('grading', $grading);
        }

        if ($gradeLevel) {
            $offeringsQuery->where('grade_level', $gradeLevel);
        }

        $offerings = $offeringsQuery->orderBy('grade_level')
            ->orderBy('track')
            ->orderBy('grading')
            ->get();

        // Get available filters
        $availableSchoolYears = $this->getAvailableSchoolYears();
        $availableGradings = ['First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading'];
        $availableGradeLevels = ['Grade 11', 'Grade 12'];

        // Get statistics
        $stats = [
            'total_offerings' => $offerings->count(),
            'active_offerings' => $offerings->where('status', 'active')->count(),
            'total_enrolled' => $offerings->sum('enrolled_students'),
            'total_capacity' => $offerings->sum('max_students'),
        ];

        return view('registrar.subject-offerings.index', compact(
            'offerings',
            'schoolYear',
            'grading',
            'gradeLevel',
            'availableSchoolYears',
            'availableGradings',
            'availableGradeLevels',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new subject offering
     */
    public function create()
    {
        // Get all master subjects created by admin
        $masterSubjects = Subject::masterSubjects()
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        // Get active teachers
        $teachers = Teacher::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get available options
        $schoolYears = $this->getAvailableSchoolYears();
        $gradings = ['First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading'];
        $gradeLevels = ['Grade 11', 'Grade 12'];
        $tracks = ['ABM', 'STEM', 'HUMSS', 'TVL'];

        return view('registrar.subject-offerings.create', compact(
            'masterSubjects',
            'teachers',
            'schoolYears',
            'gradings',
            'gradeLevels',
            'tracks'
        ));
    }

    /**
     * Store a newly created subject offering
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'school_year' => 'required|string|max:20',
            'grading' => 'required|string|max:50',
            'grade_level' => 'required|string|max:50',
            'track' => 'required|string|max:50',
            'max_students' => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string|max:1000',
            // Schedule fields
            'schedules' => 'required|array|min:1',
            'schedules.*.day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        DB::beginTransaction();
        try {
            // Create the subject offering
            $offering = SubjectOffering::create([
                'subject_id' => $validated['subject_id'],
                'registrar_id' => Auth::guard('registrar')->id(),
                'teacher_id' => $validated['teacher_id'],
                'school_year' => $validated['school_year'],
                'grading' => $validated['grading'],
                'grade_level' => $validated['grade_level'],
                'track' => $validated['track'],
                'max_students' => $validated['max_students'],
                'notes' => $validated['notes'],
                'status' => 'active',
            ]);

            // Create schedules
            foreach ($validated['schedules'] as $scheduleData) {
                SubjectSchedule::create([
                    'subject_offering_id' => $offering->id,
                    'day_of_week' => $scheduleData['day_of_week'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                ]);
            }

            DB::commit();

            return redirect()->route('registrar.subject-offerings.index')
                ->with('success', 'Subject offering created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to create subject offering: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified subject offering
     */
    public function show(SubjectOffering $subjectOffering)
    {
        // Ensure the offering belongs to the current registrar
        if ($subjectOffering->registrar_id !== Auth::guard('registrar')->id()) {
            abort(403, 'Unauthorized access to this subject offering.');
        }

        $subjectOffering->load(['subject', 'teacher', 'schedules', 'students']);

        return view('registrar.subject-offerings.show', compact('subjectOffering'));
    }

    /**
     * Show the form for editing the specified subject offering
     */
    public function edit(SubjectOffering $subjectOffering)
    {
        // Ensure the offering belongs to the current registrar
        if ($subjectOffering->registrar_id !== Auth::guard('registrar')->id()) {
            abort(403, 'Unauthorized access to this subject offering.');
        }

        $subjectOffering->load(['subject', 'schedules']);

        // Get all master subjects
        $masterSubjects = Subject::masterSubjects()
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        // Get active teachers
        $teachers = Teacher::where('status', 'active')
            ->orderBy('name')
            ->get();

        // Get available options
        $schoolYears = $this->getAvailableSchoolYears();
        $gradings = ['First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading'];
        $gradeLevels = ['Grade 11', 'Grade 12'];
        $tracks = ['ABM', 'STEM', 'HUMSS', 'TVL'];

        return view('registrar.subject-offerings.edit', compact(
            'subjectOffering',
            'masterSubjects',
            'teachers',
            'schoolYears',
            'gradings',
            'gradeLevels',
            'tracks'
        ));
    }

    /**
     * Update the specified subject offering
     */
    public function update(Request $request, SubjectOffering $subjectOffering)
    {
        // Ensure the offering belongs to the current registrar
        if ($subjectOffering->registrar_id !== Auth::guard('registrar')->id()) {
            abort(403, 'Unauthorized access to this subject offering.');
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'school_year' => 'required|string|max:20',
            'grading' => 'required|string|max:50',
            'grade_level' => 'required|string|max:50',
            'track' => 'required|string|max:50',
            'max_students' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive,full',
            'notes' => 'nullable|string|max:1000',
            // Schedule fields
            'schedules' => 'required|array|min:1',
            'schedules.*.day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
        ]);

        DB::beginTransaction();
        try {
            // Update the subject offering
            $subjectOffering->update([
                'subject_id' => $validated['subject_id'],
                'teacher_id' => $validated['teacher_id'],
                'school_year' => $validated['school_year'],
                'grading' => $validated['grading'],
                'grade_level' => $validated['grade_level'],
                'track' => $validated['track'],
                'max_students' => $validated['max_students'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            // Delete existing schedules and create new ones
            $subjectOffering->schedules()->delete();

            foreach ($validated['schedules'] as $scheduleData) {
                SubjectSchedule::create([
                    'subject_offering_id' => $subjectOffering->id,
                    'day_of_week' => $scheduleData['day_of_week'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                ]);
            }

            DB::commit();

            return redirect()->route('registrar.subject-offerings.index')
                ->with('success', 'Subject offering updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to update subject offering: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified subject offering
     */
    public function destroy(SubjectOffering $subjectOffering)
    {
        // Ensure the offering belongs to the current registrar
        if ($subjectOffering->registrar_id !== Auth::guard('registrar')->id()) {
            abort(403, 'Unauthorized access to this subject offering.');
        }

        // Check if there are enrolled students
        if ($subjectOffering->enrolled_students > 0) {
            return back()->with('error', 'Cannot delete subject offering with enrolled students.');
        }

        $subjectOffering->delete();

        return redirect()->route('registrar.subject-offerings.index')
            ->with('success', 'Subject offering deleted successfully.');
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear(): string
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        // School year starts in June (month 6)
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    /**
     * Get available school years
     */
    private function getAvailableSchoolYears(): array
    {
        $currentYear = date('Y');
        $years = [];

        // Generate 3 years: previous, current, next
        for ($i = -1; $i <= 1; $i++) {
            $year = $currentYear + $i;
            $years[] = $year . '-' . ($year + 1);
        }

        return $years;
    }
}
