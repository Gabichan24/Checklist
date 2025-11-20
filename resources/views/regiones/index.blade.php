@extends('layouts.table')

@section('content')
<div class="container" x-data="{
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
    }">

    <!-- Encabezado -->
    <div class="header">
        <h2>Regiones</h2>
        <button @click="open = true">+ Crear</button>
    </div>

    <!-- Acciones superiores -->
    <div class="actions">
        <div class="buttons">
            <button onclick="window.location='{{ route('regiones.export.xlsx') }}'">XLSX</button>
            <button onclick="window.location='{{ route('regiones.export.csv') }}'">CSV</button>
        </div>

        <div class="search">
            <input type="search" placeholder="Escribe para buscar..." x-model="search">
        </div>
    </div>

    <!-- Tabla -->
    <div class="table-wrapper">
        <table>
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
                            <span class="status" :class="region.estatus === 'Activo' ? 'activo' : 'inactivo'" x-text="region.estatus"></span>
                        </td>
                        <td>
                            <button 
                                @click="openModal = true; regionId=region.id_region; descripcion=region.nombre; estado=region.estados"
                                class="table-btn edit"
                                :disabled="region.estatus === 'Inactivo'"
                                :class="region.estatus === 'Inactivo' ? 'opacity-50 cursor-not-allowed' : ''">
                                ✏️
                            </button>

                            <form :action="'/regiones/toggle/' + region.id_region" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="table-btn lock" :class="region.estatus === 'Activo' ? 'activo' : 'inactivo'">
                                    <span x-text="region.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>

                <tr x-show="filtered.length === 0">
                    <td colspan="5" class="text-center" style="padding:10px; color:#555;">No hay regiones</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Editar -->
    <div x-show="openModal" class="modal-overlay" x-cloak>
        <div class="modal">
            <h2>Editar Región</h2>
            <form method="POST" :action="'/regiones/' + regionId">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Descripción</label>
                    <input type="text" name="descripcion" x-model="descripcion">
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" x-model="estado" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->nombre }}">{{ $estado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="openModal = false" class="cancel">Cancelar</button>
                    <button type="submit" class="save">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Crear -->
    <div x-show="open" class="modal-overlay" x-cloak>
        <div class="modal">
            <h3>Crear Región</h3>
            <form action="{{ route('regiones.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->nombre }}">{{ $estado->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="open = false" class="cancel">Cancelar</button>
                    <button type="submit" class="save">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

