<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Add debugging
        Log::info('Admin Dashboard accessed', [
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

        // If no monthly data, add current month data to show something
        $hasMonthlyData = collect($monthlyRegistrations)->sum(function($item) {
            return $item['students'] + $item['teachers'];
        });

        if ($hasMonthlyData == 0) {
            // Add current month with actual totals to show something
            $monthlyRegistrations[5] = [
                'month' => now()->format('M Y'),
                'students' => $totalStudents,
                'teachers' => $totalTeachers
            ];
        }

        // Ensure students by track data exists
        if ($studentsByTrack->isEmpty()) {
            $studentsByTrack = collect([
                ['track' => 'Not Set', 'count' => $totalStudents]
            ]);
        }

        // Add debugging for dashboard data
        Log::info('Admin Dashboard Data', [
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'usersByRole' => $usersByRole,
            'monthlyRegistrations' => $monthlyRegistrations,
            'studentsByGrade' => $studentsByGrade->toArray()
        ]);

        // Students by strand (for senior high)
        $studentsByStrand = Student::select('strand', DB::raw('count(*) as count'))
            ->whereNotNull('strand')
            ->where('strand', '!=', '')
            ->groupBy('strand')
            ->orderBy('count', 'desc')
            ->get();

        // Grading Scale Analytics
        $gradingScaleAnalytics = $this->calculateGradingScaleAnalytics();

        // Grading Scale Analytics by Section
        $gradingScaleBySection = $this->calculateGradingScaleBySection();

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
                'monthlyRegistrations',
                'gradingScaleAnalytics',
                'gradingScaleBySection'
            ));
        } catch (\Exception $e) {
            // Handle any errors gracefully
            Log::error('Admin Dashboard Error: ' . $e->getMessage());

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
        Log::info('Pass/Fail Stats API called', [
            'request_params' => $request->all()
        ]);

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

        Log::info('Pass/Fail Stats Result', [
            'stats' => $stats,
            'students_count' => $students->count()
        ]);

        return response()->json(['data' => $stats]);
    }

    /**
     * Calculate grading scale analytics based on teacher-uploaded grades
     */
    private function calculateGradingScaleAnalytics()
    {
        try {
            // Get total grades recorded (final grades from teacher uploads)
            $totalGrades = Grade::whereNotNull('final_grade')
                ->count();

            if ($totalGrades === 0) {
                return [
                    'total_grades' => 0,
                    'outstanding_count' => 0,
                    'very_satisfactory_count' => 0,
                    'satisfactory_count' => 0,
                    'fairly_satisfactory_count' => 0,
                    'failed_count' => 0,
                    'outstanding_percentage' => 0,
                    'passing_percentage' => 0,
                    'most_common_grade' => 'No data'
                ];
            }

            // Count grades by the specified scale ranges
            // 90–100: Outstanding (Passed)
            $outstanding = Grade::whereNotNull('final_grade')
                ->where('final_grade', '>=', 90)
                ->where('final_grade', '<=', 100)
                ->count();

            // 85–89: Very Satisfactory (Passed)
            $verySatisfactory = Grade::whereNotNull('final_grade')
                ->where('final_grade', '>=', 85)
                ->where('final_grade', '<=', 89)
                ->count();

            // 80–84: Satisfactory (Passed)
            $satisfactory = Grade::whereNotNull('final_grade')
                ->where('final_grade', '>=', 80)
                ->where('final_grade', '<=', 84)
                ->count();

            // 75–79: Fairly Satisfactory (Passed)
            $fairlySatisfactory = Grade::whereNotNull('final_grade')
                ->where('final_grade', '>=', 75)
                ->where('final_grade', '<=', 79)
                ->count();

            // Below 75: Did Not Meet Expectations (Failed)
            $failed = Grade::whereNotNull('final_grade')
                ->where('final_grade', '<', 75)
                ->count();

            // Calculate percentages
            $passingGrades = $outstanding + $verySatisfactory + $satisfactory + $fairlySatisfactory;
            $passingPercentage = $totalGrades > 0 ? round(($passingGrades / $totalGrades) * 100, 1) : 0;
            $outstandingPercentage = $totalGrades > 0 ? round(($outstanding / $totalGrades) * 100, 1) : 0;

            // Determine most common grade range
            $gradeCounts = [
                'Outstanding (90-100)' => $outstanding,
                'Very Satisfactory (85-89)' => $verySatisfactory,
                'Satisfactory (80-84)' => $satisfactory,
                'Fairly Satisfactory (75-79)' => $fairlySatisfactory,
                'Failed (Below 75)' => $failed
            ];

            $mostCommonGrade = array_keys($gradeCounts, max($gradeCounts))[0] ?? 'No data';

            return [
                'total_grades' => $totalGrades,
                'outstanding_count' => $outstanding,
                'very_satisfactory_count' => $verySatisfactory,
                'satisfactory_count' => $satisfactory,
                'fairly_satisfactory_count' => $fairlySatisfactory,
                'failed_count' => $failed,
                'outstanding_percentage' => $outstandingPercentage,
                'passing_percentage' => $passingPercentage,
                'most_common_grade' => $mostCommonGrade
            ];

        } catch (\Exception $e) {
            Log::error('Error calculating grading scale analytics: ' . $e->getMessage());

            return [
                'total_grades' => 0,
                'outstanding_count' => 0,
                'very_satisfactory_count' => 0,
                'satisfactory_count' => 0,
                'fairly_satisfactory_count' => 0,
                'failed_count' => 0,
                'outstanding_percentage' => 0,
                'passing_percentage' => 0,
                'most_common_grade' => 'Error loading data'
            ];
        }
    }

    /**
     * Calculate grading scale analytics by section based on teacher-uploaded grades
     */
    private function calculateGradingScaleBySection()
    {
        try {
            // Get all sections that have students with grades
            $sections = Student::select('section')
                ->whereNotNull('section')
                ->where('section', '!=', '')
                ->whereHas('grades', function($query) {
                    $query->whereNotNull('final_grade');
                })
                ->distinct()
                ->pluck('section')
                ->sort()
                ->values();

            if ($sections->isEmpty()) {
                return [
                    'sections' => [],
                    'outstanding' => [],
                    'very_satisfactory' => [],
                    'satisfactory' => [],
                    'fairly_satisfactory' => [],
                    'failed' => []
                ];
            }

            $sectionData = [];

            foreach ($sections as $section) {
                // Get all final grades for students in this section (from teacher uploads)
                $grades = Grade::whereHas('student', function($query) use ($section) {
                    $query->where('section', $section);
                })
                ->whereNotNull('final_grade')
                ->pluck('final_grade');

                if ($grades->isEmpty()) {
                    $sectionData[$section] = [
                        'outstanding' => 0,
                        'very_satisfactory' => 0,
                        'satisfactory' => 0,
                        'fairly_satisfactory' => 0,
                        'failed' => 0,
                        'total' => 0
                    ];
                    continue;
                }

                // Count grades by the specified scale ranges
                // 90–100: Outstanding (Passed)
                $outstanding = $grades->filter(function($grade) { return $grade >= 90 && $grade <= 100; })->count();
                // 85–89: Very Satisfactory (Passed)
                $verySatisfactory = $grades->filter(function($grade) { return $grade >= 85 && $grade <= 89; })->count();
                // 80–84: Satisfactory (Passed)
                $satisfactory = $grades->filter(function($grade) { return $grade >= 80 && $grade <= 84; })->count();
                // 75–79: Fairly Satisfactory (Passed)
                $fairlySatisfactory = $grades->filter(function($grade) { return $grade >= 75 && $grade <= 79; })->count();
                // Below 75: Did Not Meet Expectations (Failed)
                $failed = $grades->filter(function($grade) { return $grade < 75; })->count();

                $sectionData[$section] = [
                    'outstanding' => $outstanding,
                    'very_satisfactory' => $verySatisfactory,
                    'satisfactory' => $satisfactory,
                    'fairly_satisfactory' => $fairlySatisfactory,
                    'failed' => $failed,
                    'total' => $grades->count()
                ];
            }

            // Prepare data for Chart.js
            return [
                'sections' => $sections->toArray(),
                'outstanding' => $sections->map(function($section) use ($sectionData) {
                    return $sectionData[$section]['outstanding'] ?? 0;
                })->toArray(),
                'very_satisfactory' => $sections->map(function($section) use ($sectionData) {
                    return $sectionData[$section]['very_satisfactory'] ?? 0;
                })->toArray(),
                'satisfactory' => $sections->map(function($section) use ($sectionData) {
                    return $sectionData[$section]['satisfactory'] ?? 0;
                })->toArray(),
                'fairly_satisfactory' => $sections->map(function($section) use ($sectionData) {
                    return $sectionData[$section]['fairly_satisfactory'] ?? 0;
                })->toArray(),
                'failed' => $sections->map(function($section) use ($sectionData) {
                    return $sectionData[$section]['failed'] ?? 0;
                })->toArray(),
                'section_data' => $sectionData
            ];

        } catch (\Exception $e) {
            Log::error('Error calculating grading scale by section: ' . $e->getMessage());

            return [
                'sections' => [],
                'outstanding' => [],
                'very_satisfactory' => [],
                'satisfactory' => [],
                'fairly_satisfactory' => [],
                'failed' => [],
                'section_data' => []
            ];
        }
    }

    /**
     * API endpoint: Get sections for filter dropdown
     */
    public function getSections()
    {
        try {
            $sections = Student::select('section')
                ->whereNotNull('section')
                ->where('section', '!=', '')
                ->distinct()
                ->orderBy('section')
                ->pluck('section');

            return response()->json($sections);
        } catch (\Exception $e) {
            Log::error('Error fetching sections: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * API endpoint: Get subjects for filter dropdown
     */
    public function getSubjects()
    {
        try {
            $subjects = Subject::select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($subjects);
        } catch (\Exception $e) {
            Log::error('Error fetching subjects: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * API endpoint: Get filtered grading scale data
     */
    public function getGradingScaleData(Request $request)
    {
        try {
            $track = $request->input('track');
            $cluster = $request->input('cluster');
            $section = $request->input('section');
            $subject = $request->input('subject');
            $quarter = $request->input('quarter');

            // Build the base query for students
            $studentsQuery = Student::query()
                ->whereNotNull('section')
                ->where('section', '!=', '');

            // Apply filters
            if ($track) {
                $studentsQuery->where('track', $track);
            }
            if ($cluster) {
                $studentsQuery->where('cluster', $cluster);
            }
            if ($section) {
                $studentsQuery->where('section', $section);
            }

            // Get sections based on filtered students
            $sections = (clone $studentsQuery)
                ->select('section')
                ->distinct()
                ->orderBy('section')
                ->pluck('section');

            if ($sections->isEmpty()) {
                return response()->json([
                    'sections' => [],
                    'outstanding' => [],
                    'very_satisfactory' => [],
                    'satisfactory' => [],
                    'fairly_satisfactory' => [],
                    'failed' => [],
                    'section_data' => []
                ]);
            }

            $sectionData = [];
            $outstanding = [];
            $verySatisfactory = [];
            $satisfactory = [];
            $fairlySatisfactory = [];
            $failed = [];

            foreach ($sections as $sectionName) {
                // Build grades query for this section (based on teacher-uploaded grades)
                $gradesQuery = Grade::whereHas('student', function($query) use ($sectionName, $track, $cluster) {
                    $query->where('section', $sectionName);
                    if ($track) {
                        $query->where('track', $track);
                    }
                    if ($cluster) {
                        $query->where('cluster', $cluster);
                    }
                });

                // Apply subject filter
                if ($subject) {
                    $gradesQuery->where('subject_id', $subject);
                }

                // Apply quarter filter - check specific quarter grades or final grade
                if ($quarter) {
                    switch ($quarter) {
                        case '1':
                            $gradesQuery->whereNotNull('quarter1');
                            break;
                        case '2':
                            $gradesQuery->whereNotNull('quarter2');
                            break;
                        case '3':
                            $gradesQuery->whereNotNull('quarter3');
                            break;
                        case '4':
                            $gradesQuery->whereNotNull('quarter4');
                            break;
                        default:
                            $gradesQuery->whereNotNull('final_grade');
                    }
                } else {
                    // Default to final grades when no quarter is specified
                    $gradesQuery->whereNotNull('final_grade');
                }

                // Determine which grade column to use for analysis
                $gradeColumn = 'final_grade'; // Default
                if ($quarter) {
                    switch ($quarter) {
                        case '1':
                            $gradeColumn = 'quarter1';
                            break;
                        case '2':
                            $gradeColumn = 'quarter2';
                            break;
                        case '3':
                            $gradeColumn = 'quarter3';
                            break;
                        case '4':
                            $gradeColumn = 'quarter4';
                            break;
                    }
                }

                // Count grades by the specified scale ranges
                // 90–100: Outstanding (Passed)
                $outstandingCount = (clone $gradesQuery)->where($gradeColumn, '>=', 90)->where($gradeColumn, '<=', 100)->count();
                // 85–89: Very Satisfactory (Passed)
                $verySatisfactoryCount = (clone $gradesQuery)->where($gradeColumn, '>=', 85)->where($gradeColumn, '<=', 89)->count();
                // 80–84: Satisfactory (Passed)
                $satisfactoryCount = (clone $gradesQuery)->where($gradeColumn, '>=', 80)->where($gradeColumn, '<=', 84)->count();
                // 75–79: Fairly Satisfactory (Passed)
                $fairlySatisfactoryCount = (clone $gradesQuery)->where($gradeColumn, '>=', 75)->where($gradeColumn, '<=', 79)->count();
                // Below 75: Did Not Meet Expectations (Failed)
                $failedCount = (clone $gradesQuery)->where($gradeColumn, '<', 75)->count();

                $totalCount = $outstandingCount + $verySatisfactoryCount + $satisfactoryCount + $fairlySatisfactoryCount + $failedCount;

                // Only include sections with data
                if ($totalCount > 0) {
                    $outstanding[] = $outstandingCount;
                    $verySatisfactory[] = $verySatisfactoryCount;
                    $satisfactory[] = $satisfactoryCount;
                    $fairlySatisfactory[] = $fairlySatisfactoryCount;
                    $failed[] = $failedCount;

                    $sectionData[$sectionName] = [
                        'total' => $totalCount,
                        'outstanding' => $outstandingCount,
                        'very_satisfactory' => $verySatisfactoryCount,
                        'satisfactory' => $satisfactoryCount,
                        'fairly_satisfactory' => $fairlySatisfactoryCount,
                        'failed' => $failedCount
                    ];
                }
            }

            // Filter sections to only those with data
            $sectionsWithData = array_keys($sectionData);

            return response()->json([
                'sections' => $sectionsWithData,
                'outstanding' => $outstanding,
                'very_satisfactory' => $verySatisfactory,
                'satisfactory' => $satisfactory,
                'fairly_satisfactory' => $fairlySatisfactory,
                'failed' => $failed,
                'section_data' => $sectionData
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching filtered grading scale data: ' . $e->getMessage());
            return response()->json([
                'sections' => [],
                'outstanding' => [],
                'very_satisfactory' => [],
                'satisfactory' => [],
                'fairly_satisfactory' => [],
                'failed' => [],
                'section_data' => []
            ]);
        }
    }
}