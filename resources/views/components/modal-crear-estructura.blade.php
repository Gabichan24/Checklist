<!-- resources/views/tu-vista-principal.blade.php -->
<div x-data="{ openModal: false }">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Regiones</h2>
        <button 
            x-on:click="openModal = true"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
        >
            Crear
        </button>
    </div>

    <!-- Incluir el modal con el contenido personalizado -->
    @include('components.modal-crear-estructura', [
        'title' => 'Crear Nueva Región',
        'slot' => '
            <form>
                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 font-medium">Nombre o Descripción</label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        class="w-full p-2 border rounded-lg" 
                        placeholder="Ingresa el nombre o descripción"
                    >
                </div>
                <div class="mb-4">
                    <label for="estado" class="block text-gray-700 font-medium">Estados de la Región</label>
                    <select 
                        id="estado" 
                        name="estado" 
                        class="w-full p-2 border rounded-lg"
                    >
                        <option value="" disabled selected>Selecciona un estado</option>
                        <option value="Aguascalientes">Aguascalientes</option>
                        <option value="Baja California">Baja California</option>
                        <option value="Baja California Sur">Baja California Sur</option>
                        <option value="Campeche">Campeche</option>
                        <option value="Chiapas">Chiapas</option>
                        <option value="Chihuahua">Chihuahua</option>
                        <option value="Coahuila">Coahuila</option>
                        <option value="Colima">Colima</option>
                        <option value="Durango">Durango</option>
                        <option value="Estado de México">Estado de México</option>
                        <option value="Guanajuato">Guanajuato</option>
                        <option value="Guerrero">Guerrero</option>
                        <option value="Hidalgo">Hidalgo</option>
                        <option value="Jalisco">Jalisco</option>
                        <option value="Michoacán">Michoacán</option>
                        <option value="Morelos">Morelos</option>
                        <option value="Nayarit">Nayarit</option>
                        <option value="Nuevo León">Nuevo León</option>
                        <option value="Oaxaca">Oaxaca</option>
                        <option value="Puebla">Puebla</option>
                        <option value="Querétaro">Querétaro</option>
                        <option value="Quintana Roo">Quintana Roo</option>
                        <option value="San Luis Potosí">San Luis Potosí</option>
                        <option value="Sinaloa">Sinaloa</option>
                        <option value="Sonora">Sonora</option>
                        <option value="Tabasco">Tabasco</option>
                        <option value="Tamaulipas">Tamaulipas</option>
                        <option value="Tlaxcala">Tlaxcala</option>
                        <option value="Veracruz">Veracruz</option>
                        <option value="Yucatán">Yucatán</option>
                        <option value="Zacatecas">Zacatecas</option>
                    </select>
                </div>
            </form>
        '
    ])
</div>