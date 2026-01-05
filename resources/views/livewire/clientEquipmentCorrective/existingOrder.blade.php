<div>
    <div >
        <form wire:submit.prevent="assign()" class="m-4">
            <div class="flex justify-between items-center pb-4">
                <h3 class="text-xl font-bold  text-gray-900" id="brand-modal-title"

                >Reporte General  </h3>
                <button onclick="Livewire.dispatch('closeModal', { component: 'client-equipment-corrective.Existing-order'})"   type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- block 1 -->
            <div class="md:flex flex-col ">


                @if($validator)
                    <div class=" pr-0 md:pr-4 mb-4 md:mb-0 "  >
                        <label for="email" class="block text-gray-700 font-bold  mb-2" title="" >Seleccionar Reporte:</label>
                        <select  id="general_report_id" wire:model.lazy="general_report_id" name="general_report_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option   value="" >Seleccionar</option>
                            @if( count($general_report_list) == 0 )
                                <option   value="" >Sin reporte</option>
                            @else
                                @foreach($general_report_list as $general_report)
                                    <option  wire:key="{{$general_report->id}}" value="{{$general_report->id}}" >{{$general_report->serial}}</option>
                                @endforeach
                            @endif

                        </select>
                        <div class="h-4">
                            @error('general_report_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                @else
                    <h2  class="text-base font-semibold"> {{$error_msm}} </h2>
                @endif




            </div>
            <div class="mt-6 flex justify-end space-x-4">
                <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                <button  onclick="Livewire.dispatch('closeModal', { component: 'client-equipment-corrective.Existing-order'})"  type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cancelar</button>
                <x-buttons.common>Asignar</x-buttons.common>
            </div>
        </form>
    </div>

</div>

