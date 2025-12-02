@extends('layouts.app')

@section('content')
<div class="container" x-data="perfilesData()">

    <!-- FILA SUPERIOR -->
    <div class="top-bar">
        <h1>Perfiles</h1>
        <button @click="abrirCrear()">+ Crear</button>
    </div>

    <!-- CONTENEDOR TABLA -->
    <div class="table-container">
        <div class="table-actions">
            <!-- BOTÓN OJO -->
            <div class="column-toggle-wrapper">
                <button @click="mostrarColumnas = !mostrarColumnas">👁</button>
                <div class="column-toggle-menu" x-show="mostrarColumnas" x-cloak>
                    <template x-for="(visible, key) in columnas" :key="key">
                        <label>
                            <input type="checkbox" x-model="columnas[key]">
                            <span x-text="key.charAt(0).toUpperCase() + key.slice(1)"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- BÚSQUEDA -->
            <div class="search-wrapper">
                <input type="text" x-model="buscar" placeholder="Buscar">
            </div>
        </div>

        <!-- TABLA -->
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th x-show="columnas.nombre">Nombre</th>
                        <th x-show="columnas.descripcion">Descripción</th>
                        <th x-show="columnas.superior">Superior</th>
                        <th x-show="columnas.nivel">Nivel</th>
                        <th x-show="columnas.asignacion">Asignación múltiple</th>
                        <th x-show="columnas.estatus">Estatus</th>
                        <th x-show="columnas.acciones">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="perfil in perfiles" :key="perfil.id_perfil">
                        <tr x-show="!buscar || (perfil.nombre_perfil.toLowerCase().includes(buscar.toLowerCase()) || (perfil.descripcion ?? '').toLowerCase().includes(buscar.toLowerCase()))">
                            <td x-show="columnas.nombre" x-text="perfil.nombre_perfil"></td>
                            <td x-show="columnas.descripcion" x-text="perfil.descripcion ?? ''"></td>
                            <td x-show="columnas.superior" x-text="perfil.superior ?? ''"></td>
                            <td x-show="columnas.nivel" x-text="perfil.nivel_asignacion ?? ''"></td>
                            <td x-show="columnas.asignacion" x-text="perfil.asignacion_multiple ? 'Sí' : 'No'"></td>
                            <td x-show="columnas.estatus">
                                <span :class="perfil.estatus === 'Activo' ? 'status-active' : 'status-inactive'" x-text="perfil.estatus"></span>
                            </td>
                            <td x-show="columnas.acciones" class="actions-cell">
                                <button @click="editarPerfil(perfil)" :disabled="perfil.estatus === 'Inactivo'" :class="perfil.estatus === 'Inactivo' ? 'disabled' : ''">✏</button>
                                <button type="button" @click="toggleEstatus(perfil)" :class="perfil.estatus === 'Activo' ? 'toggle-active' : 'toggle-inactive'">
                                    <span x-text="perfil.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL CREAR / EDITAR -->
    <div class="modal-overlay" x-show="openCreate" x-cloak>
        <div class="modal" @click.away="openCreate = false">
            <h2 x-text="modoEditar ? 'Editar Perfil' : 'Crear Perfil'"></h2>
            <form :action="modoEditar ? '/perfiles/' + perfilId : '{{ route('perfiles.store') }}'" method="POST" @submit.prevent="submitPerfil">
                @csrf
                <template x-if="modoEditar">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="modal-grid">
                    <div>
                        <label>Nombre de perfil</label>
                        <input type="text" name="nombre_perfil" x-model="nombre_perfil" required>
                    </div>
                    <div>
                        <label>Superior</label>
                        <input type="text" name="superior" x-model="superior">
                    </div>
                    <div class="col-span-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" rows="2" x-model="descripcion"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label>Nivel de Asignación</label>
                        <select name="nivel_asignacion" x-model="nivel_asignacion">
                            <option value="">Seleccione...</option>
                            <option value="Región">Región</option>
                            <option value="Zona">Zona</option>
                            <option value="Sucursal">Sucursal</option>
                            <option value="Área">Área</option>
                        </select>
                    </div>
                    <div class="col-span-2 checkbox-wrapper">
                        <input type="checkbox" name="asignacion_multiple" x-model="asignacion_multiple" value="1">
                        <label>Asignación múltiple</label>
                    </div>
                </div>

                <div class="modal-buttons-top">
                    <button type="button" @click="abrirModalPermisos()">Permisos</button>
                </div>

                <div class="modal-buttons">
                    <button type="button" @click="openCreate = false">Cerrar</button>
                    <button type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PERMISOS -->
    <div class="modal-overlay" x-show="openPermisos" x-cloak>
        <div class="modal-permisos" @click.away="openPermisos = false">
            <div class="modal-header">
                <h2>Permisos del perfil</h2>
                <button @click="regresarDePermisos()">Volver →</button>
            </div>

            <div class="permisos-grid">
                <template x-for="(acciones, modulo) in permisosPorModulo" :key="modulo">
                    <div class="permiso-module">
                        <label x-text="modulo"></label>
                        <div @click="openDropdown = openDropdown === modulo ? null : modulo" class="dropdown" :class="{'active': openDropdown === modulo}">
                            <div class="selected">
                                <template x-if="!getSelectedForModule(modulo).length">
                                    <span>Seleccione</span>
                                </template>
                                <template x-for="opt in getSelectedForModule(modulo)" :key="opt.value">
                                    <span class="selected-item">
                                        <span x-text="opt.label"></span>
                                        <button type="button" @click.stop="removePermission(opt.value)">X</button>
                                    </span>
                                </template>
                            </div>
                            <svg xmlns='http://www.w3.org/2000/svg' class='icon-chevron' viewBox='0 0 24 24'><path d='M19 9l-7 7-7-7'/></svg>
                        </div>
                        <div x-show="openDropdown === modulo" x-cloak class="dropdown-options">
                            <template x-for="opt in acciones" :key="opt.value">
                                <div @click="togglePermission(opt.value)" :class="permisosSeleccionados.includes(opt.value) ? 'selected-option' : 'hover-option'">
                                    <span x-text="opt.label"></span>
                                    <svg x-show="permisosSeleccionados.includes(opt.value)" class="icon-check" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div class="modal-permisos-footer">
                <button @click="openPermisos = false">Cerrar</button>
                <button @click="guardarPermisos()">Guardar Permisos</button>
            </div>
        </div>
    </div>
</div>
<!-- -------------------- SCRIPT -------------------- -->
<script>
function perfilesData() {
    return {
        openCreate: false,
        openPermisos: false,
        modoEditar: false,
        perfilId: null,
        nombre_perfil: '',
        descripcion: '',
        superior: '',
        nivel_asignacion: '',
        asignacion_multiple: false,
        mostrarColumnas: false,
        buscar: '',
        modalAnterior: null, // 👈 guarda de qué modal vienes (crear o editar)

        columnas: {
            nombre: true,
            descripcion: true,
            superior: true,
            nivel: true,
            asignacion: true,
            estatus: true,
            acciones: true
        },

        permisosPorModulo: @json($permisosPorModulo ?? []),
        permisosAsignados: @json($permisosAsignados ?? []),
        perfiles: @json($perfiles),

        permisosSeleccionados: [],
        permisosGuardadosTemporal: {},
        openDropdown: null,

        // Abrir modal de crear
        abrirCrear() {
            this.modoEditar = false;
            this.perfilId = null;
            this.nombre_perfil = '';
            this.descripcion = '';
            this.superior = '';
            this.nivel_asignacion = '';
            this.asignacion_multiple = false;
            this.openCreate = true;
            this.modalAnterior = null;
        },

        // Abrir modal de editar
        editarPerfil(perfil) {
            this.modoEditar = true;
            this.perfilId = perfil.id_perfil;
            this.nombre_perfil = perfil.nombre_perfil;
            this.descripcion = perfil.descripcion;
            this.superior = perfil.superior;
            this.nivel_asignacion = perfil.nivel_asignacion;
            this.asignacion_multiple = perfil.asignacion_multiple == 1;
            this.openCreate = true;
            this.modalAnterior = null;
        },

        // Guardar perfil (crear o editar)
        async submitPerfil(e) {
            try {
                const baseUrl = '{{ url("perfiles") }}';
                let url = baseUrl;
                let method = 'POST';
                if (this.modoEditar && this.perfilId) {
                    url = `${baseUrl}/${this.perfilId}`;
                    method = 'PUT';
                }

                const payload = {
                    nombre_perfil: this.nombre_perfil,
                    descripcion: this.descripcion,
                    superior: this.superior,
                    nivel_asignacion: this.nivel_asignacion,
                    asignacion_multiple: this.asignacion_multiple ? 1 : 0
                };

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const data = await response.json();

                if (data.success || response.status === 200 || response.status === 201) {
                    window.location.reload();
                } else {
                    alert(data.error || 'Error al guardar el perfil.');
                }
            } catch (err) {
                console.error('Error submitPerfil:', err);
                alert('Ocurrió un error al guardar el perfil (ver consola).');
            }
        },

        // ------------------- PERMISOS -------------------

        getSelectedForModule(modulo) {
            const acciones = this.permisosPorModulo[modulo] || [];
            return acciones.filter(opt => this.permisosSeleccionados.includes(opt.value))
                .map(opt => ({ value: opt.value, label: opt.label }));
        },

        togglePermission(idPermiso) {
            if (this.permisosSeleccionados.includes(idPermiso)) {
                this.permisosSeleccionados = this.permisosSeleccionados.filter(p => p !== idPermiso);
            } else {
                this.permisosSeleccionados.push(idPermiso);
            }
        },

        removePermission(idPermiso) {
            this.permisosSeleccionados = this.permisosSeleccionados.filter(p => p !== idPermiso);
        },

        // Abrir modal de permisos desde la lista principal
        abrirPermisos(id) {
            this.perfilId = id;
            this.modalAnterior = 'lista';
            this.openCreate = false;
            this.openPermisos = true;
            this.openDropdown = null;
            this.permisosSeleccionados = this.permisosAsignados[id]
                ? [...this.permisosAsignados[id]]
                : [];
        },

        // Abrir modal de permisos desde crear o editar
        abrirModalPermisos() {
            if (!this.perfilId) {
                alert("❌ No hay perfil seleccionado.");
                return;
            }

            // 👇 Guardamos de dónde venimos
            this.modalAnterior = this.modoEditar ? 'editar' : 'crear';
            this.openCreate = false;
            this.openPermisos = true;
            this.permisosSeleccionados = this.permisosAsignados[this.perfilId]
                ? [...this.permisosAsignados[this.perfilId]]
                : [];
        },

        // 👈 Cierra permisos y vuelve al modal anterior
        regresarDePermisos() {
            this.openPermisos = false;
            if (this.modalAnterior === 'editar') {
                this.modoEditar = true;
                this.openCreate = true;
            } else if (this.modalAnterior === 'crear') {
                this.modoEditar = false;
                this.openCreate = true;
            }
        },

        // Guardar permisos y volver al modal anterior
        async guardarPermisos() {
            if (!this.perfilId) {
                alert("❌ No hay perfil seleccionado.");
                return;
            }
            try {
                const baseUrl = '{{ url("perfiles") }}';
                const response = await fetch(`${baseUrl}/${this.perfilId}/guardar-permisos`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ permisos: this.permisosSeleccionados })
                });

                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const data = await response.json();

                if (data.mensaje || data.success) {
                    alert(data.mensaje || '✅ Permisos guardados correctamente.');
                    this.permisosAsignados[this.perfilId] = [...this.permisosSeleccionados];

                    // 👇 Cierra y vuelve al modal anterior
                    this.openPermisos = false;
                    if (this.modalAnterior === 'editar') {
                        this.modoEditar = true;
                        this.openCreate = true;
                    } else if (this.modalAnterior === 'crear') {
                        this.modoEditar = false;
                        this.openCreate = true;
                    }
                } else {
                    alert(data.error || '❌ Error al guardar los permisos.');
                }
            } catch (err) {
                console.error('Error guardarPermisos:', err);
                alert('❌ Error al guardar los permisos en el servidor (ver consola).');
            }
        },

        // Cambiar estatus sin recargar
        async toggleEstatus(perfil) {
            try {
                const url = `/perfiles/${perfil.id_perfil}/toggle`;
                const response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error(`HTTP ${response.status}`);

                const data = await response.json();

                if (data.success) {
                    perfil.estatus = data.nuevo_estatus;
                } else {
                    alert('❌ No se pudo cambiar el estatus.');
                }
            } catch (error) {
                console.error('Error al cambiar estatus:', error);
                alert('⚠ Ocurrió un error al intentar cambiar el estatus.');
            }
        }
    }
}
</script>
<!-- -------------------- CSS -------------------- -->
<style>
    /* -------------------- CONTENEDOR GENERAL -------------------- */
.container {
    padding: 24px;
    background-color: #f9fafb;
    min-height: 100vh;
    font-family: Arial, sans-serif;
}

/* -------------------- FILA SUPERIOR -------------------- */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}
.top-bar h1 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #4b5563; /* gray-700 */
}
.top-bar button {
    background-color: #16a34a; /* green-600 */
    color: #fff;
    padding: 8px 20px;
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 4px;
}
.top-bar button:hover {
    background-color: #15803d; /* green-700 */
}

/* -------------------- TABLA -------------------- */
.table-container {
    background-color: #fff;
    border-radius: 1rem;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.table-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

/* BOTÓN OJO */
.column-toggle-wrapper {
    position: relative;
}
.column-toggle-wrapper button {
    padding: 6px;
    border: 2px solid #8b5cf6;
    color: #8b5cf6;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background 0.2s;
    background-color: #fff;
}
.column-toggle-wrapper button:hover {
    background-color: #f5f3ff; /* purple-50 */
}
.column-toggle-menu {
    position: absolute;
    left: 0;
    margin-top: 8px;
    width: 240px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    z-index: 50;
    font-size: 0.875rem;
}
.column-toggle-menu label {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}

/* BÚSQUEDA */
.search-wrapper input {
    width: 256px;
    padding: 6px 12px;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    outline: none;
}
.search-wrapper input:focus {
    border-color: #a78bfa; /* purple-400 */
    box-shadow: 0 0 0 2px rgba(167,139,250,0.4);
}

/* TABLA */
.table-scroll {
    overflow-x: auto;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    color: #374151; /* gray-700 */
}
thead {
    background-color: #f3f4f6; /* gray-100 */
    color: #4b5563; /* gray-600 */
    text-transform: uppercase;
    font-size: 0.75rem;
}
th, td {
    padding: 12px 16px;
    text-align: left;
}
tbody tr:hover {
    background-color: #f9fafb; /* gray-50 */
}

/* ESTATUS */
.status-active {
    background-color: #dcfce7; /* green-100 */
    color: #166534; /* green-700 */
    padding: 2px 6px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-inactive {
    background-color: #fee2e2; /* red-100 */
    color: #991b1b; /* red-700 */
    padding: 2px 6px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* ACCIONES */
.actions-cell {
    display: flex;
    justify-content: center;
    gap: 8px;
}
.actions-cell button {
    padding: 6px 12px;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}
.actions-cell button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
.actions-cell button:nth-child(1) {
    background-color: #8b5cf6; /* purple-600 */
    color: #fff;
}
.actions-cell button:nth-child(1):hover:not(.disabled) {
    background-color: #7c3aed; /* purple-700 */
}
.toggle-active {
    background-color: #9ca3af; /* gray-400 */
    color: #fff;
}
.toggle-active:hover {
    background-color: #6b7280; /* gray-500 */
}
.toggle-inactive {
    background-color: #facc15; /* yellow-400 */
    color: #fff;
}
.toggle-inactive:hover {
    background-color: #eab308; /* yellow-500 */
}

/* -------------------- MODALES -------------------- */
.modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgb(241, 237, 237);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 16px;
    z-index: 50;
}
.modal, .modal-permisos {
    background-color: #fff;
    border-radius: 1rem;
    padding: 24px;
    width: 100%;
    max-width: 640px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 4px 12px rgb(245, 240, 240);
}
.modal-permisos {
    max-width: 960px;
    max-height: 80vh;
}



/* -------------------- MODAL PERMISOS -------------------- */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}
.modal-header button {
    background-color: #8b5cf6;
    color: #fff;
    padding: 4px 12px;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
}
.modal-header button:hover {
    background-color: #7c3aed;
}

.permisos-grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 12px;
}
@media(min-width: 640px){
    .permisos-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(min-width: 1024px){
    .permisos-grid { grid-template-columns: repeat(3, 1fr); }
}

.permiso-module label {
    font-size: 0.75rem;
    font-weight: 500;
    margin-bottom: 4px;
    display: block;
}
.dropdown {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 4px 8px;
    background-color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-height: 34px;
    cursor: pointer;
}
.dropdown.active {
    box-shadow: 0 0 0 2px rgba(167,139,250,0.4);
}
.selected {
    display: flex;
    gap: 4px;
    overflow-x: auto;
}
.selected-item {
    display: flex;
    align-items: center;
    gap: 4px;
    background-color: #f3f4f6;
    padding: 2px 4px;
    border-radius: 9999px;
    font-size: 0.75rem;
}
.selected-item button {
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
    font-size: 10px;
    line-height: 1;
}
.dropdown-options {
    position: absolute;
    left: 0;
    z-index: 50;
    margin-top: 4px;
    background-color: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    width: 224px;
    max-height: 192px;
    overflow-y: auto;
    font-size: 0.875rem;
}
.hover-option:hover {
    background-color: #f9fafb;
}
.selected-option {
    background-color: #f5f3ff;
    color: #7c3aed;
}
.icon-chevron {
    width: 16px;
    height: 16px;
    color: #6b7280;
    flex-shrink: 0;
}
.icon-check {
    width: 16px;
    height: 16px;
    color: #7c3aed;
}

/* FOOTER MODAL PERMISOS */
.modal-permisos-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}
.modal-permisos-footer button:first-child {
    background-color: #d1d5db;
    padding: 6px 12px;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
}
.modal-permisos-footer button:first-child:hover {
    background-color: #9ca3af;
}
.modal-permisos-footer button:last-child {
    background-color: #2563eb;
    color: #fff;
    padding: 6px 12px;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
}
.modal-permisos-footer button:last-child:hover {
    background-color: #1d4ed8;
}

</style>
@endsection