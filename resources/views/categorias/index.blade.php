@extends('layouts.table')

@section('content')
<div 
    x-data="{
        openCreate: false,
        openEdit: false,
        categoriaId: null,
        nombre: '',
        search: '',
        categorias: @js($categorias),
        get filtered() {
            if (this.search === '') return this.categorias;
            return this.categorias.filter(c => c.nombre.toLowerCase().includes(this.search.toLowerCase()));
        }
    }" 
    class="container">

    <!-- Encabezado -->
    <div class="header">
        <h2>Categorías</h2>
        <button @click="openCreate = true" class="btn btn-purple">+ Crear</button>
    </div>

    <!-- Acciones superiores -->
    <div class="actions">
        <div class="export-buttons">
            <button onclick="window.location='{{ route('categorias.export.xlsx') }}'" class="btn btn-blue">XLSX</button>
            <button onclick="window.location='{{ route('categorias.export.csv') }}'" class="btn btn-lightblue">CSV</button>
        </div>
        <input type="search" placeholder="Buscar categoría..." x-model="search" class="search-input">
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <table class="areas-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="categoria in filtered" :key="categoria.id_categoria">
                    <tr>
                        <td x-text="categoria.id_categoria"></td>
                        <td x-text="categoria.nombre"></td>
                        <td>
                            <span :class="categoria.estatus === 'Activo' ? 'badge-active' : 'badge-inactive'" x-text="categoria.estatus"></span>
                        </td>
                        <td>
                            <button @click="openEdit = true; categoriaId = categoria.id_categoria; nombre = categoria.nombre" 
                                    :disabled="categoria.estatus === 'Inactivo'" class="btn btn-purple">
                                ✏️
                            </button>
                            <form :action="'/categorias/toggle/' + categoria.id_categoria" method="POST" style="display:inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-toggle" :class="categoria.estatus === 'Activo' ? 'btn-gray' : 'btn-yellow'">
                                    <span x-text="categoria.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>
                <tr x-show="filtered.length === 0">
                    <td colspan="4" class="no-data">No hay categorías</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Crear -->
    <div x-show="openCreate" @click.away="openCreate=false" class="modal-overlay" x-cloak>
        <div class="modal-box">
            <h3>Crear Categoría</h3>
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <label>Nombre</label>
                <input type="text" name="nombre" required>
                <div class="modal-actions">
                    <button type="button" @click="openCreate=false" class="btn btn-cancel">Cancelar</button>
                    <button type="submit" class="btn btn-purple">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div x-show="openEdit" @click.away="openEdit=false" class="modal-overlay" x-cloak>
        <div class="modal-box">
            <h3>Editar Categoría</h3>
            <form method="POST" :action="'/categorias/' + categoriaId">
                @csrf
                @method('PUT')
                <label>Nombre</label>
                <input type="text" name="nombre" x-model="nombre" required>
                <div class="modal-actions">
                    <button type="button" @click="openEdit=false" class="btn btn-cancel">Cancelar</button>
                    <button type="submit" class="btn btn-purple">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
