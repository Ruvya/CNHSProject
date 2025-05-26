<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //

    public function showStudentRegisterForm() {
        return view('auth.register');
    }
}
