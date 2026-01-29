<div class="max-h-screen overflow-y-auto">

    <div class="bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10 dark:bg-gray-700">
        <div class="flex flex-col">
            <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">Cliente: {{ $client_name }}</h2>
            <h2 class="text-base font-semibold text-gray-800 dark:text-gray-200">Sede: {{ $headquarter_name }}</h2>
        </div>
        <a href="{{ route('admin.clients-equipments', ['client' => $client->slug, 'headquarter' => $headquarter->slug]) }}" title="atras" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition duration-300">
            <svg class="h-8 w-8 text-blue-600 hover:text-white" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z"/>
                <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" />
            </svg>
        </a>
    </div>

    <div class="container mx-auto px-4 py-6 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        @if(count($photo_paths) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @foreach($photo_paths as $index => $path)
                    <div class="border border-gray-300 rounded-lg shadow-sm overflow-hidden dark:border-gray-600">
                        <div class="p-3 bg-gray-50 dark:bg-gray-700">
                            <h3 class="font-semibold text-base text-gray-700 dark:text-gray-200 mb-2">Foto {{ $index + 1 }}</h3>
                            <div class="aspect-square w-full bg-gray-200 dark:bg-gray-600 rounded-md overflow-hidden">
                                <img
                                    src="{{ asset('storage/' . $path) }}"
                                    alt="Foto {{ $index + 1 }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                >
                            </div>
                            <button
                                wire:click="download_photo_by_path({{ json_encode($path) }})"
                                type="button"
                                class="mt-3 w-full bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300 text-sm font-medium"
                            >
                                Descargar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 px-4">
                <p class="text-base font-bold text-gray-600 dark:text-gray-400">Sin registro fotográfico</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">No hay fotos cargadas para este equipo.</p>
            </div>
        @endif
    </div>
</div>
