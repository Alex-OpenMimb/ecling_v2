<div>

    <div class="flex gap-6">
        <div class="w-full  flex gap-6">
            <div  class="">
                <x-forms.search property="query" method="search" id="client_search"></x-forms.search>
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
    </div>
    <table class="w-full relative" x-data="">
        <thead class="border-b border-neutral-200 dark:border-neutral-700">
        <tr class="group">
            @foreach($heads as $index => $head)
                <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{$head}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if(!$orders->isEmpty())
            @foreach($orders as $order)
                <tr class="group" wire:key="{{ $order->id }}">
                    <x-table.row> {{$order->id}} </x-table.row>
                    <x-table.row> <div class="truncate-13" title="{{$order->name}}"> {{$order->name}} </div> </x-table.row>
                    <x-table.row> {{$order->nit}} </x-table.row>
                    <x-table.row> {{$order->contact_name}} </x-table.row>
                    <x-table.row> {{$order->phone_1}} </x-table.row>
                    <x-table.row> {{$order->email}} </x-table.row>
                    <x-table.row>
                        <div class="flex justify-around space-x-1">
                            <a href="{{ route('admin.service-order.client', ['clientId' => $order->id]) }}"
                               class="p-1 text-blue-600 rounded hover:bg-blue-500 hover:text-white cursor-pointer"
                               title="Ver órdenes del cliente">
                                <svg class="h-5 w-5 text-blue-500 hover:text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <polyline points="9 11 12 14 22 4" />  <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" /></svg>
                            </a>

                        </div>
                    </x-table.row>

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
        $wire.on('clear_input', () => {
            document.getElementById('clients_search').value = ''
        });

    </script>
    @endscript


</div>
