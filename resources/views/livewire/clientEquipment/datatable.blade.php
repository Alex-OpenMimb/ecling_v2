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
                <th class="p-0 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{$head}}
                </th>
            @endforeach
            <th class="p-0 text-center text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                Acciones
            </th>
        </tr>
        </thead>
        <tbody>

        @foreach($equipments AS $equipment  )
            <tr class="group" wire:key="{{$equipment->id}}">
                <x-table.row> {{$counter}} </x-table.row>
                <x-table.row> {{$equipment->equipment_name}} </x-table.row>
                <x-table.row> {{$equipment->internal_id}} </x-table.row>
                <x-table.row> <div title="{{$equipment->equipment_model}}" class="truncate-13" >{{$equipment->equipment_model}}</div> </x-table.row>
                <x-table.row> <div class="truncate-13" title="{{$equipment->brand_name}}" >{{$equipment->brand_name}}</div> </x-table.row>
                <x-table.row > <div class="truncate-13" title="{{$equipment->equipment_class_name}}" >{{$equipment->equipment_class_name}}</div> </x-table.row>
                <x-table.row>
                    <label class="inline-flex items-center me-5 cursor-pointer">
                        <input  wire:click="status('{{$equipment->id}}')"  type="checkbox" value="" class="sr-only peer"  @if($equipment->status) checked @endif  >
                        <div  class="relative w-10 h-5 bg-gray-300 rounded-full peer dark:bg-gray-700 peer-focus:ring-3 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600"></div>
                    </label>
                </x-table.row>
                <x-table.row>
                    @if($equipment->preventive_services)
                        <x-icons.check></x-icons.check>
                    @else
                        <x-icons.x-circle>  </x-icons.x-circle>
                    @endif
                </x-table.row>
                <x-table.row  >
                    <div class="flex justify-around space-x-1">
                        <button  onclick="Livewire.dispatch('openModal', { component: 'client-equipment.show-client-equipment',arguments:{client_equipment_id: {{$equipment->id}}  } })" class="p-1 text-orange-900 rounded hover:bg-orange-900 hover:text-white" title="ver registro">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                        </button>
                        <a  href="{{route('admin.clients-equipments.edit',['client'=>$client->slug, 'headquarter'=> $headquarter->slug,'client_equipment' => $equipment->id  ])}}"   title="editar" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                        </a>
                        <a   href="{{route('admin.clients-equipments.photo',['client'=>$client->slug, 'headquarter'=> $headquarter->slug,'client_equipment' => $equipment->id  ])}}"   title="Foto"  class="p-1 text-red-500 rounded hover:bg-red-500 hover:text-white">
                            <svg class="h-6 w-6"  width="24" height="24" viewBox="0 0 20 20" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="15" y1="8" x2="15.01" y2="8" />  <rect x="4" y="4" width="16" height="16" rx="3" />  <path d="M4 15l4 -4a3 5 0 0 1 3 0l 5 5" />  <path d="M14 14l1 -1a3 5 0 0 1 3 0l 2 2" /></svg>
                        </a>
                    </div>
                </x-table.row>
            </tr>
            @php $counter++ @endphp
        @endforeach

        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">
        <tr class="group">

        </tr>
        </tfoot>
    </table>
    {{ $equipments->links() }}
    @if($equipments->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif
    @script
    <script>


    </script>
    @endscript

</div>
