<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        Log::info('Principal Dashboard Access', [
            'user' => Auth::guard('principal')->user(),
            'authenticated' => Auth::guard('principal')->check()
        ]);

        return view('Principal.dashboard');
    }
} 