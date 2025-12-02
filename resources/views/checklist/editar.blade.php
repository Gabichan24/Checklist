@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-checklist">

    <h2>Editar Plantilla</h2>

    <!-- ===========================
         FORMULARIO EDITAR PLANTILLA
    ============================ -->
    <form id="formEditarChecklist"
          data-id="{{ $checklist->id_checklist }}"
          onsubmit="event.preventDefault(); actualizarChecklist();">

        <label>Nombre del Checklist</label>
        <input type="text" name="nombre" value="{{ $checklist->nombre_checklist }}" required>

        <label>Categoría</label>
        <select name="categoria" required>
            @foreach($categorias as $c)
                <option value="{{ $c->id_categoria }}"
                    {{ $c->id_categoria == $checklist->id_categoria ? 'selected' : '' }}>
                    {{ $c->nombre }}
                </option>
            @endforeach
        </select>

        <label>Área</label>
        <select name="area" required>
            @foreach($areas as $a)
                <option value="{{ $a->id_area }}"
                    {{ $a->id_area == $checklist->id_area ? 'selected' : '' }}>
                    {{ $a->nombre }}
                </option>
            @endforeach
        </select>

    </form>


    <!-- ===========================
         AGREGAR PREGUNTAS
    ============================ -->

    <button class="btn" onclick="abrirModalTipoPregunta()">
        ➕ Agregar Pregunta
    </button>

    <button class="btn" onclick="document.getElementById('inputExcel').click();">
    📄 Cargar preguntas
</button>

<form id="formExcel" enctype="multipart/form-data" style="display:none;">
    <input type="file" id="inputExcel" name="archivo"
           accept=".xlsx,.xls"
           onchange="subirExcel(event)">
</form>

    <!-- Guardar cambios -->
        <button class="btn btn-guardar" onclick="actualizarChecklist()">
            💾 Guardar
        </button>

        <!-- Eliminar plantilla -->
        <form action="{{ route('checklist.eliminar', $checklist->id_checklist) }}"
              method="POST"
              onsubmit="return confirm('¿Seguro que deseas eliminar esta plantilla?');"
              style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button class="btn btn-eliminar">🗑 Eliminar</button>
        </form>

     <!-- =======================
         BOTONES SUPERIORES
    ======================== -->
    <div class="acciones-superiores">

        
        <!-- Aprobar plantilla -->
        @if($checklist->estado !== 'Aprobado')
            <button class="btn btn-aprobar" onclick="aprobarChecklist({{ $checklist->id_checklist }})">
                ✔ Aprobar
            </button>
        @endif

    <!-- ===========================
         TABLA DE ITEMS
    ============================ -->
    <table class="tabla-items">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Pregunta</th>
                <th>Puntuacion</th>
                <th>Plan de accion</th>
                <th>Evidencia</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody id="listaItems">
            @foreach($items as $item)
                <tr data-id="{{ $item->id_item }}">
                    <td>{{ $item->tipo_item }}</td>
                    <td>{{ $item->nombre_item }}</td>
                    <td>{{ $item->puntuacion }}</td>
                    <td>{{ $item->plan_accion }}</td>
                    <td>{{ $item->tipo_evidencias }}</td>

                    <td>
                        <button onclick="eliminarItem({{ $item->id_item }})"
                                class="btn-eliminar">
                            🗑
                        </button>
                        <button onclick="editarItem({{ $item->id_item }})"
                                class="btn-editar">
                            Editar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>





<!-- ===========================
     MODAL: Elegir tipo pregunta
=========================== -->
<div id="modalTipoPregunta" class="checklist-modal-bg">
    <div class="checklist-modal">
        <h3>Selecciona tipo de pregunta</h3>

        <button class="btn" onclick="abrirModalTexto()">Pregunta de Texto</button>
        <br><br>

        <button class="btn" onclick="abrirModalSiNo()">Pregunta Sí/No</button>

        <br><br>
        <button class="btn-cerrar" onclick="cerrarModalTipoPregunta()">Cerrar</button>
    </div>
</div>


<!-- ===========================
     MODAL: Pregunta Texto
=========================== -->
<div id="modalTexto" class="checklist-modal-bg">
    <div class="checklist-modal">
        <h3>Pregunta de Texto</h3>
        <div class="checklist-modal-grid">
            <div class="checklist-modal-row">
                <label>Pregunta</label>
                <input type="text" id="textoPregunta" class="checklist-input">
            </div>
            <div class="checklist-modal-row">
                <label>Tipo de Evidencia</label>
                <div class="checklist-evidence-option"><label><input type="radio" name="evTexto" value="comentario" checked> Observaciones</label></div>
                <div class="checklist-evidence-option"><label><input type="radio" name="evTexto" value="foto"> Foto</label></div>
                <div class="checklist-evidence-option"><label><input type="radio" name="evTexto" value="documento"> Archivo</label></div>
            </div>
            <div class="checklist-modal-row">
                <label>Puntuación</label>
                <input type="number" id="textoPunt" value="0" min="0" class="checklist-input">
            </div>
            <div class="checklist-modal-row">
                <label>Es obligatorio</label>
                <label class="checklist-switch"><input type="checkbox" id="textoObligatorio"><span class="slider"></span></label>
            </div>
            <div class="checklist-modal-row">
                <label>Plan de acción</label>
                <label class="checklist-switch"><input type="checkbox" id="textoPlan"><span class="slider"></span></label>
            </div>

            <div style="display:flex; gap:8px; margin-top:12px;">
                <button class="btn-secondary" onclick="cerrarModales()">Cancelar</button>
                <button type="button" class="btn" onclick="actualizarTexto()">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<!-- ===========================
     MODAL: Pregunta Sí/No
=========================== -->
<div id="modalSiNo" class="checklist-modal-bg">
    <div class="checklist-modal">
        <h3>Pregunta Sí / No</h3>
        <div class="checklist-modal-grid">
            <div class="checklist-modal-row">
                <label>Descripción</label>
                <input type="text" id="siPregunta" class="checklist-input">
            </div>
            <div class="checklist-modal-row">
                <label>Tipo de Evidencia</label>
                <div class="checklist-evidence-option"><label><input type="radio" name="evSi" value="comentario" checked> Observaciones</label></div>
                <div class="checklist-evidence-option"><label><input type="radio" name="evSi" value="foto"> Foto</label></div>
                <div class="checklist-evidence-option"><label><input type="radio" name="evSi" value="documento"> Archivo</label></div>
            </div>
            <div class="checklist-modal-row">
                <label>Puntuación</label>
                <input type="number" id="siPunt" value="0" min="0" class="checklist-input">
            </div>
            <div class="checklist-modal-row">
                <label>Respuesta esperada</label>
                <select id="siEsperado" class="checklist-input"><option value="SI">SI</option><option value="NO">NO</option></select>
            </div>
            <div class="checklist-modal-row">
                <label>Es obligatorio</label>
                <label class="checklist-switch"><input type="checkbox" id="siObligatorio"><span class="slider"></span></label>
            </div>
            <div class="checklist-modal-row">
                <label>Plan de acción</label>
                <label class="checklist-switch"><input type="checkbox" id="siPlan"><span class="slider"></span></label>
            </div>

            <div style="display:flex; gap:8px; margin-top:12px;">
                <button class="btn-secondary" onclick="cerrarModales()">Cancelar</button>
                <button type="button" class="btn" onclick="actualizarSiNo()">Actualizar</button>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
/* ===========================================
   VARIABLES GLOBALES
=========================================== */
let preguntasExcel = []; // preguntas cargadas desde Excel
let preguntasPendientes = []; // items ya guardados desde DB

/* ===========================================
   MODALES
=========================================== */
function cerrarTodosLosModales() {
    document.querySelectorAll('.checklist-modal-bg').forEach(m => m.style.display = "none");
}

function abrirModalTipoPregunta() {
    cerrarTodosLosModales();
    document.getElementById("modalTipoPregunta").style.display = "flex";
}

function cerrarModalTipoPregunta() {
    document.getElementById("modalTipoPregunta").style.display = "none";
}

function abrirModalTexto() {
    cerrarTodosLosModales();
    const modal = document.getElementById("modalTexto");
    modal.dataset.idItem = ''; 
    modal.dataset.desdeExcel = 'false';
    document.getElementById("textoPregunta").value = '';
    document.getElementById("textoPunt").value = 0;
    document.getElementById("textoPlan").checked = false;
    document.getElementById("textoObligatorio").checked = false;
    document.querySelector("input[name='evTexto'][value='comentario']").checked = true;
    modal.style.display = "flex";
}

function abrirModalSiNo() {
    cerrarTodosLosModales();
    const modal = document.getElementById("modalSiNo");
    modal.dataset.idItem = '';
    modal.dataset.desdeExcel = 'false';
    document.getElementById("siPregunta").value = '';
    document.getElementById("siPunt").value = 0;
    document.getElementById("siPlan").checked = false;
    document.getElementById("siObligatorio").checked = false;
    document.getElementById("siEsperado").value = 'SI';
    document.querySelector("input[name='evSi'][value='comentario']").checked = true;
    modal.style.display = "flex";
}

function cerrarModales() {
    cerrarTodosLosModales();
}

/* ===========================================
   TABLA DINÁMICA
=========================================== */
function agregarFila(item, desdeExcel=false) {
    const tbody = document.getElementById("listaItems");
    const id = desdeExcel ? `excel-${Math.random().toString(36).substr(2, 9)}` : item.id_item;
    let fila = `
        <tr data-id="${id}" data-desde-excel="${desdeExcel}">
            <td>${item.tipo_item}</td>
            <td>${item.nombre_item}</td>
            <td>${item.puntuacion}</td>
            <td>${item.plan_accion ? 'Sí' : 'No'}</td>
            <td>${item.tipo_evidencias}</td>
            <td>
                <button onclick="editarItem('${id}')" class="btn-editar">Editar</button>
                <button onclick="eliminarItem('${id}')" class="btn-eliminar">🗑</button>
            </td>
        </tr>
    `;
    tbody.insertAdjacentHTML("beforeend", fila);
}

/* ===========================================
   NUEVAS PREGUNTAS
=========================================== */
function guardarTexto() {
    let idChecklist = document.getElementById("formEditarChecklist").dataset.id;
    let nombre = document.getElementById("textoPregunta").value.trim();
    let evidencia = document.querySelector("input[name='evTexto']:checked").value;
    let puntuacion = document.getElementById("textoPunt").value;
    let obligatorio = document.getElementById("textoObligatorio").checked ? 1 : 0;
    let plan = document.getElementById("textoPlan").checked ? 1 : 0;

    if(!nombre) { alert("Escribe la pregunta"); return; }

    fetch(`/items/agregar`, {
        method: "POST",
        headers: {"Content-Type": "application/json","X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content},
        body: JSON.stringify({
            id_checklist:idChecklist,
            tipo_item:"texto",
            nombre_item:nombre,
            tipo_evidencias:evidencia,
            puntuacion,
            plan_accion:plan,
            pregunta_obligatoria:obligatorio
        })
    })
    .then(r=>r.json())
    .then(item=>{
        agregarFila(item);
        cerrarModales();
    });
}

function guardarSiNo() {
    let idChecklist = document.getElementById("formEditarChecklist").dataset.id;
    let nombre = document.getElementById("siPregunta").value.trim();
    let evidencia = document.querySelector("input[name='evSi']:checked").value;
    let puntuacion = document.getElementById("siPunt").value;
    let esperado = document.getElementById("siEsperado").value;
    let obligatorio = document.getElementById("siObligatorio").checked ? 1 : 0;
    let plan = document.getElementById("siPlan").checked ? 1 : 0;

    if(!nombre) { alert("Escribe la pregunta"); return; }

    fetch(`/items/agregar`, {
        method: "POST",
        headers: {"Content-Type": "application/json","X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content},
        body: JSON.stringify({
            id_checklist:idChecklist,
            tipo_item:"si_no",
            nombre_item:nombre,
            tipo_evidencias:evidencia,
            puntuacion,
            respuesta_esperada:esperado,
            plan_accion:plan,
            pregunta_obligatoria:obligatorio
        })
    })
    .then(r=>r.json())
    .then(item=>{
        agregarFila(item);
        cerrarModales();
    });
}

/* ===========================================
   EDITAR ITEMS
=========================================== */
function editarItem(idItem){
    const fila = document.querySelector(`tr[data-id='${idItem}']`);
    if(!fila) return;
    const desdeExcel = fila.dataset.desdeExcel === "true";

    const tipo = fila.children[0].textContent.trim();
    const nombre = fila.children[1].textContent.trim();
    const puntuacion = fila.children[2].textContent.trim()==='-'?0:Number(fila.children[2].textContent.trim());
    const plan = fila.children[3].textContent.trim()==='Sí';
    const evidencia = fila.children[4].textContent.trim();

    if(tipo==='texto'){
        abrirModalTexto();
        document.getElementById("textoPregunta").value = nombre;
        document.getElementById("textoPunt").value = puntuacion;
        document.getElementById("textoPlan").checked = plan;
        const evRadio = document.querySelector(`input[name='evTexto'][value='${evidencia}']`);
        if(evRadio) evRadio.checked = true;
        document.getElementById("modalTexto").dataset.idItem = idItem;
        document.getElementById("modalTexto").dataset.desdeExcel = desdeExcel;
    } else if(tipo==='si_no'){
        abrirModalSiNo();
        document.getElementById("siPregunta").value = nombre;
        document.getElementById("siPunt").value = puntuacion;
        document.getElementById("siPlan").checked = plan;
        const evRadio = document.querySelector(`input[name='evSi'][value='${evidencia}']`);
        if(evRadio) evRadio.checked = true;
        document.getElementById("modalSiNo").dataset.idItem = idItem;
        document.getElementById("modalSiNo").dataset.desdeExcel = desdeExcel;
    }
}

/* ===========================================
   ACTUALIZAR ITEMS
=========================================== */
function actualizarTexto(){
    const modal = document.getElementById("modalTexto");
    const idItem = modal.dataset.idItem;
    const desdeExcel = modal.dataset.desdeExcel === "true";
    const nombre = document.getElementById("textoPregunta").value.trim();
    const puntuacion = document.getElementById("textoPunt").value;
    const plan = document.getElementById("textoPlan").checked ? 1 : 0;
    const evidencia = document.querySelector("input[name='evTexto']:checked").value;

    if(desdeExcel){
        const fila = document.querySelector(`tr[data-id='${idItem}']`);
        fila.children[1].textContent = nombre;
        fila.children[2].textContent = puntuacion;
        fila.children[3].textContent = plan?'Sí':'No';
        fila.children[4].textContent = evidencia;

        const index = preguntasExcel.findIndex(p=>p.nombre_item===fila.children[1].textContent);
        if(index>=0){
            preguntasExcel[index].nombre_item = nombre;
            preguntasExcel[index].puntuacion = puntuacion;
            preguntasExcel[index].plan_accion = plan;
            preguntasExcel[index].tipo_evidencias = evidencia;
        }
    } else {
        fetch(`/items/${idItem}/actualizar`,{
            method:'PUT',
            headers:{"Content-Type":"application/json","X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content},
            body:JSON.stringify({nombre_item:nombre,puntuacion,plan_accion:plan,tipo_evidencias:evidencia})
        }).then(r=>r.json()).then(item=>{
            const fila = document.querySelector(`tr[data-id='${idItem}']`);
            fila.children[1].textContent = item.nombre_item;
            fila.children[2].textContent = item.puntuacion;
            fila.children[3].textContent = item.plan_accion?'Sí':'No';
            fila.children[4].textContent = item.tipo_evidencias;
        });
    }
    cerrarModales();
}

function actualizarSiNo(){
    const modal = document.getElementById("modalSiNo");
    const idItem = modal.dataset.idItem;
    const desdeExcel = modal.dataset.desdeExcel === "true";
    const nombre = document.getElementById("siPregunta").value.trim();
    const puntuacion = document.getElementById("siPunt").value;
    const plan = document.getElementById("siPlan").checked ? 1 : 0;
    const evidencia = document.querySelector("input[name='evSi']:checked").value;
    const esperado = document.getElementById("siEsperado").value;

    if(desdeExcel){
        const fila = document.querySelector(`tr[data-id='${idItem}']`);
        fila.children[1].textContent = nombre;
        fila.children[2].textContent = puntuacion;
        fila.children[3].textContent = plan?'Sí':'No';
        fila.children[4].textContent = evidencia;

        const index = preguntasExcel.findIndex(p=>p.nombre_item===fila.children[1].textContent);
        if(index>=0){
            preguntasExcel[index].nombre_item = nombre;
            preguntasExcel[index].puntuacion = puntuacion;
            preguntasExcel[index].plan_accion = plan;
            preguntasExcel[index].tipo_evidencias = evidencia;
        }
    } else {
        fetch(`/items/${idItem}/actualizar`,{
            method:'PUT',
            headers:{"Content-Type":"application/json","X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content},
            body:JSON.stringify({nombre_item:nombre,puntuacion,plan_accion:plan,tipo_evidencias:evidencia,respuesta_esperada:esperado})
        }).then(r=>r.json()).then(item=>{
            const fila = document.querySelector(`tr[data-id='${idItem}']`);
            fila.children[1].textContent = item.nombre_item;
            fila.children[2].textContent = item.puntuacion;
            fila.children[3].textContent = item.plan_accion?'Sí':'No';
            fila.children[4].textContent = item.tipo_evidencias;
        });
    }
    cerrarModales();
}

/* ===========================================
   ELIMINAR ITEMS
=========================================== */
function eliminarItem(idItem){
    const fila = document.querySelector(`tr[data-id='${idItem}']`);
    const desdeExcel = fila.dataset.desdeExcel === "true";
    if(!confirm("¿Eliminar esta pregunta?")) return;
    if(desdeExcel){
        fila.remove();
        preguntasExcel = preguntasExcel.filter(p=>p.nombre_item!==fila.children[1].textContent);
    } else {
        fetch(`/items/${idItem}`,{
            method:'DELETE',
            headers:{"X-CSRF-TOKEN":document.querySelector("meta[name='csrf-token']").content}
        }).then(()=>fila.remove());
    }
}

/* ===========================================
   ACTUALIZAR CHECKLIST + GUARDAR PREGUNTAS EXCEL
=========================================== */
function actualizarChecklist(){
    let form = document.getElementById("formEditarChecklist");
    let id = form.dataset.id;
    let datos = {nombre:form.nombre.value, categoria:form.categoria.value, area:form.area.value};

    fetch(`/checklist/${id}/actualizar`,{
        method:'PUT',
        headers: {"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector("meta[name='csrf-token']").content},
        body: JSON.stringify(datos)
    }).then(r=>r.json())
    .then(()=>{
        if(preguntasExcel.length>0){
            fetch(`/items/agregar-masivo`,{
                method:'POST',
                headers: {"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector("meta[name='csrf-token']").content},
                body: JSON.stringify({id_checklist:id, items: preguntasExcel})
            }).then(()=>alert("Checklist y preguntas Excel guardadas correctamente"));
        } else {
            alert("Checklist actualizado correctamente");
        }
    });
}

/* ===========================================
   APROBAR CHECKLIST
=========================================== */
function aprobarChecklist(idChecklist){
    if(!confirm("¿Aprobar esta plantilla?")) return;
    fetch(`/checklist/${idChecklist}/aprobar`,{method:'PUT', headers:{"X-CSRF-TOKEN":document.querySelector("meta[name='csrf-token']").content}})
    .then(r=>r.json()).then(()=>location.reload());
}

/* ===========================================
   EXCEL
=========================================== */
const inputExcel = document.getElementById('inputExcel');
if(inputExcel){
    inputExcel.addEventListener('change', function(e){
        const file = e.target.files[0];
        if(!file) return;
        const ext = file.name.split('.').pop().toLowerCase();
        if(ext==='xlsx'||ext==='xls') leerExcel(file);
        else alert('Solo se soportan archivos Excel (.xlsx, .xls)');
        this.value='';
    });
}

function leerExcel(archivo){
    const lector = new FileReader();
    lector.onload = function(evento){
        const datos = new Uint8Array(evento.target.result);
        const workbook = XLSX.read(datos,{type:"array"});
        const hoja = workbook.Sheets[workbook.SheetNames[0]];
        const filas = XLSX.utils.sheet_to_json(hoja,{header:1});
        preguntasExcel = [];
        filas.forEach((fila,i)=>{
            if(i===0) return;
            if(!fila[0]) return;
            let p = {
                tipo_item: fila[1] || "texto",
                nombre_item: fila[0],
                puntuacion: fila[4] || 0,
                plan_accion: fila[6]?.toString().toLowerCase()==="si"?1:0,
                tipo_evidencias: fila[2] || "comentario"
            };
            preguntasExcel.push(p);
            agregarFila(p,true);
        });
    };
    lector.readAsArrayBuffer(archivo);
}
</script>

<!-- ============================
     ESTILOS
============================== -->
<style>
    .container-checklist{
        max-width: 800px;
        margin: 20px auto;
        padding: 25px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    .tabla-items{
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }
    .tabla-items th, .tabla-items td{
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    .btn-agregar, .btn-guardar, .btn{
        background: #3498db;
        color: #fff;
        border: none;
        padding: 10px 18px;
        border-radius: 7px;
        cursor: pointer;
    }
    .btn-eliminar{
        background: #e74c3c;
        color: #fff;
        padding: 7px 14px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }
    .checklist-modal-bg{
        display:none;
        position: fixed;
        top:0;left:0;right:0;bottom:0;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 10;
    }
    .checklist-modal{
        background:#fff;
        width: 380px;
        padding: 25px;
        border-radius: 10px;
        text-align:center;
    }
</style>
@endsection
