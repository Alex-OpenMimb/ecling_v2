<div>
    <div  class="flex justify-between">
        <x-forms.search property="query" method="search" id="quipemnt_search"></x-forms.search>
        <button wire:click="$dispatch('openModal', { component: 'store.volt.form-volt' })"  type="button" class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Crear</button>

    </div>
    <table class="w-full relative">
        <thead class="border-b border-neutral-200 dark:border-neutral-700">
        <tr class="group">
            @foreach($head as $index => $row)
                <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{$row}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if( !$volts->isEmpty() )
            @foreach($volts as $volt  )
                <tr class="group"  wire:key="{{$volt->id}}">
                    <x-table.row> {{$counter}} </x-table.row>
                    <x-table.row> {{$volt->volt_measurement}} </x-table.row>
                    <x-table.row> {{$volt->unit}} </x-table.row>
                    <x-table.row>
                        <label class="inline-flex items-center me-5 cursor-pointer">
                            <input  wire:click="status('{{$volt->id}}')"  type="checkbox" value="" class="sr-only peer"  @if($volt->status) checked @endif  >
                            <div  class="relative w-10 h-5 bg-gray-300 rounded-full peer dark:bg-gray-700 peer-focus:ring-3 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600"></div>
                        </label>
                    </x-table.row>
                    <x-table.row>
                        <button  class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white" title="editar" wire:click="$dispatch('openModal', { component: 'store.volt.form-volt',arguments:{volt: {{$volt->id}} }})"  type="button">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                        </button>
                    </x-table.row>
                </tr>
                @php $counter++ @endphp
            @endforeach
        @endif
        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">

        </tfoot>
    </table>
    {{ $volts->links() }}
    @if( $volts->isEmpty() )
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>
    @endif

</div>
