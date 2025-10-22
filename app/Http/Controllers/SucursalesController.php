<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\Zona;
use App\Models\Area;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SucursalesExport;

class SucursalesController extends Controller
{
    // 📋 Listado de sucursales
    public function index(Request $request)
    {
        $search = $request->input('search');

        $sucursales = Sucursal::with('zona')
            ->when($search, function ($query, $search) {
                return $query->where('nombre', 'like', "%{$search}%")
                             ->orWhere('identificador', 'like', "%{$search}%");
            })
            ->get();

        $zonas = Zona::all();
        $areas = Area::all();

        return view('sucursales.index', compact('sucursales', 'zonas', 'areas'));
    }

    // 🏗️ Crear sucursal
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'identificador' => 'required|string|max:55',
            'id_zona' => 'required|integer|exists:zona,id_zona',
            'zona_horaria' => 'required|string|max:100',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'radio' => 'nullable|integer',
            'codigo_postal' => 'nullable|string|max:10',
            'direccion' => 'nullable|string|max:200',
            'areas' => 'nullable|array',
            'areas.*' => 'exists:area,id_area',
        ]);

        $sucursal = new Sucursal();
        $sucursal->nombre = $request->nombre;
        $sucursal->clave = $request->identificador;
        $sucursal->id_zona = $request->id_zona;
        $sucursal->zona_horaria = $request->zona_horaria;
        $sucursal->codigo_postal = $request->codigo_postal;
        $sucursal->direccion = $request->direccion;
        $sucursal->latitud = $request->latitud;
        $sucursal->longitud = $request->longitud;
        $sucursal->radio = $request->radio;
        $sucursal->estatus = 'Activo';
        $sucursal->id_area = $request->areas ? implode(',', $request->areas) : null;
        $sucursal->save();

        return redirect()->route('sucursales.index')->with('success', 'Sucursal creada correctamente.');
    }

    // ✏️ Mostrar formulario de edición (opcional si usas modal)
    public function edit($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $zonas = Zona::all();
        $areas = Area::all();

        return view('sucursales.edit', compact('sucursal', 'zonas', 'areas'));
    }

    // 💾 Actualizar sucursal
    public function update(Request $request, $id)
    {
        $sucursal = Sucursal::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'identificador' => 'required|string|max:55',
            'id_zona' => 'required|integer|exists:zona,id_zona',
            'zona_horaria' => 'required|string|max:100',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'radio' => 'nullable|integer',
            'codigo_postal' => 'nullable|string|max:10',
            'direccion' => 'nullable|string|max:200',
            'areas' => 'nullable|array',
            'areas.*' => 'exists:area,id_area',
        ]);

        $sucursal->nombre = $request->nombre;
        $sucursal->identificador = $request->identificador;
        $sucursal->clave = $request->identificador;
        $sucursal->id_zona = $request->id_zona;
        $sucursal->zona_horaria = $request->zona_horaria;
        $sucursal->codigo_postal = $request->codigo_postal;
        $sucursal->direccion = $request->direccion;
        $sucursal->latitud = $request->latitud;
        $sucursal->longitud = $request->longitud;
        $sucursal->radio = $request->radio;
        $sucursal->id_area = $request->areas ? implode(',', $request->areas) : null;
        $sucursal->save();

        return redirect()->route('sucursales.index')->with('success', 'Sucursal actualizada correctamente.');
    }

    // 🔄 Cambiar estatus (Activo / Inactivo)
    public function toggle($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $sucursal->estatus = ($sucursal->estatus === 'Activo') ? 'Inactivo' : 'Activo';
        $sucursal->save();

        return redirect()->back()->with('success', 'Estatus de la sucursal actualizado correctamente.');
    }

    // 📤 Exportar a Excel
    public function exportXlsx()
    {
        return Excel::download(new SucursalesExport, 'sucursales.xlsx');
    }

    // 📤 Exportar a CSV
    public function exportCsv()
    {
        return Excel::download(new SucursalesExport, 'sucursales.csv');
    }
}
