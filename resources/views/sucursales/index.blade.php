@extends('layouts.table')

@section('table-title', 'Sucursales')

@section('table-actions')
    <div class="flex space-x-2">
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">
            Crear Sucursal
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
        <th class="px-4 py-2 border">Clave</th>
        <th class="px-4 py-2 border">Descripción</th>
        <th class="px-4 py-2 border">Zona</th>
        <th class="px-4 py-2 border">Estatus</th>
        <th class="px-4 py-2 border">Acciones</th>
    </tr>
@endsection

@section('table-body')
    <tr class="text-center">
        <td class="px-4 py-2 border">1</td>
