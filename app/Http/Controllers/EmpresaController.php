<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    // Mostrar la configuración de la empresa
    public function index()
    {
        $empresa = Empresa::first();

        // Si no existe, crear un registro vacío
        if (!$empresa) {
            $empresa = Empresa::create([]);
        }

        return view('empresa.index', compact('empresa'));
    }

    // Actualizar los datos de la empresa
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $request->validate([
            'nombre_comercial' => 'required|string|max:100',
            'razon_social' => 'required|string|max:100',
            'rfc' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:200',
            'codigo_postal' => 'nullable|string|max:10',
            'correo' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:2048',
            'tolerancia' => 'nullable|integer',
            'tiempo_max_respuesta' => 'nullable|integer',
            'horario_notificaciones' => 'nullable|string|max:100',
            'hora_ini' => 'nullable|string|max:11',
        ]);

        // Guardar el logo si se sube
        if ($request->hasFile('logo')) {
            // Borrar logo anterior
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }
            $empresa->logo = $request->file('logo')->store('logos', 'public');
        }

        // Actualizar el resto de campos
        $empresa->update($request->except('logo'));

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }
}
