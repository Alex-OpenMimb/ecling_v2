<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold"> Informe </h2>
            <h2 class="text-base font-semibold"> Client: <span class="font-normal">{{$client_name}}</span> </h2>
            <h2 class="text-base font-semibold"> Preventivo: @if($preventive) <x-icons.check></x-icons.check> @else  <x-icons.x-circle>  </x-icons.x-circle> @endif  </h2>
            <h2 class="text-base font-semibold"> Correctivo: @if($corrective) <x-icons.check></x-icons.check> @else  <x-icons.x-circle>  </x-icons.x-circle> @endif</h2>


            <div class="flex gap-4 mt-4">
                <button  wire:click="startTime" id=""
                        type="button"
                        class="bg-white border border-blue-500 text-blue-500 px-2 py-1 rounded-md
                                 hover:bg-blue-500 hover:text-white transition duration-300">Iniciar Contador</button>
            @if( $start_time )
                    <span>{{ $start_time  }}</span>
                    <span> hasta </span>
                    <span>{{ $end_time  }}</span>
            @endif

                <div  wire:loading class="h-4">
                    @if(!$start_time)
                        <x-loader></x-loader>
                    @endif
                </div>
            </div>
        </div>


        <!--Router to back -->
        <a href="{{route('admin.general-reports',['service_order_id'=>$service_order_id])}}"  title="editar" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
            <svg class="h-8 w-8 text-blue-600 hover:text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" /></svg>
        </a>

    </div>
    <div class="container mx-auto   px-4 py-3 mb-8  bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form  wire:submit.prevent="updateOrStore()" >
            <!-- block 1 -->
            <h2 class="font-bold text-lg text-gray-700">1. Datos Generales del Servicio</h2>
            <div class="md:flex md:items-center my-4 ">

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="serial" class="block text-gray-700 font-bold mb-2">Orden de servicio*:</label>
                    <input wire:model.lazy="serial"  readonly type="text" id="" name="serial" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('serial') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="date" class="block text-gray-700 font-bold mb-2">Fecha*:</label>
                    <input wire:model.lazy="date"   type="date" id="" name="date" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                    <div class="h-4">
                        @error('date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="start_hour" class="block text-gray-700 font-bold mb-2">Hora de Entrada*:</label>
                    <input wire:model.lazy="start_hour"   type="time" id="" name="start_hour" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('start_hour') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="end_hour" class="block text-gray-700 font-bold mb-2">Hora de Salida*:</label>
                    <input wire:model.lazy="end_hour"   type="time" id="" name="end_hour" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('end_hour') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="serial" class="block text-gray-700 font-bold mb-2">No. Teécnicos*:</label>
                    <input wire:model.lazy="operator" type="number" id="" name="operator" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('operator') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


            </div>
            <hr class="my-4">
            <!-- block 2 -->
            <h2 class="font-bold text-lg text-gray-700">2. Datos Generales del Equipo</h2>
            <div class="md:flex md:items-center my-4 items-center">
                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="equipment_type" class="block text-gray-700 font-bold mb-2">Nombre*:</label>
                    <input wire:model.lazy="equipment_name"  value="" readonly  type="text" id="" name="equipment_name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('equipment_name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="location" class="block text-gray-700 font-bold mb-2">Modelo*:</label>
                    <input  wire:model.lazy="equipment_model" value="" readonly  type="text" id="" name="equipment_model" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('equipment_model') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>



                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="capacity_type" class="block text-gray-700 font-bold mb-2">Serial*:</label>
                    <input  value=" @if($equipment_serial) {{$equipment_serial}} @else Sin serial @endif "  readonly  type="text" id="" name="equipment_serial" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('equipment_serial') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="brand" class="block text-gray-700 font-bold mb-2">Marca*:</label>
                    <input  wire:model.lazy="brand" value="" readonly  type="text" id="" name="brand" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('brand') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


            </div>
            <!-- block 2.1 -->
            <div class="md:flex md:items-center my-4 items-center">

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="brand" class="block text-gray-700 font-bold mb-2">Voltaje*:</label>
                    <input   value="{{$volt_measurement}} - {{$volt_unit}}" readonly  type="text" id="" name="" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('volt_measurement') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="equipment_model" class="block text-gray-700 font-bold mb-2">Amperaje*:</label>
                    <input   value="{{$amperage_measurement}} - {{$ampere_unit}}" readonly  type="text" id="" name="" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('amperage_measurement') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="type_refrigerant" class="block text-gray-700 font-bold mb-2">Ubicación*:</label>
                    <input  wire:model.lazy="location" value="" readonly   type="text" id="" name="location" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('location') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>

            <hr class="my-4">
            <!-- block 3 -->

            <h2 class="font-bold text-lg text-gray-700">3. MATENIMIENTO</h2>
            <!-- block 3.1 -->

            <h2 class="font-semibold ml-6 text-base text-gray-700  @if(!$preventive) hidden @endif ">Actividad de Mantenimiento Preventivo</h2>

            <div class="md:flex md:items-center my-4 items-center @if(!$preventive) hidden @endif ">
                <div class="md:w-full">
                    @if(empty($preventive_activities))
                        <div class="flex justify-center items-center h-48">
                            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
                        </div>
                    @else
                        <table class="w-full bg-white border border-gray-300">
                            <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Actividad</th>
                                <th class="py-2 px-4 border-b text-center text-sm font-medium text-gray-700">SI</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $preventive_activities as $activity )
                                <tr wire:key="{{$activity->id}}">
                                    <td class="py-2 px-4 border-b text-sm text-gray-700 w-full">{{$activity->activity}}</td>
                                    <td class="py-2 px-4 border-b text-center text-sm text-gray-700" > <input value="{{$activity->id}}" wire:model.lazy="preventive_activities_check"  type="checkbox"> </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif

                </div>
            </div>

            <h2 class="font-semibold ml-6 text-base text-gray-700 @if(!$corrective) hidden @endif ">Actividad de Mantenimiento Correctivo</h2>

            <div class="md:flex md:items-center my-4 items-center @if(!$corrective) hidden @endif">
                <div class="md:w-full">
                    @if(empty($corrective_activities))
                        <div class="flex justify-center items-center h-48">
                            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
                        </div>
                    @else
                        <table class="w-full bg-white border border-gray-300">
                            <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Actividad</th>
                                <th class="py-2 px-4 border-b text-center text-sm font-medium text-gray-700">SI</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $corrective_activities as $activity )
                                <tr  wire:key="{{$activity['id']}}">
                                    <td class="py-2 px-4 border-b text-sm text-gray-700 w-full">{{$activity['activity']}}</td>
                                    <td class="py-2 px-4 border-b text-center text-sm text-gray-700" > <input value="{{$activity['id']}}" wire:model.lazy="corrective_activities_check"  type="checkbox"> </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif

                </div>
            </div>


            <h2 class="font-bold text-lg text-gray-700">4. DESCIPCIÓN DEL SERVICIO</h2>
            <!-- block 3.2 -->
            <div class="md:flex md:items-center my-4 items-center">
                <div
                    class="md:w-full">
                    <div class="flex">

                        <div class="mb-2 ml-4">
                            <button id="clear-param-observation"
                                    data-id="param-observation"
                                    x-on:click="$wire.description_service = ''"
                                    type="button"
                                    class="bg-white border border-blue-500 text-blue-500 px-2 py-1
                                     rounded-md
                                 hover:bg-blue-500 hover:text-white transition duration-300">Limpiar</button>
                        </div>
                    </div>

                    <textarea
                        wire:model.lazy="description_service"
                        id="param-observation"
                        type="text" name="observations_param" cols="" rows="4"
                        class="resize-none mt-4 focus:outline-none bg-gray-50 border
                              border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                               focus:border-blue-500 block w-full p-2.5
                                dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                                  dark:focus:ring-blue-500 dark:focus:border-blue-500
                                 mb-2"></textarea>

                    <div class="h-4">
                        @error('description_service') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>

            </div>


            <hr class="my-4">
            <!-- block 4 -->
            <h2 class="font-bold text-lg text-gray-700">5. OBSERVACIÓN TÉCNICO</h2>

            <div class="md:flex md:items-center mt-4 items-center">
                <div  class="md:w-full">
                    <div  class="flex">
                        <div class="mb-2 ml-4">
                            <button id=""

                                    x-on:click="$wire.observations = ''"
                                    type="button"
                                    class="bg-white border border-blue-500 text-blue-500 px-2 py-1
                                     rounded-md
                                 hover:bg-blue-500 hover:text-white transition duration-300">Limpiar</button>
                        </div>
                    </div>
                    <textarea wire:model.lazy="observations"
                              cols="" rows="4" class="resize-none mt-4
                              focus:outline-none bg-gray-50 border
                     border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                     focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700
                     dark:border-gray-600 dark:placeholder-gray-400
                     dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"></textarea>
                    <div class="h-4">
                        @error('observations') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>


            </div>

            <hr class="my-4">
            <!-- block 4 -->
            <h2 class="font-bold text-lg text-gray-700">6.Pendiente</h2>

            <div class="md:w-1/3 pr-0 md:pr-4  md:mb-0" >
                <label for="" class="font-semibold text-base">Pendiente</label>
                <input wire:model.lazy="pending" @if($pending) checked @endif  type="checkbox" name="" id="">
            </div>


            <div class="md:flex md:items-center mt-4 items-center">
                <div  class="md:w-full">
                    <div  class="flex">
                        <div class="mb-2 ml-4">
                            <button id=""
                                    x-on:click="$wire.pending_note = ''"
                                    type="button"
                                    class="bg-white border border-blue-500 text-blue-500 px-2 py-1
                                     rounded-md
                                 hover:bg-blue-500 hover:text-white transition duration-300">Limpiar</button>
                        </div>
                    </div>
                    <textarea wire:model.lazy="pending_note"
                              id="executed_activity"
                              name="executed_activity"
                              cols="" rows="4" class="resize-none mt-4
                              focus:outline-none bg-gray-50 border
                     border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                     focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700
                     dark:border-gray-600 dark:placeholder-gray-400
                     dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"></textarea>
                    <div class="h-4">
                        @error('pending_note') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>


            </div>

            <!-- block 4 .2-->
            <h2 class="font-semibold ml-6 text-base text-gray-700">Repuestos Utilizados</h2>

            <div class="md:flex md:items-center my-4 items-center">
                <div class="md:w-full  overflow-x-auto">

                    @if(count($used_spare_parts) === 0)
                        <div class="flex justify-center items-center h-10">
                            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
                        </div>
                    @else

                        <table class="w-full bg-white border border-gray-300">
                            <thead>

                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Nombre</th>
                                <th class="py-2 px-4 border-b text-center text-sm font-medium text-gray-700">SI</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $used_spare_parts as $parts )
                                <tr>
                                    <td class="py-2 px-4 border-b text-sm text-gray-700 w-full">{{$parts->spare_part_name}}</td>
                                    <td class="py-2 px-4 border-b text-center text-sm text-gray-700 flex">
                                        <input class="mx-2"  value="{{$parts->id}}" wire:model.lazy="spare_part_check"  type="checkbox">
                                        <input   wire:model.lazy="spare_part_input.{{$parts->id}}"  class="w-16 mx-2 focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block   p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"
                                            type="number">
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif

                </div>
            </div>

            <!-- block 4 .2-->
            <h2 class="font-semibold ml-6 text-base text-gray-700">Materiales Utilizados</h2>

            <div class="md:flex md:items-center my-4 items-center">
                <div class="md:w-full  overflow-x-auto">

                    @if(count($used_materials) === 0)
                        <div class="flex justify-center items-center h-10">
                            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
                        </div>
                    @else
                        <table class="w-full bg-white border border-gray-300">
                            <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Nombre</th>
                                <th class="py-2 px-4 border-b text-center text-sm font-medium text-gray-700">SI</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $used_materials as $material )
                                <tr>
                                    <td class="py-2 px-4 border-b text-sm text-gray-700 w-full">{{$material->material_name}}</td>
                                    <td class="py-2 px-4 border-b text-center text-sm text-gray-700 flex">
                                        <input  class="mx-2" value="{{$material->id}}" wire:model.lazy="materials_check"  type="checkbox">
                                        <input    wire:model.lazy="material_inputs.{{$material->id}}"  class="w-16 mx-2 focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block   p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"
                                               type="number">
                                        <select id="" wire:model.lazy="material_select.{{$material->id}}"  name="low_pressure_unity" class="w-16 mx-2 mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option  value="">Selec</option>
                                            @foreach($units as $unit  )
                                                <option  value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif

                </div>
            </div>

            <hr class="my-4">
            <h2 class="font-bold text-lg text-gray-700">5. Evidencia</h2>

            <div class="mt-4">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm text-gray-600">Agrega las evidencias necesarias para el reporte y asigna un título descriptivo.</p>
                    <button type="button"
                            wire:click="addPhotoInput"
                            class="inline-flex items-center px-3 py-2 text-sm font-semibold text-blue-600 border border-blue-200 rounded-md hover:bg-blue-50 hover:text-blue-800 transition">
                        <i class='bx bx-plus mr-1 text-base'></i> Añadir foto
                    </button>
                </div>

                <div class="space-y-5">
                    @foreach($photoInputs as $index => $photo)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-4" wire:key="photo-input-{{ $index }}">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-gray-700">Evidencia {{ $loop->iteration }}</span>
                                    <div class="flex items-center gap-1">
                                        <span wire:loading.flex wire:target="photoInputs.{{ $index }}.file" class="text-blue-500 text-sm items-center gap-1">
                                            <i class='bx bx-loader-alt bx-spin text-lg'></i> Procesando…
                                        </span>
                                        <span wire:loading.remove wire:target="photoInputs.{{ $index }}.file">
                                            @if(($photo['flag'] ?? false) || ($photo['existing_path'] ?? null))
                                                <x-icons.check></x-icons.check>
                                            @else
                                                <x-icons.x-circle></x-icons.x-circle>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @if(count($photoInputs) > 1 && !$photo['existing_path']  )
                                    <button type="button"
                                            wire:click="removePhotoInput({{ $index }})"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                        Eliminar
                                    </button>
                                @endif
                            </div>

                            <div class="grid gap-4 md:grid-cols-12 md:items-end">
                                <div class="md:col-span-5"
                                     x-data="{ fileName: '', isReady: {{ isset($photo['flag']) && $photo['flag'] ? 'true' : 'false' }} }">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Archivo</label>
                                    <label class="flex items-center cursor-pointer bg-blue-100 text-blue-700 px-4 py-2 rounded-md border border-blue-200 hover:bg-blue-200 hover:text-blue-800 transition duration-300">
                                        <span>Cargar foto</span>
                                        <span x-text="fileName" class="ml-2 truncate"></span>
                                        <input
                                            type="file"
                                            class="hidden"
                                            wire:model.live="photoInputs.{{ $index }}.file"
                                            x-on:change="
                                                if ($event.target.files.length > 0) {
                                                    fileName = $event.target.files[0].name;
                                                    isReady = true;
                                                }
                                            "
                                        >
                                    </label>
                                    <div class="mt-1 h-4">
                                        @error('photoInputs.'.$index.'.file') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    @if(!empty($photo['existing_path']))
                                        <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $photo['existing_path'] }}">
                                            Archivo actual: {{ basename($photo['existing_path']) }}
                                        </p>
                                    @endif
                                </div>

                                <div class="md:col-span-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Título de la foto</label>
                                    <select wire:model.defer="photoInputs.{{ $index }}.title_photo_id"
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 px-3 py-2.5">
                                        <option value="">Selecciona un título</option>
                                        @foreach($titlePhotoOptions as $option)
                                            <option value="{{ $option->id }}">{{ $option->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-1 h-4">
                                        @error('photoInputs.'.$index.'.title_photo_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="md:col-span-2 flex items-center justify-center">
                                    <span class="text-xs text-gray-500 text-center">
                                        @if(!empty($photo['file']))
                                            Procesando…
                                        @elseif(!empty($photo['existing_path']))
                                            Registrada
                                        @else
                                            Sin archivo
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($titlePhotoOptions->isEmpty())
                    <p class="text-xs text-orange-600 mt-3">
                        No hay títulos disponibles. Crea títulos en el módulo de configuraciones para asignarlos a las evidencias.
                    </p>
                @endif
            </div>


            <hr class="my-4">
            <h2 class="font-bold text-lg text-gray-700">6. Recibe</h2>

            <div class="md:flex md:items-center mt-4 items-center">
                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="nit" class="block text-gray-700 font-bold mb-2">Nombre*:</label>
                    <input wire:model.lazy="receptor_name"   type="text" id="name" name="receptor_name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('receptor_name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="nit" class="block text-gray-700 font-bold mb-2">Cargo*:</label>
                    <input wire:model.lazy="receptor_position"   type="text" id="receptor_position" name="name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('receptor_position') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/5 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="font-semibold ml-6 text-base text-gray-700" title="">Documento:</label>
                    <select id="receptor_document_type" wire:model.lazy="receptor_document_type"  name="type_power_sources" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option  value="">Sleccionar</option>
                        <option  value="cc">CC</option>
                    </select>
                    <div class="h-4">
                        @error('receptor_document_type') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="nit" class="block text-gray-700 font-bold mb-2">Identificación*:</label>
                    <input wire:model.lazy="receptor_document"   type="number" id="name" name="receptor_document"
                           class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('receptor_document') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                <label for="nit" class="block text-gray-700 font-bold mb-2">Solicitado por:</label>
                <input wire:model.lazy="request_name"   type="text" id="name" name="receptor_name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                <div class="h-4">
                    @error('request_name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div  class="md:flex md:items-center mt-4 items-center">

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex gap-6">
                        <div class="flex">
                            <label for="signature-canvas" class="block text-gray-700 font-bold mb-2">Firma*:</label>
                            @if($receptor_signature)
                                <x-icons.check></x-icons.check>
                            @else
                                <x-icons.x-circle>  </x-icons.x-circle>
                            @endif
                        </div>
                        <div class="mb-2">
                            <button id="clear-signature"
                                    type="button"
                                    class="bg-white border border-blue-500 text-blue-500 px-2 py-1
                                     rounded-md
                                 hover:bg-blue-500 hover:text-white transition duration-300">Limpiar</button>
                        </div>
                    </div>
                    <canvas id="signature-canvas" width="400" height="100"
                            class="bg-gray-50 border border-gray-300"
                    ></canvas>
                </div>

            </div>
            <input type="hidden" id="signature-input">
            <div  class="flex flex-col text-gray-400 mt-4">
                <span>* Campo obligatorio</span>
            </div>
            <div class="md:flex md:items-center mb-4 ">
                @if($start_time)
                    <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6 ">
                        <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 ">
                            <button id="stored-button"
                                    wire:loading.class="opacity-50"
                                    wire:loading.attr="disabled"
                                    type="submit"
                                    class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md
                                 hover:bg-blue-500 hover:text-white transition duration-300">Guardar</button>
                        </div>
                        <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                    </div>

                @else
                    <div  class="bg-red-600">
                        <span class="font-semibold  text-base text-white">Para guardar el reporte debes iniciar el contador*</span>
                    </div>

                @endif



                <div class="mt-2">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-400 text-[0.9rem]">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>


            </div>

        </form>

    </div>

</div>
@script
<script>
    const d = document;
    const canvas = document.getElementById('signature-canvas');
    const input = document.getElementById('signature-input');
    const storeButton = document.getElementById('stored-button');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let validator = false;

    const $param_observations = d.getElementById('param-observation')


    function getEventPosition(e) {
        if (e.touches && e.touches.length > 0) {
            return {
                x: e.touches[0].clientX - canvas.getBoundingClientRect().left,
                y: e.touches[0].clientY - canvas.getBoundingClientRect().top
            };
        } else {
            return {
                x: e.clientX - canvas.getBoundingClientRect().left,
                y: e.clientY - canvas.getBoundingClientRect().top
            };
        }
    }

    function startDrawing(e) {
        isDrawing = true;
        validator = true
        const pos = getEventPosition(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        e.preventDefault();

    }

    function draw(e) {
        if (!isDrawing) return;
        const pos = getEventPosition(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        e.preventDefault();
    }

    function stopDrawing(e) {
        if (!isDrawing) return;
        isDrawing = false;
        ctx.closePath();
        e.preventDefault();
    }

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        Livewire.dispatch('clear_signature')
        validator = false
    }
    function validateSignature(){
        const signatureDataUrl = canvas.toDataURL('image/png');
        if( validator )  Livewire.dispatch('save_signature',{signatureDataUrl})
    }

    // Event listeners for mouse
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);
    canvas.addEventListener('mouseleave', validateSignature);

    // Event listeners for touch
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);
    canvas.addEventListener('touchcancel', stopDrawing);
    canvas.addEventListener('touchend', validateSignature);

    //Clear canvas
    document.getElementById('clear-signature').addEventListener('click', clearCanvas);



</script>
@endscript


