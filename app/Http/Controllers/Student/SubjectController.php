<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        // Get subjects specifically assigned to this student by the registrar
        $assignedSubjects = $student->subjects()->with('teacher')->get();

        // Get available subjects based on student's track and strand (for reference)
        $availableSubjects = Subject::where('track', $student->track)
            ->where('strand', $student->strand)
            ->where('grade_level', $student->grade_level)
            ->with('teacher')
            ->get();

        // Calculate statistics
        $totalAssigned = $assignedSubjects->count();
        $totalUnits = $assignedSubjects->sum('units');
        $subjectsWithTeachers = $assignedSubjects->whereNotNull('teacher_id')->count();

        return view('student.subjects', compact(
            'student',
            'assignedSubjects',
            'availableSubjects',
            'totalAssigned',
            'totalUnits',
            'subjectsWithTeachers'
        ));
    }

    public function show($id)
    {
        $student = Auth::guard('student')->user();

        // Get the specific subject with teacher information
        $subject = Subject::with('teacher')->findOrFail($id);

        // Check if this subject is assigned to the student
        $isAssigned = $student->subjects()->where('subject_id', $id)->exists();

        // Get the student's grade for this subject if it exists
        $studentGrade = $student->grades()->where('subject_id', $id)->first();

        // Get other students in this subject (for class size info)
        $classSize = $subject->students()->count();

        return view('student.subject-details', compact(
            'student',
            'subject',
            'isAssigned',
            'studentGrade',
            'classSize'
        ));
    }
}