<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Exports\ZonasExport;
use Maatwebsite\Excel\Facades\Excel;

class ZonasController extends Controller
{
    // Mostrar listado de zonas
    public function index()
{
    $zonas = Zona::select('zona.id_zona', 'zona.nombre', 'zona.estatus', 'region.nombre as region_nombre')
                 ->leftJoin('region', 'zona.id_region', '=', 'region.id_region')
                 ->get();
    $regiones = Region::all(); // Para selects o filtros
    return view('zonas.index', compact('zonas', 'regiones'));
}
    

    // Guardar nueva zona
    public function store(Request $request)
{
    Zona::create([
        'nombre' => $request->nombre,
        'id_region' => $request->id_region,
        'estatus' => 'Activo', // por defecto nueva zona Activo
    ]);

    return redirect()->route('zonas.index')->with('success', 'Zona creada correctamente.');
}
public function create()
{
    $regiones = Region::all(); // Para llenar el select
    return view('zonas.create', compact('regiones'));
}
    // Mostrar formulario para editar zona (opcional, si usas modal solo index)
    public function edit(Zona $zona)
    {
        $regiones = Region::all();
        return view('zonas.edit', compact('zona', 'regiones'));
    }

    // Actualizar zona
    public function update(Request $request, Zona $zona)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'id_region' => 'required|exists:region,id_region',
    ]);

    $zona->update([
        'nombre' => $request->nombre,
        'id_region' => $request->id_region,
        'estatus' => $request->estatus ?? $zona->estatus, // Mantener texto
    ]);

    return redirect()->route('zonas.index')->with('success', 'Zona actualizada correctamente.');
}

    // Cambiar estatus (Activo/Inactivo)
   public function toggle($id)
{
    $zona = Zona::findOrFail($id); // busca la zona existente
    $zona->estatus = $zona->estatus === 'Activo' ? 'Inactivo' : 'Activo';
    $zona->save();

    return redirect()->route('zonas.index')->with('success', 'Estatus actualizado correctamente.');
}
    // Exportar zonas
    public function exportXlsx()
    {
        return Excel::download(new ZonasExport, 'zonas.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new ZonasExport, 'zonas.csv');
    }
}

