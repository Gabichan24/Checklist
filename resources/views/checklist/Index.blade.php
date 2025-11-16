@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded-lg p-10 mt-6">

    <h2 class="text-2xl font-bold mb-6 text-gray-700">Crear Plantilla</h2>

    <form action="{{ route('checklist.store') }}" method="POST">
        @csrf

        <!-- Campo: Nombre del Checklist -->
        <div class="mb-8 mx-8">
            <label for="nombre_checklist" class="block text-gray-700 font-semibold mb-3">
                Nombre del Checklist
            </label>
            <input type="text" id="nombre_checklist" name="nombre_checklist"
                class="border rounded px-4 py-2 text-sm focus:ring focus:ring-blue-200 w-full max-w-3xl"
                placeholder="Ingresa el nombre del checklist" required>
        </div>

        <!-- Selección de Categoría, Área y Puntuación -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full text-gray-700">
                <thead>
                    <tr class="text-left">
                        <th class="px-4 py-2 font-semibold">Selecciona Categoría</th>
                        <th class="px-4 py-2 font-semibold">Selecciona Área</th>
                        <th class="px-4 py-2 font-semibold">Puntuación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2">
                            <select id="id_categoria" name="id_categoria"
                                class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200" required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categorias as $categoria)
                                    @if(strtolower($categoria->estatus) === 'activo')
                                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-2">
                            <select id="id_area" name="id_area"
                                class="w-full border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200" required>
                                <option value="">Selecciona un área</option>
                                @foreach($areas as $area)
                                    @if(strtolower($area->estatus) === 'activo')
                                        <option value="{{ $area->id_area }}">{{ $area->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-2">
                            <input type="number" id="puntuacion_total" name="puntuacion_total" value="0" min="0"
                                class="w-full border rounded text-center px-3 py-2 text-sm focus:ring focus:ring-blue-200"
                                placeholder="0" required>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Botón Guardar -->
        <div class="flex justify-end mt-6">
            <button type="submit"
                class="px-8 py-3 bg-blue-600 text-gray font-medium rounded hover:bg-blue-700 transition">
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
