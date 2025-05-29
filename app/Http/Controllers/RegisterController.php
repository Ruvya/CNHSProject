<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function registerStudent(Request $request)
    {
        Log::info($request->all());

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:8|confirmed',
            'grade_level' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'student_id' => 'required|string|unique:students',
            'emergency_name' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:50',
            'emergency_relationship' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'advisor' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student = new Student();
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name = $request->last_name;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->grade_level = $request->grade_level;
        $student->gender = $request->gender;
        $student->student_id = $request->student_id;
        // $student->emergency_name = $request->emergency_name;
        // $student->emergency_phone = $request->emergency_phone;
        // $student->emergency_relationship = $request->emergency_relationship;

        // $student->section = $request->section;
        // $student->advisor = $request->advisor;
        // $student->phone = $request->phone;
        // $student->address = $request->address;
        $student->save();

        $assignedId = $student->student_id;

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function registerTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $teacher = Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }
}
