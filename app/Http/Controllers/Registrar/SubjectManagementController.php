<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubjectAssignmentNotification;

class SubjectManagementController extends Controller
{
    /**
     * Display subjects with their assignments
     */
    public function index(Request $request)
    {
        $query = Subject::with(['teacher', 'teacherAssignments.teacher', 'students']);

        // Handle search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%");
            });
        }

        // Handle grade level filter
        if ($request->filled('grade_level')) {
            $query->where('grade_level', 'Grade ' . $request->input('grade_level'));
        }

        // Handle track filter
        if ($request->filled('track')) {
            $query->where('track', $request->input('track'));
        }

        // Handle assignment status filter
        if ($request->filled('assignment_status')) {
            if ($request->assignment_status === 'assigned') {
                $query->whereHas('teacherAssignments', function($q) {
                    $q->where('status', 'active');
                });
            } elseif ($request->assignment_status === 'unassigned') {
                $query->whereDoesntHave('teacherAssignments', function($q) {
                    $q->where('status', 'active');
                });
            }
        }

        $subjects = $query->orderBy('grade_level')->orderBy('name')->paginate(15);

        // Get current school year and semester for assignment context
        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentSemester = $this->getCurrentSemester();

        // Get assignment statistics
        $stats = [
            'total_subjects' => Subject::count(),
            'assigned_subjects' => Subject::whereHas('teacherAssignments', function($q) use ($currentSchoolYear, $currentSemester) {
                $q->where('school_year', $currentSchoolYear)
                  ->where('semester', $currentSemester)
                  ->where('status', 'active');
            })->count(),
            'unassigned_subjects' => Subject::whereDoesntHave('teacherAssignments', function($q) use ($currentSchoolYear, $currentSemester) {
                $q->where('school_year', $currentSchoolYear)
                  ->where('semester', $currentSemester)
                  ->where('status', 'active');
            })->count(),
            'total_teachers' => Teacher::where('status', 'active')->count(),
            'active_assignments' => TeacherAssignment::where('school_year', $currentSchoolYear)
                ->where('semester', $currentSemester)
                ->where('status', 'active')
                ->count(),
        ];

        // Get filter options
        $gradeLevels = Subject::distinct()->pluck('grade_level')->filter()->sort();
        $tracks = Subject::distinct()->pluck('track')->filter()->sort();

        return view('registrar.subject-management.index', compact(
            'subjects',
            'stats',
            'gradeLevels',
            'tracks',
            'currentSchoolYear',
            'currentSemester'
        ));
    }

    /**
     * Show the form for creating a new subject
     */
    public function create()
    {
        // Get all active teachers for assignment
        $teachers = Teacher::where('status', 'active')
            ->orderBy('name')
            ->get();

        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentSemester = $this->getCurrentSemester();

        return view('registrar.subject-management.create', compact('teachers', 'currentSchoolYear', 'currentSemester'));
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        // Check if this is a core subject
        $isCoreSubject = $request->has('is_core_subject');

        $validationRules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'is_core_subject' => 'nullable|boolean',
            'is_master_subject' => 'nullable|boolean',
        ];

        // For core subjects, these fields are auto-filled and not required to be validated
        if (!$isCoreSubject) {
            $validationRules['grade_level'] = 'required|in:Grade 11,Grade 12';
            $validationRules['track'] = 'required|string|max:100';
            $validationRules['semester'] = 'required|in:1st Semester,2nd Semester,Both Semesters';
        } else {
            $validationRules['grade_level'] = 'nullable|in:Grade 11,Grade 12';
            $validationRules['track'] = 'nullable|string|max:100';
            $validationRules['grading'] = 'nullable|in:First Grading,Second Grading,Third Grading,Fourth Grading,All Gradings';
        }

        $validationRules['cluster'] = 'nullable|string|max:100';
        $validationRules['specialization'] = 'nullable|string|max:100';
        
        // Schedule validation rules
        $validationRules['schedule_days'] = 'nullable|array';
        $validationRules['schedule_days.*'] = 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday';
        $validationRules['start_time'] = 'nullable|date_format:H:i';
        $validationRules['end_time'] = 'nullable|date_format:H:i|after:start_time';
        $validationRules['room'] = 'nullable|string|max:100';
        $validationRules['schedule_notes'] = 'nullable|string|max:500';

        // Assignment validation rules
        $validationRules['assign_teacher'] = 'nullable|boolean';
        $validationRules['assignment_teacher_id'] = 'nullable|exists:teachers,id';
        $validationRules['assignment_schedule'] = 'nullable|array';
        $validationRules['assignment_schedule.*.day'] = 'nullable|string|max:255';
        $validationRules['assignment_schedule.*.start_time'] = 'nullable|date_format:H:i';
        $validationRules['assignment_schedule.*.end_time'] = 'nullable|date_format:H:i|after:assignment_schedule.*.start_time';
        $validationRules['assignment_notes'] = 'nullable|string|max:500';
        $validationRules['send_email'] = 'nullable|boolean';

        $validated = $request->validate($validationRules);

        // Convert checkbox values
        $validated['is_core_subject'] = $request->has('is_core_subject');
        $validated['is_master_subject'] = $request->has('is_master_subject');

        // For core subjects, auto-fill the required fields
        if ($validated['is_core_subject']) {
            $validated['grade_level'] = 'Grade 11';
            $validated['track'] = 'All';
            $validated['cluster'] = 'All';
            $validated['grading'] = 'All Gradings';
        }

        // Automatically assign the current registrar or admin as the creator
        $acting = $this->getActingUser();
        if ($acting['type'] === 'admin') {
            $validated['registrar_id'] = null;
            $validated['created_by_admin_id'] = $acting['user']->id;
        } elseif ($acting['type'] === 'registrar') {
            $validated['registrar_id'] = $acting['user']->id;
        }

        // Ensure code is uppercase
        $validated['code'] = strtoupper($validated['code']);

        // Process schedule days - convert array to comma-separated string
        if (isset($validated['schedule_days']) && is_array($validated['schedule_days'])) {
            $validated['schedule_days'] = implode(',', $validated['schedule_days']);
        }

        try {
            DB::transaction(function () use ($validated, $request) {
                // Create the subject
                $subject = Subject::create($validated);

                // Create teacher assignment if requested
                if ($request->boolean('assign_teacher') && $request->filled('assignment_teacher_id')) {
                    $this->createTeacherAssignment($subject, $request);
                }
            });

            return redirect()->route('registrar.subject-management.index')
                ->with('success', 'Subject created successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to create subject: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create subject. Please try again.');
        }
    }

    /**
     * Display the specified subject with its assignments
     */
    public function show(Subject $subject)
    {
        $subject->load(['teacher', 'teacherAssignments.teacher', 'students', 'registrar']);
        
        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentSemester = $this->getCurrentSemester();

        // Get current assignments for this subject
        $currentAssignments = $subject->teacherAssignments()
            ->where('school_year', $currentSchoolYear)
            ->where('semester', $currentSemester)
            ->where('status', 'active')
            ->with('teacher')
            ->get();

        return view('registrar.subject-management.show', compact(
            'subject',
            'currentAssignments',
            'currentSchoolYear',
            'currentSemester'
        ));
    }

    /**
     * Show the form for editing the specified subject
     */
    public function edit(Subject $subject)
    {
        // Get all active teachers for assignment
        $teachers = Teacher::where('status', 'active')
            ->orderBy('name')
            ->get();

        $currentSchoolYear = $this->getCurrentSchoolYear();
        $currentSemester = $this->getCurrentSemester();

        // Get current assignments
        $currentAssignments = $subject->teacherAssignments()
            ->where('school_year', $currentSchoolYear)
            ->where('semester', $currentSemester)
            ->where('status', 'active')
            ->with('teacher')
            ->get();

        return view('registrar.subject-management.edit', compact(
            'subject',
            'teachers',
            'currentAssignments',
            'currentSchoolYear',
            'currentSemester'
        ));
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, Subject $subject)
    {
        // Check if this is a core subject
        $isCoreSubject = $request->has('is_core_subject');

        $validationRules = [
            'name' => 'required|string|max:255',
            'code' => "required|string|max:50|unique:subjects,code,{$subject->id}",
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'is_core_subject' => 'nullable|boolean',
            'is_master_subject' => 'nullable|boolean',
        ];

        // For core subjects, these fields are auto-filled and not required to be validated
        if (!$isCoreSubject) {
            $validationRules['grade_level'] = 'required|in:Grade 11,Grade 12';
            $validationRules['track'] = 'required|string|max:100';
            $validationRules['semester'] = 'required|in:1st Semester,2nd Semester,Both Semesters';
        } else {
            $validationRules['grade_level'] = 'nullable|in:Grade 11,Grade 12';
            $validationRules['track'] = 'nullable|string|max:100';
            $validationRules['grading'] = 'nullable|in:First Grading,Second Grading,Third Grading,Fourth Grading,All Gradings';
        }

        $validationRules['cluster'] = 'nullable|string|max:100';
        $validationRules['specialization'] = 'nullable|string|max:100';
        
        // Schedule validation rules
        $validationRules['schedule_days'] = 'nullable|array';
        $validationRules['schedule_days.*'] = 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday';
        $validationRules['start_time'] = 'nullable|date_format:H:i';
        $validationRules['end_time'] = 'nullable|date_format:H:i|after:start_time';
        $validationRules['room'] = 'nullable|string|max:100';
        $validationRules['schedule_notes'] = 'nullable|string|max:500';

        $validated = $request->validate($validationRules);

        // Convert checkbox values
        $validated['is_core_subject'] = $request->has('is_core_subject');
        $validated['is_master_subject'] = $request->has('is_master_subject');

        // For core subjects, auto-fill the required fields
        if ($validated['is_core_subject']) {
            $validated['grade_level'] = 'Grade 11';
            $validated['track'] = 'All';
            $validated['cluster'] = 'All';
            $validated['grading'] = 'All Gradings';
        }

        // Ensure code is uppercase
        $validated['code'] = strtoupper($validated['code']);

        // Process schedule days - convert array to comma-separated string
        if (isset($validated['schedule_days']) && is_array($validated['schedule_days'])) {
            $validated['schedule_days'] = implode(',', $validated['schedule_days']);
        }

        // Add default units value since we removed it from the form
        $validated['units'] = $subject->units ?? 3; // Keep existing units or default to 3

        // Optionally track who updated (admin or registrar)
        $acting = $this->getActingUser();
        if ($acting['type'] === 'admin') {
            $validated['registrar_id'] = null;
            $validated['updated_by_admin_id'] = $acting['user']->id;
        } elseif ($acting['type'] === 'registrar') {
            $validated['registrar_id'] = $acting['user']->id;
        }

        $subject->update($validated);

        return redirect()->route('registrar.subject-management.index')
            ->with('success', 'Subject updated successfully! Changes have been applied.');
    }

    /**
     * Remove the specified subject
     */
    public function destroy(Subject $subject)
    {
        try {
            DB::transaction(function () use ($subject) {
                // Manually delete related records to ensure data integrity
                $subject->grades()->delete();
                $subject->students()->detach();
                $subject->teacherAssignments()->delete();

                // Now, delete the subject
                $subject->delete();
            });

            return redirect()->route('registrar.subject-management.index')
                ->with('success', "Subject '{$subject->name}' and all its related data have been deleted successfully.");

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Failed to delete subject: ' . $e->getMessage());

            return redirect()->route('registrar.subject-management.index')
                ->with('error', 'Failed to delete subject. It might be linked to other critical data. Please check the logs.');
        }
    }

    /**
     * Assign teacher to subject
     */
    public function assignTeacher(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'schedule' => 'nullable|array',
            'schedule.*.day' => 'nullable|string|max:255',
            'schedule.*.start_time' => 'nullable|date_format:H:i',
            'schedule.*.end_time' => 'nullable|date_format:H:i|after:schedule.*.start_time',
            'notes' => 'nullable|string|max:500',
            'send_email' => 'nullable|boolean'
        ]);

        $schoolYear = $this->getCurrentSchoolYear();
        $semester = $this->getCurrentSemester();

        // Check if teacher is already assigned to this subject
        $existingAssignment = TeacherAssignment::where('teacher_id', $validated['teacher_id'])
            ->where('subject_id', $subject->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
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
                $schoolYear,
                $semester
            );

            if ($hasConflict) {
                return back()->withInput()
                    ->withErrors(['schedule' => 'Teacher has a schedule conflict with existing assignments.']);
            }
        }

        try {
            DB::transaction(function () use ($subject, $validated, $schoolYear, $semester, $request) {
                // Create the assignment
                $assignment = TeacherAssignment::create([
                    'teacher_id' => $validated['teacher_id'],
                    'subject_id' => $subject->id,
                    'school_year' => $schoolYear,
                    'semester' => $semester,
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

            return redirect()->route('registrar.subject-management.show', $subject)
                ->with('success', 'Teacher assigned to subject successfully!');

        } catch (\Exception $e) {
            Log::error('Teacher assignment failed: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to assign teacher. Please try again.');
        }
    }

    /**
     * Remove teacher assignment from subject
     */
    public function removeTeacherAssignment(TeacherAssignment $assignment)
    {
        try {
            $assignment->update(['status' => 'inactive']);
            
            return redirect()->back()
                ->with('success', 'Teacher assignment removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove assignment. Please try again.');
        }
    }

    /**
     * Get tracks based on grade level (AJAX endpoint)
     */
    public function getTracksByGrade(Request $request)
    {
        $gradeLevel = $request->get('grade_level');

        if (!$gradeLevel || $gradeLevel === 'all') {
            $tracks = Subject::select('track')
                ->distinct()
                ->whereNotNull('track')
                ->where('track', '!=', '')
                ->orderBy('track')
                ->pluck('track');
        } else {
            $gradeFilter = $gradeLevel === '11' ? 'Grade 11' : 'Grade 12';
            $tracks = Subject::select('track')
                ->distinct()
                ->where('grade_level', $gradeFilter)
                ->whereNotNull('track')
                ->where('track', '!=', '')
                ->orderBy('track')
                ->pluck('track');
        }

        return response()->json($tracks);
    }

    /**
     * Get clusters based on grade level and track (AJAX endpoint)
     */
    public function getClustersByGradeAndTrack(Request $request)
    {
        $gradeLevel = $request->get('grade_level');
        $track = $request->get('track');

        $query = Subject::select('cluster')->distinct();

        if ($gradeLevel && $gradeLevel !== 'all') {
            $gradeFilter = $gradeLevel === '11' ? 'Grade 11' : 'Grade 12';
            $query->where('grade_level', $gradeFilter);
        }

        if ($track && $track !== 'all') {
            $query->where('track', $track);
        }

        $clusters = $query->whereNotNull('cluster')
            ->where('cluster', '!=', '')
            ->orderBy('cluster')
            ->pluck('cluster');

        return response()->json($clusters);
    }

    /**
     * Get subjects based on filters (AJAX endpoint)
     */
    public function getSubjectsByFilters(Request $request)
    {
        $gradeLevel = $request->get('grade_level');
        $track = $request->get('track');
        $cluster = $request->get('cluster');

        $query = Subject::with(['teacher', 'registrar']);

        if ($gradeLevel && $gradeLevel !== 'all') {
            $gradeFilter = $gradeLevel === '11' ? 'Grade 11' : 'Grade 12';
            $query->where('grade_level', $gradeFilter);
        }

        if ($track && $track !== 'all') {
            $query->where('track', $track);
        }

        if ($cluster && $cluster !== 'all') {
            $query->where('cluster', $cluster);
        }

        $subjects = $query->orderBy('name')->get();

        return response()->json([
            'subjects' => $subjects,
            'count' => $subjects->count()
        ]);
    }

    /**
     * Helper to get the acting user and type (admin or registrar)
     */
    protected function getActingUser()
    {
        if (\Auth::guard('admin')->check()) {
            return ['user' => \Auth::guard('admin')->user(), 'type' => 'admin'];
        } elseif (\Auth::guard('registrar')->check()) {
            return ['user' => \Auth::guard('registrar')->user(), 'type' => 'registrar'];
        }
        return ['user' => null, 'type' => null];
    }

    /**
     * Create teacher assignment
     */
    private function createTeacherAssignment($subject, $request)
    {
        $schoolYear = $this->getCurrentSchoolYear();
        $semester = $this->getCurrentSemester();

        $assignment = TeacherAssignment::create([
            'teacher_id' => $request->assignment_teacher_id,
            'subject_id' => $subject->id,
            'school_year' => $schoolYear,
            'semester' => $semester,
            'schedule' => $request->assignment_schedule ?? null,
            'assignment_date' => now(),
            'status' => 'active',
            'assigned_by' => auth()->guard('registrar')->id(),
            'notes' => $request->assignment_notes ?? null
        ]);

        // Send email notification if requested
        if ($request->boolean('send_email')) {
            $this->sendAssignmentNotification($assignment);
        }

        return $assignment;
    }

    /**
     * Check for schedule conflicts
     */
    private function checkScheduleConflict($teacherId, $schedule, $schoolYear, $semester, $excludeId = null)
    {
        if (!$schedule || !is_array($schedule)) {
            return false;
        }

        $query = TeacherAssignment::where('teacher_id', $teacherId)
            ->where('status', 'active')
            ->where('school_year', $schoolYear)
            ->where('semester', $semester);

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
}
