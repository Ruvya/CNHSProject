<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\Registrar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SubjectAssignmentNotification;

class SubjectAssignmentController extends Controller
{
    /**
     * Display the subject assignment interface
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $selectedTeacher = $request->get('teacher_id');
        $selectedSubject = $request->get('subject_id');
        $selectedGradeLevel = $request->get('grade_level');
        $selectedTrack = $request->get('track');
        $selectedStrand = $request->get('strand');
        $schoolYear = $request->get('school_year', $this->getCurrentSchoolYear());
        $semester = $request->get('semester', $this->getCurrentSemester());

        // Get all teachers and subjects for dropdowns
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        // Get current assignments with filters
        $assignmentsQuery = TeacherAssignment::with(['teacher', 'subject', 'assignedBy'])
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->where('status', 'active');

        if ($selectedTeacher) {
            $assignmentsQuery->where('teacher_id', $selectedTeacher);
        }

        if ($selectedSubject) {
            $assignmentsQuery->where('subject_id', $selectedSubject);
        }

        $assignments = $assignmentsQuery->orderBy('created_at', 'desc')->paginate(15);

        // Get assignment statistics
        $stats = [
            'total_assignments' => TeacherAssignment::where('school_year', $schoolYear)
                ->where('semester', $semester)
                ->where('status', 'active')
                ->count(),
            'total_teachers' => Teacher::where('status', 'active')->count(),
            'assigned_teachers' => TeacherAssignment::where('school_year', $schoolYear)
                ->where('semester', $semester)
                ->where('status', 'active')
                ->distinct('teacher_id')
                ->count(),
            'total_subjects' => Subject::count(),
            'assigned_subjects' => TeacherAssignment::where('school_year', $schoolYear)
                ->where('semester', $semester)
                ->where('status', 'active')
                ->distinct('subject_id')
                ->count(),
        ];

        return view('registrar.subject-assignments.index', compact(
            'teachers', 'subjects', 'assignments', 'stats',
            'selectedTeacher', 'selectedSubject', 'selectedGradeLevel', 
            'selectedTrack', 'selectedStrand', 'schoolYear', 'semester'
        ));
    }

    /**
     * Show the form for creating a new subject assignment
     */
    public function create()
    {
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        
        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentSemester = $this->getCurrentSemester();

        return view('registrar.subject-assignments.create', compact(
            'teachers', 'subjects', 'currentSchoolYear', 'currentSemester'
        ));
    }

    /**
     * Store a new subject assignment
     */
    public function store(Request $request)
    {
        // Debug: Log the request data
        Log::info('Subject Assignment Request Data', $request->all());

        // Set default values if not provided
        $schoolYear = $request->input('school_year') ?: $this->getCurrentSchoolYear();
        $semester = $request->input('semester') ?: $this->getCurrentSemester();

        // Ensure we have values
        if (empty($schoolYear)) {
            $schoolYear = '2024-2025';
        }
        if (empty($semester)) {
            $semester = '1st Semester';
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'schedule' => 'nullable|array',
            'schedule.*.day' => 'nullable|string|max:255',
            'schedule.*.start_time' => 'nullable|date_format:H:i',
            'schedule.*.end_time' => 'nullable|date_format:H:i|after:schedule.*.start_time',
            'notes' => 'nullable|string|max:500',
            'send_email' => 'nullable|boolean'
        ]);

        // Add the required fields with defaults
        $validated['school_year'] = $schoolYear;
        $validated['semester'] = $semester;

        Log::info('Final validated data', $validated);

        // Check if teacher is already assigned to this subject
        $existingAssignment = TeacherAssignment::where('teacher_id', $validated['teacher_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('school_year', $validated['school_year'])
            ->where('semester', $validated['semester'])
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
            return back()->withInput()
                ->withErrors(['teacher_id' => 'This teacher is already assigned to this subject for the selected period.']);
        }

        // Check for schedule conflicts if schedule is provided
        if (!empty($validated['schedule'])) {
            $hasConflict = $this->checkScheduleConflict(
                $validated['teacher_id'],
                $validated['schedule'],
                $validated['school_year'],
                $validated['grading_period']
            );

            if ($hasConflict) {
                return back()->withInput()
                    ->withErrors(['schedule' => 'Teacher has a schedule conflict with existing assignments.']);
            }
        }

        try {
            DB::transaction(function () use ($validated, $request) {
                // Create the assignment
                $assignment = TeacherAssignment::create([
                    'teacher_id' => $validated['teacher_id'],
                    'subject_id' => $validated['subject_id'],
                    'school_year' => $validated['school_year'],
                    'grading_period' => $validated['grading_period'],
                    'schedule' => $validated['schedule'] ?? null,
                    'assignment_date' => now(),
                    'status' => 'active',
                    'assigned_by' => auth()->guard('registrar')->id(),
                    'notes' => $validated['notes'] ?? null
                ]);

                // Send email notification if requested
                if ($request->boolean('send_email')) {
                    $this->sendAssignmentNotification($assignment);
                }
            });

            return redirect()->route('registrar.subject-assignments.index')
                ->with('success', 'Subject assigned to teacher successfully!');

        } catch (\Exception $e) {
            Log::error('Subject assignment failed: ' . $e->getMessage(), [
                'teacher_id' => $validated['teacher_id'],
                'subject_id' => $validated['subject_id'],
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->with('error', 'Failed to assign subject. Please try again.');
        }
    }

    /**
     * Remove a subject assignment
     */
    public function destroy(TeacherAssignment $assignment)
    {
        try {
            $assignment->update(['status' => 'inactive']);
            
            return redirect()->route('registrar.subject-assignments.index')
                ->with('success', 'Subject assignment removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove assignment. Please try again.');
        }
    }

    /**
     * Check for schedule conflicts
     */
    private function checkScheduleConflict($teacherId, $schedule, $schoolYear, $gradingPeriod, $excludeId = null)
    {
        if (!$schedule || !is_array($schedule)) {
            return false;
        }

        $query = TeacherAssignment::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->where('school_year', $schoolYear)
            ->where('grading_period', $gradingPeriod);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingAssignments = $query->get();

        foreach ($existingAssignments as $assignment) {
            if ($this->schedulesConflict($schedule, $assignment->schedule)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if two schedules conflict
     */
    private function schedulesConflict($schedule1, $schedule2)
    {
        if (!$schedule1 || !$schedule2) {
            return false;
        }

        foreach ($schedule1 as $slot1) {
            foreach ($schedule2 as $slot2) {
                if ($this->timeSlotsConflict($slot1, $slot2)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if two time slots conflict
     */
    private function timeSlotsConflict($slot1, $slot2)
    {
        // Check if same day
        if ($slot1['day'] !== $slot2['day']) {
            return false;
        }

        $start1 = strtotime($slot1['start_time']);
        $end1 = strtotime($slot1['end_time']);
        $start2 = strtotime($slot2['start_time']);
        $end2 = strtotime($slot2['end_time']);

        // Check for time overlap
        return !($end1 <= $start2 || $start1 >= $end2);
    }

    /**
     * Send email notification to teacher
     */
    private function sendAssignmentNotification($assignment)
    {
        try {
            $teacher = $assignment->teacher;

            // Send the actual email
            Mail::to($teacher->email)->send(new SubjectAssignmentNotification($assignment));

            Log::info('Subject Assignment Notification sent successfully', [
                'teacher_email' => $teacher->email,
                'teacher_name' => $teacher->name,
                'subject_name' => $assignment->subject->name,
                'assignment_id' => $assignment->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send assignment notification: ' . $e->getMessage(), [
                'assignment_id' => $assignment->id,
                'teacher_email' => $assignment->teacher->email
            ]);

            // Don't throw the exception to avoid breaking the assignment process
            // The assignment should still be created even if email fails
        }
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear(): string
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    /**
     * Get current semester
     */
    private function getCurrentSemester(): string
    {
        $currentMonth = date('n');
        
        // Determine semester based on month
        // 1st Semester: June to December (months 6-12)
        // 2nd Semester: January to May (months 1-5)
        if ($currentMonth >= 6 && $currentMonth <= 12) {
            return '1st Semester';
        } else {
            return '2nd Semester';
        }
    }

    /**
     * Get current grading period (deprecated - use getCurrentSemester)
     */
    private function getCurrentGradingPeriod(): string
    {
        return $this->getCurrentSemester();
    }
}
