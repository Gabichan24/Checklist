@extends('layouts.table')

@section('content')
<div x-data="{
        open: false, 
        openModal: false, 
        categoriaId: null, 
        nombre: '', 
        search: '',
        categorias: @js($categorias),
        get filtered() {
            if (this.search === '') {
                return this.categorias;
            }
            return this.categorias.filter(c => 
                c.nombre.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }" 
    class="max-w-5xl mx-auto bg-white shadow rounded-lg p-6">

    <!-- Encabezado -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Categorías</h2>
        <button @click="open = true" class="bg-purple-600 text-gray px-4 py-2 rounded-lg hover:bg-purple-700">
            + Crear
        </button>
    </div>

    <!-- Acciones superiores -->
    <div class="flex justify-between items-center mb-4 w-full">
        <!-- Botones de exportación -->
        <div class="flex gap-2">
            <button onclick="window.location='{{ route('categorias.export.xlsx') }}'" 
                    class="bg-blue-500 hover:bg-blue-600 text-gray px-3 py-2 rounded-lg text-sm">
                XLSX
            </button>
            <button onclick="window.location='{{ route('categorias.export.csv') }}'" 
                    class="bg-blue-400 hover:bg-blue-500 text-gray px-3 py-2 rounded-lg text-sm">
                CSV
            </button>
        </div>

        <!-- Buscador -->
        <div class="flex gap-2">
            <div class="relative flex items-center">
                <input
                    id="search"
                    type="search"
                    placeholder="Escribe para buscar..."
                    x-model="search"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm 
                           focus:ring-2 focus:ring-purple-500 focus:border-purple-500 
                           placeholder:text-gray-400"
                />
            </div>
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
                <template x-for="categoria in filtered" :key="categoria.id_categoria">
                    <tr class="text-center">
                        <td class="px-4 py-2 border" x-text="categoria.id_categoria"></td>
                        <td class="px-4 py-2 border" x-text="categoria.nombre"></td>
                        <td class="px-4 py-2 border">
                            <span 
                                class="px-2 py-1 rounded-full text-xs"
                                :class="categoria.estatus === 'Activo' 
                                        ? 'bg-green-100 text-green-600' 
                                        : 'bg-red-100 text-red-600'">
                                <span x-text="categoria.estatus"></span>
                            </span>
                        </td>
                        <td class="px-4 py-2 border flex justify-center gap-2">
                            <!-- Botón Editar -->
                            <button 
                                @click="openModal = true; categoriaId=categoria.id_categoria; nombre=categoria.nombre"
                                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700"
                                :disabled="categoria.estatus === 'Inactivo'"
                                :class="categoria.estatus === 'Inactivo' ? 'opacity-50 cursor-not-allowed' : ''">
                                ✏️
                            </button>

                            <!-- Botón Bloquear/Desbloquear -->
                            <form :action="'/categorias/toggle/' + categoria.id_categoria" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                    class="text-white px-3 py-1 rounded"
                                    :class="categoria.estatus === 'Activo' 
                                            ? 'bg-gray-400 hover:bg-gray-500' 
                                            : 'bg-yellow-400 hover:bg-yellow-500'">
                                    <span x-text="categoria.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>

                <tr x-show="filtered.length === 0">
                    <td colspan="4" class="px-4 py-2 border text-center text-gray-500">No hay categorías</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Editar -->
    <div x-show="openModal" 
         class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
         x-cloak>
        <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
            <h2 class="text-xl font-semibold mb-4">Editar Categoría</h2>
            <form method="POST" :action="'/categorias/' + categoriaId">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nombre</label>
                    <input type="text" name="nombre" x-model="nombre"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openModal = false" 
                            class="bg-gray-400 hover:bg-gray-500 text-gray px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-gray px-4 py-2 rounded-lg">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Crear -->
    <div x-show="open" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         x-cloak>
        <div class="bg-white rounded-lg p-6 w-96 relative">
            <h3 class="text-lg font-bold mb-4">Crear Categoría</h3>
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false" 
                            class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 rounded bg-purple-600 text-gray hover:bg-purple-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
