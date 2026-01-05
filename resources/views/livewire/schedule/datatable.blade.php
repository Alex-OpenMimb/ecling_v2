<div>
    <!-- 1 -->
    <div class="w-full  flex gap-6">
        <div  class="">
            <x-forms.search property="query" method="search" id="material_search"></x-forms.search>
        </div>
        <div class=" pr-0 md:pr-4 mb-4 md:mb-0">
            <select  wire:model.lazy="amount" id="" name="amount" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="75">75</option>
                <option value="100">100</option>
            </select>
        </div>
        <div  wire:loading >  <x-loader></x-loader> </div>

    </div>

    <table class="w-full relative" >
        <thead class="border-b border-neutral-200 dark:border-neutral-700">
        <tr class="group">
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Item
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                <input class="hidden" wire:click="handle_schedule_selected()" wire:model="schedule_checked" type="checkbox" name="" id="handle_schedule_checkbox">
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Cliente*
            </th>
            <th class="px-2 text-left  text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Sucursal*
            </th>
            <th class="px-2 text-left  text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Equipos*
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800"
            >
                Frecuencia
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Última fecha*
            </th>
            <th  class="  px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800"
            >
                Próxima fecha*
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Días
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Observaciones
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Estado*
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($schedules AS $schedule  )
            <tr class="group"  wire:key="{{$schedule->id}}">
                <x-table.row> {{$schedule->id}} </x-table.row>
                <x-table.row>
                    <input  name="checkbox_schedule"  wire:click="select_schedule()"  value="{{$schedule->id}}" name="schedule"  wire:model="schedule_list" type="checkbox" id="">
                </x-table.row>

                <x-table.row>
                    <div  class="truncate-13"  title="{{$schedule->client_name}}"> {{$schedule->client_name}} </div>
                </x-table.row>
                <x-table.row>
                        <button
                            onclick="Livewire.dispatch('openModal', { component: 'client-equipment.show-client-equipment',arguments:{client_equipment_id: {{$schedule->equipment_id}}  } })"
                            type="button"  title="ver equipo" class="cursor-pointer" > <div class="truncate-13">{{$schedule->headquarter_name}}</div> </button>
                </x-table.row>
                <x-table.row> <button    onclick="Livewire.dispatch('openModal', { component: 'client-equipment.show-client-equipment',arguments:{client_equipment_id: {{$schedule->equipment_id}}  } })" class="truncate-13 cursor-pointer" >  {{$schedule->equipment_name}} </button> </x-table.row>
                <x-table.row> <button type="button"  title="Editar frecuencia"
                                      @if($schedule->status === 'Agendada' || $schedule->status === 'Agendada-Orden')
                                          wire:click="show_error_msm"
                                      @else
                                          onclick="Livewire.dispatch('openModal', { component: 'schedule.form-frequency',arguments:{schedule: {{$schedule->id}},frequency:{{$schedule->frequency}} } })"
                                      @endif
                                      class="cursor-pointer"> {{$schedule->frequency}}</button> </x-table.row>
                <x-table.row> {{$schedule->last_date}} </x-table.row>
                <x-table.row>  <button type="button"  title="editar fecha"
                                       @if($schedule->status === 'Agendada' || $schedule->status === 'Agendada-Orden')
                                           wire:click="show_error_msm"
                                       @else
                                           onclick="Livewire.dispatch('openModal', { component: 'schedule.form-date',arguments:{schedule: {{$schedule->id}} } })"
                                       @endif
                                       class="cursor-pointer"> {{$schedule->next_date}}  </button>  </x-table.row>
                <x-table.row> {{$schedule->days}} </x-table.row>
                <td   class="px-2 bg-neutral-100 group-odd:bg-white group-hover:bg-neutral-200">
                    <button  onclick="Livewire.dispatch('openModal', { component: 'schedule.observations',arguments:{schedule: {{$schedule->id}} } })" class="p-1 text-orange-900 rounded hover:bg-orange-900 hover:text-white" title="ver observaciones">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                    </button>
                </td>

                <x-table.row>
                    <div class="
                        @if($schedule->status == 'A tiempo')
                         bg-green-600
                         @elseif( $schedule->status == 'Por vencer')
                         bg-yellow-500
                          @elseif( $schedule->status == 'Urgente')
                         bg-red-500
                         @elseif( $schedule->status == 'Agendada' || $schedule->status == 'Agendada-Orden' )
                           bg-blue-600
                        @endif
                        text-center text-white text-sm"> {{$schedule->status}}
                    </div>
                </x-table.row>
            </tr>
        @endforeach
        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">

        </tfoot>
    </table>
    {{ $schedules->links() }}
    @if($schedules->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @script

    <script>
        let $inputs =  document.querySelectorAll('input[name="checkbox_schedule"]');
        let $handle_checkbox = document.getElementById('handle_schedule_checkbox');

        Livewire.on('restart_schedule_check', () => {
            $handle_checkbox.checked = false
            $inputs.forEach(input=>{
                input.checked = false
            })
        });


        $wire.on('modal_validate_schedule', ({service}) => {

            Swal.fire({
                title: "¿Estás seguro de esta acción?",
                text: "Seleccionar sucursales diferentes puede causar retrasos en la atención del servicio. Considera esto al agendar el servicio",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si!"
            }).then((result) => {
                if (result.isConfirmed) {
                   if( service === 'event' )  Livewire.dispatch('redirect_event_form')
                    if(service === 'order')  Livewire.dispatch('redirect_service_order')

                }
            });
        });








    </script>
    @endscript

</div>
