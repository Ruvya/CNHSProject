<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Section;

class SectionController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        // Sections where teacher is adviser
        $sections = $teacher->advisedSections()->orderBy('grade_level')->orderBy('name')->get();

        return view('teacher.sections.index', compact('teacher', 'sections'));
    }

    public function show(Section $section)
    {
        $teacher = Auth::guard('teacher')->user();

        // authorize: teacher must be adviser of the section
        if ($section->adviser_id !== $teacher->id) {
            abort(403, 'You are not the adviser of this section.');
        }

        // Load students under this section
        $students = $section->students()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('teacher.sections.show', compact('teacher', 'section', 'students'));
    }
}


