<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $registrar = auth()->guard('registrar')->user();

        $totalStudents = Student::count();
        $totalSubjects = Subject::count();
        $mySubjects = Subject::where('registrar_id', $registrar->id)->count();

        return view('registrar.dashboard', compact('totalStudents', 'totalSubjects', 'mySubjects'));
    }

    public function profile()
    {
        return view('registrar.profile');
    }

    public function updateProfile(Request $request)
    {
        $registrar = auth()->guard('registrar')->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $registrar->first_name = $request->name;
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $registrar->profile_picture = $path;
        }
        $registrar->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}