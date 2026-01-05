<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10 "  >
        <div class="bg-gray-100 rounded-md  items-center">
            <h2 class="text-lg font-bold"> @if($action) Editar @else Crear @endif  Servicio correctivo</h2>
            <h2 class="text-base font-semibold"> </h2>
        </div>
        <x-buttons.back route="admin.corrective-management" ></x-buttons.back>
    </div>
    <div class="container mx-auto mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form  wire:submit.prevent="updateOrStore()" class="m-4 pb-4">
            <div class="md:flex md:items-center mb-4" >
                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0"  >
                    <label for="" class="block text-gray-700 font-bold  mb-2" title="" >Clase de Equipo:</label>
                    <select  @if($action) disabled @endif  id="" wire:model.lazy="equipment_class_id" name="equipment_class_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="" >Seleccionar</option>
                        @foreach($equipment_classes_list as $equipment)
                            <option wire:key="{{$equipment->id}}"  value="{{$equipment->id}}" >{{$equipment->name}}</option>
                        @endforeach
                    </select>

                </div>

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0"  >
                    <label for="" class="block text-gray-700 font-bold  mb-2" title="" >Clientes:</label>
                    <select   @if($action) disabled @endif  id="client_id" wire:model.lazy="client_id" name="client_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option @if($client_restart) selected @endif   value="" >Seleccionar</option>
                        @foreach($clients_list as $client)
                            <option wire:key="{{$client->id}}"   value="{{$client->id}}" >{{$client->name}}</option>
                        @endforeach
                    </select>

                </div>

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0"  >
                    <label for="" class="block text-gray-700 font-bold  mb-2" title="" >Sucursales:</label>
                    <select  @if($action) disabled @endif   id="" wire:model.lazy="headquarter_id" name="headquarter_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="" >Seleccionar</option>
                        @foreach($headquarters_list as $headquarter)
                            <option wire:key="{{$headquarter->id}}"  value="{{$headquarter->id}}" >{{$headquarter->name}}</option>
                        @endforeach
                    </select>

                </div>



            </div>

            <div class="flex flex-col md:flex-row justify-between mb-4 md:space-x-4 space-y-4 md:space-y-0">
                <div class="flex-1 p-4 border border-gray-300 rounded overflow-y-auto h-40">

                    <div class="flex" >
                        <h3 class="font-semibold mb-2 mr-2">Equipos</h3>
                    </div>

                    @if(empty($equipment_clients_check_input))
                        <h2>Sin resultados!</h2>
                    @else
                        @foreach($equipment_clients_check_input as $equipment)
                            <div class="flex my-4">
                                <input   name="equipments"
                                         class=""

                                         type="checkbox" value="{{$equipment['id']}}"
                                         wire:key="{{$equipment['id']}}"
                                         wire:model.defer="equipment_list">
                                <p class="ml-2 cursor-pointer"
                                   onclick="Livewire.dispatch('openModal', { component: 'client-equipment.show-client-equipment',arguments:{client_equipment_id: {{$equipment['id']}}  } })"
                                > {{$equipment['name']}} - {{ $equipment['brand_name']}} - {{ $equipment['equipment_model']}} - {{ $equipment['volt_measurement']}} {{ $equipment['volt_unit']}} @if( $equipment['amperage_measurement'] ) - {{ $equipment['amperage_measurement']}} {{ $equipment['ampere_unit']}} @endif  </p>
                            </div>
                        @endforeach

                    @endif

                </div>


                <div class="flex-1 p-4 border border-gray-300 rounded overflow-y-auto h-40">

                    <div class="flex">
                        <h3 class="font-semibold mb-2 mr-2">Actividades</h3>
                    </div>

                    @if(empty($activities_check_input))
                        <h2>Sin resultados!</h2>
                    @else
                        @foreach($activities_check_input as $corrective)
                            <div class="flex my-4">
                                <input  name="equipments"   type="checkbox" value="{{$corrective['id']}}"
                                        wire:model="corrective_list">
                                <p class="ml-2"> {{ $corrective['activity'] }}   </p>
                            </div>
                        @endforeach
                    @endif


                </div>
            </div>

                <div class="mt-6  ">
                    <x-buttons.common>Guardar</x-buttons.common>
                    <div class="h-4 mt-2"> <div wire:loading  > <span class="text-gray-400">Cargando...</span> </div></div>

                </div>

        </form>

    </div>

</div>
