@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen"
     x-data="{
        openCreate: false,
        openPermisos: false,
        permisosSeleccionados: [],
        buscar: '',
        ocultar: false
     }">

    <!-- ENCABEZADO -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Perfiles</h1>
        <div class="flex gap-2 flex-wrap">
            <button @click="openCreate = true"
                class="bg-blue-600 hover:bg-blue-700 text-gray px-4 py-2 rounded-lg shadow transition">
                + Crear Perfil
            </button>
            <button @click="ocultar = !ocultar"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow transition">
                <template x-if="ocultar">Mostrar Datos</template>
                <template x-if="!ocultar">Ocultar Datos</template>
            </button>
        </div>
    </div>

    <!-- BARRA DE BÚSQUEDA -->
    <div class="mb-4">
        <input type="text" x-model="buscar" placeholder="Buscar perfil..."
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none transition">
    </div>

    <!-- TABLA DE PERFILES -->
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">#</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Nombre</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Descripción</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Superior</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Nivel</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Estatus</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($perfiles as $perfil)
                <tr class="hover:bg-gray-50" x-show="!buscar || '{{ strtolower($perfil->nombre_perfil) }}'.includes(buscar.toLowerCase())">
                    <td class="px-4 py-3">{{ $perfil->id_perfil }}</td>
                    <td class="px-4 py-3" x-text="ocultar ? '••••••' : '{{ $perfil->nombre_perfil }}'"></td>
                    <td class="px-4 py-3">{{ $perfil->descripcion }}</td>
                    <td class="px-4 py-3">{{ $perfil->superior ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $perfil->nivel_asignacion ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($perfil->estatus)
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Activo</span>
                        @else
                            <span class="px-2 py-1 bg-gray-200 text-gray-600 text-xs rounded-full">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center gap-2 flex-wrap">
                            <a href="{{ route('perfiles.edit', $perfil->id_perfil) }}"
                               class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-sm shadow transition">
                               Editar
                            </a>
                            <form action="{{ route('perfiles.destroy', $perfil->id_perfil) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas bloquear este perfil?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-3 py-1 rounded-lg text-sm shadow transition">
                                    Bloquear
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- MODAL CREAR PERFIL -->
    <div x-show="openCreate" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4 transition-opacity">
        <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-lg overflow-y-auto max-h-[90vh]"
             @click.away="openCreate = false">

            <h2 class="text-lg font-semibold text-gray-800 mb-4">Crear Perfil</h2>

            <form action="{{ route('perfiles.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre de perfil</label>
                        <input type="text" name="nombre_perfil" class="w-full mt-1 border-gray-300 rounded-lg px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Superior (opcional)</label>
                        <input type="text" name="superior" class="w-full mt-1 border-gray-300 rounded-lg px-3 py-2">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Descripción del perfil</label>
                        <textarea name="descripcion" rows="2" class="w-full mt-1 border-gray-300 rounded-lg px-3 py-2"></textarea>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Nivel de Asignación</label>
                        <select name="nivel_asignacion" class="w-full mt-1 border-gray-300 rounded-lg px-3 py-2">
                            <option value="">Seleccione...</option>
                            <option value="Región">Región</option>
                            <option value="Zona">Zona</option>
                            <option value="Sucursal">Sucursal</option>
                            <option value="Área">Área</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-2 mt-2 col-span-2">
                        <input type="checkbox" name="asignacion_multiple" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label class="text-sm text-gray-700">Asignación múltiple</label>
                    </div>
                </div>

                <!-- BOTÓN PERMISOS -->
                <div class="mt-4">
                    <button type="button" @click="openPermisos = true"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-lg shadow transition">
                        Permisos
                    </button>
                </div>

                <!-- BOTONES GUARDAR / CERRAR -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="openCreate = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg shadow transition">Cerrar</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PERMISOS -->
    <div x-show="openPermisos" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4 transition-opacity">
        <div class="bg-white rounded-2xl p-5 w-full max-w-4xl max-h-[80vh] overflow-y-auto shadow-lg"
             @click.away="openPermisos = false">

            <div class="flex items-center justify-between mb-4">
                <button @click="openPermisos = false"
                     class="flex items-center gap-1 text-gray bg-purple-600 hover:bg-purple-700 px-2 py-1 rounded-lg text-sm transition">
            <button @click="openPermisos = false; openCreate = true"
                       class="flex items-center gap-1 text- bg-purple-600 hover:bg-purple-700 px-2 py-1 rounded-lg text-sm transition">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                          viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                               d="M15 19l-7-7 7-7" />
                     </svg>
                     Volver
                 </button>
                 <h2 class="text-lg font-semibold text-gray-800">Permisos del perfil</h2>
             </div>

            @php
                $modulos = [
                    'Usuarios','Perfiles','Áreas','Sucursales','Categorías','Datos de empresa',
                    'Incidencias','Reporte general','Reporte personalizado',
                    'Planes de acción','Regiones','Plantillas','Zonas','Logros','Asignaciones',
                    'Asignación por hora','Asignación por día','Asignación por evento','Asignación por frecuencia'
                ];

                $permisosList = [
                    ['label' => 'Ver', 'value' => 'ver'],
                    ['label' => 'Crear', 'value' => 'crear'],
                    ['label' => 'Editar', 'value' => 'editar'],
                    ['label' => 'Eliminar', 'value' => 'eliminar'],
                    ['label' => 'Deshabilitar', 'value' => 'deshabilitar'],
                    ['label' => 'Automatizar', 'value' => 'automatizar'],
                    ['label' => 'Realizar', 'value' => 'realizar'],
                    ['label' => 'Reasignar Checklist', 'value' => 'reasignar'],
                    ['label' => 'Suspender', 'value' => 'suspender'],
                ];
            @endphp
            <!-- grid compacto: 3 columnas en pantallas grandes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($modulos as $modulo)
                                    <div class="flex flex-col relative" x-data='{
                                        open: false,
                                        options: @json($permisosList),
                                        selected: [],
                
                                        toggle(opt) {
                                            const idx = this.selected.findIndex(s => s.value === opt.value)
                                            if (idx === -1) this.selected.push(opt)
                                            else this.selected.splice(idx, 1)
                                        },
                                        isSelected(opt) {
                                            return this.selected.some(s => s.value === opt.value)
                                        },
                                        remove(value) {
                                            const idx = this.selected.findIndex(s => s.value === value)
                                            if (idx !== -1) this.selected.splice(idx, 1)
                                        }
                                    }' @keydown.escape="open = false">
                        <label class="text-xs font-medium text-gray-700 mb-1">{{ $modulo }}</label>

                        <!-- contenedor pseudo-select (compacto) -->
                        <div :class="{'ring-2 ring-purple-400': open}" @click="open = !open" class="border border-gray-300 rounded-lg px-2 py-1 bg-white flex items-center justify-between cursor-pointer min-h-[34px]">
                            <div class="flex items-center gap-1 overflow-x-auto no-scrollbar">
                                <template x-if="selected.length === 0">
                                    <span class="text-gray-400 text-xs">Seleccione</span>
                                </template>

                                <template x-for="opt in selected" :key="opt.value">
                                    <span class="flex items-center gap-1 bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded-full mr-1">
                                        <span x-text="opt.label"></span>
                                        <button type="button" @click.stop="remove(opt.value)" class="text-gray-400 hover:text-gray-600 text-[10px] leading-none">✕</button>
                                    </span>
                                </template>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 ml-2 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <!-- dropdown sin checkboxes, con highlights y check a la derecha -->
                        <div x-show="open" x-cloak @click.away="open = false"
                             class="absolute left-0 z-50 mt-1 bg-white border border-gray-200 rounded-md shadow-lg p-1 w-56 max-h-48 overflow-auto text-sm">
                            <template x-for="opt in options" :key="opt.value">
                                <div
                                    @click.prevent="toggle(opt)"
                                    :class="isSelected(opt) ? 'bg-purple-50' : 'hover:bg-gray-50'"
                                    class="flex items-center justify-between gap-2 px-3 py-2 rounded cursor-pointer">
                                    <div class="text-sm" x-text="opt.label"></div>

                                    <!-- check visible si está seleccionado -->
                                    <svg x-show="isSelected(opt)" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 10-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </template>
                        </div>

                        <!-- inputs ocultos para enviar al backend -->
                        <template x-for="opt in selected" :key="opt.value">
                            <input type="hidden" :name="'permisos[{{ str_replace(' ', '_', $modulo) }}][]'" :value="opt.value">
                        </template>
                    </div>
                @endforeach
            </div>


            <div class="flex justify-end gap-2 mt-5">
                <button @click="openPermisos = false"
                        class="px-3 py-1.5 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm transition">
                    Cerrar
                </button>
                <button class="px-3 py-1.5 bg-purple-600 text-white hover:bg-purple-700 rounded-lg text-sm transition">
                    Guardar cambios
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

