@extends('layouts.table')

@section('table-title', 'Categorías')

@section('table-actions')
    <div class="flex space-x-2">
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">
            Crear Categoría
        </button>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">
            Descargar XLSX
        </button>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700">
            Descargar CSV
        </button>
    </div>
@endsection

@section('table-head')
    <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
        <th class="px-4 py-2 border">Número</th>
        <th class="px-4 py-2 border">Descripción</th>
        <th class="px-4 py-2 border">Nivel de asignación (usuario)</th>
        <th class="px-4 py-2 border">Estatus</th>
        <th class="px-4 py-2 border">Acciones</th>
    </tr>
@endsection

@section('table-body')
    <tr>
        <td class="px-4 py-2 border">1</td>
        <td class="px-4 py-2 border">Categoría de ejemplo</td>
        <td class="px-4 py-2 border">Usuario básico</td>
        <td class="px-4 py-2 border">
            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Activo</span>
        </td>
        <td class="px-4 py-2 border flex space-x-2">
            <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</button>
            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Bloquear</button>
        </td>
    </tr>
@endsection

@section('table-pagination')
    <span>Página 1 de 5</span>
@endsection

@section('table-count')
    1 de 20 registros
@endsection
