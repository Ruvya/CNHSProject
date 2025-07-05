<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Add debugging
        \Log::info('Admin Dashboard accessed', [
            'user' => Auth::guard('admin')->user(),
            'authenticated' => Auth::guard('admin')->check()
        ]);

        try {
            // Basic counts with error handling
            $totalStudents = Student::count() ?? 0;
            $totalTeachers = Teacher::count() ?? 0;
            $activeTeachers = Teacher::where('status', 'active')->count() ?? 0;
            $totalSubjects = Subject::count() ?? 0;
            $totalAdmins = 1; // For now, we have one admin

        // Student analytics
        $studentsByGrade = Student::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get();

        $studentsByTrack = Student::select('track', DB::raw('count(*) as count'))
            ->whereNotNull('track')
            ->groupBy('track')
            ->orderBy('track')
            ->get();

        $studentsByGender = Student::select('gender', DB::raw('count(*) as count'))
            ->groupBy('gender')
            ->get();

        // Recent activity (last 30 days)
        $recentStudents = Student::where('created_at', '>=', now()->subDays(30))->count();
        $recentTeachersCount = Teacher::where('created_at', '>=', now()->subDays(30))->count();
        $recentTeachers = Teacher::where('created_at', '>=', now()->subDays(30))->orderBy('created_at', 'desc')->get();

        // Calculate growth percentages
        $previousMonthStudents = Student::where('created_at', '>=', now()->subDays(60))
            ->where('created_at', '<', now()->subDays(30))->count();
        $previousMonthTeachers = Teacher::where('created_at', '>=', now()->subDays(60))
            ->where('created_at', '<', now()->subDays(30))->count();

        $studentGrowth = $previousMonthStudents > 0
            ? round((($recentStudents - $previousMonthStudents) / $previousMonthStudents) * 100, 1)
            : ($recentStudents > 0 ? 100 : 0);

        $teacherGrowth = $previousMonthTeachers > 0
            ? round((($recentTeachersCount - $previousMonthTeachers) / $previousMonthTeachers) * 100, 1)
            : ($recentTeachersCount > 0 ? 100 : 0);

        // Chart data for users by role
        $usersByRole = [
            'Admin' => 1, // For now, we have one admin
            'Teacher' => $totalTeachers,
            'Student' => $totalStudents
        ];

        // Monthly registration data (last 6 months)
        $monthlyRegistrations = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRegistrations[] = [
                'month' => $month->format('M Y'),
                'students' => Student::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'teachers' => Teacher::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count()
            ];
        }

        // Students by strand (for senior high)
        $studentsByStrand = Student::select('strand', DB::raw('count(*) as count'))
            ->whereNotNull('strand')
            ->where('strand', '!=', '')
            ->groupBy('strand')
            ->orderBy('count', 'desc')
            ->get();

        // Recent activities for timeline
        $recentActivities = collect();

        // Add recent students
        Student::latest()->take(5)->get()->each(function($student) use ($recentActivities) {
            $recentActivities->push((object)[
                'type' => 'student_added',
                'title' => 'New Student Registered',
                'description' => $student->full_name . ' has been added to the system',
                'icon' => 'fas fa-user-plus',
                'color' => 'success',
                'time' => $student->created_at,
            ]);
        });

        // Add recent teachers
        Teacher::latest()->take(3)->get()->each(function($teacher) use ($recentActivities) {
            $recentActivities->push((object)[
                'type' => 'teacher_added',
                'title' => 'New Teacher Added',
                'description' => $teacher->name . ' has joined the faculty',
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => 'primary',
                'time' => $teacher->created_at,
            ]);
        });

        // Sort activities by time
        $recentActivities = $recentActivities->sortByDesc('time')->take(8);

            return view('admin.dashboard', compact(
                'totalStudents',
                'totalTeachers',
                'activeTeachers',
                'totalSubjects',
                'totalAdmins',
                'studentsByGrade',
                'studentsByTrack',
                'studentsByGender',
                'studentsByStrand',
                'recentStudents',
                'recentTeachers',
                'recentTeachersCount',
                'studentGrowth',
                'teacherGrowth',
                'recentActivities',
                'usersByRole',
                'monthlyRegistrations'
            ));
        } catch (\Exception $e) {
            // Handle any errors gracefully
            \Log::error('Admin Dashboard Error: ' . $e->getMessage());

            // Return dashboard with default values
            return view('admin.dashboard', [
                'totalStudents' => 0,
                'totalTeachers' => 0,
                'activeTeachers' => 0,
                'totalSubjects' => 0,
                'totalAdmins' => 1,
                'studentsByGrade' => collect(),
                'studentsByTrack' => collect(),
                'studentsByGender' => collect(),
                'studentsByStrand' => collect(),
                'recentStudents' => 0,
                'recentTeachers' => collect(),
                'recentTeachersCount' => 0,
                'studentGrowth' => 0,
                'teacherGrowth' => 0,
                'recentActivities' => collect(),
                'usersByRole' => ['Admin' => 1, 'Teacher' => 0, 'Student' => 0],
                'monthlyRegistrations' => []
            ]);
        }
    }

    /**
     * API endpoint: Get pass/fail stats per school year, filterable by grade level, section, and school year
     */
    public function getPassFailStats(Request $request)
    {
        $gradeLevel = $request->input('grade_level');
        $section = $request->input('section');
        $schoolYear = $request->input('school_year');

        // Build the query for students
        $studentsQuery = \App\Models\Student::query();
        if ($gradeLevel) {
            $studentsQuery->where('grade_level', $gradeLevel);
        }
        if ($section) {
            $studentsQuery->where('section', $section);
        }
        if ($schoolYear) {
            $studentsQuery->whereHas('yearlyRecords', function($q) use ($schoolYear) {
                $q->where('school_year', $schoolYear);
            });
        }

        $students = $studentsQuery->with(['grades', 'yearlyRecords'])->get();

        // Group by school year
        $stats = [];
        foreach ($students as $student) {
            // Determine school years for this student
            $years = $student->yearlyRecords->pluck('school_year')->unique();
            foreach ($years as $year) {
                if (!isset($stats[$year])) {
                    $stats[$year] = ['passed' => 0, 'failed' => 0];
                }
                // Get grades for this year
                $grades = $student->grades;
                if ($schoolYear) {
                    $grades = $grades->filter(function($grade) use ($year) {
                        // Try to match by school_year if available in grade or subject
                        if (isset($grade->school_year) && $grade->school_year) {
                            return $grade->school_year == $year;
                        }
                        if ($grade->subject && isset($grade->subject->school_year)) {
                            return $grade->subject->school_year == $year;
                        }
                        return true; // fallback if not available
                    });
                }
                // Count pass/fail for this year
                foreach ($grades as $grade) {
                    if ($grade->final_grade !== null) {
                        if ($grade->final_grade >= 75) {
                            $stats[$year]['passed']++;
                        } else {
                            $stats[$year]['failed']++;
                        }
                    }
                }
            }
        }
        // Sort by school year
        ksort($stats);
        return response()->json(['data' => $stats]);
    }
}