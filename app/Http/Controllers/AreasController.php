<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use App\Exports\AreasExport;
use Maatwebsite\Excel\Facades\Excel;
class AreasController extends Controller
{
    // Mostrar listado de áreas
    public function index()
    {
        $areas = Area::all();
        return view('areas.index', compact('areas'));
    }

    // Mostrar formulario para crear una nueva área
    public function create()
    {
        return view('areas.create');
    }

    // Guardar nueva área
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        Area::create([
            'nombre' => $request->nombre,
            'estatus' => 'Activo', // Por defecto activa
        ]);

        return redirect()->route('areas.index')->with('success', 'Área creada correctamente.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $area = Area::findOrFail($id);
        return view('areas.edit', compact('area'));
    }

    // Actualizar área
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $area = Area::findOrFail($id);
        $area->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('areas.index')->with('success', 'Área actualizada correctamente.');
    }

    // Cambiar estatus (bloquear/desbloquear)
    public function toggle($id)
{
    $area = Area::findOrFail($id);

    // Cambia estatus entre "Activo" e "Inactivo"
    $area->estatus = ($area->estatus === 'Activo') ? 'Inactivo' : 'Activo';
    $area->save();

    return redirect()->back()->with('success', 'Estatus actualizado correctamente');
}
    // Eliminar área (opcional)
    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return redirect()->route('areas.index')->with('success', 'Área eliminada correctamente.');
    }
    // Exportar a Excel
public function exportExcel()
{
    return Excel::download(new AreasExport, 'areas.xlsx');
}

// Exportar a CSV
public function exportCsv()
{
    return Excel::download(new AreasExport, 'areas.csv');
}
}
