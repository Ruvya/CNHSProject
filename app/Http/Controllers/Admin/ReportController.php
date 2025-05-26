<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Get filter options for the report interface
        $gradelevels = Student::select('grade_level')
            ->whereNotNull('grade_level')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        $tracks = Student::select('track')
            ->whereNotNull('track')
            ->where('track', '!=', '')
            ->distinct()
            ->orderBy('track')
            ->pluck('track');

        $strands = Student::select('strand')
            ->whereNotNull('strand')
            ->where('strand', '!=', '')
            ->distinct()
            ->orderBy('strand')
            ->pluck('strand');

        $subjects = Subject::select('name', 'id')
            ->whereNotNull('name')
            ->orderBy('name')
            ->get();

        $teachers = Teacher::select('name', 'id')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.reports.index', compact(
            'gradelevels',
            'tracks',
            'strands',
            'subjects',
            'teachers'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:student_enrollment,teacher_assignment,attendance_summary,grades_report,user_activity,monthly_registration',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'grade_level' => 'nullable|string',
            'track' => 'nullable|string',
            'strand' => 'nullable|string',
            'subject_id' => 'nullable|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'user_role' => 'nullable|in:student,teacher,admin',
            'status' => 'nullable|string',
        ]);

        $reportType = $request->report_type;
        $filters = $request->only(['start_date', 'end_date', 'grade_level', 'track', 'strand', 'subject_id', 'teacher_id', 'user_role', 'status']);

        $data = [];
        $chartData = [];
        $summary = [];

        switch ($reportType) {
            case 'student_enrollment':
                $result = $this->generateStudentEnrollmentReport($filters);
                break;
            case 'teacher_assignment':
                $result = $this->generateTeacherAssignmentReport($filters);
                break;
            case 'attendance_summary':
                $result = $this->generateAttendanceSummaryReport($filters);
                break;
            case 'grades_report':
                $result = $this->generateGradesReport($filters);
                break;
            case 'user_activity':
                $result = $this->generateUserActivityReport($filters);
                break;
            case 'monthly_registration':
                $result = $this->generateMonthlyRegistrationReport($filters);
                break;
            default:
                $result = ['data' => [], 'chartData' => [], 'summary' => []];
        }

        $data = $result['data'];
        $chartData = $result['chartData'] ?? [];
        $summary = $result['summary'] ?? [];

        return view('admin.reports.show', compact(
            'data',
            'chartData',
            'summary',
            'reportType',
            'filters'
        ));
    }

    private function generateStudentEnrollmentReport($filters)
    {
        $query = Student::query();

        // Apply filters
        if (!empty($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }
        if (!empty($filters['grade_level'])) {
            $query->where('grade_level', $filters['grade_level']);
        }
        if (!empty($filters['track'])) {
            $query->where('track', $filters['track']);
        }
        if (!empty($filters['strand'])) {
            $query->where('strand', $filters['strand']);
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(50);

        // Generate summary statistics
        $summary = [
            'total_students' => $query->count(),
            'by_grade' => Student::select('grade_level', DB::raw('count(*) as count'))
                ->groupBy('grade_level')
                ->orderBy('grade_level')
                ->get(),
            'by_track' => Student::select('track', DB::raw('count(*) as count'))
                ->whereNotNull('track')
                ->where('track', '!=', '')
                ->groupBy('track')
                ->orderBy('count', 'desc')
                ->get(),
            'by_gender' => Student::select('gender', DB::raw('count(*) as count'))
                ->whereNotNull('gender')
                ->groupBy('gender')
                ->get(),
        ];

        // Generate chart data
        $chartData = [
            'enrollment_by_grade' => $summary['by_grade']->pluck('count', 'grade_level'),
            'enrollment_by_track' => $summary['by_track']->pluck('count', 'track'),
            'enrollment_by_gender' => $summary['by_gender']->pluck('count', 'gender'),
        ];

        return [
            'data' => $students,
            'summary' => $summary,
            'chartData' => $chartData
        ];
    }

    private function generateTeacherAssignmentReport($filters)
    {
        $query = Teacher::with(['subjects']);

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['subject_id'])) {
            $query->whereHas('subjects', function($q) use ($filters) {
                $q->where('id', $filters['subject_id']);
            });
        }

        $teachers = $query->orderBy('name')->paginate(50);

        // Generate summary
        $summary = [
            'total_teachers' => Teacher::count(),
            'active_teachers' => Teacher::where('status', 'active')->count(),
            'teachers_with_subjects' => Teacher::whereHas('subjects')->count(),
            'subjects_assigned' => Subject::whereNotNull('teacher_id')->count(),
            'unassigned_subjects' => Subject::whereNull('teacher_id')->count(),
        ];

        // Chart data
        $chartData = [
            'teacher_status' => [
                'Active' => Teacher::where('status', 'active')->count(),
                'Inactive' => Teacher::where('status', 'inactive')->count(),
            ],
            'subject_assignment' => [
                'Assigned' => Subject::whereNotNull('teacher_id')->count(),
                'Unassigned' => Subject::whereNull('teacher_id')->count(),
            ]
        ];

        return [
            'data' => $teachers,
            'summary' => $summary,
            'chartData' => $chartData
        ];
    }

    private function generateAttendanceSummaryReport($filters)
    {
        // Since we don't have an attendance table yet, we'll create a placeholder report
        // This can be expanded when attendance tracking is implemented

        $summary = [
            'note' => 'Attendance tracking feature is not yet implemented.',
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'suggestion' => 'This report will show attendance statistics once attendance tracking is added to the system.'
        ];

        $data = collect([
            (object)[
                'message' => 'Attendance Summary Report',
                'status' => 'Feature Not Available',
                'description' => 'Attendance tracking functionality needs to be implemented first.',
                'next_steps' => 'Contact system administrator to enable attendance tracking.'
            ]
        ]);

        return [
            'data' => $data,
            'summary' => $summary,
            'chartData' => []
        ];
    }

    private function generateGradesReport($filters)
    {
        $query = Grade::with(['student', 'subject']);

        // Apply filters
        if (!empty($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }
        if (!empty($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }
        if (!empty($filters['grade_level'])) {
            $query->whereHas('student', function($q) use ($filters) {
                $q->where('grade_level', $filters['grade_level']);
            });
        }

        $grades = $query->orderBy('created_at', 'desc')->paginate(50);

        // Generate summary
        $summary = [
            'total_grades' => $query->count(),
            'average_grade' => round($query->avg('final_grade'), 2),
            'highest_grade' => $query->max('final_grade'),
            'lowest_grade' => $query->min('final_grade'),
            'passing_grades' => $query->where('final_grade', '>=', 75)->count(),
            'failing_grades' => $query->where('final_grade', '<', 75)->count(),
        ];

        // Chart data
        $gradeRanges = [
            '90-100' => $query->whereBetween('final_grade', [90, 100])->count(),
            '80-89' => $query->whereBetween('final_grade', [80, 89])->count(),
            '75-79' => $query->whereBetween('final_grade', [75, 79])->count(),
            'Below 75' => $query->where('final_grade', '<', 75)->count(),
        ];

        $chartData = [
            'grade_distribution' => $gradeRanges,
            'pass_fail' => [
                'Passing' => $summary['passing_grades'],
                'Failing' => $summary['failing_grades']
            ]
        ];

        return [
            'data' => $grades,
            'summary' => $summary,
            'chartData' => $chartData
        ];
    }

    private function generateUserActivityReport($filters)
    {
        $data = collect();

        // Get user activity based on role filter
        if (empty($filters['user_role']) || $filters['user_role'] === 'student') {
            $students = Student::select('first_name', 'last_name', 'email', 'created_at', 'updated_at')
                ->when(!empty($filters['start_date']), function($q) use ($filters) {
                    return $q->where('created_at', '>=', $filters['start_date']);
                })
                ->when(!empty($filters['end_date']), function($q) use ($filters) {
                    return $q->where('created_at', '<=', $filters['end_date']);
                })
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function($student) {
                    return (object)[
                        'name' => $student->first_name . ' ' . $student->last_name,
                        'email' => $student->email,
                        'role' => 'Student',
                        'last_activity' => $student->updated_at,
                        'registration_date' => $student->created_at,
                    ];
                });
            $data = $data->merge($students);
        }

        if (empty($filters['user_role']) || $filters['user_role'] === 'teacher') {
            $teachers = Teacher::select('name', 'email', 'created_at', 'updated_at', 'status')
                ->when(!empty($filters['start_date']), function($q) use ($filters) {
                    return $q->where('created_at', '>=', $filters['start_date']);
                })
                ->when(!empty($filters['end_date']), function($q) use ($filters) {
                    return $q->where('created_at', '<=', $filters['end_date']);
                })
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function($teacher) {
                    return (object)[
                        'name' => $teacher->name,
                        'email' => $teacher->email,
                        'role' => 'Teacher',
                        'status' => $teacher->status,
                        'last_activity' => $teacher->updated_at,
                        'registration_date' => $teacher->created_at,
                    ];
                });
            $data = $data->merge($teachers);
        }

        if (empty($filters['user_role']) || $filters['user_role'] === 'admin') {
            $admins = Admin::select('name', 'email', 'created_at', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function($admin) {
                    return (object)[
                        'name' => $admin->name,
                        'email' => $admin->email,
                        'role' => 'Admin',
                        'last_activity' => $admin->updated_at,
                        'registration_date' => $admin->created_at,
                    ];
                });
            $data = $data->merge($admins);
        }

        // Sort by last activity
        $data = $data->sortByDesc('last_activity');

        $summary = [
            'total_users' => $data->count(),
            'students' => $data->where('role', 'Student')->count(),
            'teachers' => $data->where('role', 'Teacher')->count(),
            'admins' => $data->where('role', 'Admin')->count(),
            'active_today' => $data->where('last_activity', '>=', Carbon::today())->count(),
            'active_this_week' => $data->where('last_activity', '>=', Carbon::now()->subWeek())->count(),
        ];

        $chartData = [
            'users_by_role' => [
                'Students' => $summary['students'],
                'Teachers' => $summary['teachers'],
                'Admins' => $summary['admins'],
            ],
            'activity_timeline' => [
                'Today' => $summary['active_today'],
                'This Week' => $summary['active_this_week'],
                'Total' => $summary['total_users'],
            ]
        ];

        return [
            'data' => $data,
            'summary' => $summary,
            'chartData' => $chartData
        ];
    }

    private function generateMonthlyRegistrationReport($filters)
    {
        $months = [];
        $startDate = !empty($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->subMonths(11);
        $endDate = !empty($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now();

        // Generate monthly data
        $current = $startDate->copy()->startOfMonth();
        while ($current <= $endDate) {
            $monthData = [
                'month' => $current->format('M Y'),
                'students' => Student::whereYear('created_at', $current->year)
                    ->whereMonth('created_at', $current->month)
                    ->count(),
                'teachers' => Teacher::whereYear('created_at', $current->year)
                    ->whereMonth('created_at', $current->month)
                    ->count(),
            ];
            $monthData['total'] = $monthData['students'] + $monthData['teachers'];
            $months[] = $monthData;
            $current->addMonth();
        }

        $summary = [
            'total_months' => count($months),
            'total_registrations' => array_sum(array_column($months, 'total')),
            'avg_monthly_registrations' => count($months) > 0 ? round(array_sum(array_column($months, 'total')) / count($months), 1) : 0,
            'peak_month' => collect($months)->sortByDesc('total')->first(),
            'student_registrations' => array_sum(array_column($months, 'students')),
            'teacher_registrations' => array_sum(array_column($months, 'teachers')),
        ];

        $chartData = [
            'monthly_trend' => [
                'labels' => array_column($months, 'month'),
                'students' => array_column($months, 'students'),
                'teachers' => array_column($months, 'teachers'),
                'total' => array_column($months, 'total'),
            ]
        ];

        return [
            'data' => collect($months),
            'summary' => $summary,
            'chartData' => $chartData
        ];
    }

    public function download(Request $request)
    {
        // Basic CSV download functionality
        $reportType = $request->get('report_type');
        $filters = $request->only(['start_date', 'end_date', 'grade_level', 'track', 'strand', 'subject_id', 'teacher_id', 'user_role', 'status']);

        // Generate the report data
        $result = $this->generate($request);

        // For now, return a simple response
        // This can be enhanced with actual CSV/PDF generation
        return response()->json([
            'message' => 'Download functionality will be implemented with CSV/PDF export',
            'report_type' => $reportType,
            'data_count' => is_countable($result->getData()['data']) ? count($result->getData()['data']) : 0
        ]);
    }
}