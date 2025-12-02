@extends('layouts.app')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-checklist">
    <!-- TÍTULO -->
    <h2>Crear Plantilla</h2>

    <!-- FORMULARIO CREAR CHECKLIST -->
    <form id="formChecklist" onsubmit="event.preventDefault(); crearChecklist();">
        <label>Nombre del Checklist</label>
        <input type="text" id="nombreChecklist" name="nombre" required>

        <label>Categoría</label>
        <select id="idCategoria" name="categoria" required>
            <option value="">Selecciona...</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>

        <label>Área</label>
        <select id="idArea" name="area" required>
            <option value="">Selecciona...</option>
            @foreach($areas as $area)
                <option value="{{ $area->id_area }}">{{ $area->nombre }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn">Crear plantilla</button>
    </form>

    <!-- Hidden para id del checklist creado -->
    <input type="hidden" id="checklist_id" value="">

    <!-- ACCIONES: agregar preguntas o cargar archivo -->
    <div id="acciones" class="actions" style="display:none; margin-top:16px;">
        <button class="btn" onclick="mostrarMenuPreguntas()">Agregar pregunta manual</button>

        <button class="btn-secondary" onclick="document.getElementById('filePreguntas').click()">
            Cargar Preguntas (xlsx)
        </button>
        <input type="file" id="filePreguntas" style="display:none" accept=".txt,.docx,.xlsx,.xls">

        <button id="btnGuardarPreguntas" class="btn-green" style="display:none;">Guardar preguntas</button>
    </div>

    <hr style="margin:18px 0">

    <!-- LISTA DE PREGUNTAS -->
    <h3>Preguntas cargadas</h3>
    <div id="listaPreguntas" class="question-list" style="display:none;">
        <div id="preguntas"></div>
        <p style="margin-top:12px;color:#555;font-size:0.9rem;">
            Puedes editar las preguntas antes de guardarlas (haz clic en el texto para editar).
        </p>
    </div>
</div>

<!-- ====================
     MODALES
==================== -->

<!-- Modal seleccionar tipo pregunta -->
<div id="modalPreguntaTipo" class="checklist-modal-bg">
    <div class="checklist-modal">
        <h3>Selecciona tipo de pregunta</h3>
        <button class="btn" onclick="abrirModalTexto()">Pregunta de Texto</button>
        <br><br>
        <button class="btn" onclick="abrirModalSiNo()">Pregunta Sí / No</button>
        <br><br>
        <button class="btn-secondary" onclick="cerrarModales()">Cerrar</button>
    </div>
</div>

<!-- Modal Texto -->
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
                <button class="btn" onclick="guardarTexto()">Agregar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Si/No -->
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
                <button class="btn" onclick="guardarSiNo()">Agregar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar Checklist -->
<div id="modalEliminar" class="checklist-modal-bg" style="display:none;">
    <div class="checklist-modal">
        <h3>Eliminar la Checklist</h3>
        <p>Al eliminar la checklist ya no podrá utilizarse.</p>
        <div style="display:flex; gap:8px; margin-top:12px;">
            <button class="btn-danger" onclick="confirmarEliminar()">Eliminar</button>
            <button class="btn-secondary" onclick="cerrarModales()">Cancelar</button>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let preguntasPendientes = [];

/* =========================
   CREAR CHECKLIST
========================= */
async function crearChecklist() {
    const nombre = document.getElementById('nombreChecklist').value.trim();
    const id_categoria = document.getElementById('idCategoria').value;
    const id_area = document.getElementById('idArea').value;

    if (!nombre || !id_categoria || !id_area) {
        alert('Completa nombre, categoría y área.');
        return;
    }

    try {
        const res = await fetch("{{ route('checklist.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ nombre_checklist: nombre, id_categoria, id_area })
        });
        const data = await res.json();

        if (data.success) {
            document.getElementById('checklist_id').value = data.checklist_id;
            document.getElementById('acciones').style.display = 'flex';
            document.getElementById('listaPreguntas').style.display = 'block';
            document.getElementById('btnGuardarPreguntas').style.display = 'inline-block';
            alert('Checklist creada. Ahora puedes agregar preguntas o cargar archivo.');
        } else {
            alert('Error al crear checklist: ' + (data.error || 'desconocido'));
        }
    } catch (err) {
        console.error(err);
        alert('Error al crear checklist. Revisa la consola.');
    }
}

/* =========================
   MODALES
========================= */
function mostrarMenuPreguntas() { document.getElementById('modalPreguntaTipo').style.display = 'flex'; }
function abrirModalTexto() { cerrarModales(); document.getElementById('modalTexto').style.display = 'flex'; }
function abrirModalSiNo() { cerrarModales(); document.getElementById('modalSiNo').style.display = 'flex'; }
function cerrarModales() { document.querySelectorAll('.checklist-modal-bg').forEach(m => m.style.display = 'none'); }

/* =========================
   AGREGAR PREGUNTAS MANUAL
========================= */
function guardarTexto() {
    const texto = document.getElementById('textoPregunta').value.trim();
    if (!texto) return alert('Escribe la pregunta.');
    preguntasPendientes.push({
        texto,
        tipo_item: 'texto',
        tipo_evidencias: document.querySelector("input[name='evTexto']:checked")?.value || 'comentario',
        esperado: '',
        puntuacion: Number(document.getElementById('textoPunt').value) || 0,
        obligatorio: document.getElementById('textoObligatorio').checked ? 1 : 0,
        plan_accion: document.getElementById('textoPlan').checked ? 1 : 0
    });
    renderPreguntas();
    cerrarModales();
    document.getElementById('textoPregunta').value = '';
}

function guardarSiNo() {
    const texto = document.getElementById('siPregunta').value.trim();
    if (!texto) return alert('Escribe la pregunta.');
    preguntasPendientes.push({
        texto,
        tipo_item: 'si_no',
        tipo_evidencias: document.querySelector("input[name='evSi']:checked")?.value || 'comentario',
        esperado: document.getElementById('siEsperado').value || '',
        puntuacion: Number(document.getElementById('siPunt').value) || 0,
        obligatorio: document.getElementById('siObligatorio').checked ? 1 : 0,
        plan_accion: document.getElementById('siPlan').checked ? 1 : 0
    });
    renderPreguntas();
    cerrarModales();
    document.getElementById('siPregunta').value = '';
}

/* =========================
   RENDERIZAR PREGUNTAS
========================= */
function renderPreguntas() {
    const cont = document.getElementById('preguntas');
    cont.innerHTML = '';
    preguntasPendientes.forEach((p, idx) => {
        const div = document.createElement('div');
        div.className = 'question-item pregunta-item';
        div.dataset.index = idx;
        div.style = 'padding:10px;border:1px solid #e6e6e6;border-radius:8px;margin-bottom:8px;background:#fff;';

        div.innerHTML = `
            <div style="display:flex; justify-content:space-between; gap:8px; align-items:center;">
                <div style="flex:1">
                    <div class="p-texto" contenteditable="true" style="font-weight:600; margin-bottom:6px;">${escapeHtml(p.texto)}</div>
                    <div style="font-size:0.9rem; color:#555;">
                        Tipo: <span class="p-tipo">${escapeHtml(p.tipo_item)}</span> |
                        Evidencia: <span class="p-evidencia">${escapeHtml(p.tipo_evidencias)}</span> |
                        Esperado: <span class="p-esperado">${escapeHtml(p.esperado)}</span> |
                        Puntos: <span class="p-puntos">${escapeHtml(String(p.puntuacion))}</span> |
                        Obligatorio: <span class="p-obligatorio">${p.obligatorio ? 'SI' : 'NO'}</span> |
                        Plan: <span class="p-plan">${p.plan_accion ? 'SI' : 'NO'}</span>
                    </div>
                </div>
                <div style="display:flex; gap:8px; margin-left:12px;">
                    <button class="btn-secondary" onclick="editarPregunta(${idx})">Guardar cambio</button>
                    <button class="btn-danger" onclick="eliminarPregunta(${idx})">Eliminar</button>
                </div>
            </div>
        `;
        cont.appendChild(div);
    });
}

/* =========================
   GUARDAR TODAS LAS PREGUNTAS EN BD
========================= */
document.getElementById('btnGuardarPreguntas').addEventListener('click', async function() {
    const checklist_id = document.getElementById('checklist_id').value;
    if (!checklist_id) return alert('Primero crea una plantilla.');
    if (preguntasPendientes.length === 0) return alert('No hay preguntas para guardar.');

    try {
        const res = await fetch("{{ route('checklist.guardarPreguntas') }}", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": csrfToken 
            },
            body: JSON.stringify({ checklist_id, preguntas: preguntasPendientes })
        });

        // Verificar si la respuesta es JSON
        const text = await res.text();
        let data;
        try { data = JSON.parse(text); } 
        catch(e) { throw new Error("El servidor no devolvió JSON: " + text); }

        if (data.success) {
            alert(data.message || 'Preguntas guardadas correctamente.');
            preguntasPendientes = [];
            renderPreguntas();
            document.getElementById('btnGuardarPreguntas').style.display = 'none';
            window.location.reload();
        } else {
            alert('Error al guardar preguntas: ' + (data.error || 'desconocido'));
        }
    } catch (err) {
        console.error(err);
        alert('Error al guardar preguntas. Revisa la consola.');
    }
});

/* =========================
   IMPORTAR EXCEL
========================= */
const fileInput = document.getElementById('filePreguntas');
fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const ext = file.name.split('.').pop().toLowerCase();
    if(ext === 'xlsx' || ext === 'xls') leerExcel(file);
    else alert('Solo se soporta Excel (.xlsx, .xls)');
    this.value = '';
});

function leerExcel(file){
    const reader = new FileReader();
    reader.onload = function(e){
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type:'array' });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        const rows = XLSX.utils.sheet_to_json(sheet, { header:1 });
        const nuevasPreguntas = [];

        rows.forEach((row, index) => {
            if(index === 0) return; // saltar encabezado
            const texto = row[0]?.toString().trim() || '';
            if(!texto) return;
            nuevasPreguntas.push({
                texto,
                tipo_item: row[1]?.toString().trim() || 'texto',
                tipo_evidencias: row[2]?.toString().trim() || 'comentario',
                esperado: row[3]?.toString().trim() || '',
                puntuacion: Number(row[4] || 0),
                obligatorio: row[5]?.toString().trim().toLowerCase() === 'si' ? 1 : 0,
                plan_accion: row[6]?.toString().trim().toLowerCase() === 'si' ? 1 : 0
            });
        });

        preguntasPendientes = preguntasPendientes.concat(nuevasPreguntas);
        renderPreguntas();
        if(nuevasPreguntas.length > 0) alert(`Se agregaron ${nuevasPreguntas.length} preguntas desde Excel`);
        else alert('No se encontraron preguntas válidas en el Excel');
    };
    reader.readAsArrayBuffer(file);
}


/* =========================
   FUNCIONES AUXILIARES
========================= */
function escapeHtml(text) {
    return text.replace(/[&<>"']/g, m => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' })[m]);
}

// Eliminar pregunta del arreglo
function eliminarPregunta(idx){
    if(confirm('¿Deseas eliminar esta pregunta?')){
        preguntasPendientes.splice(idx,1);
        renderPreguntas();
    }
}

// Editar pregunta antes de guardar
function editarPregunta(idx){
    const div = document.querySelector(`.pregunta-item[data-index='${idx}'] .p-texto`);
    if(div){
        const nuevoTexto = div.innerText.trim();
        if(!nuevoTexto){
            alert('El texto de la pregunta no puede estar vacío.');
            return;
        }
        preguntasPendientes[idx].texto = nuevoTexto;
        alert('Pregunta actualizada correctamente.');
        renderPreguntas();
    }
}

// Renderizar preguntas con botones de editar/eliminar
function renderPreguntas() {
    const cont = document.getElementById('preguntas');
    cont.innerHTML = '';
    preguntasPendientes.forEach((p, idx) => {
        const div = document.createElement('div');
        div.className = 'question-item pregunta-item';
        div.dataset.index = idx;
        div.style = 'padding:10px;border:1px solid #e6e6e6;border-radius:8px;margin-bottom:8px;background:#fff;';

        div.innerHTML = `
            <div style="display:flex; justify-content:space-between; gap:8px; align-items:center;">
                <div style="flex:1">
                    <div class="p-texto" contenteditable="true" style="font-weight:600; margin-bottom:6px;">${escapeHtml(p.texto)}</div>
                    <div style="font-size:0.9rem; color:#555;">
                        Tipo: <span class="p-tipo">${escapeHtml(p.tipo_item)}</span> |
                        Evidencia: <span class="p-evidencia">${escapeHtml(p.tipo_evidencias)}</span> |
                        Esperado: <span class="p-esperado">${escapeHtml(p.esperado)}</span> |
                        Puntos: <span class="p-puntos">${escapeHtml(String(p.puntuacion))}</span> |
                        Obligatorio: <span class="p-obligatorio">${p.obligatorio ? 'SI' : 'NO'}</span> |
                        Plan: <span class="p-plan">${p.plan_accion ? 'SI' : 'NO'}</span>
                    </div>
                </div>
                <div style="display:flex; gap:8px; margin-left:12px;">
                    <button class="btn-secondary" onclick="editarPregunta(${idx})">Guardar cambio</button>
                    <button class="btn-danger" onclick="eliminarPregunta(${idx})">Eliminar</button>
                </div>
            </div>
        `;
        cont.appendChild(div);
    });
}

</script>



<style>
/* RESET BÁSICO */
body, h1, h2, h3, ul, li, p {
    margin: 0;
    padding: 0;
}
body {
    font-family: Arial, sans-serif;
    background: #f3f4f6;
}

/* ===========================
   CONTENIDO CHECKLIST
=========================== */

.container-checklist {
    max-width: 900px;
    margin: 30px auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
}
/* FORMULARIOS */
label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
    color: #444;
}

input[type="text"],
input[type="number"],
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 15px;
}
/* BOTONES */
.btn {
    background: #1d4ed8;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: 0.2s;
}

.btn:hover { background: #163bb8; }
.btn-secondary { background: #6b7280; }
.btn-danger { background: #dc2626; }
.btn-green { background: #10b981; }

/* √ MODALES AISLADOS — SIN CHOQUES */
.checklist-modal-bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.55);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9000 !important;
}

.checklist-modal {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    width: 650px;
    max-width: 95%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    animation: modalFade .25s ease-out;
    display: flex;
    flex-direction: column;
}

/* GRID */
.checklist-modal-grid {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.checklist-modal-row {
    display: flex;
    flex-direction: column;
}

.checklist-input {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 100%;
}

/* Evidencias */
.checklist-evidence-option {
    margin-bottom: 8px;
}

/* Switches */
.checklist-switch {
    position: relative;
    width: 45px;
    height: 24px;
}

.checklist-switch input {
    opacity: 0;
}

.checklist-switch .slider {
    position: absolute;
    cursor: pointer;
    inset: 0;
    background: #ccc;
    transition: .3s;
    border-radius: 24px;
}

.checklist-switch .slider:before {
    content: "";
    position: absolute;
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .3s;
}

.checklist-switch input:checked + .slider {
    background: #4f46e5;
}

.checklist-switch input:checked + .slider:before {
    transform: translateX(20px);
}

@keyframes modalFade {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>

@endsection
