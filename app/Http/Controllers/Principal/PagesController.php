<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class PagesController extends Controller
{
    public function index()
    {
        return view('Principal.index');
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
        return view('Principal.news', compact('announcements'));
    }

    public function contact()
    {
        return view('Principal.contact');
    }
} 