<div class="max-h-screen overflow-y-auto">
    <div class="bg-gray-100 rounded-md p-4 flex flex-col md:flex-row justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">Gestión de Mantenimientos Correctivos</h2>
        </div>

        <div class="flex  flex-col md:flex-row gap-2 w-1/2 md:w-auto md:justify-end">
            <div class="pr-0 md:pr-4 mb-4 md:mb-0  flex gap-2 mt-8">
                <div>
                    <a onclick="Livewire.dispatch('openModal', { component: 'client-equipment-corrective.existing-order'})" type="button" class="cursor-pointer  bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Reporte Existente</a>
                </div>

                <div>
                    <button
                        @click="$dispatch('validate_corrective_service_order')"
                        type="button" class="cursor-pointer  bg-white border border-blue-500 text-blue-500
                    px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Orden</button>
                </div>

                    <div>
                        <button @click="$dispatch('validate_corrective')"  type="button" class="cursor-pointer bg-white border border-blue-500
                        text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white
                        transition duration-300">Agendar</button>
                    </div>

                <div>
                    <a href="{{route('admin.corrective-management.create')}}"  type="button" class="bg-white border border-blue-500 text-blue-500 px-4 py-2
                     rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Crear</a>
                </div>
            </div>
        </div>

    </div>
    <div class="mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <livewire:client-equipment-corrective.datatable-equipment-corrective  />
    </div>

</div>
