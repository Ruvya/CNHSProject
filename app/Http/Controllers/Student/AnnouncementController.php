<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        // You can fetch announcements here and pass to the view
        $announcements = [];
        return view('student.announcements', compact('announcements'));
    }
} 