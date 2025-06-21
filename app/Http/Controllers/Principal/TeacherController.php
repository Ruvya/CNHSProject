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
        return view('Principal.teachers.index', compact('teachers'));
    }

    // Principal cannot create teacher accounts - teachers register themselves
    public function create()
    {
        return redirect()->route('principal.teachers.index')
            ->with('info', 'Teachers must register their own accounts. You can only manage existing teacher information.');
    }

    // Principal cannot create teacher accounts - teachers register themselves
    public function store(Request $request)
    {
        return redirect()->route('principal.teachers.index')
            ->with('error', 'Teachers must register their own accounts. You can only manage existing teacher information.');
    }

    public function edit(Teacher $teacher)
    {
        return view('Principal.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        // Store original data for comparison
        $originalData = $teacher->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email,' . $teacher->id,
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'strand' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'subject' => $request->subject,
            'strand' => $request->strand,
            'status' => $request->status,
        ];

        // Principal cannot change teacher passwords - teachers manage their own passwords

        // Update the teacher
        $updated = $teacher->update($updateData);

        // Check if update was successful
        if ($updated) {
            // Refresh the model to get updated data
            $teacher->refresh();

            // Log the update for debugging
            \Log::info('Teacher updated successfully', [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->name,
                'updated_fields' => array_keys($updateData),
                'new_data' => $teacher->only(array_keys($updateData))
            ]);

            return redirect()->route('principal.teachers.index')
                ->with('success', 'Teacher information has been updated successfully!');
        } else {
            \Log::error('Failed to update teacher', [
                'teacher_id' => $teacher->id,
                'update_data' => $updateData
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update teacher information. Please try again.');
        }
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('principal.teachers.index')
            ->with('success', 'Teacher deleted successfully');
    }
} 