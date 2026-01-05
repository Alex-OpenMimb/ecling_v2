<div>

    <div class="flex gap-6">
        <x-forms.search property="query" method="search" id="clients_search"></x-forms.search>
        <div  wire:loading >  <x-loader></x-loader> </div>
    </div>
    <table class="w-full relative" x-data="">
        <thead class="border-b border-neutral-200 dark:border-neutral-700">
        <tr class="group">
            @foreach($heads as $index => $head)
                <th class="px-1 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{$head}}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @if(!$report_generals->isEmpty())
            @foreach($report_generals as $report)
                <tr class="group" wire:key="{{ $report->id }}">
                    <x-table.row> {{$counter}}</x-table.row>
                    <x-table.row> {{ $report->serial_report }} </x-table.row>
                    <x-table.row>
                       @if( $report->time_spent )
                            <button
                                onclick="Livewire.dispatch('openModal', { component: 'general-report.time-spent',arguments:{general_report: {{$report->id}}  } })"
                            > {{ $report->time_spent }}  </button>
                       @else
                           Sin registro
                       @endif
                    </x-table.row>
                    <x-table.row> @if($report->sent) {{$report->sent}} @else  <x-icons.x-circle>  </x-icons.x-circle> @endif  </x-table.row>

                    <x-table.row>
                        <div class="
                          @if($report->status === 'Cerrado' )
                                  bg-blue-500
                          @elseif( $report->status === 'Abierto' )

                                bg-red-500
                          @elseif($report->status === 'Cancelado')
                             bg-yellow-400
                          @endif
                         text-white text-center" >{{$report->status}}</div>
                    </x-table.row>
                    <x-table.row> @if($report->preventive) <x-icons.check></x-icons.check> @else  <x-icons.x-circle>  </x-icons.x-circle> @endif  </x-table.row>
                    <x-table.row> @if($report->corrective) <x-icons.check></x-icons.check> @else  <x-icons.x-circle>  </x-icons.x-circle> @endif  </x-table.row>

                    <x-table.row>
                        <button type="button"  class="cursor-pointer"
                                onclick="Livewire.dispatch('openModal', { component: 'client-equipment.show-client-equipment',arguments:{client_equipment_id: {{$report->equipment_id}}  } })"
                                > {{ $report->name_equipment }} </button>
                    </x-table.row>
                    <x-table.row>
                        <div class="flex">

                            <button    @if($report->status === 'Cancelado')    wire:click="error_msm_form('cancel')" @else  wire:click="redirect_form_report( {{$service_order_id}} , {{$report->id}})" @endif class="p-1 text-blue-600 rounded hover:bg-blue-500 hover:text-white cursor-pointer">
                                <svg class="h-5 w-5 text-blue-500 hover:text-white"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round">  <polyline points="9 11 12 14 22 4" />  <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" /></svg>
                            </button>

                            <a @if($report->status === 'Cerrado')  wire:click="redirect_document( {{$report->id}})" @else  wire:click="error_msm_form('document')"  @endif   class="p-1 text-green-500 rounded hover:bg-green-500 hover:text-white cursor-pointer">
                                <svg class="h-5 w-5 text-green-500 hover:text-white"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </a>

                            <button    wire:click="email_send_handle( {{$report->id}})" class="p-1 text-red-600 rounded hover:bg-red-500 hover:text-white cursor-pointer">
                                <svg class="h-5 w-5 text-red-500 hover:text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <rect x="3" y="5" width="18" height="14" rx="2" />  <polyline points="3 7 12 13 21 7" /></svg>
                            </button>
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
    {{ $report_generals->links() }}

    @if($report_generals->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>

    @endif

    @script
    <script>
        $wire.on('redirect_document', (event) => {
            let route = event;
            let url = "{{ route('admin.general-reports.document', ['general_report_id' => ':id']) }}";
            url = url.replace(':id', route);
            //window.location.href = url;
            window.open(url);
        });

    </script>
    @endscript


</div>
