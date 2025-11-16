@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen" x-data="perfilesData()">

    <!-- 🟣 FILA SUPERIOR -->
    <div class="flex justify-between items-start mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Perfiles</h1>
        <button @click="abrirCrear()"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow transition mt-1">
            + Crear
        </button>
    </div>

    <!-- 🟣 CONTENEDOR TABLA -->
    <div class="bg-white rounded-2xl shadow-md p-5">
        <div class="flex justify-between items-center mb-4">
            <!-- BOTÓN OJO -->
            <div class="relative">
                <button @click="mostrarColumnas = !mostrarColumnas"
                    class="border-2 border-purple-500 text-purple-600 hover:bg-purple-50 p-2 rounded-lg shadow-sm transition">
                    👁
                </button>

                <!-- MENÚ COLUMNAS -->
                <div x-show="mostrarColumnas" x-cloak
                    class="absolute left-0 mt-2 w-60 bg-white border border-gray-200 rounded-lg shadow-lg z-50 p-3 text-sm">
                    <template x-for="(visible, key) in columnas" :key="key">
                        <label class="flex items-center gap-2 mb-1">
                            <input type="checkbox" x-model="columnas[key]">
                            <span x-text="key.charAt(0).toUpperCase() + key.slice(1)"></span>
                        </label>
                    </template>
                </div>
            </div>

            <!-- BÚSQUEDA -->
            <div class="relative w-64">
                <input type="text" x-model="buscar"
                    placeholder="Buscar"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400">
            </div>
        </div>

        <!-- 🟣 TABLA -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th x-show="columnas.nombre" class="px-4 py-3 text-left font-semibold">Nombre</th>
                        <th x-show="columnas.descripcion" class="px-4 py-3 text-left font-semibold">Descripción</th>
                        <th x-show="columnas.superior" class="px-4 py-3 text-left font-semibold">Superior</th>
                        <th x-show="columnas.nivel" class="px-4 py-3 text-left font-semibold">Nivel</th>
                        <th x-show="columnas.asignacion" class="px-4 py-3 text-left font-semibold">Asignación múltiple</th>
                        <th x-show="columnas.estatus" class="px-4 py-3 text-left font-semibold">Estatus</th>
                        <th x-show="columnas.acciones" class="px-4 py-3 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <template x-for="perfil in perfiles" :key="perfil.id_perfil">
                        <tr class="hover:bg-gray-50"
                            x-show="!buscar || (
                                perfil.nombre_perfil.toLowerCase().includes(buscar.toLowerCase()) ||
                                (perfil.descripcion ?? '').toLowerCase().includes(buscar.toLowerCase())
                            )">

                            <td x-show="columnas.nombre" class="px-4 py-3" x-text="perfil.nombre_perfil"></td>
                            <td x-show="columnas.descripcion" class="px-4 py-3" x-text="perfil.descripcion ?? ''"></td>
                            <td x-show="columnas.superior" class="px-4 py-3" x-text="perfil.superior ?? ''"></td>
                            <td x-show="columnas.nivel" class="px-4 py-3" x-text="perfil.nivel_asignacion ?? ''"></td>
                            <td x-show="columnas.asignacion" class="px-4 py-3" x-text="perfil.asignacion_multiple ? 'Sí' : 'No'"></td>

                            <!-- 🔹 ESTATUS -->
                            <td x-show="columnas.estatus" class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                      :class="{
                                          'bg-green-100 text-green-700': perfil.estatus === 'Activo',
                                          'bg-red-100 text-red-700': perfil.estatus === 'Inactivo'
                                      }"
                                      x-text="perfil.estatus">
                                </span>
                            </td>

                            <!-- 🔹 ACCIONES -->
                            <td x-show="columnas.acciones" class="px-4 py-3 text-center flex justify-center gap-2">
                                <!-- EDITAR -->
                                <button 
                                    @click="editarPerfil(perfil)"
                                    class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700"
                                    :disabled="perfil.estatus === 'Inactivo'"
                                    :class="perfil.estatus === 'Inactivo' ? 'opacity-50 cursor-not-allowed' : ''">
                                    ✏
                                </button>

                                <!-- TOGGLE ESTATUS -->
                                <button 
                                    type="button"
                                    class="text-white px-3 py-1 rounded transition"
                                    :class="perfil.estatus === 'Activo' 
                                        ? 'bg-gray-400 hover:bg-gray-500' 
                                        : 'bg-yellow-400 hover:bg-yellow-500'"
                                    @click="toggleEstatus(perfil)">
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
    <div x-show="openCreate" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-lg overflow-y-auto max-h-[90vh]"
             @click.away="openCreate = false">
            <h2 class="text-lg font-semibold text-gray-800 mb-4" 
                x-text="modoEditar ? 'Editar Perfil' : 'Crear Perfil'"></h2>

            <form :action="modoEditar ? '/perfiles/' + perfilId : '{{ route('perfiles.store') }}'" method="POST" @submit.prevent="submitPerfil">
                @csrf
                <template x-if="modoEditar">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre de perfil</label>
                        <input type="text" name="nombre_perfil" x-model="nombre_perfil"
                               class="w-full mt-1 border-gray-300 rounded-lg px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Superior</label>
                        <input type="text" name="superior" x-model="superior"
                               class="w-full mt-1 border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="descripcion" rows="2" x-model="descripcion"
                                  class="w-full mt-1 border-gray-300 rounded-lg px-3 py-2"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Nivel de Asignación</label>
                        <select name="nivel_asignacion" x-model="nivel_asignacion"
                                class="w-full mt-1 border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Seleccione...</option>
                            <option value="Región">Región</option>
                            <option value="Zona">Zona</option>
                            <option value="Sucursal">Sucursal</option>
                            <option value="Área">Área</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2 mt-2 col-span-2">
                        <input type="checkbox" name="asignacion_multiple" x-model="asignacion_multiple" value="1"
                               class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label class="text-sm text-gray-700">Asignación múltiple</label>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <button type="button" @click="abrirModalPermisos()"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-lg shadow transition">
                        Permisos
                    </button>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="openCreate = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg shadow transition">Cerrar</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-gray px-4 py-2 rounded-lg shadow transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PERMISOS -->
    <div x-show="openPermisos" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
        <div class="bg-white rounded-2xl p-5 w-full max-w-4xl max-h-[80vh] overflow-y-auto shadow-lg"
             @click.away="openPermisos = false">
            <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-gray-800">Permisos del perfil</h2>

    <button @click="regresarDePermisos()"
            class="flex items-center gap-1 bg-purple-600 hover:bg-purple-700 text-gray px-3 py-1 rounded-lg text-sm transition">
        Volver →
    </button>
</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <template x-for="(acciones, modulo) in permisosPorModulo" :key="modulo">
                    <div class="flex flex-col relative">
                        <label class="text-xs font-medium text-gray-700 mb-1" x-text="modulo"></label>

                        <div @click="openDropdown = openDropdown === modulo ? null : modulo"
                             class="border border-gray-300 rounded-lg px-2 py-1 bg-white flex items-center justify-between cursor-pointer min-h-[34px]"
                             :class="{'ring-2 ring-purple-400': openDropdown === modulo}">
                            
                            <div class="flex items-center gap-1 overflow-x-auto no-scrollbar">
                                <template x-if="!getSelectedForModule(modulo).length">
                                    <span class="text-gray-400 text-xs">Seleccione</span>
                                </template>
                                <template x-for="opt in getSelectedForModule(modulo)" :key="opt.value">
                                    <span class="flex items-center gap-1 bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded-full mr-1">
                                        <span x-text="opt.label"></span>
                                        <button type="button" @click.stop="removePermission(opt.value)" 
                                                class="text-gray-400 hover:text-gray-600 text-[10px] leading-none">X</button>
                                    </span>
                                </template>
                            </div>

                            <svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4 text-gray-500 ml-2 shrink-0' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/>
                            </svg>
                        </div>

                        <!-- Dropdown options -->
                        <div x-show="openDropdown === modulo" x-cloak @click.away="openDropdown = null"
                             class="absolute left-0 z-50 mt-1 bg-white border border-gray-200 rounded-md shadow-lg p-1 w-56 max-h-48 overflow-auto text-sm">
                            <template x-for="opt in acciones" :key="opt.value">
                                <div @click="togglePermission(opt.value)"
                                     :class="permisosSeleccionados.includes(opt.value) ? 'bg-purple-50' : 'hover:bg-gray-50'"
                                     class="flex items-center justify-between gap-2 px-3 py-2 rounded cursor-pointer">
                                    <div class="text-sm" x-text="opt.label"></div>
                                    <svg x-show="permisosSeleccionados.includes(opt.value)" 
                                         xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <!-- RESUMEN VISUAL -->
            <div x-show="Object.keys(permisosGuardadosTemporal).length > 0" class="mt-4 p-3 bg-gray-100 rounded text-xs">
                <strong>Permisos temporales guardados:</strong>
                <template x-for="(permisos, idPerfil) in permisosGuardadosTemporal" :key="idPerfil">
                    <div class="mt-2">
                        <strong x-text="'Perfil ID: ' + idPerfil"></strong>
                        <ul class="list-disc list-inside ml-4">
                            <template x-for="p in permisos" :key="p.id_permiso">
                                <li x-text="p.modulo + ' → ' + p.accion"></li>
                            </template>
                        </ul>
                    </div>
                </template>
            </div>

            <div class="flex justify-end gap-2 mt-5">
                <button @click="openPermisos = false"
                        class="px-3 py-1.5 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm transition">
                    Cerrar
                </button>
                <button @click="guardarPermisos()" 
                        class="bg-blue-600 text-gray px-3 py-2 rounded hover:bg-blue-700">
                    Guardar Permisos
                </button>
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

@endsection