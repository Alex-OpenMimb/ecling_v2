<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold"> @if($serial) Editar @else Crear  @endif Equipo   </h2>
            <h2 class="text-base font-semibold"> Cliente: {{$client_name}}  </h2>
            <h2 class="text-base font-semibold"> Sucursal: {{$headquarter_name}}  </h2>

        </div>
        <a href="{{route('admin.clients-equipments',['client'=> $client->slug,'headquarter' =>$headquarter->slug ])}}"  title="atras" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
            <svg class="h-8 w-8 text-blue-600 hover:text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" /></svg>
        </a>

    </div>
    <div class="container mx-auto   px-4 py-6 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form  wire:submit.prevent="updateOrStore()" >
            <!-- block 1 -->
            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex">
                        <label for="equipment_id" class="block text-gray-700 font-bold mb-2" title="">Equipo*:</label>
                        @if($action) <span   class="block ml-4 text-red-400">Solo lectura </span> @endif
                    </div>
                    <select id="equipment_id"  @if($action)  disabled @endif wire:model.lazy="equipment_id"  name="equipment_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="0" >Seleccionar</option>
                        @foreach( $equipments_list as $equipment )
                            <option  @if( $equipment_id === $equipment->id) selected @endif  wire:key="{{$equipment->id}}"  value="{{$equipment->id}}"  > {{$equipment->name}} - {{$equipment->brand_name}} - {{$equipment->equipment_model}} - {{$equipment->volt_measurement}} {{$equipment->volt_unit}} @if($equipment->amperage_measurement) - {{$equipment->amperage_measurement}} {{$equipment->ampere_unit}} @endif </option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('equipment_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex">
                        <label for="email" class="block text-gray-700 font-bold mb-2" title="">Clase de equipo*:</label>
                        @if($action) <span   class="block ml-4 text-red-400">Solo lectura </span> @endif
                    </div>
                    <select id="equipment_class_id" @if($action)  disabled @endif wire:model.lazy="equipment_class_id"  name="equipment_class_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="0" >Seleccionar</option>
                        @foreach($equipment_classes_list as $equipment_class  )
                            <option  wire:key="{{$equipment_class->id}}"  value="{{$equipment_class->id}}" >{{$equipment_class->name}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('equipment_class_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="nit" class="block text-gray-700 font-bold mb-2">Serial*:</label>
                    <input wire:model.lazy="serial" type="text" id="serial" name="serial" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('serial') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>
            <!-- block 2 -->
            <div class="md:flex md:items-center mb-4 items-center">


                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2" title="">Ubicación*:</label>
                    <select id="location_id" wire:model.lazy="location_id"  name="location_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="" >Seleccionar</option>
                        @foreach($locations_list as $location  )
                            <option  wire:key="{{$location->id}}"  value="{{$location->id}}" >{{$location->name}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('location_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  mr-2">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Observaciones:</label>
                    <textarea  wire:model.lazy="observations" name="observations" class="resize-none focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"></textarea>
                    <div class="h-4">
                        @error('observations') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


            </div>
            <!-- block 3 -->


            <div class="md:flex md:items-center mb-4 items-center">

                <div x-data="{ fileName: '', isChecked: @entangle('plate_flag') }"  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex gap-2">
                        <label for="" class="block text-gray-700 font-bold mb-2" title="">Foto de la placa:</label>

                            @if($plate_photo)
                                <x-icons.check></x-icons.check>
                            @else
                                <x-icons.x-circle>  </x-icons.x-circle>
                            @endif

                    </div>
                    <label for="plate_file_input" class="block flex items-center cursor-pointer bg-blue-100 text-blue-700 px-4 py-2 rounded-md border border-blue-200 hover:bg-blue-200 hover:text-blue-800 transition duration-300">
                        <span>Cargar foto</span>
                        <span x-text=" fileName ? fileName : '' " id="" class="ml-2"></span>
                        <input id="plate_file_input"   wire:model.live="plate_photo" name="plate_photo" type="file"
                               class="hidden" x-on:change="fileName = $event.target.files[0].name"
                               @change="isChecked = $event.target.files.length > 0"
                        >
                    </label>
                    <div class="h-4">
                        @error('plate_photo') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <input x-model="isChecked" wire:model.live="plate_flag"  type="checkbox"  class="hidden" id="plate_flag_id">
                </div>


                <div  x-data="{ fileName: '', isChecked: @entangle('perimeter_flag') }" class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex gap-2">
                        <label for="" class="block text-gray-700 font-bold mb-2" title="">Foto Panorámica:</label>

                            @if($perimeter_photo)
                                <x-icons.check></x-icons.check>
                            @else
                                <x-icons.x-circle>  </x-icons.x-circle>
                            @endif

                    </div>
                       <label for="perimeter_file_input" class="block flex items-center cursor-pointer bg-blue-100 text-blue-700 px-4 py-2 rounded-md border border-blue-200 hover:bg-blue-200 hover:text-blue-800 transition duration-300">
                        <span>Cargar foto</span>
                        <span x-text=" fileName ? fileName : '' " id="" class="ml-2"></span>
                        <input id="perimeter_file_input"   wire:model.live="perimeter_photo" name="perimeter_photo" type="file"
                               class="hidden" x-on:change="fileName = $event.target.files[0].name"
                               @change="isChecked = $event.target.files.length > 0"
                        >
                    </label>

                    <div class="h-4">
                        @error('perimeter_photo') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <input  x-model="isChecked" wire:model.live="perimeter_flag" type="checkbox" class="hidden" id="perimeter_flag_id">
            </div>

            <div class="md:w-1/2 pr-0 md:pr-4 mt-6 md:mb-0 ">
                <div class="flex gap-2">
                    <lablel   class="block text-gray-700 font-bold mb-2"> Asignar rutina </lablel>
                    <input    wire:model.lazy="routine_validator" type="checkbox">
                </div>

            </div>


            <div  class="flex flex-col text-gray-400 mt-2 mb-4">
                <span>* Campo obligatorio</span>
            </div>

            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6">
                    <x-buttons.save content="Guardar"></x-buttons.save>
                    <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                </div>

            </div>


        </form>

    </div>

</div>


