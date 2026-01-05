<div>
    <div x-data="{name: @entangle('model')}">

        <form  wire:submit.prevent="updateOrStore()" class="m-4">
            <div class="flex justify-between items-center pb-4">
                <h3 class="text-xl  text-gray-900" id="brand-modal-title"
                    x-text="name ? 'Editar Modelo' : 'Crear Modelo'"
                >  </h3>
                <button   wire:click="$dispatch('closeModal', { component: 'store.model.form-model' })"  type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

        <div  class="flex">

            <div class="w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                <label for="email" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                <input   wire:model.lazy="model"  type="text" id="model"  name="model" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                <div class="h-4">
                    @error('model') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                <label for="countries" class="block text-gray-700 font-bold mb-2">Clase de Equipo*:</label>
                <select wire:model.lazy="equipment_class_id" id="equipment_class_id" name="equipment_class_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccionar</option>
                    @foreach( $equipment_class_list as $equipment )
                        <option value="{{$equipment->id}}"> {{$equipment->name}} </option>
                    @endforeach
                </select>
                <div class="h-4">
                    @error('equipment_class_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

            <div class="mt-6 flex justify-end space-x-4">
                <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                <button wire:click="$dispatch('closeModal', { component: 'store.model.form-model' })"  type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cancelar</button>
                <x-buttons.common>Guardar</x-buttons.common>
            </div>
        </form>
    </div>

</div>

