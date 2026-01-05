<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold"> @if($name) Editar @else Crear  @endif Equipo  </h2>
        </div>
        <x-buttons.back route="admin.equipments" ></x-buttons.back>

    </div>
    <div class="container mx-auto mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form  wire:submit.prevent="updateOrStore()" >
            <!-- block 1 -->
            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="nit" class="block text-gray-700 font-bold mb-2">Nombre*:</label>
                    <input wire:model.lazy="name" type="text" id="name" name="name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                      <div class="flex">
                          <label for="email" class="block text-gray-700 font-bold mb-2" title="">Clase de equipo*:</label>
                          @if($asset_assignment || $routine_assignment) <span   class="block ml-4 text-red-400">Solo lectura </span> @endif
                      </div>
                    <select  @if($asset_assignment  || $routine_assignment) disabled @endif  id="equipment_class_id" wire:model.lazy="equipment_class_id"  name="equipment_class_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="" >Seleccionar</option>
                        @foreach($equipment_class_lists as $class)
                            <option  wire:key="{{$class->id}}" value="{{$class->id}}" >{{$class->name}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('equipment_class_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2" title="">Modelo*:</label>
                    <select  id="equipment_model_id" wire:model.lazy="equipment_model_id"  name="equipment_model_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="" >Seleccionar</option>
                        @foreach($models_list as $model)
                            <option  @if( $equipment_model_id === $model->id ) selected @endif  wire:key="{{$model->id}}" value="{{$model->id}}" >{{$model->model}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('equipment_model_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>
            <!-- block 2 -->
            <div class="md:flex md:items-center mb-4 items-center">
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2" title="">Voltios*:</label>
                    <select id="volt_id" wire:model.lazy="volt_id"  name="volt_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="" >Seleccionar</option>
                        @foreach($volt_lists as $volt)
                            <option  wire:key="{{$volt->id}}" value="{{$volt->id}}" >{{$volt->volt_measurement}} - {{$volt->unit}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('volt_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold  mb-2" title="" >Amperios:</label>
                    <select  id="ampere_id" wire:model.lazy="ampere_id" name="ampere_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="" >Seleccionar</option>
                        @foreach($ampere_lists as $ampere)
                            <option  wire:key="{{$ampere->id}}" value="{{$ampere->id}}" >{{$ampere->amperage_measurement}} - {{$ampere->unit}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">

                    </div>
                </div>


                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2" title="Nomenclatura principal">Marca*:</label>
                    <select id="brand_id" wire:model.lazy="brand_id"  name="brand_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option   value="" >Seleccionar</option>
                        @foreach($brands_lists as $brand)
                            <option  wire:key="{{$brand->id}}" value="{{$brand->id}}" >{{$brand->name}} </option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('brand_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="">
                <div  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  ">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Descripción:</label>
                    <textarea  wire:model.lazy="description" name="description" class="resize-none focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"></textarea>
                </div>
                <div class="h-4">
                    @error('description') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="h-4">
                @error('equipment_class_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
            </div>
            <div  class="flex flex-col text-gray-400 mt-2 ">
                <span>* Campo obligatorio</span>
            </div>
            <div class="md:flex md:items-center mb-4 ">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6">
                    <x-buttons.save content="Guaardar"></x-buttons.save>
                    <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                </div>


            </div>

        </form>

    </div>

</div>


