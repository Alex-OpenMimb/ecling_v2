<div class="mt-2">
    <div >

        <div   class="m-4">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-base font-semibold tracking-tight">Cliente: {{$client}} </h3>
                    <h3 class="text-base font-semibold tracking-tight">Sucursal: {{$headquarter}} </h3>

                </div>
                <div class="flex justify-between items-center pb-4">
                    <button  onclick="Livewire.dispatch('closeModal', { component: 'client-equipment.show-client-equipment' })" type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-2 gap-2 p-2 bg-white rounded-lg shadow-md">
                <div class="border">
                    <h3 class="text-lg font-semibold">Serial:</h3>
                    <p class="text-gray-600"> {{$serial}} </p>
                </div>
                <div class="border">
                    <h3 class="text-lg font-semibold">Nombre:</h3>
                    <p class="text-gray-600"> {{$equipment_name}} </p>
                </div>
                <div class="border">
                    <h3 class="text-lg font-semibold mr-4">Id Interno:</h3>
                    <p class="text-gray-600">{{$internal_id}}</p>
                </div>

                <div class="border">
                    <h3 class="text-lg font-semibold mr-4">Modelo:</h3>
                    <p class="text-gray-600">{{$equipment_model}}</p>
                </div>
                <div class="border">
                    <h3 class="text-lg font-semibold mr-4">Marca:</h3>
                    <p class="text-gray-600"> {{$brand_name}} </p>
                </div>

                <div class="border">
                    <h3 class="text-lg font-semibold mr-4">Clase de euqipo:</h3>
                    <p class="text-gray-600">{{$equipment_class}}</p>
                </div>

                <div class="border">
                    <h3 class="text-lg font-semibold mr-4">Ubicación:</h3>
                    <p class="text-gray-600">{{$location}}</p>
                </div>
                <div class="border">
                    <h3 class="text-lg font-semibold mr-4">Voltios:</h3>
                    <p class="text-gray-600">{{$volt}} - {{$volt_unit}}</p>
                </div>
                <div class="border">
                    <h3 class="text-lg font-semibold mr-4">Amperios:</h3>
                    <p class="text-gray-600">  @if( !$ampere ) Sin registro @else  {{$ampere}} - {{$ampere_unit}} @endif   </p>
                </div>
            </div>

            <div class="">
                <h3 class="text-lg font-semibold mr-4">Observaciones:</h3>
                <textarea wire:model.lazy="observations" readonly  name="observations" cols="" rows="2" class="resize-none  focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"> </textarea>

            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button onclick="Livewire.dispatch('closeModal', { component: 'client-equipment.show-client-equipment' })" type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cerrar</button>
            </div>
        </div>
    </div>

</div>

