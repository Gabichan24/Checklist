@extends('layouts.table')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Crear Región</h2>
    <form action="{{ route('regiones.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Nombre</label>
            <input type="text" name="nombre" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label for="estados" class="block mb-2 font-medium text-gray-700">Selecciona un estado:</label>
            <select id="estados" name="estados" class="border rounded px-3 py-2 w-full" required>
                <option value="">-- Selecciona --</option>
                @foreach($regiones as $region)
                    <option value="{{ $region['nombre'] }}">{{ $region['nombre'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-2">
            <a href="{{ route('regiones.index') }}" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancelar</a>
            <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-white hover:bg-purple-700">Guardar</button>
        </div>
    </form>
</div>
@endsection