<div>
    <div>

        <form  wire:submit.prevent="update()" class="m-4 pb-4">
            <div class="flex justify-between items-center pb-4">
                <h3 class="text-xl  text-gray-900" id="brand-modal-title"

                >  Editar Activdad </h3>
                <button   onclick="Livewire.dispatch('closeModal', { component: 'event.editEvent' })"  type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="md:flex md:items-center mb-4" >
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0"  >
                    <label for="email" class="block text-gray-700 font-bold  mb-2" title="" >Fecha:</label>
                    <input  wire:model.defer="date"   type="date" id="" name="date" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                    <div class="h-4">
                        @error('date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>


                </div>



            </div>

            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4  md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Hora incial*:</label>
                    <select  id="start_hour_id"  wire:model.defer="start_hour" name="start_hour" class=" mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 "

                    >
                        <option  value="">Seleccionar</option>
                        @for( $hour = 8; $hour < 24;$hour++ )
                            <option value="{{ sprintf('%02d', $hour) }}:00">{{ sprintf('%02d', $hour) }}:00</option>
                        @endfor

                    </select>
                    <div class="h-4">
                        @error('start_hour') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/2 pr-0 md:pr-r md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Hora Final*:</label>
                    <select  id="end_hour_id"  wire:model.defer="end_hour" name="end_hour" class=" mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 "

                    >
                        <option  value="">Seleccionar</option>
                        @for( $hour = 8; $hour < 24;$hour++ )
                            <option value="{{ sprintf('%02d', $hour) }}:00">{{ sprintf('%02d', $hour) }}:00</option>
                        @endfor

                    </select>
                    <div class="h-4">
                        @error('end_hour') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>


            <div class="mt-6 flex justify-end space-x-4">
                <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                <button onclick="Livewire.dispatch('closeModal', { component: 'event.editEvent' })"  type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cancelar</button>
                <x-buttons.common>Guardar</x-buttons.common>
            </div>

        </form>
    </div>

</div>

