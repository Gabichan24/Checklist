<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SucursalesController extends Controller
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