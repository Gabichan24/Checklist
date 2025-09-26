@extends('layouts.table') {{-- aquí indicas que quieres usar table.blade.php como layout --}}

@section('content')
<div x-data="{ open: false }" class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Regiones</h2>
        <button 
            @click="open = true" 
            class="bg-purple-600 text-gray px-4 py-2 rounded-lg hover:bg-purple-700 flex items-center gap-2"
        >
           + Crear
        </button>
    </div>

    <!-- Acciones superiores (Exportar, Buscar) -->
    <div class="flex justify-between mb-3">
        <div class="flex gap-2">
            <!-- Botón Exportar Excel (temporal) -->
            <button class="bg-blue-500 hover:bg-blue-600 text-gray px-3 py-2 rounded-lg text-sm cursor-not-allowed opacity-50">
               XLSX
            </button>
            <!-- Botón Exportar CSV (temporal) -->
            <button class="bg-blue-400 hover:bg-blue-500 text-gray px-3 py-2 rounded-lg text-sm cursor-not-allowed opacity-50">
               CSV
            </button>
        </div>

        <!-- Buscar -->
        <div>
            <input type="text" placeholder="Buscar..." 
                   class="border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-200"/>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Descripción</th>
                    <th class="px-4 py-2 border">Estados</th>
                    <th class="px-4 py-2 border">Estatus</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td class="px-4 py-2 border">1</td>
                    <td class="px-4 py-2 border">Campeche</td>
                    <td class="px-4 py-2 border">Campeche</td>
                    <td class="px-4 py-2 border">
                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Activo</span>
                    </td>
                    <td class="px-4 py-2 border flex justify-center gap-2">
                        <!-- Editar -->
                        <a href="#" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded">
                            ✏️
                        </a>
                        <!-- Bloquear -->
                        <a href="#" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">
                            🔒
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="flex items-center justify-between mt-4 text-sm text-gray-500">
        <div>
            <select class="border rounded px-2 py-1 text-sm">
                <option>10</option>
                <option>25</option>
                <option>50</option>
            </select>
        </div>
        <div>
            Mostrando 1 a 1 de 1 registros
        </div>
    </div>

    <!-- Modal -->
    <div 
        x-show="open"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-lg p-6 w-96 relative">
            <h3 class="text-lg font-bold mb-4">Crear Región</h3>
            
            <!-- Formulario de ejemplo (sin DB) -->
            <form @submit.prevent="alert('¡Región creada!'); open = false">
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Estado</label>
                    <!-- Menú desplegable de regiones -->
    <div class="mb-4">
        <label for="regiones" class="block mb-2 font-medium text-gray-700">Selecciona una región:</label>
        <select id="regiones" class="border rounded px-3 py-2 w-full">
            <option value="">-- Selecciona --</option>
            @foreach($regiones as $region)
                <option value="{{ $region['id'] }}">{{ $region['nombre'] }}</option>
            @endforeach
        </select>
    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-gray hover:bg-purple-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
