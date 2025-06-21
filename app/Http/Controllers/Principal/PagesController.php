<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class PagesController extends Controller
{
    public function index()
    {
        $upcomingEvents = \App\Models\Event::where('start', '>=', now())
            ->orderBy('start', 'asc')
            ->limit(3)
            ->get();
        return view('Principal.index', compact('upcomingEvents'));
    }

    public function about()
    {
        return view('Principal.about');
    }

    public function academics()
    {
        return view('Principal.academics');
    }

    public function news()
    {
        $announcements = Announcement::latest()->get();
        $upcomingEvents = \App\Models\Event::where('start', '>=', now())
            ->orderBy('start', 'asc')
            ->get();
        return view('Principal.news', compact('announcements', 'upcomingEvents'));
    }

    public function contact()
    {
        return view('Principal.contact');
    }
} 