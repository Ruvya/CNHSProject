<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with(['teacherAssignments' => function($query) {
            $query->where('status', 'active')
                  ->with('teacher')
                  ->latest();
        }])->orderBy('name')->paginate(20);

        // Calculate statistics
        $totalSubjects = Subject::count();
        $assignedSubjects = Subject::whereHas('teacherAssignments', function($query) {
            $query->where('status', 'active');
        })->count();
        $coreSubjects = Subject::where('is_core_subject', true)->count();
        $masterSubjects = Subject::where('is_master_subject', true)->count();

        return view('admin.subjects.index', compact(
            'subjects',
            'totalSubjects',
            'assignedSubjects',
            'coreSubjects',
            'masterSubjects'
        ));
    }

    public function create()
    {
        $teachers = \App\Models\Teacher::where('status', 'active')->orderBy('name')->get();
        return view('admin.subjects.create', compact('teachers'));
    }

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
            $validationRules['grade_level'] = 'required|string';
            $validationRules['track'] = 'required|string|max:100';
            $validationRules['semester'] = 'required|in:1st Semester,2nd Semester,Both Semesters';
        } else {
            $validationRules['grade_level'] = 'nullable|string';
            $validationRules['track'] = 'nullable|string|max:100';
            $validationRules['semester'] = 'nullable|in:1st Semester,2nd Semester,Both Semesters';
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

        $validated['code'] = strtoupper($validated['code']);
        
        // Process schedule days - convert array to comma-separated string
        if (isset($validated['schedule_days']) && is_array($validated['schedule_days'])) {
            $validated['schedule_days'] = implode(',', $validated['schedule_days']);
        }
        
        Subject::create($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        // Check if this is a core subject
        $isCoreSubject = $request->has('is_core_subject');

        $validationRules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'is_core_subject' => 'nullable|boolean',
            'is_master_subject' => 'nullable|boolean',
        ];

        // For core subjects, these fields are auto-filled and not required to be validated
        if (!$isCoreSubject) {
            $validationRules['grade_level'] = 'required|string';
            $validationRules['track'] = 'required|string|max:100';
            $validationRules['semester'] = 'required|in:1st Semester,2nd Semester,Both Semesters';
        } else {
            $validationRules['grade_level'] = 'nullable|string';
            $validationRules['track'] = 'nullable|string|max:100';
            $validationRules['semester'] = 'nullable|in:1st Semester,2nd Semester,Both Semesters';
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

        $validated['code'] = strtoupper($validated['code']);
        
        // Process schedule days - convert array to comma-separated string
        if (isset($validated['schedule_days']) && is_array($validated['schedule_days'])) {
            $validated['schedule_days'] = implode(',', $validated['schedule_days']);
        }
        
        $subject->update($validated);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
