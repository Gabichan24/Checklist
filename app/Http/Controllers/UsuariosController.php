<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use App\Models\Sucursal;
use App\Models\Vacacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UsuariosController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('perfil')->get();
        $perfiles = Perfil::all();
        $sucursales = Sucursal::all();

        return view('usuarios.index', compact('usuarios', 'perfiles', 'sucursales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'id_perfil' => 'required|integer',
            'correo' => 'required|email|unique:usuario,correo',
            'password' => 'required|string|min:6|confirmed',
            'telefono' => 'nullable|string|max:20',
            'id_sucursal' => 'nullable|integer',
            'superior' => 'nullable|string|max:55',
            'foto' => 'nullable|image|max:2048'
        ]);

        $fotoPath = $request->file('foto') ? $request->file('foto')->store('usuarios', 'public') : null;

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'id_perfil' => $request->id_perfil,
            'correo' => $request->correo,
            'password' => bcrypt($request->password),
            'telefono' => $request->telefono,
            'id_sucursal' => $request->id_sucursal,
            'superior' => $request->superior,
            'reportes_adicionales' => $request->reportes_adicionales ?? 0,
            'foto' => $fotoPath,
            'estatus' => 1,
            'sistema' => 'checklist',
            'app' => 'web',
            'nivel' => 'Sucursal',
        ]);

        return response()->json([
            'success' => true,
            'usuario' => $usuario
        ]);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'id_perfil' => 'required|integer',
            'correo' => 'required|email|unique:usuario,correo,' . $id . ',id_usuario',
            'password' => 'nullable|string|min:6|confirmed',
            'telefono' => 'nullable|string|max:20',
            'id_sucursal' => 'nullable|integer',
            'superior' => 'nullable|string|max:55',
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->file('foto')) {
            if ($usuario->foto) Storage::disk('public')->delete($usuario->foto);
            $fotoPath = $request->file('foto')->store('usuarios', 'public');
            $usuario->foto = $fotoPath;
        }

        $usuario->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'id_perfil' => $request->id_perfil,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'id_sucursal' => $request->id_sucursal,
            'superior' => $request->superior,
            'reportes_adicionales' => $request->reportes_adicionales ?? 0,
            'password' => $request->password ? bcrypt($request->password) : $usuario->password,
        ]);

        return response()->json([
            'success' => true,
            'usuario' => $usuario
        ]);
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
        } catch (\Exception $e) {
            Log::error('Error eliminando vacación: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar.'], 500);
        }
        }
    }



