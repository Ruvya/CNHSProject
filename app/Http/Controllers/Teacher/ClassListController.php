<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class ClassListController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        // Get teacher's subjects
        $subjects = Subject::where('teacher_id', $teacher->id)->get();

        // Get unique grade levels and sections from teacher's subjects
        $gradeLevels = $subjects->pluck('grade_level')->unique()->sort()->values();
        $sections = ['A', 'B', 'C', 'D', 'E']; // Add more sections as needed

        // Initialize students query - ONLY show students enrolled in teacher's subjects
        $studentsQuery = Student::whereHas('subjects', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        });

        // Apply additional filters if provided
        if ($request->filled('subject_id')) {
            $studentsQuery->whereHas('subjects', function($query) use ($request, $teacher) {
                $query->where('subjects.id', $request->subject_id)
                      ->where('teacher_id', $teacher->id); // Ensure it's still the teacher's subject
            });
        }

        if ($request->filled('grade_level')) {
            $studentsQuery->where('grade_level', $request->grade_level);
        }

        if ($request->filled('section')) {
            $studentsQuery->where('section', $request->section);
        }

        // Get students with their subjects and grades for teacher's subjects
        $students = $studentsQuery->with([
            'subjects' => function($query) use ($teacher) {
                $query->where('teacher_id', $teacher->id);
            },
            'grades' => function($query) use ($teacher) {
                $query->whereHas('subject', function($subQuery) use ($teacher) {
                    $subQuery->where('teacher_id', $teacher->id);
                });
            }
        ])->orderBy('last_name')->orderBy('first_name')->get();

        return view('teacher.classlist', compact('teacher', 'subjects', 'gradeLevels', 'sections', 'students', 'request'));
    }

    public function getSubjects($gradeLevel)
    {
        $teacher = Auth::guard('teacher')->user();
        $subjects = Subject::where('teacher_id', $teacher->id)
            ->where('grade_level', $gradeLevel)
            ->get();

        return response()->json($subjects);
    }

    public function getStudents($gradeLevel, $subjectId)
    {
        $teacher = Auth::guard('teacher')->user();

        // Verify the subject belongs to this teacher
        $subject = Subject::where('id', $subjectId)
            ->where('teacher_id', $teacher->id)
            ->first();

        if (!$subject) {
            return response()->json([]);
        }

        $students = Student::where('grade_level', $gradeLevel)
            ->whereHas('subjects', function($query) use ($subjectId, $teacher) {
                $query->where('subjects.id', $subjectId)
                      ->where('teacher_id', $teacher->id); // Ensure it's the teacher's subject
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

        // Start with students enrolled in teacher's subjects
        $studentsQuery = Student::whereHas('subjects', function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
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
        $student = Student::with(['subjects' => function($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        }, 'grades' => function($query) use ($teacher) {
            $query->whereHas('subject', function($subQuery) use ($teacher) {
                $subQuery->where('teacher_id', $teacher->id);
            });
        }])->findOrFail($studentId);

        // Verify teacher has access to this student
        $hasAccess = $student->subjects->where('teacher_id', $teacher->id)->count() > 0;

        if (!$hasAccess) {
            abort(403, 'You do not have access to this student.');
        }

        return view('teacher.student-profile', compact('teacher', 'student'));
    }
}