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
                <th class="p-0 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800 mx-2">
                    {{$head}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($pending_activities AS $pending  )
            <tr class="group" wire:key="{{$pending->id}}">
                <x-table.row> {{$counter}} </x-table.row>
                <x-table.row> <span class="font-9-rem">{{$pending->serial}}</span> </x-table.row>
                <x-table.row> <span  class="font-9-rem">{{$pending->serial_report}}</span> </x-table.row>
                <x-table.row> {{$pending->date}} </x-table.row>
                <x-table.row> <p class="text-justify font-9-rem" style="width: 200px" >{{$pending->pending_note}}</p> </x-table.row>
                <x-table.row > <p class="text-justify  font-9-rem" style="width: 200px" >{{$pending->management_observations}}</p> </x-table.row>
                <x-table.row>
                  <div
                 class="
                 text-white
                 text-center
                     @if( $pending->status === 'Abierto' )
                        bg-red-500

                    @elseif( $pending->status === 'Cerrado' )
                       bg-blue-500

                     @elseif( $pending->status === 'Rechazado' )
                     bg-yellow-500
                    @endif
                 "
                  > {{$pending->status}} </div>
                </x-table.row>
                <x-table.row  >
                    <div class="flex justify-around space-x-1">
                        <button
                            onclick="Livewire.dispatch('openModal', { component: 'pending.manage-pending',arguments:{pendingActivity: {{$pending->id}} } })"
                            title="Gestionar" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                        </button>
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
    {{ $pending_activities->links() }}
    @if($pending_activities->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif


</div>
