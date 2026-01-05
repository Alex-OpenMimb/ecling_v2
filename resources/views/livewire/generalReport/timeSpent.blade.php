<div>
    <div >

        <div   class="m-4">
            <div class="flex justify-between items-center pb-4">
                <h3 class="text-xl  text-gray-900 font-bold" id="brand-modal-title"
                > Tiempo</h3>
                <button   onclick="Livewire.dispatch('closeModal', { component: 'general-report.time-spent' })" type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="w-full pr-0 md:pr-4 mb-4 md:mb-0  text-center">
               <span class="font-semibold">  {{ $start_time }}  </span>
               <span class="mx-2">  hasta  </span>
               <span class="font-semibold">    {{$end_time}} </span>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                <button onclick="Livewire.dispatch('closeModal', { component: 'general-report.time-spent' })" type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cancelar</button>
            </div>
        </div>
    </div>

</div>

