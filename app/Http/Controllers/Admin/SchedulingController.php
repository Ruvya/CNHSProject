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
        $selectedRoom = $request->get('room_id');
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
        $rooms = Room::where('is_available', true)->orderBy('name')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Get schedules with filters
        $schedulesQuery = Schedule::with(['teacher', 'subject', 'section', 'room'])
            ->where('status', 'active');

        if ($schoolYear) {
            $schedulesQuery->where('school_year', $schoolYear);
        }
        if ($gradingPeriod) {
            $schedulesQuery->where('grading_period', $gradingPeriod);
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
        if ($selectedRoom) {
            $schedulesQuery->where('room_id', $selectedRoom);
        }
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
            'total_rooms' => Room::where('is_available', true)->count(),
        ];

        return view('admin.scheduling.index', compact(
            'teachers', 'subjects', 'sections', 'rooms', 'days', 'schedules', 'stats',
            'selectedTeacher', 'selectedSubject', 'selectedSection', 'selectedRoom', 
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
        $rooms = Room::where('is_available', true)->orderBy('name')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Pre-select values if provided
        $selectedTeacher = $request->get('teacher_id');
        $selectedSubject = $request->get('subject_id');
        $selectedSection = $request->get('section_id');

        return view('admin.scheduling.create', compact(
            'teachers', 'subjects', 'sections', 'rooms', 'days',
            'selectedTeacher', 'selectedSubject', 'selectedSection'
        ));
    }

    /**
     * Store a newly created schedule
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'room_id' => 'required|exists:rooms,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'school_year' => 'required|string',
            'semester' => 'required|in:1st Semester,2nd Semester',
            'notes' => 'nullable|string|max:500',
        ]);

        // Add created_by field
        $validated['created_by'] = auth()->guard('admin')->id() ?? 1;
        $validated['status'] = 'active';

        // Validate schedule for conflicts
        $validation = $this->validationService->validateSchedule($validated);

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

        // Create the schedule
        $schedule = Schedule::create($validated);

        // Get related models for success message
        $teacher = Teacher::find($validated['teacher_id']);
        $subject = Subject::find($validated['subject_id']);
        $section = Section::find($validated['section_id']);
        $room = Room::find($validated['room_id']);

        $successMessage = "✅ Schedule Created Successfully!\n\n";
        $successMessage .= "📋 Schedule Details:\n";
        $successMessage .= "👨‍🏫 Teacher: {$teacher->name}\n";
        $successMessage .= "📚 Subject: {$subject->name} ({$subject->code})\n";
        $successMessage .= "🏫 Section: {$section->name}\n";
        $successMessage .= "🏢 Room: {$room->name} ({$room->code})\n";
        $successMessage .= "📅 Day: {$validated['day']}\n";
        $successMessage .= "🕒 Time: " . date('g:i A', strtotime($validated['start_time'])) . " - " . date('g:i A', strtotime($validated['end_time'])) . "\n";
        $successMessage .= "📊 School Year: {$validated['school_year']}\n";
        $successMessage .= "📝 Grading Period: {$validated['grading_period']}\n";
        $successMessage .= "🕒 Created on: " . now()->format('M d, Y h:i A');

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
        $rooms = Room::where('is_available', true)->orderBy('name')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('admin.scheduling.edit', compact('schedule', 'teachers', 'subjects', 'sections', 'rooms', 'days'));
    }

    /**
     * Update the specified schedule
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'room_id' => 'required|exists:rooms,id',
            'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'school_year' => 'required|string',
            'semester' => 'required|in:1st Semester,2nd Semester',
            'status' => 'required|in:active,inactive,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

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

        // Update the schedule
        $schedule->update($validated);

        // Get related models for success message
        $teacher = Teacher::find($validated['teacher_id']);
        $subject = Subject::find($validated['subject_id']);
        $section = Section::find($validated['section_id']);
        $room = Room::find($validated['room_id']);

        $successMessage = "✅ Schedule Updated Successfully!\n\n";
        $successMessage .= "📋 Updated Schedule Details:\n";
        $successMessage .= "👨‍🏫 Teacher: {$teacher->name}\n";
        $successMessage .= "📚 Subject: {$subject->name} ({$subject->code})\n";
        $successMessage .= "🏫 Section: {$section->name}\n";
        $successMessage .= "🏢 Room: {$room->name} ({$room->code})\n";
        $successMessage .= "📅 Day: {$validated['day']}\n";
        $successMessage .= "🕒 Time: " . date('g:i A', strtotime($validated['start_time'])) . " - " . date('g:i A', strtotime($validated['end_time'])) . "\n";
        $successMessage .= "📊 School Year: {$validated['school_year']}\n";
        $successMessage .= "📝 Grading Period: {$validated['grading_period']}\n";
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
        $scheduleData = $request->only([
            'teacher_id', 'subject_id', 'section_id', 'room_id',
            'day', 'start_time', 'end_time', 'school_year', 'grading_period'
        ]);

        $excludeScheduleId = $request->get('exclude_schedule_id');

        $validation = $this->validationService->validateSchedule($scheduleData, $excludeScheduleId);

        return response()->json($validation);
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
