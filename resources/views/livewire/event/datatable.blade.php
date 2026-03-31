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
                Fecha*
            </th>
            <th class="px-2 text-left  text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Día*
            </th>
            <th class="px-2 text-left  text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Hora
            </th>
            <th class="px-2 text-left  text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Cliente*
            </th>
            <th title="editar frecuencia" class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800 cursor-pointer"

            >
                Orden*
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Servicio*
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Usuarios
            </th>
            <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Acciones
            </th>

        </tr>
        </thead>
        <tbody>
        @foreach($events AS $event  )
            <tr class="group"  wire:key="{{$event->id}}">
                <x-table.row> {{$counter}}</x-table.row>
                <x-table.row>{{$event->date}} </x-table.row>
                <x-table.row>{{$event->day}}  {{$event->user_id}} </x-table.row>
                <x-table.row>  {{$event->start_hour}} - {{$event->end_hour}} </x-table.row>
                <x-table.row> {{$event->client_name}} </x-table.row>
                <x-table.row> <div class="" style="width: 200px">  @if($event->serial)  {{$event->serial}} @else Sin orden @endif  </div>  </x-table.row>
                <x-table.row> {{$event->activity}} </x-table.row>
                <td   class="px-2 flex  bg-neutral-100 group-odd:bg-white group-hover:bg-neutral-200 hover:text-white">
                    <button type="button" wire:click="$dispatch('openModal', { component: 'event.users', arguments: { event_id: {{ $event->id }}, visit_id: @json($event->visit_id) } })" class="p-1 text-green-500 rounded hover:bg-green-500 hover:text-white" title="Usuarios (evento / visita)">
                        <svg class="h-5 w-5 text-green-500 hover:text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />  <circle cx="9" cy="7" r="4" />  <path d="M23 21v-2a4 4 0 0 0-3-3.87" />  <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                    </button>
                </td>
                <x-table.row>
                    <div class="flex ">
                        <a   @if( $event->service_order )  wire:click="error_message_event('delete')" @else  @click="$dispatch('open_modal_event',{id: {{ $event->id }} })"  @endif       class="p-1 text-blue-600 rounded hover:bg-red-600 hover:text-white cursor-pointer" title="Eliminar"  @click="$dispatch('open_modal_corrective')">
                            <svg class="h-5 w-5 text-red-500 hover:text-white"  width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="4" y1="7" x2="20" y2="7" />  <line x1="10" y1="11" x2="10" y2="17" />  <line x1="14" y1="11" x2="14" y2="17" />  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                        </a>
                        <a  onclick="Livewire.dispatch('openModal', { component: 'event.editEvent',arguments:{event:{{$event->id}} } })" title="editar" class="cursor-pointer  p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                        </a>
                        @if( $event->activity != 'Visita'  )
                            <a @if( !$event->service_order ) wire:click="create_order_by_service('{{$event->id}}')"  @else href="{{route('admin.service-order')}}"  @endif class="p-1 text-blue-600 rounded hover:bg-blue-500 hover:text-white cursor-pointer">
                                <svg class="h-5 w-5 text-blue-500 hover:text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <polyline points="9 11 12 14 22 4" />  <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" /></svg>
                            </a>

                        @else
                            @if(!empty($event->visit_id))
                                <a href="{{ route('admin.visit.manage', ['visit' => $event->visit_id]) }}" class="p-1 text-blue-600 rounded hover:bg-blue-500 hover:text-white cursor-pointer" title="Gestionar visita">
                                    <svg class="h-5 w-5 text-blue-500 hover:text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4" /><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" /></svg>
                                </a>
                            @endif
                        @endif

                    </div>
                </x-table.row>

            </tr>
            @php $counter++ @endphp
        @endforeach
        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">

        </tfoot>
    </table>
    {{ $events->links() }}
    @if($events->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @script
    <script>
        $wire.on('open_modal_event', (id) => {

            Swal.fire({
                title: "¿Estás seguro de que deseas continuar?",
                text: "Si eliminas este evento, no podrás recuperarlo.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete_event',{event:id})
                }
            });
        });
    </script>
    @endscript

</div>
