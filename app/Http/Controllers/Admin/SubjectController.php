<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        // Get all available grade levels for the filter dropdown
        $availableGradeLevels = Subject::select('grade_level')
            ->distinct()
            ->whereNotNull('grade_level')
            ->orderBy('grade_level')
            ->pluck('grade_level');

        // Build the subjects query
        $subjectsQuery = Subject::with('teacher');

        // Apply grade level filter if provided
        $selectedGradeLevel = $request->get('grade_level');
        if ($selectedGradeLevel && $selectedGradeLevel !== 'all') {
            $subjectsQuery->where('grade_level', $selectedGradeLevel);
        }

        $subjects = $subjectsQuery->orderBy('name')->get();

        // Get statistics
        $totalSubjects = Subject::count();
        $subjectsByGrade = Subject::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get();

        // Get teachers for dropdown
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();

        return view('admin.subjects.index', compact(
            'subjects',
            'availableGradeLevels',
            'selectedGradeLevel',
            'totalSubjects',
            'subjectsByGrade',
            'teachers'
        ));
    }

    public function create()
    {
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        return view('admin.subjects.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'grade_level' => 'required|string|max:50',
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'track' => 'nullable|string|max:100',
            'strand' => 'nullable|string|max:100',
            'grading' => 'required|string|max:50',
        ]);

        // Mark as master subject
        $validated['is_master_subject'] = true;

        Subject::create($validated);

        return redirect()->route('admin.subjects')->with('success', 'Master subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        $teachers = Teacher::where('status', 'active')->orderBy('name')->get();
        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('subjects')->ignore($subject->id)],
            'grade_level' => 'required|string|max:50',
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'track' => 'nullable|string|max:100',
            'strand' => 'nullable|string|max:100',
            'grading' => 'required|string|max:50',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects')->with('success', 'Master subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        // Check if subject has enrolled students
        $studentCount = $subject->students()->count();

        if ($studentCount > 0) {
            return redirect()->route('admin.subjects')
                ->with('error', "Cannot delete subject '{$subject->name}' because it has {$studentCount} enrolled students.");
        }

        $subject->delete();
        return redirect()->route('admin.subjects')->with('success', 'Subject deleted successfully.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['teacher', 'students']);
        $enrolledStudents = $subject->students()->with('grades')->get();

        return view('admin.subjects.show', compact('subject', 'enrolledStudents'));
    }
}
