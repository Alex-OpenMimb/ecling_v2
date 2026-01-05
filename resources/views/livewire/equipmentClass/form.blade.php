<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white shadow-md rounded-lg border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">
                    {{ $id ? 'Editar clase de equipo' : 'Crear clase de equipo' }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Gestiona las clases de equipos disponibles en el sistema.
                </p>
            </div>
            <a href="{{ route('admin.configurations.equipment-class.index') }}"
               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                Volver al listado
            </a>
        </div>

        <form wire:submit.prevent="save" class="px-6 py-6 space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre *
                </label>
                <input
                    wire:model.defer="name"
                    type="text"
                    id="name"
                    class="w-full rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm px-4 py-2.5"
                    placeholder="Ingresa el nombre de la clase de equipo"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                <div>
                    <p class="text-sm font-medium text-gray-700">Estado</p>
                    <p class="text-xs text-gray-500">Define si la clase de equipo estará disponible para su uso.</p>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <input wire:model="status" type="checkbox" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-focus:ring-2 peer-focus:ring-blue-300 peer-checked:bg-blue-600 transition-colors duration-200 relative">
                        <div class="absolute top-0.5 left-1 w-5 h-5 bg-white rounded-full border border-gray-200 transition-all duration-200 peer-checked:translate-x-5"></div>
                    </div>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.configurations.equipment-class.index') }}"
                   class="px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-500 hover:bg-blue-700 rounded-md shadow-sm">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

