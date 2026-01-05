<div  class="max-h-screen overflow-y-auto">
    <div class="container  mx-auto bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">Crear orden de servicio</h2>
            <h3 class="text-base font-semibold"></h3>
        </div>
        <!--Validate which index coming from, schedules or corrective -->
        @if($previous_url !== 'planner' )
            @if($activity_type === 'schedule')
                <x-buttons.back route="admin.schedule"></x-buttons.back>
            @elseif($activity_type === 'corrective' )
                <x-buttons.back route="admin.corrective-management"></x-buttons.back>
            @endif

        @else
            <x-buttons.back route="admin.planner"></x-buttons.back>
        @endif


    </div>

    <!--Block 1 -->
    <div class="container mx-auto  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">

        <form
            @if( $previous_url !== 'planner' )
                wire:submit.prevent="store()"
            @else
                wire:submit.prevent="store_from_planner()"
            @endif
        >
            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Cliente:</label>
                    <input disabled value="{{$client_name}}" type="text" id="" name="" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                    <div class="h-4">

                    </div>

                    <div class="md:w-full pr-0  md:mb-0 @if($previous_url === 'planner') hidden @endif">
                        <div class="flex ">
                            <div class="flex items-center ">
                                <input  wire:model.defer="schedule_validator" id="" class="mx-2 cursor-pointer" type="checkbox"  >
                                <p class="text-[0.8rem] font-bold text-gray-700">Validar agenda</p>
                            </div>
                        </div>

                    </div>

                </div>



                <div class="md:w-1/5 pr-0 md:pr-4 mb-4 md:mb-0 @if($previous_url === 'planner') hidden @endif  ">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Hora incial*:</label>
                    <select  id="start_hour_id"  wire:model.defer="start_hour" name="start_hour" class=" mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 "

                    >
                        <option  value="">Seleccionar</option>
                        @for( $hour = 8; $hour < 24;$hour++ )
                            <option value="{{ sprintf('%02d', $hour) }}:00">{{ sprintf('%02d', $hour) }}:00</option>
                        @endfor

                    </select>
                    <div class="h-8">
                        @error('start_hour') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="md:w-1/5 pr-0 md:pr-4 mb-4 md:mb-0 @if($previous_url === 'planner') hidden @endif ">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Hora final:</label>
                    <select  id="end_hour_id" wire:model.defer="end_hour" name="end_hour" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option  value="">Seleccionar</option>
                        @for( $hour = 8; $hour < 24;$hour++ )
                            <option value="{{ sprintf('%02d', $hour) }}:00">{{ sprintf('%02d', $hour) }}:00</option>
                        @endfor
                    </select>
                    <div class="h-8">
                        @error('end_hour') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>




                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 md:mt-10">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Servicio:</label>
                    <div class="flex-1 p-4 border border-gray-300 rounded overflow-y-auto  " style="height: 120px">
                        <ul>
                            @foreach($services as $service )
                                <li>  <span   class="cursor-pointer"
                                              onclick="Livewire.dispatch('openModal', { component: 'client-equipment.show-client-equipment',arguments:{client_equipment_id: {{$service['equipment_id']}}  } })"> {{ $service['internal_id']}}  </span> - {{ $service['name'] }} </li>
                            @endforeach
                        </ul>
                    </div>

                </div>


            </div>

            <div  class="md:flex md:items-center mb-4">

            </div>
            <!--Block 2 -->
            <div  class="md:flex md:items-center mb-4">

                <!--Users -->
                <div class="md:w-1/2  mx-2">
                    <div class="">
                        <h3 for="" class=" block text-gray-700 font-bold mb-2">Usuarios*</h3>
                    </div>
                    <div class="flex-1 p-4 border border-gray-300 rounded overflow-y-auto  " style="height: 120px">
                        @foreach($users as $user)
                            <!--Dosen´t render the user with manager profile -->
                            <div class="flex
                              @foreach($user['roles'] as $role)
                               @if($role['role_name']  === 'Gerente General' || $role['role_name']  === 'Administrativo' ) hidden @endif
                              @endforeach
                            " wire:key="{{$user['id']}}">
                                <input class="cursor-pointer" name="activities" type="checkbox" value="{{$user['id']}}" wire:model.defer="users_list" >
                                <p class="ml-2"  > {{$user['name']}}  </p>
                            </div>

                        @endforeach
                    </div>
                    <div class="h-4">

                    </div>

                </div>

                <!--Activities -->
                <div class="md:w-full mx-2">

                    <div class="">
                        <h3 for="observations" class=" block text-gray-700 font-bold mb-2">Observaciones</h3>
                    </div>
                    <textarea wire:model.lazy="observations"  name="observations" cols="" rows="5" class="resize-none  focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"> </textarea>
                    <div class="h-4">
                        @error('observations') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>



            </div>


            <div  class="flex flex-col text-gray-400 ">
                <span>* Campo obligatorio.</span>
            </div>
            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6">
                    <x-buttons.save content="Crear"></x-buttons.save>
                    <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                </div>
            </div>

        </form>

    </div>


</div>



