<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function index()
    {
        return view('areas.index');
    }

    public function create()
    {
        return view('areas.create');
    }
}
