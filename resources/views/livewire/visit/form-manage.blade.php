<div class="max-h-screen overflow-y-auto">
    <div class="container mx-auto bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">Gestión de visita</h2>
            @if($visit)
                <span class="text-sm text-gray-600">Visita #{{ $visit->id }}</span>
            @endif
        </div>
        <a href="{{ route('admin.planner') }}" title="atrás" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
            <svg class="h-8 w-8 text-blue-600 hover:text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z"/>
                <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" />
            </svg>
        </a>
    </div>

    <div class="container mx-auto px-4 py-6 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        @if(!$visit)
            <p class="text-gray-600 dark:text-gray-300">No hay una visita seleccionada.</p>
        @else
            <div class="mb-6">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Razón de visita</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $visit->visitReason?->name ?? '—' }}
                </p>
            </div>

            <div class="mb-4">
                <label for="report" class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Reporte</label>
                <textarea id="report" wire:model="report" name="report" rows="4" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Reporte de la visita…"></textarea>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="generate_quotation" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                    <span class="ml-2 text-gray-700 dark:text-gray-200 font-medium">Datos para generar una cotización</span>
                </label>
            </div>

            @if($generate_quotation)
                <div class="mb-6 p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900/40 space-y-4">
                    <div>
                        <label for="quotation_expiration_date" class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Fecha de expiración</label>
                        <input type="date" id="quotation_expiration_date" wire:model="quotation_expiration_date" name="quotation_expiration_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div>
                        <label for="quotation_description" class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Descripción</label>
                        <textarea id="quotation_description" wire:model="quotation_description" name="quotation_description" rows="4" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Descripción para la cotización…"></textarea>
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label for="client_id" class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Cliente</label>
                <select id="client_id" wire:model.live="client_id" name="client_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccionar cliente</option>
                    @foreach($clients_list as $client)
                        <option wire:key="client-{{ $client->id }}" value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="headquarter_id" class="block text-gray-700 dark:text-gray-200 font-bold mb-2">Sucursal</label>
                <select id="headquarter_id" wire:model="headquarter_id" name="headquarter_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500" @if(!$client_id) disabled @endif>
                    <option value="">Seleccionar sucursal</option>
                    @foreach($headquarters_list as $headquarter)
                        <option wire:key="headquarter-{{ $headquarter->id }}" value="{{ $headquarter->id }}">{{ $headquarter->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
</div>
