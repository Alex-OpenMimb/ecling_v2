<div>
    <div class="bg-gray-100 rounded-md p-4 flex justify-between items-center">
        <h2 class="text-lg font-bold">Usuarios</h2>

        <div class="flex gap-2">
            <x-buttons.route route="admin.create.user" content="Crear"></x-buttons.route>
        </div>
    </div>
    <div class="mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <livewire:user.datatable-users/>
    </div>


</div>
