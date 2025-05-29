<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Admin has VIEW-ONLY access to subjects.
     * Subject creation, editing, and deletion is exclusively handled by Registrar.
     */
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

        // Get teachers for dropdown (for display purposes only)
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

    public function show(Subject $subject)
    {
        $subject->load(['teacher', 'students']);
        $enrolledStudents = $subject->students()->with('grades')->get();

        return view('admin.subjects.show', compact('subject', 'enrolledStudents'));
    }

    /**
     * Prevent Admin from creating subjects - this is exclusively a Registrar function
     */
    public function create()
    {
        return redirect()->route('admin.subjects.index')
            ->with('error', 'Subject creation is exclusively managed by the Registrar. Admin has view-only access to subjects.');
    }

    /**
     * Prevent Admin from storing subjects - this is exclusively a Registrar function
     */
    public function store(Request $request)
    {
        return redirect()->route('admin.subjects.index')
            ->with('error', 'Subject creation is exclusively managed by the Registrar. Admin has view-only access to subjects.');
    }

    /**
     * Prevent Admin from editing subjects - this is exclusively a Registrar function
     */
    public function edit(Subject $subject)
    {
        return redirect()->route('admin.subjects.index')
            ->with('error', 'Subject editing is exclusively managed by the Registrar. Admin has view-only access to subjects.');
    }

    /**
     * Prevent Admin from updating subjects - this is exclusively a Registrar function
     */
    public function update(Request $request, Subject $subject)
    {
        return redirect()->route('admin.subjects.index')
            ->with('error', 'Subject editing is exclusively managed by the Registrar. Admin has view-only access to subjects.');
    }

    /**
     * Prevent Admin from deleting subjects - this is exclusively a Registrar function
     */
    public function destroy(Subject $subject)
    {
        return redirect()->route('admin.subjects.index')
            ->with('error', 'Subject deletion is exclusively managed by the Registrar. Admin has view-only access to subjects.');
    }
}
