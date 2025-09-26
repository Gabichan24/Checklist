@extends('layouts.table') {{-- usa la plantilla tablet.blade.php --}}

@section('content')
<div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Zonas</h2>
        <a href="{{ route('zonas.create') }}" 
           @click="openZona = true" 
    class="bg-green-600 text-gray px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2"
>
           + Crear
        </a>
    </div>

     <!-- Acciones superiores (Exportar, Buscar) -->
<div class="flex justify-between mb-3">
    <div class="flex gap-2">
        <!-- Botón Exportar Excel (temporal) -->
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm cursor-not-allowed opacity-50">
           XLSX
        </button>
        <!-- Botón Exportar CSV (temporal) -->
        <button class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-2 rounded-lg text-sm cursor-not-allowed opacity-50">
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
                    <th class="px-4 py-2 border">Estado</th>
                    <th class="px-4 py-2 border">Región</th>
                    <th class="px-4 py-2 border">Estatus</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td class="px-4 py-2 border">1</td>
                    <td class="px-4 py-2 border">Zona Norte</td>
                    <td class="px-4 py-2 border">Campeche</td>
                    <td class="px-4 py-2 border">Región 1</td>
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

</div>
<!-- Modal Crear Zona -->
<div 
    x-data="{ openZona: false }" 
    x-show="openZona" 
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
>
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
        <h2 class="text-xl font-bold mb-4">Crear Zona</h2>

        <!-- Formulario -->
        <form>
            <!-- Nombre o descripción -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="nombre">Nombre o Descripción</label>
                <input type="text" id="nombre" name="nombre" 
                       class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"/>
            </div>

            <!-- Estado (select con regiones, ejemplo de cómo enlazar) -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="estado">Estado</label>
                <select id="estado" name="estado" 
                        class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200">
                    <option value="">Selecciona un estado</option>
                    @foreach($regiones as $region)
                        <option value="{{ $region['nombre'] }}">{{ $region['nombre'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-2">
                <button type="button" @click="openZona = false" 
                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </button>
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>

        <!-- Cerrar modal con X -->
        <button @click="openZona = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            ✕
        </button>
    </div>
</div>
@endsection
