@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen" x-data="{
    todoDia: false,
    noRepite: true,
    personalizado: false,
    diasRepite: []
}">
    
    <!-- Título -->
    <h1 class="text-2xl font-bold mb-6">Programar tarea</h1>

    <!-- Contenedor del formulario -->
    <div class="bg-white p-6 rounded shadow space-y-6">

        <!-- Botones superiores -->
        <div class="flex justify-between">
            <button class="px-4 py-2 border rounded hover:bg-gray-100">Limpiar</button>

            <div class="space-x-3">
                <button class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Plantilla</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
            </div>
        </div>


        <!-- Fechas -->
        <div>
            <h2 class="font-semibold mb-2">Fechas</h2>

            <!-- Fecha inicio -->
            <label class="block mb-1">Fecha de inicio</label>
            <input type="date" class="border rounded px-3 py-2 w-full mb-4">

            <!-- Rango de fechas -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1">De:</label>
                    <input type="datetime-local" class="border rounded px-3 py-2 w-full">
                </div>

                <div>
                    <label class="block mb-1">A:</label>
                    <input type="datetime-local" class="border rounded px-3 py-2 w-full">
                </div>
            </div>
        </div>


        <!-- Opciones de repetición -->
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <input type="checkbox" x-model="todoDia">
                <label>Todo el día</label>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" x-model="noRepite">
                <label>No se repite</label>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" x-model="personalizado" @click="noRepite = false">
                <label>Personalizado</label>
            </div>

            <!-- Termina -->
            <div>
                <label class="block font-medium mb-1">Termina</label>
                <input type="date" class="border rounded px-3 py-2 w-full">
            </div>
        </div>


        <!-- Repetición semanal -->
        <div x-show="personalizado" class="mt-4">
            <h3 class="font-semibold mb-2">Se repite el:</h3>

            <div class="grid grid-cols-7 gap-2 text-center">
                <template x-for="dia in ['D','L','M','M','J','V','S']">
                    <button
                        @click="diasRepite.includes(dia) ? diasRepite = diasRepite.filter(d => d!=dia) : diasRepite.push(dia)"
                        x-bind:class="diasRepite.includes(dia) ? 'bg-blue-600 text-white' : 'bg-gray-200'"
                        class="px-3 py-2 rounded hover:bg-gray-300">
                        <span x-text="dia"></span>
                    </button>
                </template>
            </div>
        </div>


        <!-- Asignar a -->
        <div>
            <label class="block font-medium mb-1">Asignar a:</label>
            <select class="border rounded px-3 py-2 w-full">
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Asignar a sucursal -->
        <div>
            <label class="block font-medium mb-1">Asignar a sucursal:</label>
            <select class="border rounded px-3 py-2 w-full">
                @foreach($sucursales as $s)
                    <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Usuarios seguidores -->
        <div>
            <label class="block font-medium mb-1">Usuarios seguidores:</label>

            <select multiple class="border rounded px-3 py-2 w-full h-32">
                @foreach($usuarios as $u)
                    <option value="{{ $u->id }}">{{ $u->nombre }}</option>
                @endforeach
            </select>

            <p class="text-sm text-gray-500 mt-1">* Mantén CTRL para seleccionar varios</p>
        </div>
    </div>
</div>
@endsection
