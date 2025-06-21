<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentYearlyRecord;
use Illuminate\Http\Request;

class StudentYearlyRecordController extends Controller
{
    public function index(Student $student)
    {
        $records = $student->yearlyRecords()->orderBy('school_year', 'desc')->get();
        return view('registrar.students.yearly-records', compact('student', 'records'));
    }

    public function create(Student $student)
    {
        return view('registrar.students.yearly-records-create', compact('student'));
    }

    public function store(Request $request, Student $student)
    {
        $request->validate([
            'school_year' => 'required|string|max:9', // e.g., 2023-2024
            'grade_level' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $student->yearlyRecords()->create($request->all());

        return redirect()->route('registrar.students.yearly-records.index', $student)
            ->with('success', 'Student yearly record created successfully.');
    }

    public function edit(Student $student, StudentYearlyRecord $record)
    {
        return view('registrar.students.yearly-records-edit', compact('student', 'record'));
    }

    public function update(Request $request, Student $student, StudentYearlyRecord $record)
    {
        $request->validate([
            'school_year' => 'required|string|max:9',
            'grade_level' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $record->update($request->all());

        return redirect()->route('registrar.students.yearly-records.index', $student)
            ->with('success', 'Student yearly record updated successfully.');
    }

    public function destroy(Student $student, StudentYearlyRecord $record)
    {
        $record->delete();

        return redirect()->route('registrar.students.yearly-records.index', $student)
            ->with('success', 'Student yearly record deleted successfully.');
    }
} 