<div>
    <div >

        <div  wire:submit.prevent="updateOrStore()" class="m-4">
            <div class="flex justify-between items-center pb-4">
                <h3 class="text-xl  text-gray-900 font-semibold" id="brand-modal-title"

                > Descripción </h3>
                <button   onclick="Livewire.dispatch('closeModal', { component: 'client-equipment-corrective.observations' })" type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="w-full pr-0 md:pr-4 mb-4 md:mb-0">
                <textarea wire:model.lazy="description" readonly  name="description" cols="" rows="5" class="resize-none  focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"> </textarea>
            </div>

            <div class="mt-6 flex justify-end space-x-4">

                <button onclick="Livewire.dispatch('closeModal', { component: 'client-equipment-corrective.observations' })" type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cancelar</button>

            </div>
        </div>
    </div>

</div>

