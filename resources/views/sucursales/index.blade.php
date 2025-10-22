@extends('layouts.table')

@section('content')
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
    class="max-w-6xl mx-auto bg-white shadow rounded-lg p-6">

    <!-- Encabezado y botones -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Sucursales</h2>
        <button @click="open = true" class="bg-purple-600 text-gray px-4 py-2 rounded-lg hover:bg-purple-700">
            + Crear
        </button>
    </div>

    <!-- Buscador y exportación -->
    <div class="flex justify-between items-center mb-4 w-full">
        <div class="flex gap-2">
            <button onclick="window.location='{{ route('sucursales.export.xlsx') }}'" class="bg-blue-500 hover:bg-blue-600 text-gray px-3 py-2 rounded-lg text-sm">XLSX</button>
            <button onclick="window.location='{{ route('sucursales.export.csv') }}'" class="bg-blue-400 hover:bg-blue-500 text-gray px-3 py-2 rounded-lg text-sm">CSV</button>
        </div>

        <input type="search" placeholder="Buscar sucursal..." x-model="search"
            class="w-64 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder:text-gray-400">
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr class="text-center font-semibold">
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Clave</th>
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Zona</th>
                    <th class="px-4 py-2 border">Estatus</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="(sucursal, index) in filtered" :key="sucursal.id_sucursal">
                    <tr class="text-center hover:bg-gray-50">
                        <td x-text="sucursal.id_sucursal" class="px-4 py-2 border"></td>
                        <td x-text="sucursal.identificador" class="px-4 py-2 border"></td>
                        <td x-text="sucursal.nombre" class="px-4 py-2 border"></td>
                        <td x-text="sucursal.zona?.nombre ?? 'Sin zona'" class="px-4 py-2 border"></td>
                        <td class="px-4 py-2 border">
                            <span :class="sucursal.estatus === 'Activo' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
                                  class="px-2 py-1 rounded-full text-xs"
                                  x-text="sucursal.estatus"></span>
                        </td>
                        <td class="px-4 py-2 border flex justify-center gap-2">

                            <!-- Botón Editar -->
                            <button 
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
                                    $nextTick(() => {
                                        $refs.areasModal.seleccionadas = sucursal.id_area
                                            ? sucursal.id_area.split(',').map(Number)
                                            : [];
                                    });
                                "
                                class="bg-purple-600 text-white px-3 py-1 rounded-lg hover:bg-purple-700"
                                :disabled="sucursal.estatus === 'Inactivo'"
                                :class="sucursal.estatus === 'Inactivo' ? 'opacity-50 cursor-not-allowed' : ''">
                                ✏️
                            </button>

                            <!-- Botón activar/desactivar -->
                            <form :action="'/sucursales/toggle/' + sucursal.id_sucursal" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="text-white px-3 py-1 rounded"
                                    :class="sucursal.estatus === 'Activo'
                                            ? 'bg-gray-400 hover:bg-gray-500'
                                            : 'bg-yellow-400 hover:bg-yellow-500'">
                                    <span x-text="sucursal.estatus === 'Activo' ? '🔓' : '🔒'"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                </template>

                <tr x-show="filtered.length === 0">
                    <td colspan="6" class="px-4 py-2 border text-center text-gray-500">No hay sucursales</td>
                </tr>
            </tbody>
        </table>
    </div>

<!-- Modal Crear -->
<div x-show="open" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4" x-cloak>
    <div class="bg-white rounded-lg p-6 w-full max-w-xl max-h-[85vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4 text-center">Crear Sucursal</h3>

        <form action="{{ route('sucursales.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Nombre -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" class="w-full border rounded px-2 py-1 text-sm" required>
                </div>

                <!-- Zona -->
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Zona</label>
                    <select name="id_zona" class="w-full border rounded px-2 py-1 text-sm">
                        <option value="">-- Selecciona --</option>
                        @foreach($zonas as $zona)
                            <option value="{{ $zona->id_zona }}">{{ $zona->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Zona Horaria -->
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Zona Horaria</label>
                    <select name="zona_horaria" class="w-full border rounded px-2 py-1 text-sm">
                        <option value="">-- Selecciona --</option>
                        <option value="Ciudad de México (Centro)">Ciudad de México (Centro)</option>
                        <option value="Cancún / Quintana Roo (Sureste)">Cancún / Quintana Roo (Sureste)</option>
                        <option value="Chihuahua (Norte)">Chihuahua (Norte)</option>
                        <option value="Hermosillo / Sonora (Pacífico sin DST)">Hermosillo / Sonora (Pacífico sin DST)</option>
                        <option value="Mazatlán (Pacífico)">Mazatlán (Pacífico)</option>
                        <option value="Tijuana / Baja California (Noroeste)">Tijuana / Baja California (Noroeste)</option>
                    </select>
                </div>

                <!-- Área -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium mb-1">Área</label>
                    <div x-data="{
                        opciones: {{ $areas->map(fn($a)=>['id'=>$a->id_area,'nombre'=>$a->nombre])->toJson() }},
                        seleccionadas: [],
                        mostrar: false,
                        toggle(area) {
                            if (this.seleccionadas.includes(area.id)) {
                                this.seleccionadas = this.seleccionadas.filter(a => a !== area.id)
                            } else {
                                this.seleccionadas.push(area.id)
                            }
                        }
                    }"
                    x-init="$watch('seleccionadas', value => $refs.hiddenInputs.innerHTML = value.map(id => `<input type=hidden name=areas[] value=${id}>`).join(''))"
                    class="w-full border rounded px-2 py-1 text-sm bg-white relative">

                        <div @click="mostrar = !mostrar" class="flex flex-wrap items-center w-full text-sm cursor-pointer min-h-[38px]">
                            <template x-for="id in seleccionadas" :key="id">
                                <span class="flex items-center m-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                    <span x-text="opciones.find(o => o.id === id).nombre"></span>
                                    <button type="button" @click.stop="toggle(opciones.find(o => o.id === id))" class="ml-1 text-gray-500 hover:text-gray-700">✕</button>
                                </span>
                            </template>
                            <span x-show="seleccionadas.length === 0" class="text-gray-400">Selecciona áreas...</span>
                        </div>

                        <div x-show="mostrar" @click.outside="mostrar = false"
                             class="absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="area in opciones" :key="area.id">
                                <div @click="toggle(area)" class="px-4 py-2 cursor-pointer hover:bg-gray-100 flex items-center justify-between"
                                     :class="seleccionadas.includes(area.id) ? 'bg-gray-50' : ''">
                                    <span x-text="area.nombre"></span>
                                    <template x-if="seleccionadas.includes(area.id)">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div x-ref="hiddenInputs"></div>
                    </div>
                </div>

                <!-- Otros campos -->
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Identificador</label>
                    <input type="text" name="identificador" class="w-full border rounded px-2 py-1 text-sm">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Código Postal</label>
                    <input type="text" name="codigo_postal" class="w-full border rounded px-2 py-1 text-sm">
                </div>

                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium mb-1">Dirección</label>
                    <input type="text" name="direccion" class="w-full border rounded px-2 py-1 text-sm">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Latitud</label>
                    <input type="text" name="latitud" class="w-full border rounded px-2 py-1 text-sm">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Longitud</label>
                    <input type="text" name="longitud" class="w-full border rounded px-2 py-1 text-sm">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Radio</label>
                    <input type="number" name="radio" class="w-full border rounded px-2 py-1 text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4 sticky bottom-0 bg-white pt-4">
                <button type="button" @click="open = false" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-gray hover:bg-purple-700">Guardar</button>
            </div>
        </form>
    </div>
</div>

   <!-- Modal Editar -->
<div x-show="openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4" x-cloak>
    <div class="bg-white rounded-lg p-6 w-full max-w-xl max-h-[85vh] overflow-y-auto">

        <h3 class="text-lg font-bold mb-4 text-center" x-text="sucursalId === null ? 'Crear Sucursal' : 'Editar Sucursal'"></h3>

        <form :action="sucursalId === null ? '{{ route('sucursales.store') }}' : '/sucursales/' + sucursalId" 
              method="POST">
            @csrf
            <template x-if="sucursalId !== null">
                @method('PUT')
            </template>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Nombre -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="nombre" class="w-full border rounded px-2 py-1 text-sm"
                           x-model="nombre" required>
                </div>

                <!-- Zona -->
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Zona</label>
                    <select name="id_zona" class="w-full border rounded px-2 py-1 text-sm" x-model="id_zona">
                        <option value="">-- Selecciona --</option>
                        @foreach($zonas as $zona)
                            <option value="{{ $zona->id_zona }}">{{ $zona->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Zona Horaria -->
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Zona Horaria</label>
                    <select name="zona_horaria" class="w-full border rounded px-2 py-1 text-sm" x-model="zona_horaria">
                        <option value="">-- Selecciona --</option>
                        <option value="Ciudad de México (Centro)">Ciudad de México (Centro)</option>
                        <option value="Cancún / Quintana Roo (Sureste)">Cancún / Quintana Roo (Sureste)</option>
                        <option value="Chihuahua (Norte)">Chihuahua (Norte)</option>
                        <option value="Hermosillo / Sonora (Pacífico sin DST)">Hermosillo / Sonora (Pacífico sin DST)</option>
                        <option value="Mazatlán (Pacífico)">Mazatlán (Pacífico)</option>
                        <option value="Tijuana / Baja California (Noroeste)">Tijuana / Baja California (Noroeste)</option>
                    </select>
                </div>

                <!-- Área -->
                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium mb-1">Área</label>
                    <div x-data="{
                        opciones: {{ $areas->map(fn($a)=>['id'=>$a->id_area,'nombre'=>$a->nombre])->toJson() }},
                        seleccionadas: [],
                        mostrar: false,
                        toggle(area) {
                            if (this.seleccionadas.includes(area.id)) {
                                this.seleccionadas = this.seleccionadas.filter(a => a !== area.id)
                            } else {
                                this.seleccionadas.push(area.id)
                            }
                        }
                    }"
                    x-init="
                        $watch('seleccionadas', value => $refs.hiddenInputs.innerHTML = value.map(id => `<input type=hidden name=areas[] value=${id}>`).join(''));
                        $watch('openModal', () => {
                            if(openModal && sucursalId !== null){
                                seleccionadas = sucursales.find(s => s.id_sucursal === sucursalId).id_area
                                                ? sucursales.find(s => s.id_sucursal === sucursalId).id_area.split(',').map(Number)
                                                : [];
                            } else {
                                seleccionadas = [];
                            }
                        })
                    "
                    class="w-full border rounded px-2 py-1 text-sm bg-white relative">

                        <!-- Caja principal -->
                        <div @click="mostrar = !mostrar" class="flex flex-wrap items-center w-full text-sm cursor-pointer min-h-[38px]">
                            <template x-for="id in seleccionadas" :key="id">
                                <span class="flex items-center m-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                    <span x-text="opciones.find(o => o.id === id).nombre"></span>
                                    <button type="button" @click.stop="toggle(opciones.find(o => o.id === id))" class="ml-1 text-gray-500 hover:text-gray-700">✕</button>
                                </span>
                            </template>
                            <span x-show="seleccionadas.length === 0" class="text-gray-400">Selecciona áreas...</span>
                        </div>

                        <!-- Opciones desplegables -->
                        <div x-show="mostrar" @click.outside="mostrar = false"
                             class="absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-y-auto">
                            <template x-for="area in opciones" :key="area.id">
                                <div @click="toggle(area)"
                                     class="px-4 py-2 cursor-pointer hover:bg-gray-100 flex items-center justify-between"
                                     :class="seleccionadas.includes(area.id) ? 'bg-gray-50' : ''">
                                    <span x-text="area.nombre"></span>
                                    <template x-if="seleccionadas.includes(area.id)">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Inputs ocultos -->
                        <div x-ref="hiddenInputs"></div>
                    </div>
                </div>

                <!-- Otros campos (identificador, código postal, dirección, lat/lng/radio) -->
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Identificador</label>
                    <input type="text" name="identificador" class="w-full border rounded px-2 py-1 text-sm" x-model="identificador">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Código Postal</label>
                    <input type="text" name="codigo_postal" class="w-full border rounded px-2 py-1 text-sm" x-model="codigo_postal">
                </div>

                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium mb-1">Dirección</label>
                    <input type="text" name="direccion" class="w-full border rounded px-2 py-1 text-sm" x-model="direccion">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Latitud</label>
                    <input type="text" name="latitud" class="w-full border rounded px-2 py-1 text-sm" x-model="latitud">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Longitud</label>
                    <input type="text" name="longitud" class="w-full border rounded px-2 py-1 text-sm" x-model="longitud">
                </div>

                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium mb-1">Radio</label>
                    <input type="number" name="radio" class="w-full border rounded px-2 py-1 text-sm" x-model="radio">
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4 sticky bottom-0 bg-white pt-4">
                <button type="button" @click="openModal = false; sucursalId = null" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-gray hover:bg-purple-700">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

