<div class="max-h-screen overflow-y-auto ">

    <div class=" my-5 flex justify-center ">
        <div class="max-w-lg w-full  bg-white p-8 rounded-lg shadow-md">
            <div class="mb-6 flex justify-center">
                <div class="h-32 w-32 rounded-full overflow-hidden border-4 border-gray-200">
                    <img
                        @if(! auth()->user()->url_image )
                            src="{{asset('image/profile/avatar.jpg')}}"
                        @else
                            src="{{$urlImage}}"
                        @endif
                        alt="Profile Picture" class="h-full w-full object-cover" />
                </div>
            </div>
            <div>
                <div class="mb-4">
                    <form x-data="{ fileName: '',isChecked: @entangle('photo_flag') }" wire:submit.prevent="update_photo('{{$slug}}')">
                        <label for="file_input" class="block flex items-center cursor-pointer bg-blue-100 text-blue-700 px-4 py-2 rounded-md border border-blue-200 hover:bg-blue-200 hover:text-blue-800 transition duration-300">
                            <span>Cargar foto</span>
                            <span x-text=" fileName ? fileName : '' " id="file_name" class="ml-2"></span>
                            <input id="file_input"   wire:model="image" name="image" type="file"
                                   class="hidden" x-on:change="fileName = $event.target.files[0].name"
                                   @change="isChecked = $event.target.files.length > 0"
                            >
                        </label>
                        <div class="flex mt-2" >
                            <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 ">
                                <button wire:loading.class="opacity-50"  @click="fileName='' " type="submit" class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Actualizar foto</button>
                            </div>
                            <div class="h-4">
                                @error('image') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Cargando...</span> </div></div>
                        <input x-model="isChecked" wire:model.live="photo_flag"  type="checkbox"  class="hidden" id="photo_flag_id">
                    </form>
                </div>
            </div>
            <div class="mb-4">
                <div class="flex justify-between mb-4">
                    <div style="width: 45%;">
                        <h2 class="block text-sm font-medium text-gray-700">Nombre:</h2>
                        <p class="truncate" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{$name}}</p>
                    </div>
                    <div style="width: 45%;">
                        <h2 class="block text-sm font-medium text-gray-700">Email:</h2>
                        <p title="nombre de email" class="truncate" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{$email}}</p>
                    </div>
                </div>

                <div class="flex justify-between mb-4">
                    <div style="width: 45%;">
                        <h2 class="block text-sm font-medium text-gray-700">Rol:</h2>
                        <p class="truncate" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{$role}}</p>
                    </div>
                    <div style="width: 45%;">
                        <h2 class="block text-sm font-medium text-gray-700">Teléfono:</h2>
                        <p class="truncate" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">{{$phone}}</p>
                    </div>
                </div>

                <div x-data="{ showform : false }">
                    <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6">
                        <button type="button" @click="showform = !showform" class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Actualizar datos</button>

                    </div>

                    <div x-show="showform">
                        <form  wire:submit.prevent="update_user('{{ $slug }}')">
                            <div class="md:flex flex-col  mb-4">
                                <div class=" pr-0 md:pr-4 mb-4 md:mb-0">
                                    <label for="name" class="block text-gray-700 font-bold mb-2">Nombre*:</label>
                                    <input  wire:model="name"  value="{{$name}}" type="text" id="" name="name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                                    <div class="h-4">
                                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class=" pr-0 md:pr-4 mb-4 md:mb-0">
                                    <label for="email" class="block text-gray-700 font-bold mb-2">Email*:</label>
                                    <input wire:model="email"  value="{{$email}}"  type="email" id="" name="email" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                                    <div class="h-4">
                                        @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class=" pr-0 md:pr-4 ">
                                    <label for="phone" class="block text-gray-700 font-bold mb-2">Teléfono:</label>
                                    <input wire:model="phone"  value="{{$phone}}" type="number" id="" name="phone" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                                    <div class="h-4">
                                        @error('phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <x-buttons.save content="Guardar"></x-buttons.save>
                        </form>

                        <form  wire:submit.prevent="update_password( '{{ $slug }}' )"  action="">

                            <div class="md:flex md:flex-col md:space-y-4">
                                <div x-data="{ showpassword: true }" class="max-w-full md:max-w-64">
                                    <label for="current_password" class="block text-gray-700 font-bold mb-2">Contraseña actual:</label>
                                    <input  name="passwordIncoming"   wire:model.live="passwordIncoming" value=""  :type="showpassword ? 'password' : 'text'" id="current_password" name="current_password" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">
                                    <div class="flex items-center">
                                        <input type="checkbox"    @click="showpassword = !showpassword" id="show_current_password">
                                        <label for="show_current_password" class="ml-2 text-gray-400 text-sm">Mostrar contraseña</label>
                                    </div>
                                </div>

                                <div x-data="{ showpassword: true }" class="max-w-full md:max-w-64">
                                    <label for="new_password" class="block text-gray-700 font-bold mb-2">Nueva Contraseña:</label>
                                    <input  wire:model.live ="newPassword"  value=""  name="newPassword"   :type="showpassword ? 'password' : 'text'" id="new_password" name="new_password" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2">

                                    <div class="flex items-center">
                                        <input  type="checkbox" @click="showpassword = !showpassword" id="show_new_password">
                                        <label for="show_new_password" class="ml-2 text-gray-400 text-sm">Mostrar contraseña</label>
                                    </div>

                                </div>
                            </div>

                            <div class="flex justify-between mt-4">
                                <x-buttons.save content="Confirmar"></x-buttons.save>
                                @error('newPassword') <span class="text-red-400 text-[14px]">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </div>
    @script
    <script>
        $wire.on('clear_password', () => {
            document.getElementById('current_password').value = ''
            document.getElementById('new_password').value = ''
        });
        $wire.on('reload', () => {
            window.location.reload()
        });
    </script>
    @endscript
</div>
