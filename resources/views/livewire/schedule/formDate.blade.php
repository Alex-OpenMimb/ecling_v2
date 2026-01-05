<div class="mt-2">
    <div >


        <div   class="m-4">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-xl font-bold tracking-tight">Editar  Fecha </h3>
                </div>
                <button   wire:click="$dispatch('closeModal', { component: 'schedule.form-date' })" type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <form  wire:submit.prevent="update()" action="">

                <div class=" pr-0 md:pr-4 mb-4 md:mb-0 mt-4">
                    <input   wire:model.lazy="next_date"  type="date" id="next_date"  name="next_date" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('next_date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button wire:click="$dispatch('closeModal', { component: 'schedule.form-date' })" type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cerrar</button>
                    <x-buttons.common>Guardar</x-buttons.common>
                </div>
            </form>

        </div>
    </div>



</div>

