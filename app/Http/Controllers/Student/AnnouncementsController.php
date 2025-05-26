<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('author')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.announcements', compact('announcements'));
    }
} 