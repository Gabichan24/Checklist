@extends('layouts.table')

@section('content')
<div 
    x-data="{
        open: false,
        openModal: false,
        zonaId: null,
        nombre: '',
        regionId: '',
        search: '',
        zonas: @js($zonas),
        get filtered() {
            if (this.search === '') return this.zonas;
            return this.zonas.filter(z =>
                z.nombre.toLowerCase().includes(this.search.toLowerCase()) ||
                z.region_nombre.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }"
class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6">
    <!-- Encabezado --> 
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Zonas</h2>
        <button @click="open = true" 
        class="bg-purple-600 text-gray px-4 py-2 rounded-lg hover:bg-purple-700">
            + Crear
        </button>
    </div>

    <!-- Acciones superiores -->
<div class="flex justify-between items-center mb-4 w-full">
    <!-- Botones de exportación -->
    <div class="flex items-center gap-2">
        <button 
            onclick="window.location='{{ route('zonas.export.xlsx') }}'" 
            class="bg-blue-500 hover:bg-blue-600 text-gray px-3 py-2 rounded-lg text-sm">
            XLSX
        </button>
        <button 
            onclick="window.location='{{ route('zonas.export.csv') }}'" 
            class="bg-blue-400 hover:bg-blue-500 text-gray px-3 py-2 rounded-lg text-sm">
            CSV
        </button>
    </div>

    <!-- Buscador -->
    <div class="relative flex items-center w-1/3">
        <input
            id="search"
            type="search"
            placeholder="Buscar zona o región..."
            x-model="search"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder:text-gray-400"
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
                    <th class="px-4 py-2 border">Región</th>
                    <th class="px-4 py-2 border">Estatus</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="zona in filtered" :key="zona.id_zona">
                    <tr class="text-center">
                        <td class="px-4 py-2 border" x-text="zona.id_zona"></td>
                        <td class="px-4 py-2 border" x-text="zona.nombre"></td>
                        <td class="px-4 py-2 border" x-text="zona.region_nombre"></td>
                        <td class="px-4 py-2 border">
                            <span 
    class="px-2 py-1 rounded-full text-xs"
    :class="zona.estatus === 'Activo' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
    <span x-text="zona.estatus"></span> <!-- usa directamente el texto -->
</span>
                            </span>
                        </td>
                        <td class="px-4 py-2 border flex justify-center gap-2">
                            <!-- Botón Editar -->
                            <button 
                                @click="openModal = true; zonaId = zona.id_zona; nombre = zona.nombre; regionId = zona.id_region"
                                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700"
                                :disabled="zona.estatus === 'Inactivo'"
                                :class="zona.estatus === 'Inactivo' ? 'opacity-50 cursor-not-allowed' : ''">
                                ✏️
                            </button>

                            <!-- Botón Bloquear/Desbloquear -->
                            <form :action="`/zonas/toggle/${zona.id_zona}`" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                            class="px-3 py-1 rounded"
                            :class="zona.estatus === 'Activo' 
                            ? 'bg-gray-400 hover:bg-gray-500' 
                            : 'bg-yellow-400 hover:bg-yellow-500'">
                            <span x-text="zona.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                            </button>
                        </td>
                    </tr>
                </template>

                <tr x-show="filtered.length === 0">
                    <td colspan="5" class="px-4 py-2 border text-gray-500">No hay zonas</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Crear/Editar -->
    <div x-show="open || openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-lg p-6 w-96 relative">
            <h3 class="text-lg font-bold mb-4" x-text="openModal ? 'Editar Zona' : 'Crear Zona'"></h3>

            <form :action="openModal ? `{{ url('zonas') }}/${zonaId}` : '{{ route('zonas.store') }}'" method="POST">
                @csrf
                <template x-if="openModal">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" x-model="nombre" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Región</label>
                    <select name="id_region" x-model="regionId" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($regiones as $region)
                            <option value="{{ $region->id_region }}">{{ $region->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false; openModal = false" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-gray hover:bg-purple-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

