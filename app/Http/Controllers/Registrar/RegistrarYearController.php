<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\RegistrarYear;
use App\Models\SchoolYear;

class RegistrarYearController extends Controller
{
    public function index()
    {
        $years = SchoolYear::orderByDesc('start_year')->get();
        $registrarYears = RegistrarYear::orderByDesc('school_year')->get()->keyBy('school_year');
        return view('registrar.years.index', compact('years', 'registrarYears'));
    }
}


