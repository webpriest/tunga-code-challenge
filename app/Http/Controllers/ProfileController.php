<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display upload form
     * 
     * @return void
     */
    public function index()
    {
        return view('index');
    }
}
