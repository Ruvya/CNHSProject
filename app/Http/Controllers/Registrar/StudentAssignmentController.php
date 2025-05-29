<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subject;
use App\Models\StudentAssignment;
use App\Services\SubjectPackageService;
use Illuminate\Support\Facades\DB;

class StudentAssignmentController extends Controller
{
    // Middleware is applied at route level, no need for constructor middleware

    /**
     * Display student assignment dashboard
     */
    public function index(Request $request)
    {
        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentGradingPeriod = $request->get('grading_period', 'First Grading');

        // Get filter parameters
        $gradeLevel = $request->get('grade_level');
        $track = $request->get('track');
        $strand = $request->get('strand');
        $search = $request->get('search');

        // Build query for assignments
        $assignmentsQuery = StudentAssignment::with(['student', 'assignedBy'])
            ->where('school_year', $currentSchoolYear)
            ->where('grading_period', $currentGradingPeriod)
            ->where('status', 'active');

        // Apply filters
        if ($gradeLevel) {
            $assignmentsQuery->where('grade_level', $gradeLevel);
        }

        if ($track) {
            $assignmentsQuery->where('track', $track);
        }

        if ($strand) {
            $assignmentsQuery->where('strand', $strand);
        }

        if ($search) {
            $assignmentsQuery->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $assignments = $assignmentsQuery->paginate(20);

        // Get unassigned students
        $unassignedStudents = Student::whereNotIn('id', function($query) use ($currentSchoolYear, $currentGradingPeriod) {
            $query->select('student_id')
                  ->from('student_assignments')
                  ->where('school_year', $currentSchoolYear)
                  ->where('grading_period', $currentGradingPeriod)
                  ->where('status', 'active');
        })->orderBy('grade_level')->orderBy('last_name')->get();

        // Statistics
        $stats = [
            'total_students' => Student::count(),
            'assigned_students' => StudentAssignment::where('school_year', $currentSchoolYear)
                ->where('grading_period', $currentGradingPeriod)
                ->where('status', 'active')
                ->count(),
            'unassigned_students' => $unassignedStudents->count(),
            'total_subjects' => Subject::count(),
        ];

        return view('registrar.student-assignments.index', compact(
            'assignments',
            'unassignedStudents',
            'stats',
            'currentSchoolYear',
            'currentGradingPeriod',
            'gradeLevel',
            'track',
            'strand',
            'search'
        ));
    }

    /**
     * Show form to assign student with academic information
     */
    public function create(Request $request)
    {
        $studentId = $request->get('student_id');
        $student = $studentId ? Student::findOrFail($studentId) : null;

        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentGradingPeriod = 'First Grading';

        // Get all students for selection
        $students = Student::orderBy('grade_level')->orderBy('last_name')->get();

        // Available options
        $gradeLevels = ['Grade 11', 'Grade 12'];
        $tracks = ['Academic Track', 'TVL'];
        $strands = [
            'Academic Track' => ['STEM', 'ABM', 'HUMSS', 'GAS'],
            'TVL' => ['ICT', 'Home Economics', 'Agri-Fishery Arts', 'Industrial Arts']
        ];
        $gradingPeriods = ['First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading'];

        return view('registrar.student-assignments.create', compact(
            'student',
            'students',
            'currentSchoolYear',
            'currentGradingPeriod',
            'gradeLevels',
            'tracks',
            'strands',
            'gradingPeriods'
        ));
    }

    /**
     * Get subject packages based on academic information
     */
    public function getSubjectPackages(Request $request)
    {
        $request->validate([
            'grade_level' => 'required|string',
            'track' => 'required|string',
            'strand' => 'required|string',
            'grading_period' => 'required|string'
        ]);

        $subjectPackageService = new SubjectPackageService();
        $packages = $subjectPackageService->getSubjectPackage(
            $request->grade_level,
            $request->track,
            $request->strand,
            $request->grading_period
        );

        return response()->json([
            'success' => true,
            'packages' => [
                'core' => $packages['core']->map(function($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'code' => $subject->code,
                        'description' => $subject->description
                    ];
                }),
                'applied' => $packages['applied']->map(function($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'code' => $subject->code,
                        'description' => $subject->description
                    ];
                }),
                'specialized' => $packages['specialized']->map(function($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'code' => $subject->code,
                        'description' => $subject->description
                    ];
                })
            ]
        ]);
    }

    /**
     * Store student assignment with subjects
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'school_year' => 'required|string',
            'grading_period' => 'required|string',
            'grade_level' => 'required|string',
            'track' => 'required|string',
            'strand' => 'required|string',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if student already has assignment for this term
        $existingAssignment = StudentAssignment::where('student_id', $request->student_id)
            ->where('school_year', $request->school_year)
            ->where('grading_period', $request->grading_period)
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
            return back()->withErrors(['student_id' => 'Student is already assigned for this term.']);
        }

        DB::transaction(function () use ($request) {
            // Create student assignment
            $assignment = StudentAssignment::create([
                'student_id' => $request->student_id,
                'school_year' => $request->school_year,
                'grading_period' => $request->grading_period,
                'grade_level' => $request->grade_level,
                'track' => $request->track,
                'strand' => $request->strand,
                'subjects' => $request->subjects,
                'assignment_date' => now(),
                'status' => 'active',
                'assigned_by' => auth()->guard('registrar')->id(),
                'notes' => $request->notes
            ]);

            // Create subject assignments in pivot table
            $subjectPackageService = new SubjectPackageService();
            $subjectPackageService->createSubjectAssignments(
                $request->student_id,
                $request->subjects,
                $request->school_year,
                $request->grading_period
            );
        });

        return redirect()->route('registrar.student-assignments.index')
            ->with('success', 'Student assigned with subjects successfully.');
    }

    /**
     * Show assignment details
     */
    public function show(StudentAssignment $studentAssignment)
    {
        $studentAssignment->load(['student', 'assignedBy']);

        // Get assigned subjects
        $assignedSubjects = [];
        if ($studentAssignment->subjects && is_array($studentAssignment->subjects)) {
            $assignedSubjects = Subject::whereIn('id', $studentAssignment->subjects)->get();
        }

        return view('registrar.student-assignments.show', compact('studentAssignment', 'assignedSubjects'));
    }

    /**
     * Show form to edit assignment
     */
    public function edit(StudentAssignment $studentAssignment)
    {
        $currentSchoolYear = $studentAssignment->school_year;
        $currentGradingPeriod = $studentAssignment->grading_period;

        // Available options
        $gradeLevels = ['Grade 11', 'Grade 12'];
        $tracks = ['Academic Track', 'TVL'];
        $strands = [
            'Academic Track' => ['STEM', 'ABM', 'HUMSS', 'GAS'],
            'TVL' => ['ICT', 'Home Economics', 'Agri-Fishery Arts', 'Industrial Arts']
        ];
        $gradingPeriods = ['First Grading', 'Second Grading', 'Third Grading', 'Fourth Grading'];

        // Get assigned subjects
        $assignedSubjects = [];
        if ($studentAssignment->subjects && is_array($studentAssignment->subjects)) {
            $assignedSubjects = Subject::whereIn('id', $studentAssignment->subjects)->get();
        }

        return view('registrar.student-assignments.edit', compact(
            'studentAssignment',
            'gradeLevels',
            'tracks',
            'strands',
            'gradingPeriods',
            'assignedSubjects'
        ));
    }

    /**
     * Update assignment
     */
    public function update(Request $request, StudentAssignment $studentAssignment)
    {
        $request->validate([
            'grade_level' => 'required|string',
            'track' => 'required|string',
            'strand' => 'required|string',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
            'status' => 'required|in:active,transferred,dropped',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::transaction(function () use ($request, $studentAssignment) {
            $studentAssignment->update([
                'grade_level' => $request->grade_level,
                'track' => $request->track,
                'strand' => $request->strand,
                'subjects' => $request->subjects,
                'status' => $request->status,
                'notes' => $request->notes
            ]);

            // Update subject assignments in pivot table
            $subjectPackageService = new SubjectPackageService();
            $subjectPackageService->updateSubjectAssignments(
                $studentAssignment->student_id,
                $request->subjects,
                $studentAssignment->school_year,
                $studentAssignment->grading_period
            );
        });

        return redirect()->route('registrar.student-assignments.index')
            ->with('success', 'Student assignment updated successfully.');
    }

    /**
     * Remove assignment
     */
    public function destroy(StudentAssignment $studentAssignment)
    {
        DB::transaction(function () use ($studentAssignment) {
            $studentAssignment->update(['status' => 'dropped']);
        });

        return redirect()->route('registrar.student-assignments.index')
            ->with('success', 'Student assignment removed successfully.');
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
}
