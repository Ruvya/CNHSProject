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

        return view('registrar.subjects.index', compact(
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
        // Get all active teachers for assignment
        $teachers = \App\Models\Teacher::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('registrar.subjects.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'grade_level' => 'required|in:Grade 11,Grade 12',
            'track' => 'required|string|max:100',
            'strand' => 'required|string|max:100',
            'cluster' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'grading' => 'required|in:First Grading,Second Grading,Third Grading,Fourth Grading,All Gradings',
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'is_core_subject' => 'nullable|boolean',
            'is_master_subject' => 'nullable|boolean',
        ]);

        // Convert checkbox values
        $validated['is_core_subject'] = $request->has('is_core_subject');
        $validated['is_master_subject'] = $request->has('is_master_subject');

        // Automatically assign the current registrar as the creator
        $validated['registrar_id'] = Auth::guard('registrar')->id();

        // Ensure code is uppercase
        $validated['code'] = strtoupper($validated['code']);

        // Add default units value since we removed it from the form
        $validated['units'] = 3; // Default to 3 units

        Subject::create($validated);

        return redirect()->route('registrar.subjects.index')->with('success', 'Subject created successfully! You can now assign it to students or create subject offerings.');
    }

    public function show(Subject $subject)
    {
        return view('registrar.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        // Check if the current registrar owns this subject
        if ($subject->registrar_id !== Auth::guard('registrar')->id()) {
            return redirect()->route('registrar.subjects.index')
                ->with('error', 'You can only edit subjects that you created.');
        }

        // Get all active teachers for assignment
        $teachers = \App\Models\Teacher::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('registrar.subjects.edit', compact('subject', 'teachers'));
    }

    public function update(Request $request, Subject $subject)
    {
        // Check if the current registrar owns this subject
        if ($subject->registrar_id !== Auth::guard('registrar')->id()) {
            return redirect()->route('registrar.subjects.index')
                ->with('error', 'You can only update subjects that you created.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => "required|string|max:50|unique:subjects,code,{$subject->id}",
            'grade_level' => 'required|in:Grade 11,Grade 12',
            'track' => 'required|string|max:100',
            'strand' => 'required|string|max:100',
            'cluster' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'grading' => 'required|in:First Grading,Second Grading,Third Grading,Fourth Grading,All Gradings',
            'teacher_id' => 'nullable|exists:teachers,id',
            'description' => 'nullable|string|max:1000',
            'is_core_subject' => 'nullable|boolean',
            'is_master_subject' => 'nullable|boolean',
        ]);

        // Convert checkbox values
        $validated['is_core_subject'] = $request->has('is_core_subject');
        $validated['is_master_subject'] = $request->has('is_master_subject');

        // Ensure code is uppercase
        $validated['code'] = strtoupper($validated['code']);

        // Add default units value since we removed it from the form
        $validated['units'] = $subject->units ?? 3; // Keep existing units or default to 3

        $subject->update($validated);

        return redirect()->route('registrar.subjects.index')->with('success', 'Subject updated successfully! Changes have been applied.');
    }

    public function destroy(Subject $subject)
    {
        // Check if the current registrar owns this subject
        if ($subject->registrar_id !== Auth::guard('registrar')->id()) {
            return redirect()->route('registrar.subjects.index')
                ->with('error', 'You can only delete subjects that you created.');
        }

        // Check if subject is assigned to any students (optional safety check)
        // Uncomment if you have student-subject relationships
        // if ($subject->students()->count() > 0) {
        //     return redirect()->route('registrar.subjects.index')
        //         ->with('error', 'Cannot delete subject that is assigned to students.');
        // }

        $subjectName = $subject->name;
        $subject->delete();

        return redirect()->route('registrar.subjects.index')
            ->with('success', "Subject '{$subjectName}' has been deleted successfully.");
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
