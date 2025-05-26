<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $registrar = Auth::guard('registrar')->user();

        // Get the grade filter from request
        $gradeFilter = $request->get('grade_level');

        // Build query for all subjects
        $query = Subject::with(['teacher', 'registrar'])
            ->orderBy('grade_level')
            ->orderBy('strand')
            ->orderBy('name');

        // Apply grade filter if specified
        if ($gradeFilter && $gradeFilter !== 'all') {
            $query->where('grade_level', $gradeFilter);
        }

        $allSubjects = $query->get();

        // Get all subjects for total count (unfiltered)
        $totalSubjectsCount = Subject::count();

        // Get filtered count
        $filteredSubjectsCount = $allSubjects->count();

        // Get available grade levels for dropdown
        $availableGrades = Subject::select('grade_level')
            ->distinct()
            ->whereNotNull('grade_level')
            ->orderBy('grade_level')
            ->pluck('grade_level')
            ->filter()
            ->values();

        return view('registrar.subjects', compact(
            'allSubjects',
            'totalSubjectsCount',
            'filteredSubjectsCount',
            'availableGrades',
            'gradeFilter'
        ));
    }

    public function subjectsFixed()
    {
        // Get all subjects with proper relationships
        $allSubjects = Subject::with('teacher')->get();

        // Calculate statistics
        $totalSubjects = $allSubjects->count();

        // Count subjects by grade level (handle both formats)
        $grade11Count = $allSubjects->filter(function($subject) {
            $gradeLevel = $subject->grade_level;
            return $gradeLevel === 'Grade 11' || $gradeLevel === '11';
        })->count();

        $grade12Count = $allSubjects->filter(function($subject) {
            $gradeLevel = $subject->grade_level;
            return $gradeLevel === 'Grade 12' || $gradeLevel === '12';
        })->count();

        // Get available strands
        $availableStrands = $allSubjects->pluck('strand')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        // If no strands found, use default strands
        if ($availableStrands->isEmpty()) {
            $availableStrands = collect(['HUMSS', 'AFA', 'CAREGIVING', 'ICT-CSS', 'ICT-TDCP']);
        }

        // Group subjects by grade and strand for organized display
        $subjectsByGradeAndStrand = [];

        foreach (['Grade 11', 'Grade 12'] as $grade) {
            $subjectsByGradeAndStrand[$grade] = [];

            foreach ($availableStrands as $strand) {
                $subjectsByGradeAndStrand[$grade][$strand] = $allSubjects->filter(function($subject) use ($grade, $strand) {
                    $subjectGrade = $subject->grade_level;
                    $subjectStrand = $subject->strand;

                    // Handle both grade formats
                    $gradeMatch = ($subjectGrade === $grade) ||
                                 ($grade === 'Grade 11' && $subjectGrade === '11') ||
                                 ($grade === 'Grade 12' && $subjectGrade === '12');

                    // Handle strand matching (including null/empty strands)
                    $strandMatch = ($subjectStrand === $strand) ||
                                  (empty($subjectStrand) && $strand === 'HUMSS'); // Default to HUMSS for empty strands

                    return $gradeMatch && $strandMatch;
                });
            }
        }

        return view('registrar.subjects.fixed', compact(
            'allSubjects',
            'totalSubjects',
            'grade11Count',
            'grade12Count',
            'availableStrands',
            'subjectsByGradeAndStrand'
        ));
    }

    public function create()
    {
        return view('registrar.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'grade_level' => 'required|string|max:50',
            'units' => 'required|integer|min:1|max:10',
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'track' => 'nullable|string|max:100',
            'strand' => 'nullable|string|max:100',
        ]);

        // Automatically assign the current registrar as the creator
        $validated['registrar_id'] = Auth::guard('registrar')->id();

        Subject::create($validated);

        return redirect()->route('registrar.subjects.index')->with('success', 'Subject created successfully and added to your subjects list');
    }

    public function show(Subject $subject)
    {
        return view('registrar.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('registrar.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $subject->id,
            'grade_level' => 'required|string|max:50',
            'units' => 'required|integer|min:1|max:10',
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'track' => 'nullable|string|max:100',
            'strand' => 'nullable|string|max:100',
        ]);

        $subject->update($validated);

        return redirect()->route('registrar.subjects.index')->with('success', 'Subject updated successfully');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('registrar.subjects.index')->with('success', 'Subject deleted successfully');
    }

    public function assignSubjects($studentId)
    {
        $student = Student::findOrFail($studentId);
        $subjects = Subject::all();
        return view('registrar.subjects.assign', compact('student', 'subjects'));
    }

    public function storeAssignedSubjects(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);
        $student->subjects()->sync($request->subjects);

        return redirect()->back()->with('success', 'Subjects assigned successfully');
    }
}
