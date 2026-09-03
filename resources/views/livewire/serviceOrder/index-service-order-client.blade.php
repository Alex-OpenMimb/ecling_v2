<div class="max-h-screen overflow-y-auto">
    <div class="bg-gray-100 rounded-md p-4 flex flex-col gap-4 mt-10">
        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
            <div>
                <h2 class="text-lg font-bold">Órdenes de servicio — {{ $client->name }}</h2>
                <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <div class="flex gap-2">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Teléfono</dt>
                        <dd>{{ $phone ?? '—' }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">NIT</dt>
                        <dd>{{ $client->nit ?? '—' }}</dd>
                    </div>
                    <div class="flex gap-2 sm:col-span-2">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Correo</dt>
                        <dd class="break-all">{{ $email ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <x-buttons.back route="admin.service-order" ></x-buttons.back>

        </div>
    </div>

    <div class="mx-2 mb-[100px] px-4 py-3 mt-6 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <livewire:service-order.datatable-service-order
            :client-id="$client->id"
            wire:key="datatable-service-order-client-{{ $client->id }}"
        />
    </div>
</div>

