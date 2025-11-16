<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\PerfilPermiso;
use App\Models\Permisoglobal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerfilesController extends Controller
{
    /**
     * Mostrar lista de perfiles y permisos asociados.
     */
    public function index()
    {
        $perfiles = Perfil::all();

        // Agrupar permisos por módulo
        $permisosPorModulo = Permisoglobal::all()
            ->groupBy('modulo')
            ->map(function ($grupo) {
                return $grupo->map(function ($permiso) {
                    return [
                        'label' => ucfirst($permiso->accion),
                        'value' => $permiso->id_permiso,
                    ];
                })->values();
            });

        // Permisos asignados a cada perfil
        $permisosAsignados = PerfilPermiso::all()
            ->groupBy('id_perfil')
            ->map(function ($grupo) {
                return $grupo->pluck('id_permiso')->toArray();
            });

        return view('perfiles.index', compact('perfiles', 'permisosPorModulo', 'permisosAsignados'));
    }

    /**
     * Guardar un nuevo perfil junto con sus permisos (si los hay).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_perfil' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $perfil = Perfil::create([
                'nombre_perfil' => $request->nombre_perfil,
                'descripcion' => $request->descripcion,
                'superior' => $request->superior,
                'nivel_asignacion' => $request->nivel_asignacion,
                'asignacion_multiple' => $request->has('asignacion_multiple') ? 1 : 0,
                'estatus' => 'Activo',
            ]);

            // Guardar permisos si existen
            if ($request->has('permisos')) {
                foreach ($request->permisos as $modulo => $acciones) {
                    foreach ($acciones as $permiso) {
                        PerfilPermiso::create([
                            'id_perfil' => $perfil->id_perfil,
                            'id_permiso' => self::mapPermisoToId($permiso),
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear perfil: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar un perfil existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_perfil' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        try {
            $perfil = Perfil::findOrFail($id);
            $perfil->update([
                'nombre_perfil' => $request->nombre_perfil,
                'descripcion' => $request->descripcion,
                'superior' => $request->superior,
                'nivel_asignacion' => $request->nivel_asignacion,
                'asignacion_multiple' => $request->has('asignacion_multiple') ? 1 : 0,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Cambiar el estatus del perfil (Activo/Inactivo) sin recargar la página.
     */
     public function toggleEstatus($id_perfil)
    {
        $perfil = Perfil::find($id_perfil);

        if (!$perfil) {
            return response()->json(['success' => false, 'message' => 'Perfil no encontrado'], 404);
        }

        $perfil->estatus = $perfil->estatus === 'Activo' ? 'Inactivo' : 'Activo';
        $perfil->save();

        return response()->json([
            'success' => true,
            'nuevo_estatus' => $perfil->estatus
        ]);
    }

    /**
     * Eliminar (bloquear o desactivar) un perfil.
     */
    public function destroy($id)
    {
        try {
            $perfil = Perfil::findOrFail($id);
            $perfil->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Guardar los permisos asignados a un perfil.
     */
    public function guardarPermisos(Request $request, $id)
    {
        try {
            PerfilPermiso::where('id_perfil', $id)->delete();

            foreach ($request->permisos ?? [] as $permisoId) {
                PerfilPermiso::create([
                    'id_perfil' => $id,
                    'id_permiso' => $permisoId
                ]);
            }

            return response()->json(['mensaje' => '✅ Permisos guardados correctamente.']);
        } catch (\Throwable $e) {
            Log::error('Error al guardar permisos: ' . $e->getMessage());
            return response()->json(['error' => '❌ Error al guardar permisos.'], 500);
        }
    }

    /**
     * Mapear nombre de permiso a ID.
     */
    private static function mapPermisoToId($permiso)
    {
        return Permisoglobal::where('accion', $permiso)->value('id_permiso');
    }
}
