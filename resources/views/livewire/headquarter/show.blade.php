<div class="mt-2">
    <div >

        <div   class="m-4">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-xl font-bold tracking-tight">{{$name}}</h3>

                </div>
                <div class="flex justify-between items-center pb-4">
                    <button   wire:click="$dispatch('closeModal', { component: 'headquarter.show-headquarter' })" type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="grid grid-cols-2 gap-2 p-2 bg-white rounded-lg shadow-md">

                <div class="border">
                    <h3 class="text-lg font-semibold mr-4">Persona de contacto:</h3>
                    <p class="text-gray-600">{{$contact_name}}</p>
                </div>
                <div class="border">
                    <h3 class="text-lg font-semibold mr-6">Teléfono 1:</h3>
                    <p class="text-gray-600"> {{$phone_1}} </p>
                </div>
                <div class="border ">
                    <h3 class="text-lg font-semibold">Teléfono 2:</h3>
                    <p class="text-gray-600"> @if($phone_2) {{$phone_2}}  @else Sin teléfono @endif </p>
                </div>
                <div class="border">
                    <h3 class="text-lg font-semibold mr-6">No. de equipos:</h3>
                    <p class="text-gray-600"> {{$equipments_amount}} </p>
                </div>

                <div class="border ">
                    <h3 class="text-lg font-semibold">Dirección:</h3>
                    <p class="text-gray-600">{{$city_name}}, {{$nomenclature_main}} {{$number_main}}, {{$nomenclature_second}} {{$number_second}} No {{$number}}  </p>
                </div>
                <div class="border ">
                    <h3 class="text-lg font-semibold">Punto de referencia:</h3>
                    <p class="text-gray-600"> {{$observations}}  </p>
                </div>

            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button wire:click="$dispatch('closeModal', { component: 'headquarter.show-headquarter' })" type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cerrar</button>
            </div>
        </div>
    </div>

</div>

