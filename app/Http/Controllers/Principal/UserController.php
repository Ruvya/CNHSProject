<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Admin;
use App\Models\Registrar;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all users for principal overview
     */
    public function index(Request $request)
    {
        $userType = $request->get('type', 'all');
        $search = $request->get('search');

        // Get statistics
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_admins' => Admin::count(),
            'total_registrars' => Registrar::count(),
            'active_students' => Student::whereNotNull('last_login_at')->count(),
            'active_teachers' => Teacher::where('status', 'active')->count(),
        ];

        // Initialize collections
        $students = collect();
        $teachers = collect();
        $admins = collect();
        $registrars = collect();

        // Get data based on selected type
        if ($userType === 'all' || $userType === 'students') {
            $studentsQuery = Student::query();
            if ($search) {
                $studentsQuery->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            $students = $studentsQuery->orderBy('last_name')->orderBy('first_name')->take(50)->get();
        }

        if ($userType === 'all' || $userType === 'teachers') {
            $teachersQuery = Teacher::query();
            if ($search) {
                $teachersQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            $teachers = $teachersQuery->orderBy('name')->take(50)->get();
        }

        if ($userType === 'all' || $userType === 'admins') {
            $adminsQuery = Admin::query();
            if ($search) {
                $adminsQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            $admins = $adminsQuery->orderBy('name')->get();
        }

        if ($userType === 'all' || $userType === 'registrars') {
            $registrarsQuery = Registrar::query();
            if ($search) {
                $registrarsQuery->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            $registrars = $registrarsQuery->orderBy('first_name')->get();
        }

        return view('Principal.users.index', compact(
            'students', 'teachers', 'admins', 'registrars', 
            'stats', 'userType', 'search'
        ));
    }

    /**
     * Display the specified student
     */
    public function showStudent(Student $student)
    {
        // Load relationships for detailed view
        $student->load(['subjects', 'grades.subject']);

        // Get student statistics
        $totalSubjects = $student->subjects->count();
        $averageGrade = $student->grades->avg('grade') ?? 0;
        $gradeDistribution = $student->grades->groupBy('grade')->map->count();

        return view('Principal.users.show-student', compact(
            'student', 'totalSubjects', 'averageGrade', 'gradeDistribution'
        ));
    }

    /**
     * Display the specified teacher
     */
    public function showTeacher(Teacher $teacher)
    {
        // Load relationships for detailed view
        $teacher->load(['teacherAssignments.subject', 'subjects']);

        // Get teacher statistics
        $totalAssignments = $teacher->teacherAssignments->where('status', 'active')->count();
        $totalStudents = $teacher->subjects->sum(function($subject) {
            return $subject->students->count();
        });

        return view('Principal.users.show-teacher', compact(
            'teacher', 'totalAssignments', 'totalStudents'
        ));
    }

    /**
     * Get user statistics for dashboard widgets
     */
    public function getUserStats()
    {
        $stats = [
            'students' => [
                'total' => Student::count(),
                'active' => Student::whereNotNull('last_login_at')->count(),
                'by_grade' => Student::selectRaw('grade_level, COUNT(*) as count')
                    ->groupBy('grade_level')
                    ->pluck('count', 'grade_level'),
                'by_track' => Student::selectRaw('track, COUNT(*) as count')
                    ->whereNotNull('track')
                    ->groupBy('track')
                    ->pluck('count', 'track'),
            ],
            'teachers' => [
                'total' => Teacher::count(),
                'active' => Teacher::where('status', 'active')->count(),
                'with_assignments' => Teacher::whereHas('teacherAssignments', function($q) {
                    $q->where('status', 'active');
                })->count(),
            ],
            'admins' => [
                'total' => Admin::count(),
            ],
            'registrars' => [
                'total' => Registrar::count(),
            ]
        ];

        return response()->json($stats);
    }
}
