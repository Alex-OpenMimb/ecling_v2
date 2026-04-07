<div>
    <div class="flex gap-6">
        <div class="w-full flex gap-6">
            <div>
                <x-forms.search property="query" method="search" id="visits_datatable_search"></x-forms.search>
            </div>
            <div class="pr-0 md:pr-4 mb-4 md:mb-0">
                <select wire:model.lazy="amount" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="10">10</option>
                    <option value="30">30</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div wire:loading>
                <x-loader></x-loader>
            </div>
        </div>
    </div>

    <table class="w-full relative" x-data="">
        <thead class="border-b border-neutral-200 dark:border-neutral-700">
        <tr class="group">
            @foreach($heads as $head)
                <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{ $head }}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @php($rowCounter = ($visits->currentPage() - 1) * $visits->perPage() + 1)
        @if(!$visits->isEmpty())
            @foreach($visits as $visit)
                <tr class="group" wire:key="visit-{{ $visit->id }}">
                    <x-table.row> {{ $rowCounter++ }} </x-table.row>
                    <x-table.row>
                        <div class="truncate-13" title="{{ $visit->client_name ?? '—' }}">{{ $visit->client_name ?? '—' }}</div>
                    </x-table.row>
                    <x-table.row>
                        <div class="truncate-13" title="{{ $visit->headquarter_name ?? '—' }}">{{ $visit->headquarter_name ?? '—' }}</div>
                    </x-table.row>
                    <x-table.row>
                        <div class="truncate-13" title="{{ $visit->visitReason?->name ?? '—' }}">{{ $visit->visitReason?->name ?? '—' }}</div>
                    </x-table.row>
                    <x-table.row>
                        <span>{{ $visit->event_date ? \Illuminate\Support\Carbon::parse($visit->event_date)->format('d/m/Y') : '—' }}</span>
                    </x-table.row>
                    <x-table.row>
                        <x-buttons.toggle status="{{ $visit->status }}" slug="{{ $visit->id }}"></x-buttons.toggle>
                    </x-table.row>
                    <x-table.row>
                        <div class="flex gap-4">
                            @if($visit->event_closed)
                                <span class="p-1 text-gray-300 cursor-not-allowed rounded"
                                      title="No se puede editar: el evento asociado está cerrado."
                                      aria-disabled="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ route('admin.visit.edit', $visit->id) }}"
                                   class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white" title="editar" type="button">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </x-table.row>
                </tr>
            @endforeach
        @endif
        </tbody>
        <tfoot class="border-t border-neutral-200 dark:border-neutral-700">
        <tr class="group"></tr>
        </tfoot>
    </table>

    {{ $visits->links() }}

    @if($visits->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>
    @endif

    @script
    <script>
        $wire.on('clear_input', () => {
            document.getElementById('visits_datatable_search').value = ''
        });
    </script>
    @endscript
</div>
