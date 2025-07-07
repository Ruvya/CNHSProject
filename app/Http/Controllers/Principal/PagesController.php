<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        // Get data for the landing page
        $recentAnnouncements = Announcement::where('status', 'active')
            ->latest()
            ->take(5)
            ->get();
            
        $upcomingEvents = Event::where('start', '>=', now())
            ->orderBy('start')
            ->take(5)
            ->get();
            
        // Get basic statistics for display
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalSubjects = Subject::count();
        
        return view('Principal.index', compact(
            'recentAnnouncements',
            'upcomingEvents',
            'totalStudents',
            'totalTeachers',
            'totalSubjects'
        ));
    }
    
    public function about()
    {
        return view('Principal.about');
    }
    
    public function academics()
    {
        return view('Principal.academics');
    }
    
    public function contact()
    {
        return view('Principal.contact');
    }
    
    public function news()
    {
        $announcements = Announcement::where('status', 'active')
            ->latest()
            ->paginate(12);
            
        $upcomingEvents = Event::where('start', '>=', now())
            ->orderBy('start')
            ->take(10)
            ->get();
            
        return view('Principal.news', compact('announcements', 'upcomingEvents'));
    }
}