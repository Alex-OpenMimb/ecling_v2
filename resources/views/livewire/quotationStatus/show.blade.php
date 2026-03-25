<div class="max-w-3xl mx-auto mt-10">
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-blue-500 font-semibold">Detalle</p>
                <h1 class="text-2xl font-semibold text-gray-800 mt-1">
                    {{ $quotationStatus->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-2">
                    Información detallada del estado de cotización.
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.configurations.quotation-status.edit', $quotationStatus->id) }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md">
                    Editar
                </a>
                <a href="{{ route('admin.configurations.quotation-status.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md">
                    Volver
                </a>
            </div>
        </div>

        <div class="px-6 py-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Nombre</p>
                    <p class="text-base font-medium text-gray-800 mt-1">{{ $quotationStatus->name }}</p>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Estado</p>
                    <span class="inline-flex items-center mt-1 px-3 py-1 text-sm font-semibold rounded-full {{ $quotationStatus->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $quotationStatus->status ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                <p class="text-xs uppercase tracking-wide text-gray-500">Descripción</p>
                <p class="text-base font-medium text-gray-800 mt-1">
                    {{ $quotationStatus->description ?? '—' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Creado el</p>
                    <p class="text-base font-medium text-gray-800 mt-1">
                        {{ optional($quotationStatus->created_at)->format('d/m/Y H:i') }}
                    </p>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Actualizado el</p>
                    <p class="text-base font-medium text-gray-800 mt-1">
                        {{ optional($quotationStatus->updated_at)->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
