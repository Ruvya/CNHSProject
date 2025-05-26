<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get subjects with schedule information
        $subjects = Subject::where('track', $student->track)
            ->where('strand', $student->strand)
            ->where('grade_level', $student->grade_level)
            ->whereNotNull('schedule')
            ->get();

        return view('student.schedule', compact('student', 'subjects'));
    }
} 