<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RegionesExport;

class RegionesController extends Controller
{
    public function index()
    {
        // Traer todas las regiones que ya existen
        $regiones = Region::all();

        // Lista fija de estados
        $estados = [
            (object)['nombre' => 'Aguascalientes'],
            (object)['nombre' => 'Baja California'],
            (object)['nombre' => 'Baja California Sur'],
            (object)['nombre' => 'Campeche'],
            (object)['nombre' => 'Chiapas'],
            (object)['nombre' => 'Chihuahua'],
            (object)['nombre' => 'Ciudad de México'],
            (object)['nombre' => 'Coahuila'],
            (object)['nombre' => 'Colima'],
            (object)['nombre' => 'Durango'],
            (object)['nombre' => 'Estado de México'],
            (object)['nombre' => 'Guanajuato'],
            (object)['nombre' => 'Guerrero'],
            (object)['nombre' => 'Hidalgo'],
            (object)['nombre' => 'Jalisco'],
            (object)['nombre' => 'Michoacán'],
            (object)['nombre' => 'Morelos'],
            (object)['nombre' => 'Nayarit'],
            (object)['nombre' => 'Nuevo León'],
            (object)['nombre' => 'Oaxaca'],
            (object)['nombre' => 'Puebla'],
            (object)['nombre' => 'Querétaro'],
            (object)['nombre' => 'Quintana Roo'],
            (object)['nombre' => 'San Luis Potosí'],
            (object)['nombre' => 'Sinaloa'],
            (object)['nombre' => 'Sonora'],
            (object)['nombre' => 'Tabasco'],
            (object)['nombre' => 'Tamaulipas'],
            (object)['nombre' => 'Tlaxcala'],
            (object)['nombre' => 'Veracruz'],
            (object)['nombre' => 'Yucatán'],
            (object)['nombre' => 'Zacatecas'],
        ];

        return view('regiones.index', compact('regiones', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $region = Region::findOrFail($id);

        $request->validate([
            'descripcion' => 'required|string|max:255',
            'estado'      => 'required|string|max:255',
        ]);

        $region->nombre = $request->descripcion;
        $region->estados = $request->estado; // asegúrate que exista esta columna en la BD
        $region->save();

        return redirect()->route('regiones.index')->with('success', 'Región actualizada correctamente.');
    }
public function toggleEstatus($id)
{
    $region = Region::findOrFail($id);

    // Cambiar estatus
    $region->estatus = $region->estatus === 'Activo' ? 'Inactivo' : 'Activo';
    $region->save();

    return redirect()->route('regiones.index')->with('success', 'Estatus actualizado.');
}
    public function exportXlsx()
    {
        return Excel::download(new RegionesExport, 'regiones.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new RegionesExport, 'regiones.csv');
    }
}

