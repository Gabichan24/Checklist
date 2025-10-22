<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\PerfilPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerfilesController extends Controller
{
    public function index()
    {
        $perfiles = Perfil::all();
        return view('perfiles.index', compact('perfiles'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $perfil = Perfil::create([
                'nombre_perfil' => $request->nombre_perfil,
                'descripcion' => $request->descripcion,
                'superior' => $request->superior,
                'nivel_asignacion' => $request->nivel_asignacion,
                'asignacion_multiple' => $request->has('asignacion_multiple') ? 1 : 0,
                'estatus' => 1
            ]);

            // Guardar permisos (si los hay)
            if ($request->has('permisos')) {
                foreach ($request->permisos as $modulo => $acciones) {
                    foreach ($acciones as $permiso) {
                        PerfilPermiso::create([
                            'id_perfil' => $perfil->id_perfil,
                            'id_permiso' => self::mapPermisoToId($permiso)
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('perfiles.index')->with('success', 'Perfil creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el perfil: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $perfil = Perfil::findOrFail($id);
        $perfil->estatus = 0;
        $perfil->save();

        return redirect()->route('perfiles.index')->with('success', 'Perfil bloqueado correctamente');
    }

    private static function mapPermisoToId($permiso)
    {
        // Mapea el nombre del permiso al id_permiso correspondiente
        // Ajusta según tu tabla de permisos real
        $map = [
            'ver' => 1,
            'crear' => 2,
            'editar' => 3,
            'eliminar' => 4,
            'deshabilitar' => 5,
            'automatizar' => 6,
            'realizar' => 7,
            'reasignar' => 8,
            'suspender' => 9,
        ];

        return $map[$permiso] ?? null;
    }
    public function listar()
    {
        $perfiles = Perfil::where('estatus', 1)
            ->select('id_perfil', 'nombre_perfil')
            ->orderBy('nombre_perfil')
            ->get();

        return response()->json($perfiles);
    }
}
