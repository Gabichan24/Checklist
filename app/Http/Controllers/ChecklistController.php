<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Item;
use App\Models\Categoria;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PlantillasExport;

// LIBRERÍAS PARA LEER ARCHIVOS
use PhpOffice\PhpSpreadsheet\IOFactory as ExcelIOFactory;

class ChecklistController extends Controller
{
    /* ============================================================
       LISTA DE CHECKLISTS
    ============================================================ */
    public function index()
    {
        $categorias = Categoria::whereRaw('LOWER(estatus) = ?', ['activo'])->get();
        $areas = Area::whereRaw('LOWER(estatus) = ?', ['activo'])->get();
        $checklists = Checklist::with(['categoria', 'area', 'items'])->get();

        return view('checklist.index', compact('checklists', 'categorias', 'areas'));
    }

    /* ============================================================
       CREAR CHECKLIST
    ============================================================ */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_checklist' => 'required|string|max:255',
            'id_categoria' => 'required|integer|exists:categoria,id_categoria',
            'id_area' => 'required|integer|exists:area,id_area'
        ]);

        try {
            $usuario = Auth::user();

            $checklist = Checklist::create([
                'nombre_checklist' => trim($request->nombre_checklist),
                'id_categoria' => $request->id_categoria,
                'id_area' => $request->id_area,
                'puntuacion_total' => 0,
                'creado_por'       => $usuario->id_usuario,
                'estado'           => 'Creado',
                'id_usuario'       => $usuario->id_usuario,
            ]);

            return response()->json([
                'success' => true,
                'checklist_id' => $checklist->id_checklist
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al crear el checklist: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ============================================================
       CARGAR PREGUNTAS DESDE EXCEL
    ============================================================ */
    public function cargarPreguntas(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            $archivo = $request->file('archivo');
            $documento = ExcelIOFactory::load($archivo->getRealPath());
            $filas = $documento->getActiveSheet()->toArray();
            $preguntas = [];

            foreach ($filas as $index => $fila) {
                if ($index === 0) continue; // saltar encabezado
                if (!empty($fila[0])) {
                    $preguntas[] = [
                        'texto'       => trim($fila[0]),
                        'tipo_item'   => $fila[1] ?? 'texto',
                        'tipo_evidencias' => $fila[2] ?? 'comentario',
                        'esperado'    => $fila[3] ?? '',
                        'puntuacion'  => $fila[4] ?? 0,
                        'obligatorio' => strtolower($fila[5] ?? '') === 'si' ? 1 : 0,
                        'plan_accion' => strtolower($fila[6] ?? '') === 'si' ? 1 : 0,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'preguntas' => $preguntas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al leer el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /* ============================================================
       GUARDAR TODAS LAS PREGUNTAS EN BD
    ============================================================ */
    public function guardarPreguntas(Request $request)
{
    $request->validate([
        'checklist_id' => 'required|integer|exists:tabla_checklist,id_checklist',
        'preguntas'    => 'required|array|min:1'
    ]);

    $checklist_id = $request->checklist_id;
    $preguntas = $request->preguntas;

    DB::beginTransaction();
    try {
        foreach ($preguntas as $p) {
            // Normalizar datos y evitar errores
            $nombre_item     = isset($p['texto']) ? trim($p['texto']) : null;
            $tipo_item       = $p['tipo_item'] ?? 'texto';
            $tipo_evidencias = $p['tipo_evidencias'] ?? 'comentario';
            $puntuacion      = isset($p['puntuacion']) ? intval($p['puntuacion']) : 0;
            $obligatorio     = isset($p['obligatorio']) ? intval($p['obligatorio']) : 0;
            $plan_accion     = isset($p['plan_accion']) ? intval($p['plan_accion']) : 0;

            if (!$nombre_item) continue; // saltar preguntas sin texto

            Item::create([
                'id_checklist'    => $checklist_id,
                'nombre_item'     => $nombre_item,
                'tipo_item'       => $tipo_item,
                'tipo_evidencias' => $tipo_evidencias,
                'puntuacion'      => $puntuacion,
                'obligatorio'     => $obligatorio,
                'plan_accion'     => $plan_accion,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => count($preguntas) . ' preguntas guardadas correctamente.'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error guardando preguntas: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Error interno al guardar preguntas: ' . $e->getMessage()
        ], 500);
    }
}

    /* ============================================================
       ELIMINAR CHECKLIST + SUS PREGUNTAS
    ============================================================ */
    public function destroy($id)
    {
        try {
            $checklist = Checklist::findOrFail($id);
            Item::where('id_checklist', $id)->delete();
            $checklist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Checklist eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /* ============================================================
       OBTENER ITEMS DE UN CHECKLIST
    ============================================================ */
    public function items($id)
    {
        try {
            $items = DB::table('items')
                ->where('id_checklist', $id)
                ->get();

            return response()->json([
                'success' => true,
                'items' => $items
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
public function misPlantillas()
{
    // Obtener todas las plantillas creadas
    $plantillas = Checklist::with(['categoria', 'area'])
        ->orderBy('id_checklist', 'desc')
        ->get();

    return view('mis_plantillas.index', compact('plantillas'));
}
public function verChecklist($id)
{
    $checklist = Checklist::with(['categoria', 'area', 'items'])->findOrFail($id);

    return view('checklist.ver', compact('checklist'));
}

public function actualizarChecklist(Request $request, $id)
{
    $checklist = Checklist::findOrFail($id);

    $checklist->nombre_checklist = $request->nombre_checklist;
    $checklist->id_categoria = $request->id_categoria;
    $checklist->id_area = $request->id_area;
    $checklist->save();

    // Actualizar items
    if ($request->items) {
        foreach ($request->items as $itemId => $nombre) {
            Item::where('id_item', $itemId)->update([
                'nombre_item' => $nombre
            ]);
        }
    }

    return redirect()->route('checklist.misplantillas')
                     ->with('success', 'Checklist actualizado correctamente');
}
public function verChecklistJson($id)
{
    $checklist = Checklist::with(['categoria', 'area', 'items'])->findOrFail($id);
    $items = Item::where('id_checklist', $id)->get();

    return response()->json([
        'checklist' => [
            'nombre_checklist' => $checklist->nombre_checklist,
            'categoria' => $checklist->categoria->nombre ?? '—',
            'area' => $checklist->area->nombre ?? '—',
            'puntuacion_total' => $checklist->puntuacion_total,
        ],
        'items' => $items
    ]);
}

public function duplicar($id)
{
    $original = Checklist::findOrFail($id);
    $items = Item::where('id_checklist', $id)->get();

    // duplicar encabezado
    $nuevo = $original->replicate();
    $nuevo->nombre_checklist = $original->nombre_checklist . " (Copia)";
    $nuevo->save();

    // duplicar preguntas
    foreach ($items as $i) {
        $newItem = $i->replicate();
        $newItem->id_checklist = $nuevo->id_checklist;
        $newItem->save();
    }

    return redirect()->route('checklist.misPlantillas')
                     ->with('success', 'Plantilla duplicada correctamente');
}
public function exportar($id)
{
    return Excel::download(new PlantillasExport($id), 'plantilla-'.$id.'.xlsx');
}
public function edit($id)
{
    $plantilla = Checklist::with('items')->findOrFail($id);
    $categorias = Categoria::all();
    $areas = Area::all();

    return view('checklist.editar', [
        'modo' => 'editar',
        'plantilla' => $plantilla,
        'items' => $plantilla->items,
        'categorias' => $categorias,
        'areas' => $areas
    ]);
}
public function eliminarItem($id)
{
    $item = Item::findOrFail($id);
    $item->delete();
    return response()->json(['success' => true]);
}

public function editarItem($id)
{
    $item = Item::findOrFail($id);
    return response()->json($item);
}

    // Mostrar la plantilla y preguntas
    public function editar($id)
{
    $checklist = DB::table('tabla_checklist')->where('id_checklist', $id)->first();
    $items = DB::table('items')->where('id_checklist', $id)->get();
    $categorias = DB::table('categoria')->get();
    $areas = DB::table('area')->get();

    return view('checklist.editar', compact('checklist', 'items', 'categorias', 'areas'));
}


    // Guardar checklist y preguntas
    public function guardar(Request $request, $id)
    {
        $checklist = Checklist::findOrFail($id);

        $checklist->nombre_checklist = $request->nombre_checklist;
        $checklist->id_categoria = $request->id_categoria;
        $checklist->id_area = $request->id_area;
        $checklist->save();

        foreach ($request->preguntas as $p) {
            if (isset($p['id_item'])) {
                $item = Item::find($p['id_item']);
                if ($item) {
                    $item->nombre_item = $p['nombre_item'];
                    $item->puntuacion = $p['puntuacion'];
                    $item->plan_accion = $p['plan_accion'];
                    $item->tipo_evidencias = $p['tipo_evidencias'];
                    $item->save();
                }
            } else {
                Item::create([
                    'id_checklist' => $checklist->id_checklist,
                    'nombre_item' => $p['nombre_item'],
                    'puntuacion' => $p['puntuacion'],
                    'plan_accion' => $p['plan_accion'],
                    'tipo_evidencias' => $p['tipo_evidencias'],
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
    public function subirExcel(Request $request, $id)
{
    $request->validate([
        'archivo' => 'required|file|mimes:xlsx,xls'
    ]);

    try {
        $archivo = $request->file('archivo');
        $documento = ExcelIOFactory::load($archivo->getRealPath());
        $filas = $documento->getActiveSheet()->toArray();
        $preguntas = [];

        foreach ($filas as $index => $fila) {
            if ($index === 0) continue;

            if (!empty($fila[0])) {
                $preguntas[] = [
                    'nombre_item'     => trim($fila[0]),
                    'tipo_item'       => $fila[1] ?? 'texto',
                    'tipo_evidencias' => $fila[2] ?? 'ninguna',
                    'puntuacion'      => intval($fila[3] ?? 0),
                    'plan_accion'     => strtolower($fila[4] ?? '') === 'si' ? 1 : 0,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'preguntas' => $preguntas
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}
public function aprobar($id)
{
    // Buscar checklist por ID
    $checklist = Checklist::find($id);

    if (!$checklist) {
        return response()->json(['error' => 'Checklist no encontrado'], 404);
    }

    // Cambiar estado
    $checklist->estado = 'Aprobado';
    $checklist->save();

    // Retornar respuesta JSON para el frontend
    return response()->json([
        'success' => true,
        'estado' => $checklist->estado
    ]);
}

}