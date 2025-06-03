<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Section;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\DB;

class TeacherAssignmentController extends Controller
{
    // Middleware is applied at route level, no need for constructor middleware

    /**
     * Display teacher assignment dashboard
     */
    public function index(Request $request)
    {
        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentGradingPeriod = $request->get('grading_period', 'First Grading');

        // Get filter parameters
        $gradeLevel = $request->get('grade_level');
        $track = $request->get('track');
        $teacherId = $request->get('teacher_id');
        $subjectId = $request->get('subject_id');
        $search = $request->get('search');

        // Build query for assignments
        $assignmentsQuery = TeacherAssignment::with(['teacher', 'subject', 'assignedBy'])
            ->where('school_year', $currentSchoolYear)
            ->where('grading_period', $currentGradingPeriod)
            ->where('status', 'active');

        // Apply filters
        if ($gradeLevel) {
            $assignmentsQuery->whereHas('subject', function($q) use ($gradeLevel) {
                $q->where('grade_level', $gradeLevel);
            });
        }

        if ($track) {
            $assignmentsQuery->whereHas('subject', function($q) use ($track) {
                $q->where('track', $track);
            });
        }

        if ($teacherId) {
            $assignmentsQuery->where('teacher_id', $teacherId);
        }

        if ($subjectId) {
            $assignmentsQuery->where('subject_id', $subjectId);
        }

        if ($search) {
            $assignmentsQuery->where(function($q) use ($search) {
                $q->whereHas('teacher', function($tq) use ($search) {
                    $tq->where('name', 'like', "%{$search}%");
                })->orWhereHas('subject', function($sq) use ($search) {
                    $sq->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $assignments = $assignmentsQuery->paginate(20);

        // Get available data for dropdowns
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $sections = Section::where('school_year', $currentSchoolYear)
            ->where('grading_period', $currentGradingPeriod)
            ->where('status', '!=', 'inactive')
            ->orderBy('grade_level')
            ->orderBy('track')
            ->orderBy('name')
            ->get();

        // Statistics
        $stats = [
            'total_teachers' => Teacher::count(),
            'assigned_teachers' => TeacherAssignment::where('school_year', $currentSchoolYear)
                ->where('grading_period', $currentGradingPeriod)
                ->where('status', 'active')
                ->distinct('teacher_id')
                ->count(),
            'total_subjects' => Subject::count(),
            'assigned_subjects' => TeacherAssignment::where('school_year', $currentSchoolYear)
                ->where('grading_period', $currentGradingPeriod)
                ->where('status', 'active')
                ->distinct('subject_id')
                ->count(),
        ];

        return view('registrar.teacher-assignments.index', compact(
            'assignments',
            'teachers',
            'subjects',
            'sections',
            'stats',
            'currentSchoolYear',
            'currentGradingPeriod',
            'gradeLevel',
            'track',
            'teacherId',
            'subjectId',
            'search'
        ));
    }

    /**
     * Show form to assign teacher to subject and section
     */
    public function create(Request $request)
    {
        $teacherId = $request->get('teacher_id');
        $teacher = $teacherId ? Teacher::findOrFail($teacherId) : null;

        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentGradingPeriod = 'First Grading';

        // Get available data
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        // Get available grade levels
        $gradeLevels = Subject::distinct()->pluck('grade_level')->sort()->values();

        return view('registrar.teacher-assignments.create', compact(
            'teacher',
            'teachers',
            'subjects',
            'gradeLevels',
            'currentSchoolYear',
            'currentGradingPeriod'
        ));
    }

    /**
     * Get subjects by grade level (AJAX endpoint)
     */
    public function getSubjectsByGradeLevel(Request $request)
    {
        $gradeLevel = $request->get('grade_level');

        if (!$gradeLevel) {
            return response()->json([
                'success' => false,
                'message' => 'Grade level is required'
            ], 400);
        }

        $subjects = Subject::where('grade_level', $gradeLevel)
            ->orderBy('track')
            ->orderBy('strand')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'grade_level', 'track', 'strand']);

        return response()->json([
            'success' => true,
            'subjects' => $subjects
        ]);
    }

    /**
     * Store teacher assignment
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'teacher_id' => 'required|exists:teachers,id',
                'subject_id' => 'required|exists:subjects,id',
                'school_year' => 'required|string',
                'grading_period' => 'required|string',
                'schedule' => 'nullable|array',
                'schedule.*.day' => 'required_with:schedule|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
                'schedule.*.start_time' => 'required_with:schedule|date_format:H:i',
                'schedule.*.end_time' => 'required_with:schedule|date_format:H:i|after:schedule.*.start_time',
                'notes' => 'nullable|string|max:500'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput()
                ->with('error', 'Please check the form for errors and try again.');
        }

        try {
            // Check if assignment already exists
            $existingAssignment = TeacherAssignment::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->where('school_year', $request->school_year)
            ->where('grading_period', $request->grading_period)
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
            return back()->withErrors(['teacher_id' => 'This teacher is already assigned to this subject for the selected period.']);
        }

        // Check for schedule conflicts if schedule is provided
        if ($request->schedule) {
            $assignment = new TeacherAssignment();
            $assignment->school_year = $request->school_year;
            $assignment->grading_period = $request->grading_period;

            if ($assignment->hasScheduleConflict($request->teacher_id, $request->schedule)) {
                return back()->withErrors(['schedule' => 'Teacher has a schedule conflict with existing assignments.']);
            }
        }

        $assignment = null;
        DB::transaction(function () use ($request, &$assignment) {
            $assignment = TeacherAssignment::create([
                'teacher_id' => $request->teacher_id,
                'subject_id' => $request->subject_id,
                'school_year' => $request->school_year,
                'grading_period' => $request->grading_period,
                'schedule' => $request->schedule,
                'assignment_date' => now(),
                'status' => 'active',
                'assigned_by' => auth()->guard('registrar')->id(),
                'notes' => $request->notes
            ]);
        });

        // Get teacher and subject details for the success message
        $teacher = Teacher::find($request->teacher_id);
        $subject = Subject::find($request->subject_id);

        $successMessage = "🎉 Teacher successfully assigned! {$teacher->name} has been assigned to teach {$subject->name} ({$subject->code}) for {$request->school_year} - {$request->grading_period}.";

        // Prepare assignment details for display
        $assignmentDetails = [
            'teacher_name' => $teacher->name,
            'teacher_email' => $teacher->email,
            'subject_name' => $subject->name,
            'subject_code' => $subject->code,
            'grade_level' => $subject->grade_level,
            'track' => $subject->track,
            'strand' => $subject->strand,
            'school_year' => $request->school_year,
            'grading_period' => $request->grading_period,
            'assignment_date' => now()->format('M d, Y'),
            'has_schedule' => !empty($request->schedule)
        ];

        return redirect()->route('registrar.teacher-assignments.index')
            ->with('success', $successMessage)
            ->with('assignment_details', $assignmentDetails);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Teacher assignment failed: ' . $e->getMessage(), [
                'teacher_id' => $request->teacher_id,
                'subject_id' => $request->subject_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()
                ->with('error', 'Failed to assign teacher. Please try again. If the problem persists, contact the system administrator.');
        }
    }

    /**
     * Show assignment details
     */
    public function show(TeacherAssignment $teacherAssignment)
    {
        $teacherAssignment->load(['teacher', 'subject', 'assignedBy']);
        return view('registrar.teacher-assignments.show', compact('teacherAssignment'));
    }

    /**
     * Show form to edit assignment
     */
    public function edit(TeacherAssignment $teacherAssignment)
    {
        $currentSchoolYear = $teacherAssignment->school_year;
        $currentGradingPeriod = $teacherAssignment->grading_period;

        // Get available data
        $teachers = Teacher::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('registrar.teacher-assignments.edit', compact(
            'teacherAssignment',
            'teachers',
            'subjects'
        ));
    }

    /**
     * Update assignment
     */
    public function update(Request $request, TeacherAssignment $teacherAssignment)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'schedule' => 'nullable|array',
            'schedule.*.day' => 'required_with:schedule|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'schedule.*.start_time' => 'required_with:schedule|date_format:H:i',
            'schedule.*.end_time' => 'required_with:schedule|date_format:H:i|after:schedule.*.start_time',
            'status' => 'required|in:active,inactive,completed',
            'notes' => 'nullable|string|max:500'
        ]);

        // Check for schedule conflicts if schedule is provided and teacher changed
        if ($request->schedule && $request->teacher_id != $teacherAssignment->teacher_id) {
            if ($teacherAssignment->hasScheduleConflict($request->teacher_id, $request->schedule, $teacherAssignment->id)) {
                return back()->withErrors(['schedule' => 'Teacher has a schedule conflict with existing assignments.']);
            }
        }

        DB::transaction(function () use ($request, $teacherAssignment) {
            $teacherAssignment->update([
                'teacher_id' => $request->teacher_id,
                'subject_id' => $request->subject_id,
                'schedule' => $request->schedule,
                'status' => $request->status,
                'notes' => $request->notes
            ]);
        });

        return redirect()->route('registrar.teacher-assignments.index')
            ->with('success', 'Teacher assignment updated successfully.');
    }

    /**
     * Remove assignment
     */
    public function destroy(TeacherAssignment $teacherAssignment)
    {
        DB::transaction(function () use ($teacherAssignment) {
            $teacherAssignment->update(['status' => 'inactive']);
        });

        return redirect()->route('registrar.teacher-assignments.index')
            ->with('success', 'Teacher assignment removed successfully.');
    }

    /**
     * Check teacher qualification for subject
     */
    public function checkQualification(Request $request)
    {
        $teacherId = $request->get('teacher_id');
        $subjectId = $request->get('subject_id');

        if (!$teacherId || !$subjectId) {
            return response()->json(['qualified' => false, 'message' => 'Invalid parameters']);
        }

        $qualified = TeacherAssignment::isTeacherQualified($teacherId, $subjectId);
        $message = $qualified ? 'Teacher is qualified for this subject' : 'Teacher may not be qualified for this subject';

        return response()->json(['qualified' => $qualified, 'message' => $message]);
    }

    /**
     * Check schedule conflicts
     */
    public function checkScheduleConflict(Request $request)
    {
        $teacherId = $request->get('teacher_id');
        $schedule = $request->get('schedule');
        $excludeId = $request->get('exclude_id');

        if (!$teacherId || !$schedule) {
            return response()->json(['conflict' => false, 'message' => 'No schedule provided']);
        }

        $assignment = new TeacherAssignment();
        $assignment->school_year = $this->getCurrentSchoolYear();
        $assignment->grading_period = $request->get('grading_period', 'First Grading');

        $hasConflict = $assignment->hasScheduleConflict($teacherId, $schedule, $excludeId);
        $message = $hasConflict ? 'Schedule conflict detected' : 'No schedule conflicts';

        return response()->json(['conflict' => $hasConflict, 'message' => $message]);
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
