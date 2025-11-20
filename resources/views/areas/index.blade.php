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
    class="container">

    <!-- Encabezado -->
    <div class="header">
        <h2>Áreas</h2>
        <button @click="open = true" class="btn btn-green">+ Crear Área</button>
    </div>

    <!-- Acciones superiores -->
    <div class="actions">
        <div class="export-buttons">
            <a href="{{ route('areas.export.excel') }}" class="btn btn-blue">XLSX</a>
            <a href="{{ route('areas.export.csv') }}" class="btn btn-lightblue">CSV</a>
        </div>
        <input type="search" placeholder="Buscar área..." x-model="search" class="search-input">
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
                <template x-for="area in filtered" :key="area.id_area">
                    <tr>
                        <td x-text="area.id_area"></td>
                        <td x-text="area.nombre"></td>
                        <td>
                            <span :class="area.estatus === 'Activo' ? 'badge-active' : 'badge-inactive'" x-text="area.estatus"></span>
                        </td>
                        <td>
                            <button @click="openModal = true; areaId = area.id_area; nombre = area.nombre" 
                                    :disabled="area.estatus === 'Inactivo'" class="btn btn-purple">
                                ✏️
                            </button>
                            <form :action="`/areas/toggle/${area.id_area}`" method="POST" style="display:inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-toggle" :class="area.estatus === 'Activo' ? 'btn-gray' : 'btn-yellow'">
                                    <span x-text="area.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>
                <tr x-show="filtered.length === 0">
                    <td colspan="4" class="no-data">No hay áreas registradas</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div x-show="open || openModal" @click.away="open = false; openModal = false" class="modal-overlay" x-cloak>
        <div class="modal-box">
            <h3 x-text="openModal ? 'Editar Área' : 'Crear Área'"></h3>
            <form :action="openModal ? `{{ url('areas') }}/${areaId}` : '{{ route('areas.store') }}'" method="POST">
                @csrf
                <template x-if="openModal">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <label>Nombre</label>
                <input type="text" name="nombre" x-model="nombre" required>
                <div class="modal-actions">
                    <button type="button" @click="open = false; openModal = false" class="btn btn-cancel">Cancelar</button>
                    <button type="submit" class="btn btn-green">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
