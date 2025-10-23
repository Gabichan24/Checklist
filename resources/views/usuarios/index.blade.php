@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-40 min-h-screen pb-10" x-data="usuariosData()">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Usuarios</h1>
        <div class="flex items-center space-x-3">
            <span class="text-gray-500 text-sm" x-text="usuarios.length + ' usuarios'"></span>
            <button @click="openModalCrear()"
                    class="bg-emerald-500 hover:bg-emerald-600 text-gray px-4 py-2 rounded-lg font-medium flex items-center space-x-1">
                <span>＋ Crear</span>
            </button>
        </div>
    </div>

    <!-- BUSCADOR Y FILTRO -->
    <div class="flex space-x-2 mb-6">
        <input 
            type="text" 
            placeholder="Buscar usuario..." 
            class="border rounded-lg px-3 py-1.5 text-sm focus:ring focus:ring-indigo-200 focus:outline-none"
            x-model="buscar"
        >

        <select 
            class="border rounded-lg px-3 py-1.5 text-sm focus:ring focus:ring-indigo-200 focus:outline-none"
            x-model="filtroPerfil"
        >
            <option value="">Buscar por perfil</option>
            <template x-for="perfil in perfiles" :key="perfil.id_perfil">
                <option :value="perfil.id_perfil" x-text="perfil.nombre_perfil"></option>
            </template>
        </select>
    </div>

    {{-- Lista de usuarios --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="usuario in filtrados" :key="usuario.id_usuario">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 relative overflow-visible">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                        <template x-if="usuario.foto">
                            <img :src="'{{ asset('storage') }}/' + usuario.foto" class="w-16 h-16 rounded-full object-cover border">
                        </template>
                        <template x-if="!usuario.foto">
                            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-3xl">
                                <i class="fas fa-user"></i>
                            </div>
                        </template>

                        <div class="flex flex-col truncate">
                            <h2 class="font-semibold text-gray-800 uppercase text-lg truncate" x-text="usuario.nombre + ' ' + usuario.apellidos"></h2>
                            <p class="text-sm text-gray-500 truncate" x-text="usuario.perfil?.nombre_perfil ?? 'Sin perfil'"></p>
                        </div>
                    </div>

                    {{-- Menú de opciones --}}
                    <div class="ml-2 relative" x-data="{ open: false }">
                        <button @click="open = !open" class="text-gray-400 hover:text-gray-600 text-2xl absolute top-0 right-0">⋮</button>
                        <div x-show="open" @click.outside="open = false"
                             x-transition
                             class="absolute right-0 mt-6 w-44 bg-white rounded-xl shadow-md border z-50">
                            <button type="button" @click="openModalEditar(usuario)" class="block px-4 py-2 hover:bg-gray-100 text-gray-700 w-full text-left">✏️ Editar</button>
                            <button type="button" @click="openVacaciones(usuario)" class="block px-4 py-2 hover:bg-gray-100 text-gray-700 w-full text-left">🌴 Vacaciones</button>
                            <form :action="`/usuarios/${usuario.id_usuario}`" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">🗑️ Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="text-sm font-medium px-3 py-1 rounded-full"
                          :class="usuario.estatus ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'"
                          x-text="usuario.estatus ? '🟢 Activo' : '🔴 Inactivo'"></span>
                </div>

                <p class="text-sm text-gray-700 truncate" x-text="usuario.correo"></p>
            </div>
        </template>
        <template x-if="filtrados.length === 0">
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-4 text-gray-500">No se encontraron usuarios</div>
        </template>
    </div>

{{-- MODAL CREAR/EDITAR USUARIO --}}
<div x-show="modalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4" x-cloak>
    <div class="bg-white rounded-lg p-6 w-full max-w-xl max-h-[85vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4 text-center" x-text="usuarioId === null ? 'Crear Usuario' : 'Editar Usuario'"></h3>

        <!-- Usamos @submit.prevent para evitar el submit normal -->
        <form @submit.prevent="guardarUsuario" enctype="multipart/form-data">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- FOTO --}}
                <div class="sm:col-span-2 flex justify-center mb-4">
                    <div class="flex flex-col items-center">
                        <template x-if="foto">
                            <img :src="foto" class="w-24 h-24 rounded-full object-cover border mb-2">
                        </template>
                        <input type="file" @change="seleccionarFoto($event)" class="text-sm w-full">
                    </div>
                </div>

                {{-- NOMBRE / APELLIDO --}}
                <div class="w-full"><label>Nombre</label><input type="text" x-model="nombre" class="w-full border rounded px-2 py-1 text-sm" required></div>
                <div class="w-full"><label>Apellido</label><input type="text" x-model="apellidos" class="w-full border rounded px-2 py-1 text-sm" required></div>

                {{-- PERFIL / SUPERIOR --}}
                <div class="w-full"><label>Perfil</label>
                    <select x-model="id_perfil" class="w-full border rounded px-2 py-1 text-sm" required>
                        <option value="">-- Selecciona --</option>
                        @foreach($perfiles as $perfil)
                            <option value="{{ $perfil->id_perfil }}">{{ $perfil->nombre_perfil }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full"><label>Superior</label>
                    <select x-model="superior" class="w-full border rounded px-2 py-1 text-sm">
                        <option value="">-- Selecciona --</option>
                        @foreach($usuarios as $u)
                            <option :selected="superior == '{{ $u->id_usuario }}'" value="{{ $u->id_usuario }}">{{ $u->nombre }} {{ $u->apellidos }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- SUCURSAL --}}
                <div class="w-full">
                    <label>Sucursal</label>
                    <select x-model="id_sucursal" class="w-full border rounded px-2 py-1 text-sm">
                        <option value="">--Selecciona una sucursal--</option>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id_sucursal }}" :selected="id_sucursal == {{ $sucursal->id_sucursal }}">
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CORREO --}}
                <div class="sm:col-span-2 w-full"><label>Correo</label><input type="email" x-model="correo" class="w-full border rounded px-2 py-1 text-sm" required></div>

                {{-- CÓDIGO PAÍS / TELÉFONO --}}
                <div class="w-full"><label>Código de país</label>
                     <select name="codigo_pais" x-model="codigo_pais" class="w-full border rounded px-2 py-1 text-sm">
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
                <div class="w-full"><label>Teléfono</label><input type="text" x-model="telefono" class="w-full border rounded px-2 py-1 text-sm"></div>

                {{-- CHECKBOXES --}}
                <div class="sm:col-span-2 flex flex-wrap gap-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="reportes_adicionales" class="h-4 w-4">
                        <label class="text-sm">Recibe Reportes Adicionales</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="email_personal" class="h-4 w-4">
                        <label class="text-sm">Tiene Email Personal</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="notificaciones_correo" class="h-4 w-4">
                        <label class="text-sm">Recibe notificaciones por Email</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="notificaciones_whatsapp" class="h-4 w-4">
                        <label class="text-sm">Recibe notificaciones por WhatsApp</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="notificaciones_push" class="h-4 w-4">
                        <label class="text-sm">Recibe notificaciones Push</label>
                    </div>
                </div>

                {{-- CONTRASEÑA --}}
                <div class="w-full"><label>Contraseña</label><input type="password" x-model="password" :required="usuarioId === null" class="w-full border rounded px-2 py-1 text-sm"></div>
                <div class="w-full"><label>Confirmar Contraseña</label><input type="password" x-model="password_confirmation" :required="usuarioId === null" class="w-full border rounded px-2 py-1 text-sm"></div>
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-2 mt-4 sticky bottom-0 bg-white pt-4">
                <button type="button" @click="modalOpen=false; resetModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancelar</button>
                <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-gray hover:bg-purple-700">Guardar</button>
            </div>
        </form>
    </div>
</div>

    {{-- MODAL VACACIONES --}}
<div x-show="modalVacaciones" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4" x-cloak>
    <div class="bg-white rounded-lg p-6 w-full max-w-xl max-h-[85vh] overflow-y-auto">
        <h3 class="text-lg font-bold mb-4 text-center">Vacaciones de <span x-text="nombreVacaciones"></span></h3>

        <div class="mb-3 flex items-center justify-between">
            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" x-model="mostrarFinalizadas" class="h-4 w-4">
                    <span>Mostrar vacaciones finalizadas</span>
                </label>
            </div>
            <button @click="openModalNuevaVacacion()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-gray rounded">＋ Crear Vacación</button>
        </div>

        <table class="w-full table-auto border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-3 py-1">#</th>
                    <th class="border px-3 py-1">Inicio</th>
                    <th class="border px-3 py-1">Fin</th>
                    <th class="border px-3 py-1">Descripción</th>
                    <th class="border px-3 py-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(v,i) in vacaciones" :key="i">
                    <tr x-show="(mostrarFinalizadas && new Date(v.fin) < new Date()) || (!mostrarFinalizadas && new Date(v.fin) >= new Date())">
                        <td class="border px-3 py-1" x-text="i+1"></td>
                        <td class="border px-3 py-1" x-text="v.inicio"></td>
                        <td class="border px-3 py-1" x-text="v.fin"></td>
                        <td class="border px-3 py-1" x-text="v.descripcion"></td>
                        <td class="border px-3 py-1">
                            <button @click="editarVacacion(v)" class="px-2 py-1 bg-yellow-400 hover:bg-yellow-500 text-gray rounded text-xs">✏️</button>
                            <button @click="eliminarVacacion(v)" class="px-2 py-1 bg-red-500 hover:bg-red-600 text-gray rounded text-xs">🗑️</button>
                        </td>
                    </tr>
                </template>
                <tr x-show="vacaciones.length === 0">
                    <td colspan="5" class="text-center py-2 text-gray-500">No hay vacaciones registradas</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end mt-4">
            <button @click="modalVacaciones=false" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Cerrar</button>
        </div>
    </div>
</div>

    {{-- MODAL CREAR/EDITAR VACACIÓN --}}
    <div x-show="modalNuevaVacacion" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4" x-cloak>
        <div class="bg-white rounded-2xl p-6 w-full max-w-md" @click.away="modalNuevaVacacion=false">
            <h3 class="text-lg font-semibold mb-4" x-text="editandoVacacion ? 'Editar Vacación' : 'Crear Vacación'"></h3>

            <form @submit.prevent="guardarVacacion()">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        <input type="text" x-model="nombreVacaciones" disabled class="w-full border rounded px-3 py-2 bg-gray-100">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium">Inicio</label>
                            <input type="date" x-model="vacacionTemp.inicio" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Fin</label>
                            <input type="date" x-model="vacacionTemp.fin" class="w-full border rounded px-3 py-2" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Descripción</label>
                        <textarea x-model="vacacionTemp.descripcion" class="w-full border rounded px-3 py-2" rows="2"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="modalNuevaVacacion=false" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-white hover:bg-purple-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function usuariosData() {
    return {
        // 🔹 Datos de usuarios
        usuarios: @json($usuarios->load('perfil')), 
        perfiles: @json($perfiles),
        sucursales: @json($sucursales),
        buscar: '',
        filtroPerfil: '',

        // 🔹 Modal de usuario
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

        // 🔹 Filtrado en vivo
        get filtrados() {
            const texto = this.buscar.toLowerCase();
            return this.usuarios.filter(u => {
                const coincideTexto = (`${u.nombre} ${u.apellidos} ${u.correo}`).toLowerCase().includes(texto);
                const coincidePerfil = this.filtroPerfil ? u.id_perfil == this.filtroPerfil : true;
                return coincideTexto && coincidePerfil;
            });
        },

        // --------------------------------------------------------------------
        // 🔹 Funciones de usuario
        // --------------------------------------------------------------------
        openModalCrear() {
            this.usuarioId = null;
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
            this.reportes_adicionales = usuario.reportes_adicionales;
            this.email_personal = usuario.email_personal;
            this.notificaciones_correo = usuario.notificaciones_correo;
            this.notificaciones_whatsapp = usuario.notificaciones_whatsapp;
            this.notificaciones_push = usuario.notificaciones_push;
            this.foto = usuario.foto ? '{{ asset("storage") }}/' + usuario.foto : null;
            this.fotoFile = null;
            this.id_sucursal = usuario.id_sucursal ?? '';
            this.modalOpen = true;
        },

        resetModal() {
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

        guardarUsuario() {
            if(!this.nombre || !this.apellidos || !this.correo) {
                return alert('Completa los campos requeridos.');
            }

            // Usamos FormData para subir archivos
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
            const method = this.usuarioId ? 'POST' : 'POST'; // PUT lo convertimos a POST con _method
            if(this.usuarioId) formData.append('_method', 'PUT');

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    if(this.usuarioId) {
                        const index = this.usuarios.findIndex(u => u.id_usuario === data.usuario.id_usuario);
                        if(index !== -1) this.usuarios[index] = data.usuario;
                    } else {
                        this.usuarios.unshift(data.usuario);
                    }
                    this.modalOpen = false;
                    this.resetModal();
                } else alert(data.message || 'No se pudo guardar el usuario.');
            })
            .catch(err => {
                console.error(err);
                alert('Error al guardar usuario (ver consola).');
            });
        },

        seleccionarFoto(event) {
            this.fotoFile = event.target.files[0];
            this.foto = URL.createObjectURL(this.fotoFile);
        },

        eliminarUsuario(usuario) {
            if(!confirm(`¿Deseas eliminar al usuario ${usuario.nombre} ${usuario.apellidos}?`)) return;

            fetch(`/usuarios/${usuario.id_usuario}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) this.usuarios = this.usuarios.filter(u => u.id_usuario !== usuario.id_usuario);
                else alert(data.message || 'No se pudo eliminar el usuario.');
            })
            .catch(err => console.error(err));
        },

        openModalNuevaVacacion() {
            this.editandoVacacion = false;
            this.vacacionTemp = { inicio: '', fin: '', descripcion: '', id: null };
            this.modalNuevaVacacion = true;
        },

        editarVacacion(v) {
            this.editandoVacacion = true;
            this.editandoVacacionIndex = this.vacaciones.findIndex(x => x.id === v.id);
            this.vacacionTemp = { inicio: v.inicio, fin: v.fin, descripcion: v.descripcion, id: v.id };
            this.modalNuevaVacacion = true;
        },

        eliminarVacacion(v) {
            const id = v.id ?? v.id_vacacion ?? null;
            if (!id) return alert('No se puede eliminar una vacación que no existe.');
            if (!confirm('¿Deseas eliminar esta vacación?')) return;

            fetch(`/usuarios/vacaciones/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept':'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) this.vacaciones = this.vacaciones.filter(x => (x.id ?? x.id_vacacion) !== id);
                else alert(data.message || 'No se pudo eliminar la vacación.');
            })
            .catch(err => console.error(err));
        },

        toggleFinalizada(v) {
            fetch(`${this.urlObtenerBase}/vacaciones/${v.id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept':'application/json' }
            })
            .then(res => res.json())
            .then(data => { if(data.success) v.finalizada = data.finalizada; })
            .catch(err => console.error(err));
        },

        guardarVacacion() {
            if(!this.vacacionTemp.inicio || !this.vacacionTemp.fin) return alert('Completa inicio y fin.');

            const payload = {
                id_usuario: this.usuarioId,
                fecha_inicio: this.vacacionTemp.inicio,
                fecha_fin: this.vacacionTemp.fin,
                descripcion: this.vacacionTemp.descripcion
            };

            if(this.editandoVacacion && this.vacacionTemp.id){
                fetch(`${this.urlObtenerBase}/vacaciones/${this.vacacionTemp.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        const idx = this.editandoVacacionIndex;
                        this.vacaciones[idx] = {...this.vacaciones[idx], ...payload};
                        this.modalNuevaVacacion = false;
                    } else alert(data.message || 'No se pudo actualizar.');
                }).catch(err => console.error(err));
                return;
            }

            fetch(this.urlGuardar, {
                method: 'POST',
                headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    this.vacaciones.unshift({...payload, id: data.id ?? Date.now(), finalizada: false});
                    this.modalNuevaVacacion = false;
                } else alert(data.message || 'No se pudo guardar la vacación.');
            })
            .catch(err => console.error(err));
        }
    }
}
</script>
@endsection
