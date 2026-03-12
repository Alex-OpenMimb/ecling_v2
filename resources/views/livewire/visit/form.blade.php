<div class="max-h-screen overflow-y-auto">
    <div class="container mx-auto bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">Programar visita</h2>
        </div>
        <a href="{{ route('admin.visit.index') }}" title="atrás" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
            <svg class="h-8 w-8 text-blue-600 hover:text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z"/>
                <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" />
            </svg>
        </a>
    </div>

    <div class="container mx-auto px-4 py-6 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form wire:submit.prevent="store">
            {{-- Cliente --}}
            <div class="mb-4">
                <label for="client_id" class="block text-gray-700 font-bold mb-2">Cliente:</label>
                <select id="client_id" wire:model.live="client_id" name="client_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccionar cliente</option>
                    @foreach($clients_list as $client)
                        <option wire:key="client-{{ $client->id }}" value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
                <div class="h-4">
                    @error('client_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Sucursal (según cliente) --}}
            <div class="mb-4">
                <label for="headquarter_id" class="block text-gray-700 font-bold mb-2">Sucursal:</label>
                <select id="headquarter_id" wire:model="headquarter_id" name="headquarter_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500" @if(!$client_id) disabled @endif>
                    <option value="">Seleccionar sucursal</option>
                    @foreach($headquarters_list as $headquarter)
                        <option wire:key="headquarter-{{ $headquarter->id }}" value="{{ $headquarter->id }}">{{ $headquarter->name }}</option>
                    @endforeach
                </select>
                <div class="h-4">
                    @error('headquarter_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Fecha --}}
            <div class="mb-4">
                <label for="date" class="block text-gray-700 font-bold mb-2">Fecha:</label>
                <input type="date" id="date" wire:model="date" name="date" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <div class="h-4">
                    @error('date') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Observaciones --}}
            <div class="mb-4">
                <label for="observations" class="block text-gray-700 font-bold mb-2">Observaciones:</label>
                <textarea id="observations" wire:model="observations" name="observations" rows="4" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Observaciones de la visita..."></textarea>
                <div class="h-4">
                    @error('observations') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Hora inicial y final --}}
            <div class="md:flex md:gap-4 mb-4">
                <div class="md:w-1/2 mb-4 md:mb-0">
                    <label for="start_time" class="block text-gray-700 font-bold mb-2">Hora inicial:</label>
                    <input type="time" id="start_time" wire:model="start_time" name="start_time" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <div class="h-4">
                        @error('start_time') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="md:w-1/2 mb-4 md:mb-0">
                    <label for="end_time" class="block text-gray-700 font-bold mb-2">Hora final:</label>
                    <input type="time" id="end_time" wire:model="end_time" name="end_time" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <div class="h-4">
                        @error('end_time') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>


            <div class="md:flex md:items-center mb-4 ">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6">
                    <x-buttons.save content="Guardar"></x-buttons.save>
                    <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                    <div class="mt-2">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-400 text-[0.9rem]">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>


            </div>
        </form>
    </div>
</div>


