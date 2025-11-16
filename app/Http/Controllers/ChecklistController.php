<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Categoria;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    public function index()
    {
        // Filtra solo categorías y áreas activas (ignorando mayúsculas/minúsculas)
        $categorias = Categoria::whereRaw('LOWER(estatus) = ?', ['activo'])->get();
        $areas = Area::whereRaw('LOWER(estatus) = ?', ['activo'])->get();

        // Trae todos los checklists con sus relaciones
        $checklists = Checklist::with(['categoria', 'area'])->get();

        return view('checklist.index', compact('checklists', 'categorias', 'areas'));
    }

    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'nombre_checklist' => 'required|string|max:255',
            'id_categoria' => 'required|integer|exists:categoria,id_categoria',
            'id_area' => 'required|integer|exists:area,id_area',
            'puntuacion_total' => 'nullable|numeric|min:0'
        ]);

        try {
            // Obtener el usuario autenticado
            $usuario = Auth::user();

            // Crear el checklist con el id_usuario correcto
            Checklist::create([
                'nombre_checklist' => trim($request->nombre_checklist),
                'id_categoria' => $request->id_categoria,
                'id_area' => $request->id_area,
                'puntuacion_total' => $request->puntuacion_total ?? 0,
                'creado_por' => $usuario->id_usuario,
                'estado' => 'Activo',
                'id_usuario' => $usuario->id_usuario,
            ]);

            return redirect()
                ->route('checklist.index')
                ->with('success', 'Checklist creado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al crear el checklist: ' . $e->getMessage());
        }
    }
}
