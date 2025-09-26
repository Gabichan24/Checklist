<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold text-gray-700">Regiones</h2>
        <button 
            wire:click="$set('openModal', true)"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
        >
            Crear
        </button>
    </div>

    @include('components.modal-simple', [
        'title' => 'Crear Nueva Región',
        'slot' => '
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 font-medium">Nombre o Descripción</label>
                    <input 
                        wire:model="nombre"
                        type="text" 
                        id="nombre" 
                        class="w-full p-2 border rounded-lg" 
                        placeholder="Ingresa el nombre o descripción"
                    >
                    @error('nombre') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label for="estado" class="block text-gray-700 font-medium">Estados de la Región</label>
                    <select 
                        wire:model="estado"
                        id="estado" 
                        class="w-full p-2 border rounded-lg"
                    >
                        <option value="" disabled selected>Selecciona un estado</option>
                        <!-- Lista de estados como en el ejemplo anterior -->
                        <option value="Aguascalientes">Aguascalientes</option>
                        <!-- ... otros estados ... -->
                    </select>
                    @error('estado') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </form>
        '
    ])
</div>