<div>
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
    <table class="w-full relative">
        <thead class="border-b border-neutral-200 dark:border-neutral-700">
        <tr class="group">
            @foreach($heads as $index => $head)
                <th class="p-0  @if($head === 'Acciones') text-center @else text-left  @endif text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{$head}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>

        @foreach($headquarters AS $headquarter  )
            <tr class="group" wire:key="{{$headquarter->id}}">
                <x-table.row> {{$headquarter->id}} </x-table.row>
                <x-table.row> {{$headquarter->head_name}} </x-table.row>
                <x-table.row> {{$headquarter->email}} </x-table.row>
                <x-table.row> {{$headquarter->contact_name}} </x-table.row>
                <x-table.row > {{$headquarter->city_name}} </x-table.row>
                <x-table.row>
                    <label class="inline-flex items-center me-5 cursor-pointer">
                        <input wire:click="handle_main('{{$headquarter->slug}}')"  type="checkbox" value="" class="sr-only peer"  @if($headquarter->main) checked @endif  >
                        <div  class="relative w-10 h-5 bg-gray-300 rounded-full peer dark:bg-gray-700 peer-focus:ring-3 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600"></div>
                    </label>
                </x-table.row>
                <x-table.row  >
                    <div class="flex justify-around space-x-1">
                        <button wire:click="$dispatch('openModal', { component: 'headquarter.show-headquarter',arguments:{headquarter_id: {{$headquarter->id}} } })" class="p-1 text-orange-900 rounded hover:bg-orange-900 hover:text-white" title="ver registro">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                        </button>
                        <a  href="{{route('admin.edit.headquarters',['client'=> $client_slug, 'headquarter'=> $headquarter->slug ])}}"    class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                        </a>

                        <a href="{{route('admin.clients-equipments',['client'=> $client_slug, 'headquarter'=> $headquarter->slug])}}" class="p-1 text-red-500 rounded hover:bg-red-500 hover:text-white" title="ver registro">
                            <svg class="h-6 w-6 "  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />  <circle cx="12" cy="12" r="3" /></svg>
                        </a>

                    </div>
                </x-table.row>
            </tr>
        @endforeach


        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">
        <tr class="group">

        </tr>
        </tfoot>
    </table>
    {{ $headquarters->links() }}
    @if($headquarters->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif

    @script
    <script>
        $wire.on('clear_input', () => {
            document.getElementById('user_search').value = ''
        });

        $wire.on('reload', () => {
            window.location.reload()
        });

    </script>
    @endscript

</div>
