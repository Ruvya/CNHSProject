<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Section;

class SectionController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $section = null;
        $classmates = collect();

        if ($student && $student->section) {
            // Find section by name and current school year if possible
            $section = Section::where('name', $student->section)
                ->orderByDesc('school_year')
                ->first();

            // Classmates: students with same textual section
            $classmates = \App\Models\Student::where('section', $student->section)
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        }

        return view('student.section', compact('student', 'section', 'classmates'));
    }
}


