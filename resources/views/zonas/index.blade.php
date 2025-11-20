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
        regiones: @js($regiones),
        get filtered() {
            if (this.search === '') return this.zonas;
            return this.zonas.filter(z =>
                z.nombre.toLowerCase().includes(this.search.toLowerCase()) ||
                z.region_nombre.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        openZonaModal(zona) {
            this.zonaId = zona.id_zona;
            this.nombre = zona.nombre;
            this.regionId = zona.id_region;
            this.openModal = true;
        }
    }"
    class="container">

    <!-- Encabezado -->
    <div class="header">
        <h2>Zonas</h2>
        <button @click="open = true" class="btn btn-green">+ Crear Zona</button>
    </div>

    <!-- Acciones superiores -->
    <div class="actions">
        <div class="export-buttons">
            <a href="{{ route('zonas.export.xlsx') }}" class="btn btn-blue">XLSX</a>
            <a href="{{ route('zonas.export.csv') }}" class="btn btn-lightblue">CSV</a>
        </div>
        <input type="search" placeholder="Buscar zona o región..." x-model="search" class="search-input">
    </div>

    <!-- Tabla -->
    <div class="table-container">
        <table class="areas-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Región</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="zona in filtered" :key="zona.id_zona">
                    <tr>
                        <td x-text="zona.id_zona"></td>
                        <td x-text="zona.nombre"></td>
                        <td x-text="zona.region_nombre"></td>
                        <td>
                            <span :class="zona.estatus === 'Activo' ? 'badge-active' : 'badge-inactive'" x-text="zona.estatus"></span>
                        </td>
                        <td>
                            <button @click="openZonaModal(zona)" 
                                    :disabled="zona.estatus === 'Inactivo'" class="btn btn-purple">
                                ✏️
                            </button>
                            <form :action="`/zonas/toggle/${zona.id_zona}`" method="POST" style="display:inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-toggle" :class="zona.estatus === 'Activo' ? 'btn-gray' : 'btn-yellow'">
                                    <span x-text="zona.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>
                <tr x-show="filtered.length === 0">
                    <td colspan="5" class="no-data">No hay zonas registradas</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div x-show="open || openModal" @click.away="open = false; openModal = false" class="modal-overlay" x-cloak>
        <div class="modal-box">
            <h3 x-text="openModal ? 'Editar Zona' : 'Crear Zona'"></h3>
            <form :action="openModal ? `{{ url('zonas') }}/${zonaId}` : '{{ route('zonas.store') }}'" method="POST">
                @csrf
                <template x-if="openModal">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <label>Nombre</label>
                <input type="text" name="nombre" x-model="nombre" required>

                <label>Región</label>
                <select name="id_region" x-model="regionId" required>
                    <option value="">-- Selecciona --</option>
                    @foreach($regiones as $region)
                        <option value="{{ $region->id_region }}">{{ $region->nombre }}</option>
                    @endforeach
                </select>

                <div class="modal-actions">
                    <button type="button" @click="open = false; openModal = false" class="btn btn-cancel">Cancelar</button>
                    <button type="submit" class="btn btn-green">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


