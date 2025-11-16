@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-8" x-data="empresaData()">

    <h2 class="text-2xl font-bold mb-6 text-gray-700">Configurar mi marca</h2>

    <form action="{{ route('empresa.update', $empresa->id_empresa) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. Imagen circular + input file invisible -->
        <div class="flex items-start mb-6 gap-6 relative">
            <div class="w-20 h-20 rounded-full overflow-hidden border border-gray-300 relative cursor-pointer">
                <img :src="previewLogo" alt="Logo empresa" class="object-cover w-full h-full rounded-full" x-show="previewLogo">
                <span x-show="!previewLogo" class="flex items-center justify-center w-full h-full text-gray-400 text-sm">Sin logo</span>

                <!-- Label transparente encima para seleccionar archivo -->
                <label class="absolute inset-0 cursor-pointer">
                    <input type="file" name="logo" @change="loadPreview" class="hidden">
                </label>
            </div>
        </div>

        <!-- Tabla de inputs -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full text-gray-700">
                <tbody>
                    <!-- Fila 1: Nombre comercial, Razón social, RFC -->
                    <tr>
                        <td class="px-4 py-2">Nombre comercial</td>
                        <td class="px-4 py-2">Razón social</td>
                        <td class="px-4 py-2">RFC</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2"><input type="text" name="nombre_comercial" value="{{ old('nombre_comercial', $empresa->nombre_comercial) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                        <td class="px-4 py-2"><input type="text" name="razon_social" value="{{ old('razon_social', $empresa->razon_social) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                        <td class="px-4 py-2"><input type="text" name="rfc" value="{{ old('rfc', $empresa->rfc) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                    </tr>

                    <!-- Fila 2: Dirección, Código Postal, Teléfono -->
                    <tr>
                        <td class="px-4 py-2">Dirección</td>
                        <td class="px-4 py-2">Código Postal</td>
                        <td class="px-4 py-2">Teléfono</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2"><input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                        <td class="px-4 py-2"><input type="text" name="codigo_postal" value="{{ old('codigo_postal', $empresa->codigo_postal) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                        <td class="px-4 py-2"><input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                    </tr>

                    <!-- Fila 3: Tolerancia, Tiempo máximo respuesta, Horario de notificaciones -->
                    <tr>
                        <td class="px-4 py-2">Tolerancia (min)</td>
                        <td class="px-4 py-2">Tiempo máximo respuesta (min)</td>
                        <td class="px-4 py-2">Horario de notificaciones</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2"><input type="number" name="tolerancia" value="{{ old('tolerancia', $empresa->tolerancia) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                        <td class="px-4 py-2"><input type="number" name="tiempo_max_respuesta" value="{{ old('tiempo_max_respuesta', $empresa->tiempo_max_respuesta) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                        <td class="px-4 py-2"><input type="time" name="horario_notificaciones" value="{{ old('horario_notificaciones', $empresa->horario_notificaciones) }}" class="w-full border rounded px-3 py-2 text-sm"></td>
                    </tr>

                    <!-- Separador: Turno Diario -->
                    <tr>
                        <td colspan="3" class="py-2 text-center border-b border-gray-300 font-semibold">Turno Diario</td>
                    </tr>

                    <!-- Hora de inicio -->
                    <tr>
                        <td colspan="3" class="px-4 py-2 w-1/3">
                            <label class="block text-sm font-medium mb-1">Hora de inicio</label>
                            <input type="time" name="hora_ini" value="{{ old('hora_ini', $empresa->hora_ini) }}" class="w-1/3 border rounded px-3 py-2 text-sm">
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Botón Guardar -->
        <div class="flex justify-end mt-6">
            <button type="submit" class="px-8 py-3 bg-purple-600 text-gray font-medium rounded hover:bg-purple-700 transition">
                Guardar
            </button>
        </div>

    </form>
</div>

<script>
function empresaData() {
    return {
        previewLogo: '{{ $empresa->logo ? Storage::url($empresa->logo) : "" }}',
        loadPreview(event) {
            const file = event.target.files[0];
            if(file) {
                this.previewLogo = URL.createObjectURL(file);
            }
        }
    }
}
</script>
@endsection
