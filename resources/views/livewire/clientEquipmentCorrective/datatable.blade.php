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
                Equipo*
            </th>
            <th title="editar frecuencia" class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800 cursor-pointer"

            >
                Actividad
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Estado*
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Acciones
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($correctives AS $corrective  )
            <tr class="group"  wire:key="{{$corrective->id}}">
                <x-table.row> {{$counter}} </x-table.row>
                <x-table.row>
                    <input   value="{{$corrective->id}}" name="corrective"
                             wire:model.lazy="corrective_list"
                             wire:click="select_corrective()"
                             type="checkbox" id="">

                </x-table.row>

                <x-table.row>
                    <div  class="truncate-13"  title="{{$corrective->client_name}}"> {{$corrective->client_name}} </div>
                </x-table.row>
                <x-table.row> {{$corrective->headquarter_name}} </x-table.row>
                <x-table.row> {{$corrective->internal_id}} </x-table.row>
                <x-table.row> <div style="width: 200px"> {{$corrective->related_activities}} </div> </x-table.row>
                <x-table.row>
                    <div
                        class="
                       @if($corrective->status == 'Abierto')
                            bg-red-500
                        @elseif( $corrective->status == 'Cerrado')
                            bg-green-500
                        @elseif( $corrective->status == 'Rechazado')
                          bg-yellow-500
                            @elseif( $corrective->status == 'Agendado' || $corrective->status == 'Agendado-Orden' )
                            bg-blue-500
                        @endif
                        text-center text-white
                        "
                    >
                        {{$corrective->status}}
                    </div>
                </x-table.row>
                <x-table.row>
                    <div class="flex">
                        @if($corrective->status === 'Agendado' || $corrective->status == 'Agendado-Orden' || $corrective->status == 'Cerrado')
                            <a  @if($corrective->status === 'Agendado')
                                    wire:click="show_error_delete('edit')"
                                @elseif( $corrective->status === 'Cerrado' )
                                    wire:click="show_error_delete('closed-edit')"
                                @endif


                                title="editar" class="cursor-pointer  p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                            </a>
                        @else
                            <button wire:click="redirect_edit('{{$corrective->id}}')"    title="editar" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                            </button>
                        @endif
                        <button class="p-1 text-red-500 rounded hover:bg-red-500"    @if(  $corrective->status == 'Agendado-Orden' ||  $corrective->status == 'Agendado') wire:click="show_error_delete('delete')" @elseif( $corrective->status === 'Cerrado' ) wire:click="show_error_delete('closed')"   @else @click="$dispatch('open_modal_corrective',{id: {{$corrective->id}} })"  @endif  >
                            <svg class="h-5 w-5 text-red-500 hover:text-white"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="4" y1="7" x2="20" y2="7" />  <line x1="10" y1="11" x2="10" y2="17" />  <line x1="14" y1="11" x2="14" y2="17" />  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                        </button>
                        <button  onclick="Livewire.dispatch('openModal', { component: 'client-equipment-corrective.observations',arguments:{corrective: {{$corrective->id}} } })"   class="p-1 text-orange-900 rounded hover:bg-orange-900 hover:text-white" title="ver observaciones">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </x-table.row>
            </tr>
            @php $counter++ @endphp
        @endforeach
        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">

        </tfoot>
    </table>
    {{ $correctives->links() }}
    @if($correctives->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @script
    <script >

        $wire.on('open_modal_corrective', (id) => {

            Swal.fire({
                title: "¿Estás seguro de que deseas continuar?",
                text: "Si eliminas este registro, no podrás recuperarlo.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete_corrective',{clients_equipments_correctives:id})
                }
            });
        });


        $wire.on('modal_validate_corrective', ({service}) => {

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
                    if( service === 'event' )  Livewire.dispatch('redirect_event_form_corrective')
                    if( service === 'order' )  Livewire.dispatch('redirect_order_form_corrective')
                }
            });
        });
        $wire.on('reload', () => {
            window.location.reload()
        });


    </script>
    @endscript

</div>


