<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">Crear Equipo</h2>
            <h2 class="text-base font-semibold"> Cliente: Nombre del Cliente </h2>
            <h2 class="text-base font-semibold"> Sucursal: Nombre de la Sucursal  </h2>

        </div>
        <a href="{{ route('admin.clients-equipments', ['client' => $client->slug, 'headquarter' => $headquarter->slug]) }}" title="atras" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
            <svg class="h-8 w-8 text-blue-600 hover:text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" /></svg>
        </a>

    </div>
    <div class="container mx-auto px-4 py-6 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        @if(count($equipments_list) > 0)
            <div class="mb-6">
                <label for="equipment_select" class="block text-gray-700 font-bold mb-2">Equipos existentes:</label>
                <select  @if( $disabled ) disabled @endif id="equipment_select" wire:model.live="selected_equipment_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">Seleccionar un equipo</option>
                    @foreach($equipments_list as $equipment)
                        <option value="{{ $equipment->id }}" >
                            {{ $equipment->name }} -
                            Marca: {{ $equipment->brand_name }},
                            Modelo: {{ $equipment->model_name }},
                            Voltios: {{ $equipment->voltage }}{{ $equipment->amperage ? ', Amperios: ' . $equipment->amperage : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <form wire:submit.prevent="updateOrStore">
            <!-- block 1 -->
            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex">
                        <label for="equipment_class_id" class="block text-gray-700 font-bold mb-2" title="">Clase de equipo*:</label>
                    </div>
                    <select   @if( $disabled ) disabled @endif id="equipment_class_id" wire:model="equipment_class_id" name="equipment_class_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccionar</option>
                        @foreach($equipment_class_lists as $class)
                            <option wire:key="{{$class->id}}" value="{{$class->id}}">{{$class->name}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('equipment_class_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="h-4">
                    </div>

                </div>

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="md:flex md:items-center md:gap-4">
                        <div class="md:w-2/3 mb-4 md:mb-0">
                            <label for="name" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                            <input type="text" id="name" wire:model="name" name="name"  @if( $readonly )  readonly @endif  class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                            <div class="h-4">
                                @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="md:w-1/3 mb-4 md:mb-0">
                            <label for="quantity" class="block text-gray-700 font-bold mb-2">Cantidad:</label>
                            <input type="number" id="quantity" wire:model="quantity"  @if( $readonly )  readonly @endif  name="quantity" min="1" step="1" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                            <div class="h-4">
                                @error('quantity') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- block 1.5 - Campos de especificaciones -->
            <div class="md:flex md:items-center mb-4">
                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <x-input-with-select
                        :options="$brand_options"
                        model-key="brand"
                        label="Marca"
                        placeholder="Escribir o elegir abajo"
                        select-placeholder="Seleccionar marca"
                        :readonly="$readonly"
                        :disabled="$disabled"
                    />
                    <div class="h-4">
                        @error('brand') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <x-input-with-select
                        :options="$model_options"
                        model-key="model"
                        label="Modelo"
                        placeholder="Escribir o elegir abajo"
                        select-placeholder="Seleccionar modelo"
                        :readonly="$readonly"
                        :disabled="$disabled"
                    />
                    <div class="h-4">
                        @error('model') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <x-input-with-select
                        :options="$voltage_options"
                        model-key="voltage"
                        label="Voltios"
                        placeholder="Escribir o elegir abajo"
                        select-placeholder="Seleccionar voltios"
                        :initial-value="$voltage ?? ''"
                        :readonly="$readonly"
                        :disabled="$disabled"
                    />
                    <div class="h-4">
                        @error('voltage') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/4 pr-0 md:pr-4 mb-4 md:mb-0">
                    <x-input-with-select
                        :options="$amperage_options"
                        model-key="amperage"
                        label="Amperios"
                        placeholder="Escribir o elegir abajo"
                        select-placeholder="Seleccionar amperios"
                        :initial-value="$amperage ?? ''"
                        :readonly="$readonly"
                        :disabled="$disabled"
                    />
                    <div class="h-4">
                        @error('amperage') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <!-- block 2 -->
            <div class="md:flex md:items-center mb-4 items-center">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <x-input-with-select
                        :options="$location_options"
                        model-key="location"
                        label="Ubicación*"
                        placeholder="Escribir o elegir abajo"
                        select-placeholder="Seleccionar ubicación"
                    />
                    <div class="h-4">
                        @error('location') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  mr-2">
                    <label for="observations" class="block text-gray-700 font-bold mb-2">Observaciones:</label>
                    <textarea wire:model="observations" name="observations" class="resize-none focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"></textarea>
                    <div class="h-4">
                        @error('observations') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>


            </div>
            <!-- block 3 -->


            <div class="md:flex md:items-center mb-4 items-center">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex gap-2 items-center">
                        <label for="" class="block text-gray-700 font-bold mb-2" title="">Foto 1:</label>
                        @if($plate_photo_flag || $plate_photo)
                            <x-icons.check></x-icons.check>
                        @else
                            <x-icons.x-circle></x-icons.x-circle>
                        @endif
                    </div>
                    <div x-data="{ fileName: '', isChecked: @entangle('plate_photo_flag') }">
                        <label for="plate_file_input" class="block flex items-center cursor-pointer bg-blue-100 text-blue-700 px-4 py-2 rounded-md border border-blue-200 hover:bg-blue-200 hover:text-blue-800 transition duration-300">
                            <span>Cargar foto</span>
                            <span x-text="fileName ? fileName : ''" class="ml-2 truncate"></span>
                            <input id="plate_file_input" wire:model.live="plate_photo" name="plate_photo" type="file"
                                   class="hidden"
                                   x-on:change="
                                       if ($event.target.files.length > 0) {
                                           fileName = $event.target.files[0].name;
                                           isChecked = true;
                                       }
                                   "
                            >
                        </label>
                    </div>
                    <div class="mt-2">
                        <label for="photo1_title_photo_id" class="block text-gray-700 font-bold mb-2">Título de la foto:</label>
                        <select id="photo1_title_photo_id" wire:model="photo1_title_photo_id" name="photo1_title_photo_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Seleccionar</option>
                            @foreach($title_photo_options as $titlePhoto)
                                <option wire:key="photo1-{{$titlePhoto->id}}" value="{{$titlePhoto->id}}">{{$titlePhoto->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="h-4">
                    </div>
                </div>


                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <div class="flex gap-2 items-center">
                        <label for="" class="block text-gray-700 font-bold mb-2" title="">Foto 2:</label>
                        @if($perimeter_photo_flag || $perimeter_photo)
                            <x-icons.check></x-icons.check>
                        @else
                            <x-icons.x-circle></x-icons.x-circle>
                        @endif
                    </div>
                    <div x-data="{ fileName: '', isChecked: @entangle('perimeter_photo_flag') }">
                        <label for="perimeter_file_input" class="block flex items-center cursor-pointer bg-blue-100 text-blue-700 px-4 py-2 rounded-md border border-blue-200 hover:bg-blue-200 hover:text-blue-800 transition duration-300">
                            <span>Cargar foto</span>
                            <span x-text="fileName ? fileName : ''" class="ml-2 truncate"></span>
                            <input id="perimeter_file_input" wire:model.live="perimeter_photo" name="perimeter_photo" type="file"
                                   class="hidden"
                                   x-on:change="
                                       if ($event.target.files.length > 0) {
                                           fileName = $event.target.files[0].name;
                                           isChecked = true;
                                       }
                                   "
                            >
                        </label>
                    </div>
                    <div class="mt-2">
                        <label for="photo2_title_photo_id" class="block text-gray-700 font-bold mb-2">Título de la foto:</label>
                        <select id="photo2_title_photo_id" wire:model="photo2_title_photo_id" name="photo2_title_photo_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="">Seleccionar</option>
                            @foreach($title_photo_options as $titlePhoto)
                                <option wire:key="photo2-{{$titlePhoto->id}}" value="{{$titlePhoto->id}}">{{$titlePhoto->title}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="h-4">
                    </div>
                </div>
            </div>

            <!-- block rutina y frecuencia -->
            <div class="md:flex md:items-center mb-4">
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="preventive_routine_id" class="block text-gray-700 font-bold mb-2">Rutina preventiva:</label>
                    <select id="preventive_routine_id" wire:model="preventive_routine_id" name="preventive_routine_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccionar</option>
                        @foreach($preventive_routine_lists as $routine)
                            <option wire:key="routine-{{$routine->id}}" value="{{$routine->id}}">{{$routine->name}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">
                        @error('preventive_routine_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="custom_frequency" class="block text-gray-700 font-bold mb-2">Frecuencia personalizada:</label>
                    <input type="number" id="custom_frequency" wire:model="custom_frequency" name="custom_frequency" min="0" placeholder="Días" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                    <div class="h-4">
                        @error('custom_frequency') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
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
