@extends('layouts.table')

@section('content')
<div class="container" x-data="{
        modalOpen: false,
        tipoPregunta: 'si_no',
        respuestaEsperada: 'si'
    }">

    <h2 class="page-title">Crear Plantilla</h2>

    <form action="{{ route('checklist.store') }}" method="POST">
        @csrf

        <!-- Nombre del Checklist -->
        <div class="form-group">
            <label for="nombre_checklist">Nombre del Checklist</label>
            <input type="text" id="nombre_checklist" name="nombre_checklist" 
                placeholder="Ingresa el nombre del checklist" required>
        </div>

        <!-- Tabla Selección Categoría / Área / Puntuación -->
        <div class="table-wrapper">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Selecciona Categoría</th>
                        <th>Selecciona Área</th>
                        <th>Puntuación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select id="id_categoria" name="id_categoria" required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categorias as $categoria)
                                    @if(strtolower($categoria->estatus) === 'activo')
                                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select id="id_area" name="id_area" required>
                                <option value="">Selecciona un área</option>
                                @foreach($areas as $area)
                                    @if(strtolower($area->estatus) === 'activo')
                                        <option value="{{ $area->id_area }}">{{ $area->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" id="puntuacion_total" name="puntuacion_total" value="0" min="0" required>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Botones: Guardar + Pregunta -->
        <div class="form-actions" style="display: flex; gap: 10px; align-items: center;">
            <button type="submit" class="btn btn-blue">Guardar</button>

            <!-- BOTÓN NUEVO QUE ABRE EL MODAL -->
            <button type="button" class="btn btn-green" @click="modalOpen = true"
                style="background-color:#28a745; color:white; padding:8px 14px; border-radius:5px;">
                + Pregunta
            </button>
        </div>
    </form>



    <!-- ========== MODAL ========== -->
    <div 
        x-show="modalOpen"
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg p-6 w-[500px] relative">

            <!-- Cerrar -->
            <button 
                @click="modalOpen = false"
                class="absolute top-3 right-3 text-gray-500 text-xl"
            >✕</button>

            <!-- SELECT ENCABEZADO -->
            <div class="mb-4">
                <label class="font-semibold text-gray-700">Tipo de pregunta</label>
                <select 
                    x-model="tipoPregunta"
                    class="w-full border rounded px-3 py-2 mt-1"
                >
                    <option value="texto">Pregunta - Texto</option>
                    <option value="si_no">Pregunta - SI/NO</option>
                </select>
            </div>

            <!-- DESCRIPCIÓN -->
            <div class="mb-4">
                <label class="font-semibold text-gray-700">Descripción</label>
                <input 
                    type="text"
                    placeholder="Ingresa la pregunta"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <!-- TIPO DE EVIDENCIA + PUNTUACIÓN -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="font-semibold text-gray-700">Tipo de evidencia</label>
                    <select class="w-full border rounded px-3 py-2 mt-1">
                        <option>Sin evidencia</option>
                        <option>Texto</option>
                        <option>Foto</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold text-gray-700">Puntuación</label>
                    <div class="flex items-center gap-2 mt-1">
                        <button class="px-3 py-1 bg-gray-200 rounded">-</button>
                        <span>0</span>
                        <button class="px-3 py-1 bg-gray-200 rounded">+</button>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN SI/NO -->
            <div x-show="tipoPregunta === 'si_no'" class="mb-6" x-transition>
                <label class="font-semibold text-gray-700">Seleccione la respuesta esperada</label>

                <div class="flex items-center gap-6 mt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" x-model="respuestaEsperada" value="si">
                        <span>SI</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" x-model="respuestaEsperada" value="no">
                        <span>NO</span>
                    </label>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="flex justify-end mt-6">
                <button 
                    @click="modalOpen = false"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                >Cerrar</button>
            </div>

        </div>
    </div>
</div>
@endsection