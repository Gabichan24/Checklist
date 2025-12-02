@extends('layouts.app')

@section('content')
<div class="container-checklist">

    <h2 class="titulo-modulo">Mis Plantillas</h2>

    <!-- LISTA -->
    <div id="vistaLista">
        @if($plantillas->count() == 0)
            <p>No hay plantillas registradas.</p>
        @else
            <table class="tabla-plantillas">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Área</th>
                        <th>estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plantillas as $p)
                        <tr>
                            <td>{{ $p->id_checklist }}</td>
                            <td>{{ $p->nombre_checklist }}</td>
                            <td>{{ $p->categoria->nombre ?? '—' }}</td>
                            <td>{{ $p->area->nombre ?? '—' }}</td>
                            <td>{{ $p->estado }}</td>
                            <td class="acciones">

                                <!-- EDITAR -->
                                <a href="{{ route('checklist.editar', $p->id_checklist) }}" class="btn-editar">
                                    Editar
                                </a>

                                <!-- DUPLICAR -->
                                <a href="{{ route('checklist.duplicar', $p->id_checklist) }}" class="btn-duplicar">
                                    Duplicar
                                </a>

                                <!-- EXPORTAR -->
                                <a href="{{ route('checklist.exportar', $p->id_checklist) }}" class="btn-exportar">
                                    Exportar
                                </a>

                                <!-- ELIMINAR -->
                                <a onclick="eliminar({{ $p->id_checklist }})" 
                                   class="btn-eliminar" style="cursor:pointer;">
                                   Eliminar
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

<script>

function eliminar(id) {
    if(!confirm("¿Seguro que deseas eliminar esta plantilla?")) return;

    fetch(`/checklist/eliminar/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}

</script>

@endsection


