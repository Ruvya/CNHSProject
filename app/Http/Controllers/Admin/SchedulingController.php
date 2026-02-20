<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Room;
use App\Services\SchedulingValidationService;

class SchedulingController extends Controller
{
    protected $validationService;

    public function __construct(SchedulingValidationService $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * Display a listing of schedules
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $selectedTeacher = $request->get('teacher_id');
        $selectedSubject = $request->get('subject_id');
        $selectedSection = $request->get('section_id');
        // Room removed from filters
        $selectedRoom = null;
        $selectedDay = $request->get('day');
        $schoolYear = $request->get('school_year');
        $gradingPeriod = $request->get('grading_period');

        // Get current school year if not specified
        if (!$schoolYear) {
            $schoolYear = $this->getCurrentSchoolYear();
        }

        // Get all options for dropdowns
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $sections = Section::orderBy('grade_level')->orderBy('name')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Determine period column (semester vs grading_period)
        $periodColumn = \Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester') ? 'semester' : 'grading_period';

        // Get schedules with filters
        $schedulesQuery = Schedule::with(['teacher', 'subject', 'section', 'room'])
            ->where('status', 'active');

        if ($schoolYear) {
            $schedulesQuery->where('school_year', $schoolYear);
        }
        if ($gradingPeriod) {
            $schedulesQuery->where($periodColumn, $gradingPeriod);
        }
        if ($selectedTeacher) {
            $schedulesQuery->where('teacher_id', $selectedTeacher);
        }
        if ($selectedSubject) {
            $schedulesQuery->where('subject_id', $selectedSubject);
        }
        if ($selectedSection) {
            $schedulesQuery->where('section_id', $selectedSection);
        }
        // Room removed from filters
        if ($selectedDay) {
            $schedulesQuery->where('day', $selectedDay);
        }

        $schedules = $schedulesQuery->orderBy('day')
            ->orderBy('start_time')
            ->paginate(20);

        // Get scheduling statistics
        $stats = [
            'total_schedules' => Schedule::where('status', 'active')->count(),
            'total_teachers' => Teacher::where('status', 'active')->count(),
            'total_subjects' => Subject::count(),
            'total_sections' => Section::count(),
        ];

        return view('admin.scheduling.index', compact(
            'teachers', 'subjects', 'sections', 'days', 'schedules', 'stats',
            'selectedTeacher', 'selectedSubject', 'selectedSection', 
            'selectedDay', 'schoolYear', 'gradingPeriod'
        ));
    }

    /**
     * Show the form for creating a new schedule
     */
    public function create(Request $request)
    {
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $sections = Section::orderBy('grade_level')->orderBy('name')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Pre-select values if provided
        $selectedTeacher = $request->get('teacher_id');
        $selectedSubject = $request->get('subject_id');
        $selectedSection = $request->get('section_id');

        return view('admin.scheduling.create', compact(
            'teachers', 'subjects', 'sections', 'days',
            'selectedTeacher', 'selectedSubject', 'selectedSection'
        ));
    }

    /**
     * Store a newly created schedule
     */
    public function store(Request $request)
    {
        $rules = [
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            // Room removed from scheduling flow
            'days' => 'required|array|min:1',
            'days.*' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'school_year' => 'required|string',
            'grading_period' => 'required|in:1st Semester,2nd Semester',
            'notes' => 'nullable|string|max:500',
        ];

        // For AJAX requests, return JSON with validation errors instead of redirect
        if ($request->wantsJson()) {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $validated = $validator->validated();
        } else {
            $validated = $request->validate($rules);
        }

        // Add created_by and status
        $validated['created_by'] = auth()->guard('admin')->id() ?? 1;
        $validated['status'] = 'active';

        // Room removed: ensure DB insert includes room_id as null for legacy schemas
        $validated['room_id'] = null;

        // Validate schedule for conflicts (map period to semester if applicable)
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester')) {
                $validated['semester'] = $validated['grading_period'] ?? null;
                unset($validated['grading_period']);
            }
        } catch (\Throwable $e) {}

        // Enforce: a teacher can only be assigned to one section per school year and semester
        try {
            $periodColumn = \Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester') ? 'semester' : 'grading_period';
            $periodValue = $validated[$periodColumn] ?? null;

            $existingSectionId = Schedule::where('teacher_id', $validated['teacher_id'])
                ->where('status', 'active')
                ->where('school_year', $validated['school_year'])
                ->when($periodValue, function($q) use ($periodColumn, $periodValue) {
                    $q->where($periodColumn, $periodValue);
                })
                ->value('section_id');

            if ($existingSectionId && (int)$existingSectionId !== (int)$validated['section_id']) {
                $message = 'This teacher is already assigned to another section for the selected school year and semester.';
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => ['section_id' => [$message]],
                    ], 422);
                }
                return redirect()->back()->withInput()->withErrors(['section_id' => $message]);
            }
        } catch (\Throwable $e) {}

        // Get selected days
        $selectedDays = $request->input('days', []);
        if (empty($selectedDays)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => ['days' => ['Please select at least one day.']],
                ], 422);
            }
            return redirect()->back()
                ->withInput()
                ->withErrors(['days' => 'Please select at least one day.']);
        }

        // Prepare base schedule data (without day)
        $baseScheduleData = $validated;
        unset($baseScheduleData['days']); // Remove days array from base data

        // Validate conflicts for each selected day
        $allErrors = [];
        $createdSchedules = [];
        
        foreach ($selectedDays as $day) {
            $scheduleData = array_merge($baseScheduleData, ['day' => $day]);
            
            // Validate schedule for conflicts
            $validation = $this->validationService->validateSchedule($scheduleData);
            
            if (!$validation['valid']) {
                foreach ($validation['errors'] as $error) {
                    $allErrors[] = "{$day}: {$error}";
                }
            } else {
                // Create the schedule for this day
                try {
                    $schedule = Schedule::create($scheduleData);
                    $createdSchedules[] = $schedule;
                } catch (\Exception $e) {
                    $allErrors[] = "{$day}: Failed to create schedule - " . $e->getMessage();
                }
            }
        }

        // If there are errors for any day, delete created schedules and return errors
        if (!empty($allErrors)) {
            // Delete any schedules that were created before errors
            foreach ($createdSchedules as $schedule) {
                try {
                    $schedule->delete();
                } catch (\Exception $e) {
                    // Ignore deletion errors
                }
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule validation failed',
                    'errors' => $allErrors,
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['days' => implode(', ', $allErrors)])
                ->with('error', 'Schedule validation failed: ' . implode(', ', $allErrors));
        }

        // Prepare success message
        $teacher = Teacher::find($validated['teacher_id']);
        $subject = Subject::find($validated['subject_id']);
        $section = Section::find($validated['section_id']);

        $dayCount = count($createdSchedules);
        $daysList = implode(', ', $selectedDays);
        
        $successMessage = "✅ Schedule Created Successfully!\n\n";
        $successMessage .= "📋 Schedule Details:\n";
        $successMessage .= "👨‍🏫 Teacher: {$teacher->name}\n";
        $successMessage .= "📚 Subject: {$subject->name}\n";
        $successMessage .= "🏫 Section: {$section->name}\n";
        $successMessage .= "📅 Days: {$daysList} ({$dayCount} " . ($dayCount == 1 ? 'schedule' : 'schedules') . " created)\n";
        $successMessage .= "🕒 Time: " . date('g:i A', strtotime($validated['start_time'])) . " - " . date('g:i A', strtotime($validated['end_time'])) . "\n";
        $successMessage .= "📊 School Year: {$validated['school_year']}\n";
        $successMessage .= "📝 Semester: " . ($validated['semester'] ?? ($validated['grading_period'] ?? '')) . "\n";
        $successMessage .= "🕒 Created on: " . now()->format('M d, Y h:i A');

        // If AJAX request, return JSON with redirect URL
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'redirect' => route('admin.scheduling.index'),
                'schedule_count' => count($createdSchedules),
                'schedule_ids' => array_map(function($s) { return $s->id; }, $createdSchedules),
            ]);
        }

        // Non-AJAX fallback
        return redirect()->route('admin.scheduling.index')
            ->with('success', $successMessage);
    }

    /**
     * Display the specified schedule
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['teacher', 'subject', 'section', 'room', 'createdBy']);
        return view('admin.scheduling.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule
     */
    public function edit(Schedule $schedule)
    {
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $sections = Section::orderBy('grade_level')->orderBy('name')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('admin.scheduling.edit', compact('schedule', 'teachers', 'subjects', 'sections', 'days'));
    }

    /**
     * Update the specified schedule
     */
    public function update(Request $request, Schedule $schedule)
    {
        $rules = [
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'school_year' => 'required|string',
            'status' => 'required|in:active,inactive,cancelled',
            'notes' => 'nullable|string|max:500',
        ];

        // Validate correct period field depending on schema
        if (\Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester')) {
            $rules['semester'] = 'required|in:1st Semester,2nd Semester';
        } else {
            $rules['grading_period'] = 'required|in:1st Semester,2nd Semester';
        }

        $validated = $request->validate($rules);

        // Map to semester column if present
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester')) {
                $validated['semester'] = $validated['grading_period'] ?? null;
                unset($validated['grading_period']);
            }
        } catch (\Throwable $e) {}

        // Validate schedule for conflicts (excluding current schedule)
        $validation = $this->validationService->validateSchedule($validated, $schedule->id);

        if (!$validation['valid']) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validation['errors'])
                ->with('error', 'Schedule validation failed: ' . implode(', ', $validation['errors']));
        }

        // Show warnings if any
        if (!empty($validation['warnings'])) {
            $request->session()->flash('warning', 'Warnings: ' . implode(', ', $validation['warnings']));
        }

        // Enforce: a teacher can only be assigned to one section per school year and semester (excluding current)
        try {
            $periodColumn = \Illuminate\Support\Facades\Schema::hasColumn('schedules', 'semester') ? 'semester' : 'grading_period';
            $periodValue = $validated[$periodColumn] ?? null;

            $existingSectionId = Schedule::where('teacher_id', $validated['teacher_id'])
                ->where('status', 'active')
                ->where('school_year', $validated['school_year'])
                ->when($periodValue, function($q) use ($periodColumn, $periodValue) {
                    $q->where($periodColumn, $periodValue);
                })
                ->where('id', '!=', $schedule->id)
                ->value('section_id');

            if ($existingSectionId && (int)$existingSectionId !== (int)$validated['section_id']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['section_id' => 'This teacher is already assigned to another section for the selected school year and semester.']);
            }
        } catch (\Throwable $e) {}

        // Update the schedule
        $schedule->update($validated);

        // Get related models for success message
        $teacher = Teacher::find($validated['teacher_id']);
        $subject = Subject::find($validated['subject_id']);
        $section = Section::find($validated['section_id']);
        // Room removed

        $successMessage = "✅ Schedule Updated Successfully!\n\n";
        $successMessage .= "📋 Updated Schedule Details:\n";
        $successMessage .= "👨‍🏫 Teacher: {$teacher->name}\n";
        $successMessage .= "📚 Subject: {$subject->name} ({$subject->code})\n";
        $successMessage .= "🏫 Section: {$section->name}\n";
        // Room removed from schedule details
        $successMessage .= "📅 Day: {$validated['day']}\n";
        $successMessage .= "🕒 Time: " . date('g:i A', strtotime($validated['start_time'])) . " - " . date('g:i A', strtotime($validated['end_time'])) . "\n";
        $successMessage .= "📊 School Year: {$validated['school_year']}\n";
        $successMessage .= "📝 Grading Period: " . ($validated['semester'] ?? ($validated['grading_period'] ?? '')) . "\n";
        $successMessage .= "📝 Status: {$validated['status']}\n";
        $successMessage .= "🕒 Updated on: " . now()->format('M d, Y h:i A');

        return redirect()->route('admin.scheduling.index')
            ->with('success', $successMessage);
    }

    /**
     * Remove the specified schedule
     */
    public function destroy(Schedule $schedule)
    {
        // Get details before deletion for confirmation message
        $teacher = $schedule->teacher;
        $subject = $schedule->subject;
        $section = $schedule->section;
        $room = $schedule->room;
        $day = $schedule->day;
        $startTime = date('g:i A', strtotime($schedule->start_time));
        $endTime = date('g:i A', strtotime($schedule->end_time));

        // Delete the schedule
        $schedule->delete();

        $successMessage = "✅ Schedule Removed Successfully!\n\n";
        $successMessage .= "📋 Removed Schedule Details:\n";
        $successMessage .= "👨‍🏫 Teacher: {$teacher->name}\n";
        $successMessage .= "📚 Subject: {$subject->name} ({$subject->code})\n";
        $successMessage .= "🏫 Section: {$section->name}\n";
        $successMessage .= "🏢 Room: {$room->name} ({$room->code})\n";
        $successMessage .= "📅 Day: {$day}\n";
        $successMessage .= "🕒 Time: {$startTime} - {$endTime}\n";
        $successMessage .= "🕒 Removed on: " . now()->format('M d, Y h:i A');

        return redirect()->route('admin.scheduling.index')
            ->with('success', $successMessage);
    }

    /**
     * Get available time slots for a teacher on a specific day
     */
    public function getAvailableTimeSlots(Request $request)
    {
        $teacherId = $request->get('teacher_id');
        $day = $request->get('day');
        $schoolYear = $request->get('school_year');
        $gradingPeriod = $request->get('grading_period');
        $excludeScheduleId = $request->get('exclude_schedule_id');

        if (!$teacherId || !$day || !$schoolYear || !$gradingPeriod) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        $timeSlots = $this->validationService->getAvailableTimeSlots(
            $teacherId, $day, $schoolYear, $gradingPeriod, $excludeScheduleId
        );

        return response()->json($timeSlots);
    }

    /**
     * Get available rooms for a specific time slot
     */
    public function getAvailableRooms(Request $request)
    {
        $day = $request->get('day');
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');
        $schoolYear = $request->get('school_year');
        $gradingPeriod = $request->get('grading_period');
        $excludeScheduleId = $request->get('exclude_schedule_id');

        if (!$day || !$startTime || !$endTime || !$schoolYear || !$gradingPeriod) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        $rooms = $this->validationService->getAvailableRooms(
            $day, $startTime, $endTime, $schoolYear, $gradingPeriod, $excludeScheduleId
        );

        return response()->json($rooms);
    }

    /**
     * Validate schedule conflicts via AJAX
     */
    public function validateConflicts(Request $request)
    {
        $baseData = $request->only([
            'teacher_id', 'subject_id', 'section_id',
            'start_time', 'end_time', 'school_year', 'grading_period', 'semester'
        ]);

        $days = $request->input('days', []);
        
        // If single day is provided (legacy support), convert to array
        if (empty($days) && $request->has('day')) {
            $days = [$request->input('day')];
        }

        if (empty($days)) {
            return response()->json([
                'valid' => false,
                'errors' => ['Please select at least one day.'],
            ]);
        }

        $excludeScheduleId = $request->get('exclude_schedule_id');
        
        $allErrors = [];
        $allWarnings = [];
        $valid = true;

        // Validate each day
        foreach ($days as $day) {
            $scheduleData = array_merge($baseData, ['day' => $day]);
            $validation = $this->validationService->validateSchedule($scheduleData, $excludeScheduleId);
            
            if (!$validation['valid']) {
                $valid = false;
                foreach ($validation['errors'] as $error) {
                    $allErrors[] = "{$day}: {$error}";
                }
            }
            
            if (isset($validation['warnings']) && !empty($validation['warnings'])) {
                foreach ($validation['warnings'] as $warning) {
                    $allWarnings[] = "{$day}: {$warning}";
                }
            }
        }

        return response()->json([
            'valid' => $valid,
            'errors' => $allErrors,
            'warnings' => $allWarnings,
        ]);
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
