
<div>
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
        @if( !$preventive_routines->isEmpty())
            @foreach($preventive_routines as $routine  )
                <tr class="group"  wire:key="{{$routine->id}}">
                    <x-table.row> {{$counter}} </x-table.row>
                    <x-table.row> {{$routine->name}} </x-table.row>
                    <x-table.row> {{$routine->equipment_class}} </x-table.row>
                    <x-table.row>  {{$routine->frequency}}  </x-table.row>
                    <x-table.row>
                        <div class="flex gap-4">
                            <button onclick="Livewire.dispatch('openModal', { component: 'preventive-routine.show-preventive-routine',arguments:{ preventive_routine: {{$routine->id}} } })" class="p-1 text-orange-900 rounded hover:bg-orange-900 hover:text-white" title="ver registro">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                            </button>
                            <a  href="{{route('admin.preventive-routine.edit',['preventive_routine'=>$routine->id])}}"  class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white" title="editar"  type="button">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                            </a>
                        </div>
                    </x-table.row>
                </tr>
                @php $counter++ @endphp
            @endforeach

        @endif
        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">

        </tfoot>
    </table>
    {{ $preventive_routines->links() }}
    @if( $preventive_routines->isEmpty() )
        <div class="flex justify-center items-center h-40">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>
    @endif


</div>
