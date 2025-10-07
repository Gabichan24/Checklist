<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Exports\CategoriasExport;
use Maatwebsite\Excel\Facades\Excel;
class CategoriasController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $categorias = Categoria::where('nombre', 'like', "%{$busqueda}%")->get();
        return view('categorias.index', compact('categorias', 'busqueda'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'estatus' => 'Activo',
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function toggle($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->estatus = $categoria->estatus === 'Activo' ? 'Inactivo' : 'Activo';
        $categoria->save();

        return redirect()->route('categorias.index');
    }

   public function exportXlsx()
{
    return Excel::download(new CategoriasExport, 'categorias.xlsx');
}

public function exportCsv()
{
    return Excel::download(new CategoriasExport, 'categorias.csv');
}
}
