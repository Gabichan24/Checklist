<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;

class RegionesController extends Controller
{
    public function index()
    {
        $regiones = Region::all();

        // Si no hay datos en la BD, usar datos iniciales
        if ($regiones->isEmpty()) {
            $regiones = collect([
                ['id' => 1, 'nombre' => 'Aguascalientes', 'estados' => 'Aguascalientes', 'estatus' => 'Activo'],
                ['id' => 2, 'nombre' => 'Baja California', 'estados' => 'Baja California', 'estatus' => 'Activo'],
                ['id' => 3, 'nombre' => 'Baja California Sur', 'estados' => 'Baja California Sur', 'estatus' => 'Activo'],
                ['id' => 4, 'nombre' => 'Campeche', 'estados' => 'Campeche', 'estatus' => 'Activo'],
                // ... Agrega todos los demás estados si quieres
            ])->map(fn($item) => (object)$item); // <- convertir a objetos
        }

        return view('regiones.index', compact('regiones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'estados' => 'required|string|max:255',
        ]);

        Region::create([
            'nombre' => $request->nombre,
            'estados' => $request->estados,
            'estatus' => 'Activo',
        ]);

        return redirect()->route('regiones.index')->with('success', 'Región guardada correctamente');
    }
}
