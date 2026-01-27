<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">Crear Equipo</h2>
            <h2 class="text-base font-semibold"> Cliente: Nombre del Cliente  </h2>
            <h2 class="text-base font-semibold"> Sucursal: Nombre de la Sucursal  </h2>

        </div>
        <a href="#"  title="atras" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
            <svg class="h-8 w-8 text-blue-600 hover:text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" /></svg>
        </a>

    </div>
    <div class="container mx-auto   px-4 py-6 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form wire:submit.prevent="updateOrStore">
            <!-- block 1 -->
            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex">
                        <label for="equipment_class_id" class="block text-gray-700 font-bold mb-2" title="">Clase de equipo*:</label>
                    </div>
                    <select id="equipment_class_id" wire:model="equipment_class_id" name="equipment_class_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccionar</option>
                        <option value="1">Clase 1</option>
                        <option value="2">Clase 2</option>
                    </select>
                    <div class="h-4">
                        @error('equipment_class_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="h-4">
                    </div>

                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                    <input type="text" id="name" wire:model="name" name="name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                    <div class="h-4">
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="md:w-1/3 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="serial" class="block text-gray-700 font-bold mb-2">Serial*:</label>
                    <input type="text" id="serial" name="serial" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                    </div>
                </div>

            </div>
            <!-- block 1.5 - Campos de especificaciones -->
            <div class="md:flex md:items-center mb-4">
                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="brand" class="block text-gray-700 font-bold mb-2">Marca:</label>
                    <input type="text" id="brand" wire:model="brand" name="brand" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                    <div class="h-4">
                        @error('brand') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="model" class="block text-gray-700 font-bold mb-2">Modelo:</label>
                    <input type="text" id="model" wire:model="model" name="model" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                    <div class="h-4">
                        @error('model') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="voltage" class="block text-gray-700 font-bold mb-2">Voltios:</label>
                    <input type="number" id="voltage" wire:model="voltage" name="voltage" step="0.01" min="0" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                    <div class="h-4">
                        @error('voltage') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="amperage" class="block text-gray-700 font-bold mb-2">Amperios:</label>
                    <input type="number" id="amperage" wire:model="amperage" name="amperage" step="0.01" min="0" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                    <div class="h-4">
                        @error('amperage') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <!-- block 2 -->
            <div class="md:flex md:items-center mb-4 items-center">


                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="location" class="block text-gray-700 font-bold mb-2" title="">Ubicación*:</label>
                    <input type="text" id="location" wire:model="location" name="location" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                    <div class="h-4">
                        @error('location') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  mr-2">
                    <label for="observations" class="block text-gray-700 font-bold mb-2">Observaciones:</label>
                    <textarea name="observations" class="resize-none focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"></textarea>
                    <div class="h-4">
                    </div>
                </div>


            </div>
            <!-- block 3 -->


            <div class="md:flex md:items-center mb-4 items-center">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex gap-2">
                        <label for="" class="block text-gray-700 font-bold mb-2" title="">Foto de la placa:</label>
                    </div>
                    <label for="plate_file_input" class="block flex items-center cursor-pointer bg-blue-100 text-blue-700 px-4 py-2 rounded-md border border-blue-200 hover:bg-blue-200 hover:text-blue-800 transition duration-300">
                        <span>Cargar foto</span>
                        <span id="plate_file_name" class="ml-2"></span>
                        <input id="plate_file_input" name="plate_photo" type="file"
                               class="hidden"
                        >
                    </label>
                    <div class="h-4">
                    </div>
                </div>


                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex gap-2">
                        <label for="" class="block text-gray-700 font-bold mb-2" title="">Foto Panorámica:</label>
                    </div>
                       <label for="perimeter_file_input" class="block flex items-center cursor-pointer bg-blue-100 text-blue-700 px-4 py-2 rounded-md border border-blue-200 hover:bg-blue-200 hover:text-blue-800 transition duration-300">
                        <span>Cargar foto</span>
                        <span id="perimeter_file_name" class="ml-2"></span>
                        <input id="perimeter_file_input" name="perimeter_photo" type="file"
                               class="hidden"
                        >
                    </label>

                    <div class="h-4">
                    </div>
                </div>
            </div>

            <div class="md:w-1/2 pr-0 md:pr-4 mt-6 md:mb-0 ">
                <div class="flex gap-2">
                    <label class="block text-gray-700 font-bold mb-2"> Asignar rutina </label>
                    <input type="checkbox">
                </div>

            </div>


            <div  class="flex flex-col text-gray-400 mt-2 mb-4">
                <span>* Campo obligatorio</span>
            </div>

            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Guardar
                    </button>
                    <div class="h-4"></div>
                </div>

            </div>


        </form>

    </div>

</div>
