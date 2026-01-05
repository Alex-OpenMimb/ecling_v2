<div class="max-w-3xl mx-auto mt-10">
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-blue-500 font-semibold">Detalle</p>
                <h1 class="text-2xl font-semibold text-gray-800 mt-1">
                    {{ $equipmentClass->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-2">
                    Información detallada de la clase de equipo configurada en el sistema.
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.configurations.equipment-class.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md">
                    Volver
                </a>
            </div>
        </div>

        <div class="px-6 py-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Nombre</p>
                    <p class="text-base font-medium text-gray-800 mt-1">{{ $equipmentClass->name }}</p>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Estado</p>
                    <span class="inline-flex items-center mt-1 px-3 py-1 text-sm font-semibold rounded-full {{ $equipmentClass->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $equipmentClass->status ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Creado el</p>
                    <p class="text-base font-medium text-gray-800 mt-1">
                        {{ optional($equipmentClass->created_at)->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Actualizado el</p>
                    <p class="text-base font-medium text-gray-800 mt-1">
                        {{ optional($equipmentClass->updated_at)->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100">
                <div class="flex justify-end">
                    @if($hasEquipments)
                        <button
                            type="button"
                            disabled
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 bg-gray-100 rounded-md cursor-not-allowed opacity-50"
                            title="No se puede eliminar: la clase de equipo está siendo utilizada por uno o más equipos">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Eliminar
                        </button>
                    @else
                        <button
                            wire:click="delete"
                            wire:confirm="¿Estás seguro de que deseas eliminar esta clase de equipo?"
                            type="button"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-md">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Eliminar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

