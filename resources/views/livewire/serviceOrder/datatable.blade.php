<div class="">

    <div class=" w-full flex gap-6">
        <div>
            <x-forms.search property="query" method="search" id="clients_search"></x-forms.search>
        </div>
        <div class="pr-0 md:pr-4 mb-4 md:mb-0">
            <input
                type="text"
                wire:model.live.debounce.300ms="filter_client"
                id="filter_client"
                name="filter_client"
                placeholder="Filtrar por cliente"
                class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div class="pr-0 md:pr-4 mb-4 md:mb-0">
            <input
                type="text"
                wire:model.live.debounce.300ms="filter_equipment"
                id="filter_equipment"
                name="filter_equipment"
                placeholder="Filtrar por equipo"
                class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
            @foreach($heads as $index => $head)
                <th class="px-1   @if($head === 'Acciones') text-center @else text-left @endif text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{$head}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if(!$orders->isEmpty())
            @foreach($orders as $order)
                <tr class="group" wire:key="{{ $order->id }}">
                    <x-table.row> {{$counter}} </x-table.row>
                    <x-table.row> {{$order->user_name}} </x-table.row>
                    <x-table.row> {{$order->serial}} </x-table.row>
                    <x-table.row>  <button onclick="Livewire.dispatch('openModal', { component: 'client-equipment.show-client-equipment',arguments:{client_equipment_id: {{$order->client_equipment_id}}  } })" > {{$order->equipments_name}} </button>  </x-table.row>
                    <x-table.row> <div class="truncate-13" title="{{$order->name}}"> {{$order->name}} </div> </x-table.row>

                    <x-table.row> {{$order->activity}} </x-table.row>
                    <x-table.row> <div class="truncate-20" > @if( $order->observations ) {{$order->observations}} @else Sin Observaciones  @endif </div> </x-table.row>

                    <x-table.row>
                            @if( $order->status === 'Rechazada' || $order->status === 'Declinada' )
                             <div  onclick="Livewire.dispatch('openModal', { component: 'service-order.form-reject',arguments:{service_order: {{$order->id}} } })" class="cursor-pointer text-white text-center bg-yellow-500">{{$order->status}} </div>
                            @else
                              <div
                                class=" text-white text-center
                                @if($order->status === 'Abierta')
                                  bg-red-500
                                @elseif( $order->status === 'Cerrada' )
                                   bg-blue-500
                                    @elseif( $order->status === 'Facturada' )
                                   bg-green-500
                                @endif
                                "
                                >
                                  {{$order->status}}
                              </div>

                             @endif
                    </x-table.row>
                    <x-table.row>
                        <div class="flex justify-center">

                            <button  onclick="Livewire.dispatch('openModal', { component: 'service-order.observations',arguments:{order: {{$order->id}} } })" class="p-1 text-orange-900 rounded hover:bg-orange-900 hover:text-white" title="Observaciones">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                            </button>
                            <button  onclick="Livewire.dispatch('openModal', { component: 'service-order.users',arguments:{order_id: {{$order->id}} } })" class="p-1 text-green-500 rounded hover:bg-green-500 hover:text-white" title="Usuarios">
                                <svg class="h-5 w-5 text-green-500 hover:text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />  <circle cx="9" cy="7" r="4" />  <path d="M23 21v-2a4 4 0 0 0-3-3.87" />  <path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                            </button>
                            <a  wire:click="redirect_general_report('{{$order->id}}')" class="p-1 text-blue-600 rounded hover:bg-blue-500 hover:text-white cursor-pointer">
                                <svg class="h-5 w-5 text-blue-500 hover:text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <polyline points="9 11 12 14 22 4" />  <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" /></svg>
                            </a>
                            <button     @if( $order->status === 'Cerrada' || $order->status === 'Rechazada' || $order->status === 'Declinada' )  wire:click="error_message_order('reject')" @else  onclick="Livewire.dispatch('openModal', { component: 'service-order.reject',arguments:{service_order: {{$order->id}} } })"  @endif   title="Rechazar"  class="text-red-500 rounded hover:bg-red-500 hover:text-white">
                                <svg class="h-5 w-5"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <circle cx="12" cy="12" r="10" />  <line x1="15" y1="9" x2="9" y2="15" />  <line x1="9" y1="9" x2="15" y2="15" /></svg>
                            </button>
                            @can( 'handel-status')
                                    <button
                                        @if( $order->status === 'Cerrada'  || $order->status === 'Facturada')
                                            onclick="Livewire.dispatch('openModal', { component: 'service-order.handle-state',arguments:{service_order: {{$order->id}} } })"
                                        @else
                                            wire:click="error_message_order('status')"
                                        @endif

                                        title="Cambiar estado"    class="p-1 text-green-500 rounded hover:bg-green-500 hover:text-white cursor-pointer">
                                        <svg class="h-5 w-5 text-green-500 hover:text-white"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </button>
                            @endcan
                        </div>
                    </x-table.row>
                    @php $counter++ @endphp
                </tr>
            @endforeach
        @endif
        </tbody>

        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">
        <tr class="group">

        </tr>
        </tfoot>
    </table>
    {{ $orders->links() }}

    @if($orders->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif
    @script
    <script>

    </script>
    @endscript


</div>
