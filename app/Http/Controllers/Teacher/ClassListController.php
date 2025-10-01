<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;

class ClassListController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        // Get teacher's subjects (both assignment methods)
        $assignedSubjects = $teacher->assignedSubjects()->get();
        $directSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $subjects = $assignedSubjects->merge($directSubjects)->unique('id');

        // Get unique grade levels and sections from teacher's subjects
        $gradeLevels = $subjects->pluck('grade_level')->unique()->sort()->values();
        $sections = ['A', 'B', 'C', 'D', 'E']; // Add more sections as needed

        // Get all subject IDs for this teacher (both assignment methods)
        $allSubjectIds = $subjects->pluck('id')->toArray();

        // Initialize students query - ONLY show students enrolled in teacher's subjects
        $studentsQuery = Student::whereHas('subjects', function($query) use ($allSubjectIds) {
            $query->whereIn('subjects.id', $allSubjectIds);
        });

        // Apply additional filters if provided
        if ($request->filled('subject_id')) {
            $studentsQuery->whereHas('subjects', function($query) use ($request) {
                $query->where('subjects.id', $request->subject_id);
            });
        }

        if ($request->filled('grade_level')) {
            $studentsQuery->where('grade_level', $request->grade_level);
        }

        if ($request->filled('section')) {
            $studentsQuery->where('section', $request->section);
        }

        // Get all subject IDs for this teacher (both assignment methods)
        $allSubjectIds = $subjects->pluck('id')->toArray();

        // Get students with their subjects and grades for teacher's subjects
        $students = $studentsQuery->with([
            'subjects' => function($query) use ($allSubjectIds) {
                $query->whereIn('subjects.id', $allSubjectIds);
            },
            'grades' => function($query) use ($allSubjectIds) {
                $query->whereHas('subject', function($subQuery) use ($allSubjectIds) {
                    $subQuery->whereIn('subjects.id', $allSubjectIds);
                });
            }
        ])->orderBy('last_name')->orderBy('first_name')->get();

        return view('teacher.classlist', compact('teacher', 'subjects', 'gradeLevels', 'sections', 'students', 'request'));
    }

    public function getSubjects($gradeLevel)
    {
        $teacher = Auth::guard('teacher')->user();

        // Get subjects from both assignment methods
        $assignedSubjects = $teacher->assignedSubjects()
            ->where('grade_level', $gradeLevel)
            ->get();

        $directSubjects = Subject::where('teacher_id', $teacher->id)
            ->where('grade_level', $gradeLevel)
            ->get();

        $subjects = $assignedSubjects->merge($directSubjects)->unique('id');

        return response()->json($subjects);
    }

    public function getStudents($gradeLevel, $subjectId)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the subject belongs to this teacher (check both assignment methods)
        $subject = Subject::find($subjectId);

        if (!$subject) {
            return response()->json([]);
        }

        $hasDirectAccess = $subject->teacher_id === $teacher->id;
        $hasAssignmentAccess = $teacher->assignedSubjects()->where('subjects.id', $subjectId)->exists();

        if (!$hasDirectAccess && !$hasAssignmentAccess) {
            return response()->json([]);
        }

        $students = Student::where('grade_level', $gradeLevel)
            ->whereHas('subjects', function($query) use ($subjectId) {
                $query->where('subjects.id', $subjectId);
            })
            ->with(['grades' => function($query) use ($subjectId) {
                $query->where('subject_id', $subjectId);
            }])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return response()->json($students);
    }

    public function getStudentsForFilters(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        // Get teacher's subjects from both assignment methods
        $assignedSubjects = $teacher->assignedSubjects()->get();
        $directSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $allSubjectIds = $assignedSubjects->merge($directSubjects)->unique('id')->pluck('id')->toArray();

        // Start with students enrolled in teacher's subjects
        $studentsQuery = Student::whereHas('subjects', function($query) use ($allSubjectIds) {
            $query->whereIn('subjects.id', $allSubjectIds);
        });

        // Apply filters
        if ($request->filled('gradeLevel')) {
            $studentsQuery->where('grade_level', $request->gradeLevel);
        }

        if ($request->filled('subjectId')) {
            // Verify the subject belongs to this teacher
            $subject = Subject::where('id', $request->subjectId)
                ->where('teacher_id', $teacher->id)
                ->first();

            if ($subject) {
                $studentsQuery->whereHas('subjects', function($query) use ($request, $teacher) {
                    $query->where('subjects.id', $request->subjectId)
                          ->where('teacher_id', $teacher->id);
                });
            } else {
                // If subject doesn't belong to teacher, return empty
                return response()->json([]);
            }
        }

        if ($request->filled('section')) {
            $studentsQuery->where('section', $request->section);
        }

        $students = $studentsQuery->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'student_id' => $student->student_id,
                    'section' => $student->section,
                    'gender' => $student->gender
                ];
            });

        return response()->json($students);
    }

    public function showStudent($studentId)
    {
        $teacher = Auth::guard('teacher')->user();

        // Get teacher's subjects from both assignment methods
        $assignedSubjects = $teacher->assignedSubjects()->get();
        $directSubjects = Subject::where('teacher_id', $teacher->id)->get();
        $allSubjectIds = $assignedSubjects->merge($directSubjects)->unique('id')->pluck('id')->toArray();

        $student = Student::with(['subjects' => function($query) use ($allSubjectIds) {
            $query->whereIn('subjects.id', $allSubjectIds);
        }, 'grades' => function($query) use ($allSubjectIds) {
            $query->whereHas('subject', function($subQuery) use ($allSubjectIds) {
                $subQuery->whereIn('subjects.id', $allSubjectIds);
            });
        }])->findOrFail($studentId);

        // Verify teacher has access to this student
        $hasAccess = $student->subjects->whereIn('id', $allSubjectIds)->count() > 0;

        // Also allow access if teacher is the adviser of the student's section (by section name)
        if (!$hasAccess && $student->section) {
            $advisesSection = Section::where('name', $student->section)
                ->where('adviser_id', $teacher->id)
                ->exists();
            $hasAccess = $hasAccess || $advisesSection;
        }

        if (!$hasAccess) {
            abort(403, 'You do not have access to this student.');
        }

        return view('teacher.student-profile', compact('teacher', 'student'));
    }
}