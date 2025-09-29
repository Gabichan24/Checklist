@extends('layouts.table')

@section('content')
<div x-data="{ open: false }" class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Regiones</h2>
        <button @click="open = true" class="bg-purple-600 text-gray px-4 py-2 rounded-lg hover:bg-purple-700">
            + Crear
        </button>
    </div>

    <!-- Acciones superiores (Exportar, Buscar) -->
    <div class="flex justify-between mb-4">
        <div class="flex gap-2">
            <button onclick="window.location='{{ route('regiones.export.xlsx') }}'" class="bg-blue-500 hover:bg-blue-600 text-gray px-3 py-2 rounded-lg text-sm">
    XLSX
</button>

<button onclick="window.location='{{ route('regiones.export.csv') }}'" class="bg-blue-400 hover:bg-blue-500 text-gray px-3 py-2 rounded-lg text-sm">
    CSV
</button>
        </div>
        <!-- (Aquí puedes agregar un buscador en el futuro) -->
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs text-center">
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Estado</th>
                    <th class="px-4 py-2 border">Estatus</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($regiones as $region)
                    <tr class="text-center">
                        <!-- Número consecutivo -->
                        <td class="px-4 py-2 border">{{ $region->id_region }}</td>

                        <!-- Nombre -->
                        <td class="px-4 py-2 border">{{ $region->nombre }}</td>

                        <!-- Estado -->
                        <td class="px-4 py-2 border">{{ $region->estados }}</td>

                        <!-- Estatus -->
                        <td class="px-4 py-2 border">
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">
                                {{ $region->estatus ?? 'Activo' }}
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="px-4 py-2 border flex justify-center gap-2">
                            <a href="#" class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded">✏️</a>
                            <a href="#" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">🔒</a>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="5" class="px-4 py-2 border">No hay regiones</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Crear Región -->
    <div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96 relative">
            <h3 class="text-lg font-bold mb-4">Crear Región</h3>
            <form action="{{ route('regiones.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Estado</label>
                    <select name="estado" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->nombre }}">{{ $estado->nombre }}</option>
                        @endforeach
                    </select>
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

