@extends('layouts.app')

@section('content')
<style>
/* ===== GLOBAL ===== */
:root{
    --bg: #f7f7f8;
    --card: #ffffff;
    --muted: #6b7280;
    --text: #1f2937;
    --green: #10b981;
    --purple:#7c3aed;
    --red:#ef4444;
    --yellow:#f59e0b;
    --shadow: rgba(2,6,23,0.08);
    --glass: rgba(0,0,0,0.5);
    --max-width: 1200px;
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --input-border: #e6e7eb;
}

/* reset / base */
*{box-sizing:border-box}
body{font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; color:var(--text); margin:0; padding:0; background:var(--bg);}
.container-page{max-width:var(--max-width); margin:0 auto; padding:24px;}
.hidden{display:none}

/* header */
.header-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
.header-title{font-size:20px;font-weight:700;color:var(--text);margin:0;}
.header-sub{color:var(--muted);font-size:13px;margin-top:4px}

/* buttons & inputs */
.controls { display:flex; gap:12px; align-items:center; }
.btn { border:0; padding:8px 12px; border-radius:8px; cursor:pointer; font-weight:600; display:inline-flex; align-items:center; gap:8px; }
.btn-create { background:var(--green); color:#fff; }
.btn-create:hover { opacity:0.95; }
.btn-gray { background:#9ca3af; color:#fff; }
.btn-close { background:#9ca3af; color:#fff; }
.search-input, .select-input, input[type="text"], input[type="email"], select, textarea, input[type="date"]{
    padding:8px 10px; border:1px solid var(--input-border); border-radius:8px; font-size:14px; background:#fff;
}
.row-gap { display:flex; gap:12px; align-items:center; }

/* grid cards */
.cards-grid{display:grid;grid-template-columns:repeat(auto-fit, minmax(260px,1fr)); gap:18px; margin-top:8px;}
.card{background:var(--card); padding:16px; border-radius:12px; box-shadow:0 6px 18px var(--shadow); display:flex; flex-direction:column; justify-content:space-between; position:relative; overflow:hidden;}
.card:hover{transform:translateY(-3px); transition:all .14s ease}

/* card header */
.card-head{display:flex; gap:12px; align-items:center; margin-bottom:8px;}
.avatar{width:64px; height:64px; border-radius:50%; object-fit:cover; border:1px solid #e5e7eb; display:flex; align-items:center; justify-content:center; background:#eaeefb; color:#4f46e5; font-weight:700}
.card-title{font-weight:700; font-size:15px; text-transform:uppercase; white-space:nowrap; overflow:hidden; text-overflow:ellipsis}
.card-sub{color:var(--muted); font-size:13px; margin-top:2px}
.card-meta{color:#9ca3af; font-size:12px; margin-top:4px}

/* badge (status) */
.badge { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; color:white; font-weight:600; }
.badge.active { background:#059669; }
.badge.inactive { background:#dc2626; }
.badge.vacaciones { background:#d97706; }

/* three-dot menu */
.menu-wrapper{position:relative;}
.menu-button{
    position:absolute; top:6px; right:6px; border:0; background:transparent; font-size:18px; color:#9ca3af; cursor:pointer;
}
.menu-button:hover{color:#6b7280}
.dropdown-menu{
    position:absolute; right:0; top:36px; min-width:160px; background:#fff; border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.08); border:1px solid #e6e7eb; z-index:80; overflow:hidden;
}
.dropdown-item{display:block; padding:10px 12px; text-align:left; width:100%; border:0; background:transparent; cursor:pointer; font-size:14px; color:#374151}
.dropdown-item:hover{background:#f3f4f6}

/* actions area (alternative) */
.card-actions{display:flex; gap:8px; margin-top:12px; align-items:center; flex-wrap:wrap;}
.btn-edit{background:var(--purple); color:white; padding:8px 10px; border-radius:8px; border:0}
.btn-toggle{padding:8px 10px; border-radius:8px; border:0; color:white}
.btn-toggle.gray{background:#4b5563}
.btn-toggle.yellow{background:var(--yellow)}
.btn-vac{background:#06b6d4;color:white;border-radius:8px;padding:8px 10px;border:0}

/* modal */
.modal-overlay{ position:fixed; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.45); z-index:990; padding:20px; }
.modal-box{ background:var(--card); width:100%; max-width:900px; border-radius:12px; padding:18px; box-shadow:0 12px 30px rgba(2,6,23,0.25); max-height:85vh; overflow:auto; }
.form-grid{ display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; }
.form-row{ display:flex; flex-direction:column; gap:6px; }
.label{ font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
.input-full{ width:100%; padding:10px; border:1px solid var(--input-border); border-radius:8px; font-size:14px; background:#fff; }
.file-input{ padding:6px; }
.modal-actions{ display:flex; justify-content:flex-end; gap:10px; margin-top:14px; position:sticky; bottom:0; background:transparent; padding-top:10px }

/* table */
.table { width:100%; border-collapse:collapse; margin-top:8px; }
.table th, .table td { border:1px solid #e6e7eb; padding:8px; text-align:left; font-size:13px; vertical-align:middle; }
.table thead th { background:#f3f4f6; }

/* small screens */
@media (max-width:640px){
    .form-grid{ grid-template-columns: 1fr; }
    .cards-grid{ grid-template-columns: 1fr; }
    .menu-button{ top:8px; right:8px; }
}

/* helper */
.text-muted{ color:var(--muted); font-size:13px; }
.center{text-align:center}
</style>

<div class="container-page" x-data="usuariosData()">

    <!-- HEADER -->
    <div class="header-row">
        <div>
            <h1 class="header-title">Usuarios</h1>
            <div class="header-sub" x-text="usuarios.length + ' usuarios'"></div>
        </div>

        <div class="controls">
            <button class="btn btn-create" @click="openModalCrear()">＋ Crear</button>
        </div>
    </div>

    <!-- SEARCH + FILTER -->
    <div style="display:flex; gap:12px; align-items:center; margin-bottom:12px;">
        <input type="text" class="search-input" placeholder="Buscar usuario..." x-model="buscar" />
        <select class="select-input" x-model="filtroPerfil">
            <option value="">Buscar por perfil</option>
            <template x-for="perfil in perfiles" :key="perfil.id_perfil">
                <option :value="perfil.id_perfil" x-text="perfil.nombre_perfil"></option>
            </template>
        </select>
    </div>

    <!-- CARDS GRID -->
    <div class="cards-grid" id="cardsGrid">
        <template x-for="usuario in filtrados" :key="usuario.id_usuario">
            <div class="card">
                <div style="position:relative;">
                    <!-- three-dot menu -->
                    <div class="menu-wrapper">
                        <button class="menu-button" @click="usuario._menuOpen = !usuario._menuOpen">⋮</button>

                        <div class="dropdown-menu" x-show="usuario._menuOpen" x-cloak @click.outside="usuario._menuOpen = false" x-transition>
                            <button type="button" class="dropdown-item" @click="$dispatch('editar-usuario', usuario); usuario._menuOpen = false">✏️ Editar</button>
                            <button type="button" class="dropdown-item" @click="$dispatch('vacaciones-usuario', usuario); usuario._menuOpen = false">🌴 Vacaciones</button>

                            <form :action="`/usuarios/${usuario.id_usuario}`" method="POST" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item" style="color:#dc2626">🗑️ Eliminar</button>
                            </form>
                        </div>
                    </div>

                    <!-- card header -->
                    <div class="card-head" style="margin-top:6px;">
                        <template x-if="usuario.foto">
                            <img :src="'{{ asset('storage') }}/' + usuario.foto" alt="foto" class="avatar">
                        </template>
                        <template x-if="!usuario.foto">
                            <div class="avatar" aria-hidden>
                                <span style="font-size:18px">👤</span>
                            </div>
                        </template>

                        <div style="min-width:0;">
                            <div class="card-title" x-text="usuario.nombre + ' ' + usuario.apellidos"></div>
                            <div class="card-sub" x-text="usuario.perfil?.nombre_perfil ?? 'Sin perfil'"></div>
                            <div class="card-meta" x-text="usuario.sucursal ?? ''"></div>
                        </div>
                    </div>
                </div>

                <!-- body -->
                <div>
                    <div style="margin-top:8px;">
                        <span class="badge"
                              :class="usuario.en_vacaciones ? 'vacaciones' : (usuario.estatus ? 'active' : 'inactive')"
                              x-text="usuario.en_vacaciones ? 'Vacaciones' : (usuario.estatus ? 'Activo' : 'Inactivo')"></span>
                    </div>

                    <div style="margin-top:8px;" class="text-muted" x-text="usuario.correo"></div>

                    <div style="margin-top:8px;color:#9ca3af;font-size:12px">
                        <span x-text="usuario.ultima_conexion ? new Date(usuario.ultima_conexion).toLocaleString() : 'Sin registro'"></span>
                        <template x-if="usuario.sistema">
                            <span x-text="' | ' + usuario.sistema + (usuario.app ? ' (' + usuario.app + ')' : '')"></span>
                        </template>
                    </div>

                    
            </div>
        </template>
    </div>

    <div x-show="filtrados.length === 0" class="center text-muted" style="margin-top:18px;">No se encontraron usuarios</div>

    <!-- listeners for external dispatch events -->
    <div x-data @editar-usuario.window="openModalEditar($event.detail)" @vacaciones-usuario.window="abrirModalVacaciones($event.detail)"></div>

    <!-- MODAL: crear/editar usuario -->
    <div x-show="modalOpen" class="modal-overlay" x-cloak @click.self="modalOpen=false">
        <div class="modal-box" @click.stop>
            <h3 style="font-size:18px;margin-bottom:8px;text-align:center" x-text="usuarioId === null ? 'Crear Usuario' : 'Editar Usuario'"></h3>

            <form @submit.prevent="guardarUsuario" enctype="multipart/form-data">
                <div class="form-grid" style="grid-template-columns: repeat(2,1fr);">
                    <div class="form-row" style="grid-column: 1 / -1; display:flex;justify-content:center; margin-bottom:8px;">
                        <div style="display:flex; flex-direction:column; align-items:center;">
                            <template x-if="foto">
                                <img :src="foto" class="avatar" style="width:96px;height:96px; margin-bottom:8px;">
                            </template>
                            <input type="file" @change="seleccionarFoto($event)" class="file-input">
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="label">Nombre</label>
                        <input type="text" x-model="nombre" class="input-full" required>
                    </div>

                    <div class="form-row">
                        <label class="label">Apellido</label>
                        <input type="text" x-model="apellidos" class="input-full" required>
                    </div>

                    <div class="form-row">
                        <label class="label">Perfil</label>
                        <select x-model="id_perfil" class="input-full" required>
                            <option value="">-- Selecciona --</option>
                            @foreach($perfiles as $perfil)
                                <option value="{{ $perfil->id_perfil }}">{{ $perfil->nombre_perfil }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <label class="label">Superior</label>
                        <select x-model="superior" class="input-full">
                            <option value="">-- Selecciona --</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->id_usuario }}">{{ $u->nombre }} {{ $u->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <label class="label">Sucursal</label>
                        <select x-model="id_sucursal" class="input-full">
                            <option value="">--Selecciona una sucursal--</option>
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id_sucursal }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row" style="grid-column: 1 / -1;">
                        <label class="label">Correo</label>
                        <input type="email" x-model="correo" class="input-full" required>
                    </div>

                    <div class="form-row">
                        <label class="label">Código de país</label>
                        <select name="codigo_pais" x-model="codigo_pais" class="input-full">
                            <option value="">-- Selecciona --</option>
                            <option value="+1">(EU,DO,PR) +1</option>
                            <option value="+51">Perú (+51)</option>
                            <option value="+52">México (+521)</option>
                            <option value="+53">Cuba (+53)</option>
                            <option value="+54">Argentina (+549)</option>
                            <option value="+55">Brasil (+55)</option>
                            <option value="+56">Chile (+56)</option>
                            <option value="+57">Colombia (+57)</option>
                            <option value="+58">Venezuela (+58)</option>
                            <option value="+503">El Salvador (+503)</option>
                            <option value="+504">Honduras (+504)</option>
                            <option value="+505">Nicaragua (+505)</option>
                            <option value="+506">Costa Rica (+506)</option>
                            <option value="+507">Panamá (+507)</option>
                            <option value="+591">Bolivia (+591)</option>
                            <option value="+593">Ecuador (+593)</option>
                            <option value="+595">Paraguay (+595)</option>
                            <option value="+598">Uruguay (+598)</option>
                            <option value="+502">Guatemala (+502)</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label class="label">Teléfono</label>
                        <input type="text" x-model="telefono" class="input-full">
                    </div>

                    <div class="form-row" style="grid-column: 1 / -1;">
                        <label class="label">Opciones</label>
                        <div style="display:flex;flex-wrap:wrap;gap:10px;">
                            <label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" x-model="reportes_adicionales"> Recibe Reportes Adicionales</label>
                            <label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" x-model="email_personal"> Tiene Email Personal</label>
                            <label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" x-model="notificaciones_correo"> Notif. Email</label>
                            <label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" x-model="notificaciones_whatsapp"> Notif. WhatsApp</label>
                            <label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" x-model="notificaciones_push"> Notif. Push</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="label">Contraseña</label>
                        <input type="password" x-model="password" :required="usuarioId === null" class="input-full">
                    </div>

                    <div class="form-row">
                        <label class="label">Confirmar Contraseña</label>
                        <input type="password" x-model="password_confirmation" :required="usuarioId === null" class="input-full">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-gray" @click="modalOpen=false; resetModal()">Cancelar</button>
                    <button type="submit" class="btn btn-create">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: VACACIONES -->
    <div x-show="modalVacaciones" class="modal-overlay" x-cloak @click.self="modalVacaciones=false">
        <div class="modal-box" @click.stop>
            <h3 style="font-size:18px;margin-bottom:8px;text-align:center">Vacaciones de <span x-text="nombreVacaciones"></span></h3>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" x-model="mostrarFinalizadas"> Mostrar finalizadas</label>
                <button class="btn" style="background:#2563eb;color:white;border-radius:8px;" @click="openModalNuevaVacacion()">＋ Crear Vacación</button>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(v,i) in vacaciones" :key="i">
                        <tr x-show="(mostrarFinalizadas && new Date(v.fin) < new Date()) || (!mostrarFinalizadas && new Date(v.fin) >= new Date())">
                            <td x-text="i+1"></td>
                            <td x-text="v.inicio"></td>
                            <td x-text="v.fin"></td>
                            <td x-text="v.descripcion"></td>
                            <td>
                                <button @click="editarVacacion(v)" style="background:var(--yellow);color:white;padding:6px 8px;border-radius:6px;border:0;margin-right:6px">✏️</button>
                                <button @click="eliminarVacacion(v)" style="background:var(--red);color:white;padding:6px 8px;border-radius:6px;border:0">🗑️</button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="vacaciones.length === 0">
                        <td colspan="5" class="center text-muted">No hay vacaciones registradas</td>
                    </tr>
                </tbody>
            </table>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:12px">
                <button @click="modalVacaciones=false" class="btn btn-close">Cerrar</button>
            </div>
        </div>
    </div>

    <!-- MODAL: NUEVA VACACION -->
    <div x-show="modalNuevaVacacion" class="modal-overlay" x-cloak @click.self="modalNuevaVacacion=false">
        <div class="modal-box" style="max-width:520px;" @click.stop>
            <h3 style="font-size:18px;margin-bottom:8px;text-align:left" x-text="editandoVacacion ? 'Editar Vacación' : 'Crear Vacación'"></h3>

            <form @submit.prevent="guardarVacacion()">
                <div style="display:grid;gap:10px;">
                    <div>
                        <label class="label">Nombre</label>
                        <input type="text" x-model="nombreVacaciones" disabled class="input-full" style="background:#f3f4f6;">
                    </div>

                    <div style="display:flex; gap:8px;">
                        <div style="flex:1;">
                            <label class="label">Inicio</label>
                            <input type="date" x-model="vacacionTemp.inicio" class="input-full" required>
                        </div>
                        <div style="flex:1;">
                            <label class="label">Fin</label>
                            <input type="date" x-model="vacacionTemp.fin" class="input-full" required>
                        </div>
                    </div>

                    <div>
                        <label class="label">Descripción</label>
                        <textarea x-model="vacacionTemp.descripcion" rows="3" class="input-full"></textarea>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:12px;">
                    <button type="button" class="btn btn-gray" @click="modalNuevaVacacion=false">Cancelar</button>
                    <button type="submit" class="btn" style="background:var(--purple);color:white;border-radius:8px;padding:8px 12px;">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function usuariosData() {
    return {
        // Datos
        usuarios: @json($usuarios->load('perfil','vacaciones')),
        perfiles: @json($perfiles),
        sucursales: @json($sucursales),
        buscar: '',
        filtroPerfil: '',

        // Modal usuario
        modalOpen: false,
        usuarioId: null,
        nombre: '',
        apellidos: '',
        id_perfil: '',
        superior: '',
        correo: '',
        codigo_pais: '',
        telefono: '',
        reportes_adicionales: false,
        email_personal: false,
        notificaciones_correo: false,
        notificaciones_whatsapp: false,
        notificaciones_push: false,
        password: '',
        password_confirmation: '',
        foto: null,
        fotoFile: null,
        id_sucursal: '',

        // Vacaciones
        vacaciones: [],
        modalVacaciones: false,
        modalNuevaVacacion: false,
        nombreVacaciones: '',
        mostrarFinalizadas: false,
        editandoVacacion: false,
        editandoVacacionIndex: null,
        vacacionTemp: { inicio:'', fin:'', descripcion:'', id:null },

        // Filtrado en vivo
        get filtrados() {
            const texto = (this.buscar || '').toLowerCase();
            // ensure each usuario has _menuOpen state for its dropdown
            this.usuarios.forEach(u => { if (u._menuOpen === undefined) u._menuOpen = false; this.actualizarEstadoVacaciones(u) });
            return this.usuarios.filter(u => {
                const t = (`${u.nombre} ${u.apellidos} ${u.correo || ''}`).toLowerCase();
                const matchTexto = texto === '' ? true : t.includes(texto);
                const matchPerfil = this.filtroPerfil ? u.id_perfil == this.filtroPerfil : true;
                return matchTexto && matchPerfil;
            });
        },

        // Funciones usuario
        resetModal() {
            this.usuarioId = null;
            this.nombre = '';
            this.apellidos = '';
            this.id_perfil = '';
            this.superior = '';
            this.correo = '';
            this.codigo_pais = '';
            this.telefono = '';
            this.reportes_adicionales = false;
            this.email_personal = false;
            this.notificaciones_correo = false;
            this.notificaciones_whatsapp = false;
            this.notificaciones_push = false;
            this.password = '';
            this.password_confirmation = '';
            this.foto = null;
            this.fotoFile = null;
            this.id_sucursal = '';
        },

        openModalCrear() {
            this.resetModal();
            this.modalOpen = true;
        },

        openModalEditar(usuario) {
            this.usuarioId = usuario.id_usuario;
            this.nombre = usuario.nombre;
            this.apellidos = usuario.apellidos;
            this.id_perfil = usuario.id_perfil;
            this.superior = usuario.superior;
            this.correo = usuario.correo;
            this.codigo_pais = usuario.codigo_pais;
            this.telefono = usuario.telefono;
            this.reportes_adicionales = !!usuario.reportes_adicionales;
            this.email_personal = !!usuario.email_personal;
            this.notificaciones_correo = !!usuario.notificaciones_correo;
            this.notificaciones_whatsapp = !!usuario.notificaciones_whatsapp;
            this.notificaciones_push = !!usuario.notificaciones_push;
            this.foto = usuario.foto ? '{{ asset("storage") }}/' + usuario.foto : null;
            this.fotoFile = null;
            this.id_sucursal = usuario.id_sucursal ?? '';
            this.modalOpen = true;
        },

        seleccionarFoto(event) {
            const f = event.target.files[0];
            if(!f) return;
            this.fotoFile = f;
            this.foto = URL.createObjectURL(f);
        },

        guardarUsuario() {
            if(!this.nombre || !this.apellidos || !this.correo) return alert('Completa los campos requeridos.');

            const formData = new FormData();
            formData.append('nombre', this.nombre);
            formData.append('apellidos', this.apellidos);
            formData.append('id_perfil', this.id_perfil);
            formData.append('superior', this.superior);
            formData.append('correo', this.correo);
            formData.append('codigo_pais', this.codigo_pais);
            formData.append('telefono', this.telefono);
            formData.append('reportes_adicionales', this.reportes_adicionales ? 1 : 0);
            formData.append('email_personal', this.email_personal ? 1 : 0);
            formData.append('notificaciones_correo', this.notificaciones_correo ? 1 : 0);
            formData.append('notificaciones_whatsapp', this.notificaciones_whatsapp ? 1 : 0);
            formData.append('notificaciones_push', this.notificaciones_push ? 1 : 0);
            formData.append('password', this.password);
            formData.append('password_confirmation', this.password_confirmation);
            formData.append('id_sucursal', this.id_sucursal);
            if(this.fotoFile) formData.append('foto', this.fotoFile);

            const url = this.usuarioId ? `/usuarios/${this.usuarioId}` : '{{ route("usuarios.store") }}';
            if(this.usuarioId) formData.append('_method', 'PUT');

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    if(this.usuarioId){
                        const index = this.usuarios.findIndex(u => u.id_usuario === data.usuario.id_usuario);
                        if(index !== -1) this.usuarios[index] = data.usuario;
                    } else {
                        this.usuarios.unshift(data.usuario);
                    }
                    this.modalOpen = false;
                    this.resetModal();
                } else alert(data.message || 'No se pudo guardar el usuario.');
            }).catch(err => {
                console.error(err);
                alert('Error al guardar usuario (ver consola).');
            });
        },

        eliminarUsuario(usuario) {
            if(!confirm(`¿Deseas eliminar al usuario ${usuario.nombre} ${usuario.apellidos}?`)) return;

            fetch(`/usuarios/${usuario.id_usuario}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) this.usuarios = this.usuarios.filter(u => u.id_usuario !== usuario.id_usuario);
                else alert(data.message || 'No se pudo eliminar el usuario.');
            }).catch(err => console.error(err));
        },

        // Vacaciones
        abrirModalVacaciones(usuario) {
            this.usuarioId = usuario.id_usuario;
            this.nombreVacaciones = `${usuario.nombre} ${usuario.apellidos}`;
            this.vacaciones = [];
            this.modalVacaciones = true;

            fetch(`/usuarios/${usuario.id_usuario}/vacaciones`, { method: 'GET', headers: { 'Accept':'application/json' } })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    // unifica formatos (fecha_inicio / fecha_fin) y mantiene finalizada
                    this.vacaciones = data.vacaciones.map(v => ({
                        id: v.id_vacacion ?? v.id,
                        inicio: v.fecha_inicio ?? v.inicio,
                        fin: v.fecha_fin ?? v.fin,
                        descripcion: v.descripcion ?? v.motivo ?? '',
                        finalizada: v.finalizada ?? false
                    }));
                    const u = this.usuarios.find(x => x.id_usuario === this.usuarioId);
                    if(u) {
                        u.vacaciones = [...this.vacaciones];
                        // recalcula estado SOLO para ese usuario
                        this.actualizarEstadoVacaciones(u);
                    }
                } else alert(data.message || 'No se pudieron cargar las vacaciones.');
            }).catch(err => console.error(err));
        },

        openModalNuevaVacacion() {
            this.editandoVacacion = false;
            this.editandoVacacionIndex = null;
            this.vacacionTemp = { inicio:'', fin:'', descripcion:'', id:null };
            this.modalNuevaVacacion = true;
        },

        editarVacacion(v) {
            this.editandoVacacion = true;
            this.editandoVacacionIndex = this.vacaciones.findIndex(x => x.id === v.id);
            this.vacacionTemp = { inicio: v.inicio, fin: v.fin, descripcion: v.descripcion, id: v.id };
            this.modalNuevaVacacion = true;
        },

        guardarVacacion() {
            if(!this.vacacionTemp.inicio || !this.vacacionTemp.fin) return alert('Completa inicio y fin.');
            const payload = {
                id_usuario: this.usuarioId,
                fecha_inicio: this.vacacionTemp.inicio,
                fecha_fin: this.vacacionTemp.fin,
                descripcion: this.vacacionTemp.descripcion
            };
            const usuario = this.usuarios.find(u => u.id_usuario === this.usuarioId);

            // Editar
            if(this.editandoVacacion && this.vacacionTemp.id){
                fetch(`/usuarios/vacaciones/${this.vacacionTemp.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        this.vacaciones[this.editandoVacacionIndex] = { ...this.vacaciones[this.editandoVacacionIndex], ...payload };
                        if(usuario) usuario.vacaciones = [...this.vacaciones];
                        if(usuario) this.actualizarEstadoVacaciones(usuario);
                        this.modalNuevaVacacion = false;
                    } else alert(data.message || 'No se pudo actualizar.');
                }).catch(err => console.error(err));
                return;
            }

            // Crear
            fetch('{{ route("usuarios.vacaciones.guardar") }}', {
                method: 'POST',
                headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    this.vacaciones.unshift({ ...payload, id: data.id ?? Date.now(), finalizada: false });
                    if(usuario) {
                        usuario.vacaciones = [...this.vacaciones];
                        this.actualizarEstadoVacaciones(usuario);
                    }
                    this.modalNuevaVacacion = false;
                } else alert(data.message || 'No se pudo guardar.');
            }).catch(err => console.error(err));
        },

        toggleFinalizada(v) {
            fetch(`/usuarios/vacaciones/${v.id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    v.finalizada = data.finalizada;
                    const usuario = this.usuarios.find(x => x.id_usuario === this.usuarioId);
                    if(usuario){
                        const idx = (usuario.vacaciones || []).findIndex(x => x.id === v.id);
                        if(idx !== -1) {
                            usuario.vacaciones[idx] = v;
                            this.actualizarEstadoVacaciones(usuario);
                        }
                    }
                }
            }).catch(err => console.error(err));
        },

        eliminarVacacion(v) {
            if(!confirm('¿Deseas eliminar esta vacación?')) return;
            const id = v.id;
            if(!id) return;

            fetch(`/usuarios/vacaciones/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    this.vacaciones = this.vacaciones.filter(x => x.id !== id);
                    const usuario = this.usuarios.find(u => u.id_usuario === this.usuarioId);
                    if(usuario){
                        usuario.vacaciones = [...this.vacaciones];
                        this.actualizarEstadoVacaciones(usuario);
                    }
                } else alert(data.message || 'No se pudo eliminar.');
            }).catch(err => console.error(err));
        },

        // función que determina si el usuario está de vacaciones hoy
        actualizarEstadoVacaciones(usuario){
            try {
                const hoy = new Date();
                usuario.en_vacaciones = (usuario.vacaciones || []).some(v => {
                    // soporta ambos formatos: { inicio, fin } o { fecha_inicio, fecha_fin }
                    const inicioS = v.inicio ?? v.fecha_inicio;
                    const finS = v.fin ?? v.fecha_fin;

                    if(!inicioS || !finS) return false;

                    // Normaliza fechas (evita problemas con zonas horarias)
                    const inicio = new Date(inicioS + 'T00:00:00');
                    const fin = new Date(finS + 'T23:59:59');

                    const finalizada = v.finalizada ?? false;
                    return inicio <= hoy && hoy <= fin && !finalizada;
                });
            } catch(e) {
                console.error('Error actualizando estado vacaciones:', e);
                usuario.en_vacaciones = false;
        }
    }
    };
}
</script>
@endsection
