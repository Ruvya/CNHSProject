<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentSubjectAssignmentController extends Controller
{
    /**
     * Display student subject assignment management
     */
    public function index(Request $request)
    {
        $query = Student::with(['subjects']);

        // Apply filters
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('track')) {
            $query->where('track', $request->track);
        }

        if ($request->filled('cluster')) {
            $query->where('cluster', $request->cluster);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('assignment_status')) {
            if ($request->assignment_status === 'assigned') {
                $query->whereHas('subjects');
            } elseif ($request->assignment_status === 'unassigned') {
                $query->whereDoesntHave('subjects');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')->paginate(20);

        // Get filter options
        $gradeLevels = Student::distinct()->pluck('grade_level')->filter()->sort();
        $tracks = Student::distinct()->pluck('track')->filter()->sort();
        $clusters = Student::distinct()->pluck('cluster')->filter()->sort();
        $sections = Student::distinct()->pluck('section')->filter()->sort();

        // Get statistics
        $totalStudents = Student::count();
        $studentsWithSubjects = Student::whereHas('subjects')->count();
        $studentsWithoutSubjects = $totalStudents - $studentsWithSubjects;

        return view('registrar.student-subject-assignments.index', compact(
            'students',
            'gradeLevels',
            'tracks',
            'clusters',
            'sections',
            'totalStudents',
            'studentsWithSubjects',
            'studentsWithoutSubjects'
        ));
    }

    /**
     * Show form to assign subjects to a specific student
     */
    public function create($studentId)
    {
        $student = Student::with('subjects')->findOrFail($studentId);

        // Get available subjects based on student's grade level, track, and cluster
        $availableSubjects = Subject::where('grade_level', $student->grade_level)
            ->where('track', $student->track)
            ->where('cluster', $student->cluster)
            ->with(['teacher', 'students'])
            ->orderBy('name')
            ->get();

        // Get all subjects for manual selection (in case automatic filtering doesn't work)
        $allSubjects = Subject::with(['teacher', 'students'])
            ->orderBy('grade_level')
            ->orderBy('track')
            ->orderBy('cluster')
            ->orderBy('name')
            ->get();

        // Get currently assigned subject IDs
        $assignedSubjectIds = $student->subjects->pluck('id')->toArray();

        return view('registrar.student-subject-assignments.create', compact(
            'student',
            'availableSubjects',
            'allSubjects',
            'assignedSubjectIds'
        ));
    }

    /**
     * Store subject assignments for a student
     */
    public function store(Request $request, $studentId)
    {
        $request->validate([
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'school_year' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        $student = Student::findOrFail($studentId);
        $currentSchoolYear = $request->school_year;
        $notes = $request->notes;

        try {
            DB::transaction(function () use ($student, $request, $currentSchoolYear, $notes) {
                // Prepare pivot data
                $pivotData = [];
                foreach ($request->subjects as $subjectId) {
                    $pivotData[$subjectId] = [
                        'school_year' => $currentSchoolYear,
                        'remarks' => $notes,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                // Sync subjects with pivot data
                $student->subjects()->sync($pivotData);
            });

            $assignedCount = count($request->subjects);
            $successMessage = "✅ Successfully assigned {$assignedCount} subject(s) to {$student->first_name} {$student->last_name} for {$currentSchoolYear}.";

            return redirect()->route('registrar.student-subject-assignments.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to assign subjects. Please try again.');
        }
    }

    /**
     * Show bulk assignment form
     */
    public function bulkCreate(Request $request)
    {
        $query = Student::query();

        // Apply filters for bulk selection
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('track')) {
            $query->where('track', $request->track);
        }

        if ($request->filled('cluster')) {
            $query->where('cluster', $request->cluster);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')->get();

        // Get subjects based on filters
        $subjects = Subject::query();

        if ($request->filled('grade_level')) {
            $subjects->where('grade_level', $request->grade_level);
        }

        if ($request->filled('track')) {
            $subjects->where('track', $request->track);
        }

        if ($request->filled('cluster')) {
            $subjects->where('cluster', $request->cluster);
        }

        $subjects = $subjects->with('teacher')->orderBy('name')->get();

        // Get filter options
        $gradeLevels = Student::distinct()->pluck('grade_level')->filter()->sort();
        $tracks = Student::distinct()->pluck('track')->filter()->sort();
        $clusters = Student::distinct()->pluck('cluster')->filter()->sort();
        $sections = Student::distinct()->pluck('section')->filter()->sort();

        return view('registrar.student-subject-assignments.bulk-create', compact(
            'students',
            'subjects',
            'gradeLevels',
            'tracks',
            'clusters',
            'sections'
        ));
    }

    /**
     * Store bulk subject assignments
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
            'school_year' => 'required|string',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $currentSchoolYear = $request->school_year;
                $notes = $request->notes;

                foreach ($request->students as $studentId) {
                    $student = Student::find($studentId);
                    if ($student) {
                        // Prepare pivot data
                        $pivotData = [];
                        foreach ($request->subjects as $subjectId) {
                            $pivotData[$subjectId] = [
                                'school_year' => $currentSchoolYear,
                                'remarks' => $notes,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }

                        // Sync subjects with pivot data
                        $student->subjects()->sync($pivotData);
                    }
                }
            });

            $studentCount = count($request->students);
            $subjectCount = count($request->subjects);
            $successMessage = "✅ Successfully assigned {$subjectCount} subject(s) to {$studentCount} student(s) for {$request->school_year}.";

            return redirect()->route('registrar.student-subject-assignments.index')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to assign subjects. Please try again.');
        }
    }

    /**
     * Remove subject assignment from student
     */
    public function removeSubject(Request $request, $studentId, $subjectId)
    {
        $student = Student::findOrFail($studentId);
        $subject = Subject::findOrFail($subjectId);

        try {
            $student->subjects()->detach($subjectId);

            return response()->json([
                'success' => true,
                'message' => "Subject '{$subject->name}' removed from {$student->first_name} {$student->last_name}."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove subject assignment.'
            ], 500);
        }
    }

    /**
     * Get subjects by filters (AJAX)
     */
    public function getSubjectsByFilters(Request $request)
    {
        $query = Subject::with('teacher');

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('track')) {
            $query->where('track', $request->track);
        }

        if ($request->filled('cluster')) {
            $query->where('cluster', $request->cluster);
        }

        $subjects = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'subjects' => $subjects
        ]);
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear()
    {
        return date('Y') . '-' . (date('Y') + 1);
    }
}
