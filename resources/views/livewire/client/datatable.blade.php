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
        @if(!$clients->isEmpty())
            @foreach($clients as $client)
                <tr class="group" wire:key="{{ $client->id }}">
                    <x-table.row> {{$client->id}} </x-table.row>
                    <x-table.row> <div class="truncate-13" title="{{$client->name}}"> {{$client->name}} </div> </x-table.row>
                    <x-table.row> {{$client->nit}} </x-table.row>
                    <x-table.row> {{$client->contact_name}} </x-table.row>
                    <x-table.row> {{$client->phone_1}} </x-table.row>
                    <x-table.row> {{$client->email}} </x-table.row>
                    <x-table.row>
                        <x-buttons.toggle status="{{ $client->status}}" slug="{{$client->slug}}" ></x-buttons.toggle>
                    </x-table.row>
                    <x-table.row>
                        <div class="flex justify-around space-x-1">
                            <button wire:click="$dispatch('openModal', { component: 'client.show-client',arguments:{client_id: {{$client->id}} } })" class="p-1 text-orange-900 rounded hover:bg-orange-900 hover:text-white" title="ver registro">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                            </button>
                            <x-buttons.edit route="admin.edit.client" entity="client" slug="{{$client->slug}}"></x-buttons.edit>
                            <a href="{{route( 'admin.headquarters',[ 'client' => $client->slug])}}" class="p-1 text-blue-600 rounded hover:bg-green-600 hover:text-white" title="Sedes">
                                <svg class="h-6 w-5  text-green-500 hover:text-white"  width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="3" y1="21" x2="21" y2="21" />  <line x1="9" y1="8" x2="10" y2="8" />  <line x1="9" y1="12" x2="10" y2="12" />  <line x1="9" y1="16" x2="10" y2="16" />  <line x1="14" y1="8" x2="15" y2="8" />  <line x1="14" y1="12" x2="15" y2="12" />  <line x1="14" y1="16" x2="15" y2="16" />  <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16" /></svg>
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
    {{ $clients->links() }}

    @if($clients->isEmpty())
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
