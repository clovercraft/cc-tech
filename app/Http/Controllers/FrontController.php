<?php

namespace App\Http\Controllers;

class FrontController extends Controller
{

    public function index()
    {
        return view('front.home');
    }

    public function register()
    {
        return view('front.register');
    }
}
