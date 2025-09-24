<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZonasController extends Controller
{
    public function index()
    {
        return view('regiones.index');
    }

    public function create()
    {
        return view('regiones.create');
    }
}
