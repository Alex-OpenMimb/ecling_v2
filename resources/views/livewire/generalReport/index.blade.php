<div>
    <div class="max-h-screen overflow-y-auto">
        <div class="bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10">
            <div class="mb-4 md:mb-0">
                <h2 class="text-lg font-bold">Informe</h2>
                <h2 class="text-base font-semibold">Cliente: {{$client_name}}</h2>
                <h2 class="text-base font-semibold">Sede: {{$headquarter_name}}</h2>
                <h2 class="text-base font-semibold">Order de servicio: {{$service_order_serial}}</h2>

            </div>

            <a href="{{ $client_id ? route('admin.service-order.client', ['clientId' => $client_id]) : route('admin.service-order') }}" title="editar" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
                <svg class="h-8 w-8 text-blue-600 hover:text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1"/></svg>
            </a>
        </div>
    </div>
    <div class="mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <livewire:general-report.datatable-general-report :service_order_id="$service_order_id" />
    </div>

</div>
