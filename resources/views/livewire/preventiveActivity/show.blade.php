<div class="mt-2">
    <div >

        <div   class="m-4">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-xl font-bold tracking-tight"></h3>

                </div>
                <div class="flex justify-between items-center pb-4">
                    <button onclick="Livewire.dispatch('closeModal', { component: 'preventive-activity.show-preventive-activity' })" type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex flex-col p-2 bg-white rounded-lg shadow-md">
                <div class="border p-3">
                    <h3 class="text-lg font-semibold">Actividad:</h3>
                    <p class="text-gray-600">{{$activity}}</p>
                </div>
                <div class="border p-3">
                    <h3 class="text-lg font-semibold">Clase de equipo:</h3>
                    <p class="text-gray-600">{{$equipment_class}}</p>
                </div>
                <div class="border p-3">
                    <h3 class="text-lg font-semibold mr-4">Descripción:</h3>
                    <p class="text-gray-600">@if(!$description) Sin descripción @else  {{$description}} @endif</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button onclick="Livewire.dispatch('closeModal', { component: 'preventive-activity.show-preventive-activity' })"  type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cerrar</button>
            </div>
        </div>
    </div>

</div>

