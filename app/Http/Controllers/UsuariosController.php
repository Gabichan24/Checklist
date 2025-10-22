<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use App\Models\Vacacion;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsuariosController extends Controller
{
    /** Mostrar lista de usuarios con filtro */
    public function index(Request $request)
    {
        $query = Usuario::with(['perfil', 'sucursal']);

        if ($request->filled('perfil')) {
            $query->where('id_perfil', $request->perfil);
        }

        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->buscar . '%')
                  ->orWhere('apellidos', 'like', '%' . $request->buscar . '%')
                  ->orWhere('correo', 'like', '%' . $request->buscar . '%');
            });
        }

        $usuarios = $query->paginate(10);
        $perfiles = Perfil::all();
        $sucursales = Sucursal::all();

        return view('usuarios.index', compact('usuarios', 'perfiles', 'sucursales'));
    }

    /** Guardar nuevo usuario */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'correo' => 'required|email|unique:usuario,correo',
            'password' => 'required|confirmed|min:6',
            'id_perfil' => 'required|exists:perfil,id_perfil',
            'id_sucursal' => 'nullable|exists:sucursal,id_sucursal',
        ]);

        $usuario = new Usuario();
        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->correo = $request->correo;
        $usuario->password = bcrypt($request->password);
        $usuario->id_perfil = $request->id_perfil;
        $usuario->superior = $request->superior ?? null;
        $usuario->id_sucursal = $request->id_sucursal;
        $usuario->telefono = $request->telefono ?? null;
        $usuario->reportes_adicionales = $request->has('reportes_adicionales') ? 1 : 0;

        if ($request->hasFile('foto')) {
            $usuario->foto = $request->file('foto')->store('usuarios', 'public');
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    /** Actualizar usuario */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'correo' => 'required|email|unique:usuario,correo,' . $usuario->id_usuario . ',id_usuario',
            'id_perfil' => 'required|exists:perfil,id_perfil',
            'id_sucursal' => 'nullable|exists:sucursal,id_sucursal',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->correo = $request->correo;
        $usuario->id_perfil = $request->id_perfil;
        $usuario->superior = $request->superior ?? null;
        $usuario->id_sucursal = $request->id_sucursal;
        $usuario->telefono = $request->telefono ?? null;
        $usuario->reportes_adicionales = $request->has('reportes_adicionales') ? 1 : 0;

        if ($request->hasFile('foto')) {
            $usuario->foto = $request->file('foto')->store('usuarios', 'public');
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /** Eliminar usuario */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    /* ---------------------- VACACIONES ---------------------- */

    public function vacaciones($id)
    {
        return response()->json(Vacacion::where('id_usuario', $id)->get());
    }

    public function guardarVacacion(Request $request)
    {
        $vacacion = Vacacion::create([
            'id_usuario' => $request->id_usuario,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'descripcion' => $request->descripcion,
            'finalizada' => false,
        ]);

        return response()->json(['success' => true, 'id_vacacion' => $vacacion->id_vacacion]);
    }

    public function actualizarVacacion(Request $request, $id)
    {
        $vacacion = Vacacion::findOrFail($id);
        $vacacion->update([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'descripcion' => $request->descripcion,
        ]);
        return response()->json(['success' => true]);
    }

    public function toggleFinalizada($id)
    {
        $vacacion = Vacacion::findOrFail($id);
        $vacacion->finalizada = !$vacacion->finalizada;
        $vacacion->save();
        return response()->json(['success' => true, 'finalizada' => $vacacion->finalizada]);
    }

    public function destroyVacacion($id)
    {
        $vacacion = Vacacion::find($id);
        if (!$vacacion) {
            return response()->json(['success' => false, 'message' => 'Vacación no encontrada.'], 404);
        }

        try {
            $vacacion->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error eliminando vacación: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar.'], 500);
        }
    }
}


