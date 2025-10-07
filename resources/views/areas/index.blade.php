@extends('layouts.table')

@section('content')
<div 
    x-data="{
        open: false,
        openModal: false,
        areaId: null,
        nombre: '',
        search: '',
        areas: @js($areas),
        get filtered() {
            if (this.search === '') return this.areas;
            return this.areas.filter(a =>
                a.nombre.toLowerCase().includes(this.search.toLowerCase()) ||
                a.estatus.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }"
    class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Áreas</h2>
        <button @click="open = true" class="bg-green-600 text-gray px-4 py-2 rounded-lg hover:bg-green-700">
            + Crear Área
        </button>
    </div>

    <!-- Acciones superiores (Exportar, Buscar) -->
    <div class="flex justify-between items-center mb-4 w-full">
        <!-- Botón Exportar Excel -->
<a href="{{ route('areas.export.excel') }}" 
   class="bg-blue-500 hover:bg-blue-600 text-gray px-3 py-2 rounded-lg text-sm">
   XLSX
</a>

<!-- Botón Exportar CSV -->
<a href="{{ route('areas.export.csv') }}" 
   class="bg-blue-400 hover:bg-blue-500 text-gray px-3 py-2 rounded-lg text-sm">
   CSV
</a>
        <!-- Buscador -->
        <div class="relative flex items-center w-1/3">
            <input
                type="search"
                placeholder="Buscar área..."
                x-model="search"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder:text-gray-400"
            />
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs text-center">
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Estatus</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="area in filtered" :key="area.id_area">
                    <tr class="text-center">
                        <td class="px-4 py-2 border" x-text="area.id_area"></td>
                        <td class="px-4 py-2 border" x-text="area.nombre"></td>
                        <td class="px-4 py-2 border">
                            <span 
                                class="px-2 py-1 rounded-full text-xs"
                                :class="area.estatus === 'Activo' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
                                <span x-text="area.estatus"></span>
                            </span>
                        </td>
                        <td class="px-4 py-2 border flex justify-center gap-2">
                            <!-- Editar -->
                            <button 
                                @click="openModal = true; areaId = area.id_area; nombre = area.nombre"
                                class="bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700"
                                :disabled="area.estatus === 'Inactivo'"
                                :class="area.estatus === 'Inactivo' ? 'opacity-50 cursor-not-allowed' : ''">
                                ✏️
                            </button>

                            <!-- Bloquear / Desbloquear -->
                            <form :action="`/areas/toggle/${area.id_area}`" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="px-3 py-1 rounded"
                                    :class="area.estatus === 'Activo' 
                                    ? 'bg-gray-400 hover:bg-gray-500' 
                                    : 'bg-yellow-400 hover:bg-yellow-500'">
                                    <span x-text="area.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            
                        </td>
                    </tr>
                </template>

                <tr x-show="filtered.length === 0">
                    <td colspan="4" class="px-4 py-2 border text-gray-500">No hay áreas registradas</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Crear / Editar -->
    <div x-show="open || openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-lg p-6 w-96 relative">
            <h3 class="text-lg font-bold mb-4" x-text="openModal ? 'Editar Área' : 'Crear Área'"></h3>

            <form :action="openModal ? `{{ url('areas') }}/${areaId}` : '{{ route('areas.store') }}'" method="POST">
                @csrf
                <template x-if="openModal">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" x-model="nombre" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false; openModal = false" 
                            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded bg-green-600 text-gray hover:bg-green-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
