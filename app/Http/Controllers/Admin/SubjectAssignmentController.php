<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherAssignment;
use App\Models\Teacher;
use App\Models\Subject;

class SubjectAssignmentController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $selectedTeacher = $request->get('teacher_id');
        $selectedSubject = $request->get('subject_id');
        $schoolYear = $request->get('school_year');
        $gradingPeriod = $request->get('grading_period');

        // Get all teachers and subjects for dropdowns
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        // Get current assignments with filters
        $assignmentsQuery = TeacherAssignment::with(['teacher', 'subject'])
            ->where('status', 'active');
        if ($schoolYear) {
            $assignmentsQuery->where('school_year', $schoolYear);
        }
        if ($gradingPeriod) {
            $assignmentsQuery->where('grading_period', $gradingPeriod);
        }
        if ($selectedTeacher) {
            $assignmentsQuery->where('teacher_id', $selectedTeacher);
        }
        if ($selectedSubject) {
            $assignmentsQuery->where('subject_id', $selectedSubject);
        }
        $assignments = $assignmentsQuery->orderBy('created_at', 'desc')->paginate(15);

        // Get assignment statistics
        $stats = [
            'total_assignments' => TeacherAssignment::where('status', 'active')->count(),
            'total_teachers' => Teacher::where('status', 'active')->count(),
            'assigned_teachers' => TeacherAssignment::where('status', 'active')->distinct('teacher_id')->count(),
            'total_subjects' => Subject::count(),
            'assigned_subjects' => TeacherAssignment::where('status', 'active')->distinct('subject_id')->count(),
        ];

        return view('admin.subject-assignments.index', compact(
            'teachers', 'subjects', 'assignments', 'stats',
            'selectedTeacher', 'selectedSubject', 'schoolYear', 'gradingPeriod'
        ));
    }

    public function create(Request $request)
    {
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $selectedSubjectId = $request->get('subject_id');

        return view('admin.subject-assignments.create', compact('teachers', 'subjects', 'selectedSubjectId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'school_year' => 'required|string',
            'grading_period' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get teacher and subject details for confirmation message
        $teacher = Teacher::findOrFail($validated['teacher_id']);
        $subject = Subject::findOrFail($validated['subject_id']);

        // Check for existing assignment to prevent duplicates
        $existingAssignment = TeacherAssignment::where('teacher_id', $validated['teacher_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('school_year', $validated['school_year'])
            ->where('grading_period', $validated['grading_period'])
            ->where('status', 'active')
            ->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->withInput()
                ->with('error', "❌ Assignment failed: {$teacher->name} is already assigned to {$subject->name} for {$validated['school_year']} - {$validated['grading_period']}.");
        }

        // Add additional fields
        $validated['assignment_date'] = now();
        $validated['assigned_by'] = auth()->guard('admin')->id() ?? 1; // Fallback to admin ID 1

        // Create the assignment
        $assignment = TeacherAssignment::create($validated);

        // Create detailed success message
        $successMessage = "✅ Subject Assignment Successful!\n\n";
        $successMessage .= "📋 Assignment Details:\n";
        $successMessage .= "👨‍🏫 Teacher: {$teacher->name}\n";
        $successMessage .= "📚 Subject: {$subject->name} ({$subject->code})\n";
        $successMessage .= "📅 School Year: {$validated['school_year']}\n";
        $successMessage .= "📊 Grading Period: {$validated['grading_period']}\n";
        $successMessage .= "📝 Status: {$validated['status']}\n";
        $successMessage .= "🕒 Assigned on: " . now()->format('M d, Y h:i A');

        return redirect()->route('admin.subject-assignments.index')
            ->with('success', $successMessage);
    }

    public function show(TeacherAssignment $subjectAssignment)
    {
        $subjectAssignment->load(['teacher', 'subject']);
        return view('admin.subject-assignments.show', compact('subjectAssignment'));
    }

    public function edit(TeacherAssignment $subjectAssignment)
    {
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        return view('admin.subject-assignments.edit', compact('subjectAssignment', 'teachers', 'subjects'));
    }

    public function update(Request $request, TeacherAssignment $subjectAssignment)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'school_year' => 'required|string',
            'grading_period' => 'required|string',
            'status' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get teacher and subject details for confirmation message
        $teacher = Teacher::findOrFail($validated['teacher_id']);
        $subject = Subject::findOrFail($validated['subject_id']);

        // Check for existing assignment (excluding current one) to prevent duplicates
        $existingAssignment = TeacherAssignment::where('teacher_id', $validated['teacher_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('school_year', $validated['school_year'])
            ->where('grading_period', $validated['grading_period'])
            ->where('status', 'active')
            ->where('id', '!=', $subjectAssignment->id)
            ->first();

        if ($existingAssignment) {
            return redirect()->back()
                ->withInput()
                ->with('error', "❌ Update failed: {$teacher->name} is already assigned to {$subject->name} for {$validated['school_year']} - {$validated['grading_period']}.");
        }

        // Update the assignment
        $subjectAssignment->update($validated);

        // Create detailed success message
        $successMessage = "✅ Subject Assignment Updated Successfully!\n\n";
        $successMessage .= "📋 Updated Assignment Details:\n";
        $successMessage .= "👨‍🏫 Teacher: {$teacher->name}\n";
        $successMessage .= "📚 Subject: {$subject->name} ({$subject->code})\n";
        $successMessage .= "📅 School Year: {$validated['school_year']}\n";
        $successMessage .= "📊 Grading Period: {$validated['grading_period']}\n";
        $successMessage .= "📝 Status: {$validated['status']}\n";
        $successMessage .= "🕒 Updated on: " . now()->format('M d, Y h:i A');

        return redirect()->route('admin.subject-assignments.index')
            ->with('success', $successMessage);
    }

    public function destroy(TeacherAssignment $subjectAssignment)
    {
        // Get details before deletion for confirmation message
        $teacher = $subjectAssignment->teacher;
        $subject = $subjectAssignment->subject;
        $schoolYear = $subjectAssignment->school_year;
        $gradingPeriod = $subjectAssignment->grading_period;

        // Delete the assignment
        $subjectAssignment->delete();

        // Create detailed success message
        $successMessage = "✅ Subject Assignment Removed Successfully!\n\n";
        $successMessage .= "📋 Removed Assignment Details:\n";
        $successMessage .= "👨‍🏫 Teacher: {$teacher->name}\n";
        $successMessage .= "📚 Subject: {$subject->name} ({$subject->code})\n";
        $successMessage .= "📅 School Year: {$schoolYear}\n";
        $successMessage .= "📊 Grading Period: {$gradingPeriod}\n";
        $successMessage .= "🕒 Removed on: " . now()->format('M d, Y h:i A');

        return redirect()->route('admin.subject-assignments.index')
            ->with('success', $successMessage);
    }
} 