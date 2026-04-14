<div>
    <div class="max-h-screen overflow-y-auto pb-10">
        <div class="bg-gray-100 rounded-md p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-10 mx-2">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Visita #{{ $visit->id }}</h2>
                <div class="mt-2 space-y-0.5 text-sm text-gray-700">
                    <p>
                        <span class="font-semibold text-gray-600">Cliente:</span>
                        {{ $visit->client?->name ?? $visit->client_name ?? '—' }}
                    </p>
                    <p>
                        <span class="font-semibold text-gray-600">Sucursal:</span>
                        {{ $visit->headquarter?->name ?? $visit->headquarter_name ?? '—' }}
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <x-buttons.back route="admin.visit.index" content="Volver"></x-buttons.back>
            </div>
        </div>

        <div class="mx-2 mt-2 mb-10 px-4 py-6 sm:px-6 sm:py-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="space-y-10 max-w-4xl">
                <section>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Datos de la visita</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 dark:bg-gray-700/50 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Nombre del cliente</p>
                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">{{ $visit->client?->name ?? $visit->client_name ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 dark:bg-gray-700/50 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Sucursal</p>
                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">{{ $visit->headquarter?->name ?? $visit->headquarter_name ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 dark:bg-gray-700/50 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Razón de visita</p>
                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">{{ $visit->visitReason?->name ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 dark:bg-gray-700/50 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Fecha del evento</p>
                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">
                                {{ $visit->event?->date ? \Illuminate\Support\Carbon::parse($visit->event->date)->format('d/m/Y') : '—' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 dark:bg-gray-700/50 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado</p>
                            <span class="inline-flex items-center mt-1 px-3 py-1 text-sm font-semibold rounded-full {{ $visit->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $visit->status ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 sm:col-span-2 dark:bg-gray-700/50 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Observaciones</p>
                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1 whitespace-pre-wrap">{{ $visit->observations ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 sm:col-span-2 dark:bg-gray-700/50 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Reporte</p>
                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1 whitespace-pre-wrap">{{ $visit->report ?? '—' }}</p>
                        </div>
                    </div>
                </section>

                @if($visit->quotations->isNotEmpty())
                    <section>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Cotización(es)</h3>
                        <div class="space-y-4">
                            @foreach($visit->quotations as $quotation)
                                <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-gray-600">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 dark:bg-gray-700/80 dark:border-gray-600 space-y-1">
                                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-100 block">Cotización #{{ $quotation->number }}</span>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            <span class="font-medium text-gray-600 dark:text-gray-400">Cliente:</span>
                                            {{ $quotation->client?->name ?? $quotation->client_name ?? '—' }}
                                            <span class="text-gray-400 mx-2">·</span>
                                            <span class="font-medium text-gray-600 dark:text-gray-400">Sucursal:</span>
                                            {{ $quotation->headquarter?->name ?? $quotation->headquarter_name ?? '—' }}
                                        </p>
                                    </div>
                                    <div class="px-4 py-4 border-b border-gray-100 bg-white dark:bg-gray-800 dark:border-gray-600">
                                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Descripción de la cotización</p>
                                        <p class="text-base text-gray-800 dark:text-gray-100 leading-relaxed whitespace-pre-wrap min-h-[2.5rem]">{{ $quotation->description !== null && $quotation->description !== '' ? $quotation->description : '—' }}</p>
                                    </div>
                                    <div class="px-4 py-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Fecha</p>
                                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">
                                                {{ $quotation->date ? \Illuminate\Support\Carbon::parse($quotation->date)->format('d/m/Y') : '—' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Vencimiento</p>
                                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">
                                                {{ $quotation->expiration_date ? \Illuminate\Support\Carbon::parse($quotation->expiration_date)->format('d/m/Y') : '—' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado cotización</p>
                                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">
                                                {{ $quotation->quotation_status_name ?? $quotation->quotation_status?->name ?? '—' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado registro</p>
                                            <span class="inline-flex items-center mt-1 px-3 py-1 text-sm font-semibold rounded-full {{ $quotation->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $quotation->status ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Nombre del cliente</p>
                                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">{{ $quotation->client?->name ?? $quotation->client_name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Sucursal</p>
                                            <p class="text-base font-medium text-gray-800 dark:text-gray-100 mt-1">{{ $quotation->headquarter?->name ?? $quotation->headquarter_name ?? '—' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @else
                    <section class="bg-gray-50 border border-dashed border-gray-200 rounded-lg px-4 py-8 text-center dark:bg-gray-700/30 dark:border-gray-600">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Esta visita no tiene cotizaciones vinculadas.</p>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
