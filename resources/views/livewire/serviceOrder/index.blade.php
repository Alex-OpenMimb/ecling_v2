<div class="max-h-screen overflow-y-auto">
        <div class="bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10">
            <div class="mb-4 md:mb-0">
                <h2 class="text-lg font-bold">Ordenes de Servicio</h2>
            </div>
        </div>
    <div class="flex flex-col md:flex-row gap-2 w-1/2 md:w-auto md:justify-end">
        <div class="pr-0 md:pr-4 mb-4 md:mb-0 mt-8">
            @can('handle-limited-time-report')
            <button     onclick="Livewire.dispatch('openModal', { component: 'limited-time-report.form-limited-time-report' })"  class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">
                Tiempo Limite
            </button>
             @endcan
        </div>
    </div>
    <div class="mx-2 mb-[100px] px-4 py-3  overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <livewire:service-order.datatable-service-order/>
    </div>

</div>
