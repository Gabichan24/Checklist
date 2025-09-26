<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    public function index()
    {
        return view('categorias.index');
    }

    public function create()
    {
        return view('categorias.create');
    }
}