@extends('layouts.table')

@section('content')
<div 
    x-data="{
        open: false,
        openModal: false,
        regionId: null,
        descripcion: '',
        estado: '',
        search: '',
        regiones: @js($regiones),
        get filtered() {
            if (this.search === '') return this.regiones;
            return this.regiones.filter(r =>
                r.nombre.toLowerCase().includes(this.search.toLowerCase()) ||
                r.estados.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }"
    class="container">

    <!-- ENCABEZADO -->
    <div class="header">
        <h2>Regiones</h2>
        <button @click="open = true; descripcion=''; estado='';" class="btn btn-green">+ Crear Región</button>
    </div>

    <!-- ACCIONES SUPERIORES -->
    <div class="actions">
        <div class="export-buttons">
            <a href="{{ route('regiones.export.xlsx') }}" class="btn btn-blue">XLSX</a>
            <a href="{{ route('regiones.export.csv') }}" class="btn btn-lightblue">CSV</a>
        </div>
        <input type="search" placeholder="Buscar región..." x-model="search" class="search-input">
    </div>

    <!-- TABLA -->
    <div class="table-container">
        <table class="areas-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="region in filtered" :key="region.id_region">
                    <tr>
                        <td x-text="region.id_region"></td>
                        <td x-text="region.nombre"></td>
                        <td x-text="region.estados"></td>
                        <td>
                            <span :class="region.estatus === 'Activo' ? 'badge-active' : 'badge-inactive'" 
                                  x-text="region.estatus"></span>
                        </td>
                        <td>
                            <!-- Editar -->
                            <button 
                                @click="
                                    openModal = true;
                                    regionId = region.id_region;
                                    descripcion = region.nombre;
                                    estado = region.estados;
                                "
                                :disabled="region.estatus === 'Inactivo'"
                                class="btn btn-purple">
                                ✏️
                            </button>

                            <!-- Activar / desactivar -->
                            <form :action="`/regiones/toggle/${region.id_region}`" method="POST" style="display:inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="btn btn-toggle" 
                                        :class="region.estatus === 'Activo' ? 'btn-gray' : 'btn-yellow'">
                                    <span x-text="region.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>

                <tr x-show="filtered.length === 0">
                    <td colspan="5" class="no-data">No hay regiones registradas</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ===========================
         MODAL UNIVERSAL
    ============================ -->
    <div x-show="open || openModal" 
         @click.away="open = false; openModal = false" 
         class="modal-overlay" 
         x-cloak>

        <div class="modal-box">
            <h3 class="modal-title" x-text="openModal ? 'Editar Región' : 'Crear Región'"></h3>

            <form :action="openModal ? `/regiones/${regionId}` : '{{ route('regiones.store') }}'" method="POST">
                @csrf

                <template x-if="openModal">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <label>Nombre</label>
                <input type="text" name="descripcion" x-model="descripcion" required>

                <label>Estado</label>
                <select name="estado" x-model="estado" required>
                    <option value="">-- Selecciona --</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->nombre }}">{{ $estado->nombre }}</option>
                    @endforeach
                </select>

                <div class="modal-actions">
                    <button type="button" @click="open = false; openModal = false" class="btn btn-cancel">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-green">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

