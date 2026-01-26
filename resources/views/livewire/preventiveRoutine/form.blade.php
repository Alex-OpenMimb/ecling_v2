<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10 "  >
        <div class="bg-gray-100 rounded-md  items-center">
            <h2 class="text-lg font-bold"> Crear Rutina</h2>
        </div>
        <x-buttons.back route="admin.preventive-routine" ></x-buttons.back>
    </div>
    <div class="container mx-auto mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form    wire:submit.prevent="updateOrStore()"  >
            <!-- block 1 -->
            <div class="md:flex md:items-center mb-4">
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="nit" class="block text-gray-700 font-bold mb-2"  @if( $action && $routine_validator ) title="Rutina en uso no se puede editar"  @endif >Nombre*:</label>
                    <input wire:model.defer="name"  @if( $action && $routine_validator ) disabled @endif  type="text" id="name" name="name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2" title="Nomenclatura principal">Frecuencia (en días):</label>
                    <select id="frequency_id" wire:model.defer="frequency" name="frequency" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="0">Seleccionar</option>
                        @for($index = 1;$index <= 12; $index++)
                            <option value="{{$index}}">{{$index}}</option>
                        @endfor
                    </select>
                    <div class="h-4">
                        @error('frequency') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  ">
                    <label for="equipment_class_id" class="block text-gray-700 font-bold mb-2" title="">Clase de equipo:</label>
                    <select  id="" @if( $action && $routine_validator ) disabled @endif wire:model.lazy="equipment_class_id" name="equipment_class_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Todos los tipos</option>
                        @foreach($equipment_classes_list as $equipment )
                            <option value="{{$equipment->id}}">{{$equipment->name}}</option>
                        @endforeach

                    </select>
                    <div class="h-4">

                    </div>
                </div>

            </div>
            <!-- block 2 -->

            <div class="flex flex-col md:flex-row justify-between mb-4 md:space-x-4 space-y-4 md:space-y-0">
               {{-- <div class="flex-1 p-4 border border-gray-300 rounded overflow-y-auto h-40">

                    <div class="flex" >
                        <h3 class="font-semibold mb-2 mr-2">Equipos</h3>

                    </div>

                    @if(empty($equipments_list))
                        <h2>Sin resultados!</h2>
                    @else
                    @foreach($equipments_list  as $equipment)
                        <div class="flex my-4">
                            <input   name="equipments"
                                     @if($action && $equipment->client_has_equipment_flag > 0) disabled @endif
                                     class=""
                                     type="checkbox" value="{{$equipment->id}}"
                                     wire:key="{{$equipment->id}}"
                                     wire:model.defer="equipments_check_inputs">
                            <p class="ml-2"> {{$equipment->name}} - {{ $equipment->brand_name}} - {{ $equipment->equipment_model}} - {{ $equipment->volt_measurement}} {{ $equipment->volt_unit}} @if( $equipment->amperage_measurement ) - {{ $equipment->amperage_measurement}} {{ $equipment->ampere_unit}} @endif  </p>
                        </div>
                    @endforeach

                    @endif

                </div> --}}


                <div class="flex-1 p-4 border border-gray-300 rounded overflow-y-auto h-40">

                    <div class="flex">
                        <h3 class="font-semibold mb-2 mr-2">Actividades</h3>

                    </div>

                    @if(empty($activities_list))
                        <h2>Sin resultados!</h2>
                    @else
                        @foreach($activities_list  as $activity)
                            <div class="flex">
                                <input  name="equipments"   type="checkbox" value="{{$activity['id']}}"
                                        wire:model.defer="activities_check_inputs">
                                <p class="ml-2"> {{$activity['activity']}}  </p>
                            </div>
                        @endforeach

                    @endif


                </div>
            </div>

            <!-- block 2-->

            <div  class="flex flex-col text-gray-400 ">
                <span>* Campo obligatorio</span>
            </div>
            <div class="md:flex md:items-center mb-4 ">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6">
                    <x-buttons.save content="Guardar"></x-buttons.save>
                    <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Cargando...</span> </div></div>
                </div>


            </div>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-confirm {
            background-color: #3085d6 !important;
            color: white !important;
        }
        .swal2-cancel {
            background-color: #d33 !important;
            color: white !important;
        }
    </style>
    @script
    <script >
        let toggle;
        let $equipments_inputs =  document.querySelectorAll('input[name="equipments"]');
        let $activities_inputs =  document.querySelectorAll('input[name="activities"]');
        $wire.on('restart_check', () => {
            $equipments_inputs.forEach(input=>{
                input.checked = false
            })
        });

        document.addEventListener('click',(e)=>{
            if(e.target.matches('#activities_checkbox_id')){
                toggle = e.target.checked
                $activities_inputs.forEach(input => {
                    input.checked =  toggle

                });
            }
        })

        document.addEventListener('click',(e)=>{
            if(e.target.matches('#equipments_checkbox_id')){
                toggle = e.target.checked
                $equipments_inputs.forEach(input => {
                    input.checked =  toggle

                });
            }
        })

        $wire.on('open_modal', () => {
            Swal.fire({
                title: "¿Estás cambiando la frecuencia, estás seguro?",
                text: "Este cambio afectará las fechas establecidas en el cronograma para los equipos que tienen " +
                    "asignado esta rutina.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si!",
                cancelButtonText: "Cancelar",
                buttonsStyling: true,
                reverseButtons: false,
                allowOutsideClick: true,
                allowEscapeKey: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('update_routine')
                }
            });
        });



    </script>
    @endscript


</div>
