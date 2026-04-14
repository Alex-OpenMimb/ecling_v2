<div class="max-h-screen overflow-y-auto">
    <div class="bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10">
        <div class="mb-4 md:mb-0">
            <h2 class="text-lg font-bold">Estados de cotización</h2>
        </div>

        <div class="flex gap-2">
            <x-buttons.route route="admin.configurations.quotation-status.create" content="Crear"></x-buttons.route>
        </div>
    </div>

    <div class="mx-2 px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <livewire:quotation-status.datatable/>
    </div>
</div>
