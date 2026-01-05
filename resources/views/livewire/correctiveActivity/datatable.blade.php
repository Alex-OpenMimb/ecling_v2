<div>
    <div  class="flex justify-between">
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
    <table class="w-full relative">
        <thead class="border-b border-neutral-200 dark:border-neutral-700">
        <tr class="group">
            @foreach($heads as $index => $column)
                <th class="p-0 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{$column}}
                </th>
            @endforeach

        </tr>
        </thead>
        <tbody>
        @if( !$activities->isEmpty())
            @foreach($activities as $activity  )
                <tr class="group"  wire:key="{{$activity->id}}">
                    <x-table.row> {{$counter}} </x-table.row>
                    <x-table.row> {{$activity->activity}} </x-table.row>
                    <x-table.row> @if($activity->description) <div class="truncate-13" title="{{$activity->description}}" >{{$activity->description}}</div> @else Sin descripción @endif </x-table.row>
                    <x-table.row>
                        <label class="inline-flex items-center me-5 cursor-pointer">
                            <input  wire:click="status('{{$activity->id}}')"  type="checkbox" value="" class="sr-only peer"  @if($activity->status) checked @endif  >
                            <div  class="relative w-10 h-5 bg-gray-300 rounded-full peer dark:bg-gray-700 peer-focus:ring-3 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600"></div>
                        </label>
                    </x-table.row>
                    <x-table.row>
                        <button  class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white" title="editar"
                                 onclick="Livewire.dispatch('openModal', {component:'corrective-activity.form-corrective', arguments:{corrective_activity:{{$activity->id}} }}  )"
                                 type="button">
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
    {{ $activities->links() }}
    @if( $activities->isEmpty() )
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>
    @endif


</div>
