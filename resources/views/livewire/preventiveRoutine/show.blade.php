<div class="mt-2">
    <div >

        <div   class="m-4">
            <div class="flex justify-between">
                <div>
                    <h3 class="text-xl font-bold tracking-tight">{{$name}}</h3>
                    <h3 class="text-base font-semibold tracking-tight">Clse de Equipo: {{$equipment_class_name}} </h3>
                    <h3 class="text-base font-semibold tracking-tight">Frecuencia: {{$frequency}} Meses</h3>

                </div>
                <div class="flex justify-between items-center pb-4">
                    <button  onclick="Livewire.dispatch('closeModal', { component: 'preventive-routine.show-preventive-routine' })"  type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Content -->
            <div class="flex flex-col p-2 bg-white rounded-lg shadow-md">
                <div class=" p-4 border border-gray-300 rounded overflow-y-auto custom-height-100">
                    <h3  class="font-semibold mb-2">Actividades</h3>
                    @foreach($activities_list as $activity)
                        <p>{{$activity->activity}}</p>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button onclick="Livewire.dispatch('closeModal', { component: 'preventive-routine.show-preventive-routine' })" type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cerrar</button>
            </div>
        </div>
    </div>

</div>

