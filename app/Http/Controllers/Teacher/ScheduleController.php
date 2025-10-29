<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Services\SemesterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher');
    }

    public function index(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $schoolYear = $request->get('school_year', SemesterService::getCurrentSchoolYear());
        $semester = $request->get('semester', SemesterService::getCurrentSemester());
        $day = $request->get('day');

        $query = Schedule::with(['subject', 'section', 'room'])
            ->where('teacher_id', $teacher->id)
            ->where('status', 'active')
            ->where('school_year', $schoolYear);

        if (!empty($semester)) {
            // The schedules table uses semester column
            $query->where('semester', $semester);
        }

        if (!empty($day)) {
            $query->where('day', $day);
        }

        $schedules = $query->orderByRaw("FIELD(day, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')")
            ->orderBy('start_time')
            ->get();

        $semesterOptions = SemesterService::getAvailableSemesters();
        $schoolYearDefault = SemesterService::getCurrentSchoolYear();

        return view('teacher.schedule.index', [
            'teacher' => $teacher,
            'schedules' => $schedules,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
            'semesterOptions' => $semesterOptions,
            'schoolYearDefault' => $schoolYearDefault,
            'day' => $day,
        ]);
    }
}


