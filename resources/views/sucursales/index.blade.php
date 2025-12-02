@extends('layouts.table')

@section('content')

<style>
    /* ============================
       ESTILOS GENERALES DE MODALES
       ============================ */

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 50;
        padding: 20px;
    }

    .modal-content {
        background: #fff;
        width: 100%;
        max-width: 800px;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        animation: modalShow 0.25s ease-out;
    }

    @keyframes modalShow {
        from {opacity: 0; transform: scale(0.95);}
        to {opacity: 1; transform: scale(1);}
    }

    .modal-content h3 {
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 20px;
        color: #222;
    }

    /* === GRID ORDENADA === */
    .modal-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
        margin-bottom: 25px;
    }

    .modal-grid label {
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
        display: block;
    }

    .modal-grid input,
    .modal-grid select {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        background: #fafafa;
        transition: 0.2s ease;
    }

    .modal-grid input:focus,
    .modal-grid select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 4px rgba(78, 115, 223, 0.4);
        outline: none;
    }

    /* FOOTER */
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .modal-footer button {
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    .modal-footer button:first-child {
        background: #ccc;
        color: #333;
    }

    .modal-footer button:last-child {
        background: #4e73df;
        color: white;
    }

</style>


<div x-data="{
        open: false,
        openModal: false,
        sucursalId: null,
        nombre: '',
        clave: '',
        descripcion: '',
        id_zona: '',
        zona_horaria: '',
        identificador: '',
        codigo_postal: '',
        direccion: '',
        latitud: '',
        longitud: '',
        radio: '',
        maps: '',
        search: '',
        sucursales: @js($sucursales),
        zonas: @js($zonas),
        areasDisponibles: @js($areas),
        get filtered() {
            if (this.search === '') return this.sucursales;
            return this.sucursales.filter(s =>
                s.nombre.toLowerCase().includes(this.search.toLowerCase()) ||
                s.descripcion.toLowerCase().includes(this.search.toLowerCase()) ||
                s.clave.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }"
    class="container">

    <!-- Encabezado -->
    <div class="header">
        <h2>Sucursales</h2>
        <button @click="open = true" class="btn-create">+ Crear</button>
    </div>

    <!-- Acciones -->
    <div class="actions">
        <div>
            <button class="btn-xlsx" onclick="window.location='{{ route('sucursales.export.xlsx') }}'">XLSX</button>
            <button class="btn-csv" onclick="window.location='{{ route('sucursales.export.csv') }}'">CSV</button>
        </div>
        <input type="search" placeholder="Buscar sucursal..." x-model="search" class="input-search">
    </div>

    <!-- Tabla -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th>Zona</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="(sucursal, index) in filtered" :key="sucursal.id_sucursal">
                    <tr>
                        <td x-text="sucursal.id_sucursal"></td>
                        <td x-text="sucursal.identificador"></td>
                        <td x-text="sucursal.nombre"></td>
                        <td x-text="sucursal.zona?.nombre ?? 'Sin zona'"></td>
                        <td>
                            <span :class="sucursal.estatus === 'Activo' ? 'status activo' : 'status inactivo'" x-text="sucursal.estatus"></span>
                        </td>

                        <td class="actions-cell">
                            <button 
                                class="btn-edit"
                                @click="
                                    openModal = true;
                                    sucursalId = sucursal.id_sucursal;
                                    nombre = sucursal.nombre;
                                    clave = sucursal.clave;
                                    descripcion = sucursal.descripcion;
                                    id_zona = sucursal.id_zona;
                                    zona_horaria = sucursal.zona_horaria;
                                    identificador = sucursal.identificador;
                                    codigo_postal = sucursal.codigo_postal;
                                    direccion = sucursal.direccion;
                                    latitud = sucursal.latitud;
                                    longitud = sucursal.longitud;
                                    radio = sucursal.radio;
                                    maps = sucursal.maps;
                                "
                                :disabled="sucursal.estatus === 'Inactivo'"
                                :class="sucursal.estatus === 'Inactivo' ? 'disabled' : ''">
                                ✏️
                            </button>

                            <form :action="'/sucursales/toggle/' + sucursal.id_sucursal" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="btn-toggle"
                                    :class="sucursal.estatus === 'Activo' ? 'activo' : 'inactivo'">
                                    <span x-text="sucursal.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>

                <tr x-show="filtered.length === 0">
                    <td colspan="6" class="no-data">No hay sucursales</td>
                </tr>

            </tbody>
        </table>
    </div>




    <!-- ============================
         MODAL CREAR
    ============================= -->
    <div x-show="open" class="modal-overlay" x-cloak>
        <div class="modal-content">
            <h3>Crear Sucursal</h3>

            <form action="{{ route('sucursales.store') }}" method="POST">
                @csrf

                <div class="modal-grid">

                    <div>
                        <label>Nombre</label>
                        <input type="text" name="nombre" required>
                    </div>

                    <div>
                        <label>Zona</label>
                        <select name="id_zona">
                            <option value="">-- Selecciona --</option>
                            @foreach($zonas as $zona)
                                <option value="{{ $zona->id_zona }}">{{ $zona->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label>Zona Horaria</label>
                        <select name="zona_horaria">
                            <option value="">-- Selecciona --</option>
                            <option value="Ciudad de México (Centro)">Ciudad de México (Centro)</option>
                            <option value="Cancún / Quintana Roo (Sureste)">Cancún / Quintana Roo (Sureste)</option>
                            <option value="Chihuahua (Norte)">Chihuahua (Norte)</option>
                            <option value="Hermosillo / Sonora (Pacífico sin DST)">Hermosillo / Sonora (Pacífico sin DST)</option>
                            <option value="Mazatlán (Pacífico)">Mazatlán (Pacífico)</option>
                            <option value="Tijuana / Baja California (Noroeste)">Tijuana / Baja California (Noroeste)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label>Identificador</label>
                        <input type="text" name="identificador">
                    </div>

                    <div>
                        <label>Código Postal</label>
                        <input type="text" name="codigo_postal">
                    </div>

                    <div>
                        <label>Dirección</label>
                        <input type="text" name="direccion">
                    </div>

                    <div>
                        <label>Latitud</label>
                        <input type="text" name="latitud">
                    </div>

                    <div>
                        <label>Longitud</label>
                        <input type="text" name="longitud">
                    </div>

                    <div>
                        <label>Radio</label>
                        <input type="number" name="radio">
                    </div>

                    <div>
                        <label>Maps</label>
                        <input type="text" name="maps" placeholder="https://www.google.com/maps?q=lat,long">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" @click="open = false">Cancelar</button>
                    <button type="submit">Guardar</button>
                </div>

            </form>
        </div>
    </div>



    <!-- ============================
         MODAL EDITAR
    ============================= -->
    <div x-show="openModal" class="modal-overlay" x-cloak>
        <div class="modal-content">
            <h3 x-text="sucursalId === null ? 'Crear Sucursal' : 'Editar Sucursal'"></h3>

            <form :action="sucursalId === null ? '{{ route('sucursales.store') }}' : '/sucursales/' + sucursalId" method="POST">
                @csrf
                <template x-if="sucursalId !== null">@method('PUT')</template>

                <div class="modal-grid">

                    <div>
                        <label>Nombre</label>
                        <input type="text" name="nombre" x-model="nombre" required>
                    </div>

                    <div>
                        <label>Zona</label>
                        <select name="id_zona" x-model="id_zona">
                            <option value="">-- Selecciona --</option>
                            @foreach($zonas as $zona)
                                <option value="{{ $zona->id_zona }}">{{ $zona->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label>Zona Horaria</label>
                        <select name="zona_horaria" x-model="zona_horaria">
                            <option value="">-- Selecciona --</option>
                            <option value="Ciudad de México (Centro)">Ciudad de México (Centro)</option>
                            <option value="Cancún / Quintana Roo (Sureste)">Cancún / Quintana Roo (Sureste)</option>
                            <option value="Chihuahua (Norte)">Chihuahua (Norte)</option>
                            <option value="Hermosillo / Sonora (Pacífico sin DST)">Hermosillo / Sonora (Pacífico sin DST)</option>
                            <option value="Mazatlán (Pacífico)">Mazatlán (Pacífico)</option>
                            <option value="Tijuana / Baja California (Noroeste)">Tijuana / Baja California (Noroeste)</option>
                        </select>
                    </div>

                    <div>
                        <label>Identificador</label>
                        <input type="text" name="identificador" x-model="identificador">
                    </div>

                    <div>
                        <label>Código Postal</label>
                        <input type="text" name="codigo_postal" x-model="codigo_postal">
                    </div>

                    <div>
                        <label>Dirección</label>
                        <input type="text" name="direccion" x-model="direccion">
                    </div>

                    <div>
                        <label>Latitud</label>
                        <input type="text" name="latitud" x-model="latitud">
                    </div>

                    <div>
                        <label>Longitud</label>
                        <input type="text" name="longitud" x-model="longitud">
                    </div>

                    <div>
                        <label>Radio</label>
                        <input type="number" name="radio" x-model="radio">
                    </div>

                    <div>
                        <label>Maps</label>
                        <input type="text" name="maps" x-model="maps">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" @click="openModal = false">Cancelar</button>
                    <button type="submit">Guardar</button>
                </div>

            </form>
        </div>
    </div>

</div>

@endsection
