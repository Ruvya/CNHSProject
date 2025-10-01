<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentController extends Controller
{
    /**
     * Display a listing of students
     */
    public function index(): View
    {
        $students = Student::with('yearlyRecords')->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create(): View
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'student_id' => 'required|string|unique:students,student_id',
            'grade_level' => 'required|string',
            'section' => 'required|string',
        ]);

        Student::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student): View
    {
        $student->load('yearlyRecords');
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student): View
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'grade_level' => 'required|string',
            'section' => 'required|string',
        ]);

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
