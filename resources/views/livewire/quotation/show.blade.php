<div class="max-w-3xl mx-auto mt-10">
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-blue-500 font-semibold">Cotización</p>
                <h1 class="text-2xl font-semibold text-gray-800 mt-1">
                    Detalle de cotización
                </h1>
                <p class="text-sm text-gray-500 mt-2">
                    Consulta número, cliente, sede, estado y descripción.
                </p>
            </div>
            <a href="{{ route('admin.quotations') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md">
                Volver
            </a>
        </div>

        <div class="px-6 py-6 space-y-5">
            <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Número</p>
                <p class="text-base font-medium text-gray-800 mt-1">{{ $quotation->number ?? '—' }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Cliente</p>
                    <p class="text-base font-medium text-gray-800 mt-1">{{ $quotation->client_name ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Sede</p>
                    <p class="text-base font-medium text-gray-800 mt-1">{{ $quotation->headquarter_name ?? '—' }}</p>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Estado de cotización</p>
                <p class="text-base font-medium text-gray-800 mt-1">{{ $quotation->quotation_status_name ?? '—' }}</p>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Descripción</p>
                <p class="text-base font-medium text-gray-800 mt-1 whitespace-pre-wrap">{{ $quotation->description ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>
