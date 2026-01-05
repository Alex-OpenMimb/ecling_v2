<div>

    <div class="flex gap-6">
        <div class="w-full flex gap-6">
            <div>
                <x-forms.search property="query" method="search" id="equipment_classes_search"></x-forms.search>
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
            @foreach($heads as $index => $head)
                <th class="px-2 text-left text-black bg-neutral-50 dark:text-white dark:bg-neutral-800">
                    {{ $head }}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @php($rowCounter = ($equipmentClasses->currentPage() - 1) * $equipmentClasses->perPage() + 1)
        @if(!$equipmentClasses->isEmpty())
            @foreach($equipmentClasses as $equipmentClass)
                <tr class="group" wire:key="equipment-class-{{ $equipmentClass->id }}">
                    <x-table.row> {{ $rowCounter++ }} </x-table.row>
                    <x-table.row> <div class="truncate-13" title="{{ $equipmentClass->name }}"> {{ $equipmentClass->name }} </div> </x-table.row>
                    <x-table.row>
                        @if($equipmentClass->equipments_count > 0)
                            <label class="inline-flex items-center me-5 cursor-not-allowed opacity-50">
                                <input type="checkbox" value="" class="sr-only peer" @if($equipmentClass->status) checked @endif disabled>
                                <div class="relative w-10 h-5 bg-gray-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600" title="No se puede cambiar el estado: la clase de equipo está siendo utilizada por uno o más equipos"></div>
                            </label>
                        @else
                            <x-buttons.toggle status="{{ $equipmentClass->status}}" slug="{{$equipmentClass->slug}}" ></x-buttons.toggle>
                        @endif
                    </x-table.row>

                    <x-table.row>
                        <div class="flex gap-4">
                            <a  href="{{ route('admin.configurations.equipment-class.show', $equipmentClass->slug) }}"  class="p-1 text-orange-900 rounded hover:bg-orange-900 hover:text-white" title="ver registro">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                            </a>

                            @if($equipmentClass->equipments_count > 0)
                                <button 
                                    class="p-1 text-blue-600 rounded opacity-50 cursor-not-allowed" 
                                    title="No se puede editar: la clase de equipo está siendo utilizada por uno o más equipos" 
                                    type="button"
                                    disabled>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </button>
                            @else
                                <a  href="{{ route('admin.configurations.equipment-class.edit', $equipmentClass->slug) }}"  class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white" title="editar"  type="button">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                </a>
                            @endif

                            @if($equipmentClass->equipments_count > 0)
                                <button
                                    type="button"
                                    disabled
                                    class="p-1 text-red-600 rounded opacity-50 cursor-not-allowed cursor-pointer"
                                    title="No se puede eliminar: la clase de equipo está siendo utilizada por uno o más equipos">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            @else
                                <button
                                    @click="$dispatch('open_modal_equipment_class',{id: {{$equipmentClass->id}} })"
                                    type="button"
                                    class="p-1 text-red-600 rounded hover:bg-red-600 hover:text-white cursor-pointer"
                                    title="eliminar">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
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
    {{ $equipmentClasses->links() }}

    @if($equipmentClasses->isEmpty())
        <div class="flex justify-center items-center h-48">
            <h3 class="text-gray-400 text-2xl">Sin resultados!</h3>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-confirm {
            background-color: #3085d6 !important;
            color: white !important;
        }
        .swal2-cancel {
            background-color: #d33 !important;
            color: white !important;
        }
    </style>
    @script
    <script>
        $wire.on('clear_input', () => {
            document.getElementById('equipment_classes_search').value = ''
        });

        $wire.on('open_modal_equipment_class', (id) => {
            Swal.fire({
                title: "¿Estás seguro de que deseas continuar?",
                text: "Si eliminas esta clase de equipo, no podrás recuperarla.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!",
                cancelButtonText: "Cancelar",
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete_equipment_class',{equipment_class_id:id.id})
                }
            });
        });
    </script>
    @endscript

</div>

