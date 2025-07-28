<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('last_name')->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|unique:students,student_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|min:8',
            'grade_level' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:20',
            'track' => 'nullable|string',
            'strand' => 'nullable|string',
            'section' => 'nullable|string|max:50',
            'lrn' => 'nullable|string|max:20',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        Student::create($validated);
        return redirect()->route('admin.users.students.index')->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => 'required|unique:students,student_id,' . $student->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'grade_level' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:20',
            'track' => 'nullable|string',
            'strand' => 'nullable|string',
            'section' => 'nullable|string|max:50',
            'lrn' => 'nullable|string|max:20',
        ]);
        $student->update($validated);
        return redirect()->route('admin.users.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.users.students.index')->with('success', 'Student deleted successfully.');
    }
} 