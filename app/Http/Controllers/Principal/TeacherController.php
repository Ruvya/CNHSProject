<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        return view('principal.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('principal.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'subject_specialty' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'subject_specialty' => $request->subject_specialty,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('principal.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        return view('principal.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email,' . $teacher->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'subject_specialty' => 'required|string|max:255',
        ]);

        $teacher->update($request->all());

        return redirect()->route('principal.teachers.index')
            ->with('success', 'Teacher updated successfully');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('principal.teachers.index')
            ->with('success', 'Teacher deleted successfully');
    }
} 