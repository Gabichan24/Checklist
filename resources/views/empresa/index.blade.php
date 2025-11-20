@extends('layouts.app')

@section('content')
<div class="empresa-container" x-data="empresaData()">

    <h2 class="empresa-title">Configurar mi marca</h2>

    <form action="{{ route('empresa.update', $empresa->id_empresa) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Logo circular -->
        <div class="logo-section">
            <div class="logo-wrapper">
                <img :src="previewLogo" alt="Logo empresa" x-show="previewLogo">
                <span x-show="!previewLogo">Sin logo</span>
                <label>
                    <input type="file" name="logo" @change="loadPreview">
                </label>
            </div>
        </div>

        <!-- Tabla de inputs -->
        <div class="table-wrapper">
            <table>
                <tbody>
                    <tr>
                        <td>Nombre comercial</td>
                        <td>Razón social</td>
                        <td>RFC</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="nombre_comercial" value="{{ old('nombre_comercial', $empresa->nombre_comercial) }}"></td>
                        <td><input type="text" name="razon_social" value="{{ old('razon_social', $empresa->razon_social) }}"></td>
                        <td><input type="text" name="rfc" value="{{ old('rfc', $empresa->rfc) }}"></td>
                    </tr>

                    <tr>
                        <td>Dirección</td>
                        <td>Código Postal</td>
                        <td>Teléfono</td>
                    </tr>
                    <tr>
                        <td><input type="text" name="direccion" value="{{ old('direccion', $empresa->direccion) }}"></td>
                        <td><input type="text" name="codigo_postal" value="{{ old('codigo_postal', $empresa->codigo_postal) }}"></td>
                        <td><input type="text" name="telefono" value="{{ old('telefono', $empresa->telefono) }}"></td>
                    </tr>

                    <tr>
                        <td>Tolerancia (min)</td>
                        <td>Tiempo máximo respuesta (min)</td>
                        <td>Horario de notificaciones</td>
                    </tr>
                    <tr>
                        <td><input type="number" name="tolerancia" value="{{ old('tolerancia', $empresa->tolerancia) }}"></td>
                        <td><input type="number" name="tiempo_max_respuesta" value="{{ old('tiempo_max_respuesta', $empresa->tiempo_max_respuesta) }}"></td>
                        <td><input type="time" name="horario_notificaciones" value="{{ old('horario_notificaciones', $empresa->horario_notificaciones) }}"></td>
                    </tr>

                    <tr>
                        <td colspan="3" class="table-separator">Turno Diario</td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <label>Hora de inicio</label>
                            <input type="time" name="hora_ini" value="{{ old('hora_ini', $empresa->hora_ini) }}" class="input-time">
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Botón Guardar -->
        <div class="button-wrapper">
            <button type="submit" class="btn-guardar">Guardar</button>
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
<!-- -------------------- CSS -------------------- -->
<style>
/* Contenedor principal */
.empresa-container {
    max-width: 900px;
    margin: 2rem auto;
    background: #fff;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    font-family: sans-serif;
}

/* Título */
.empresa-title {
    font-size: 1.75rem;
    font-weight: bold;
    color: #4B5563;
    margin-bottom: 2rem;
}

/* Logo circular */
.logo-section {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.logo-wrapper {
    width: 5rem;
    height: 5rem;
    border-radius: 50%;
    border: 1px solid #D1D5DB;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F9FAFB;
    cursor: pointer;
    position: relative;
}
.logo-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.logo-wrapper span {
    font-size: 0.75rem;
    color: #9CA3AF;
    text-align: center;
}
.logo-wrapper input[type="file"] {
    display: none;
}

/* Fila de formulario (reemplaza tabla) */
.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}
.form-row label {
    flex: 1 1 150px;
    font-size: 0.875rem;
    color: #374151;
    font-weight: 500;
}
.form-row input[type="text"],
.form-row input[type="number"],
.form-row input[type="time"] {
    flex: 2 1 200px;
    padding: 0.5rem 0.75rem;
    border: none;
    border-radius: none;
    font-size: 0.875rem;
    color: #374151;
}

/* Inputs especiales */
.input-time {
    flex: 1 1 100px;
}

/* Separador de sección */
.section-separator {
    width: 100%;
    text-align: center;
    font-weight: 600;
    padding: 0.75rem 0;
    background: #E5E7EB;
    border-radius: none;
    margin: 2rem 0 1.5rem;
    font-size: 0.875rem;
    color: #4B5563;
}

/* Fila especial (Hora de inicio) */
.full-width-row {
    width: 100%;
    background: #F3F4F6;
    padding: 1rem;
    border-radius: none;
    margin-bottom: 1rem;
}
.full-width-row label {
    margin-bottom: 0.5rem;
    display: block;
}
.full-width-row input[type="time"] {
    width: 33%;
}

/* Botón guardar */
.button-wrapper {
    display: flex;
    justify-content: flex-end;
    margin-top: 2rem;
}
.btn-guardar {
    padding: 0.75rem 2rem;
    background: #7C3AED;
    color: #F3F4F6;
    font-weight: 500;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}
.btn-guardar:hover {
    background: #6D28D9;
}

/* Responsive */
@media (max-width: 640px) {
    .form-row {
        flex-direction: column;
    }
    .form-row label, 
    .form-row input[type="text"],
    .form-row input[type="number"],
    .form-row input[type="time"] {
        flex: 1 1 100%;
    }
    .input-time {
        width: 100%;
    }
}
</style>

@endsection
