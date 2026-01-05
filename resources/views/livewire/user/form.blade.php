<div  class="max-h-screen overflow-y-auto">
    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <h2 class="text-lg font-bold">{{ $action == 'create'? 'Registro de ':'Editar'}}  usuario</h2>

        <x-buttons.back route="admin.users" ></x-buttons.back>
    </div>
    <div class="container mx-auto mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form wire:submit.prevent="updateOrStore('{{ $action }}')">
            <div class="md:flex md:items-center mb-4">
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Nombre*:</label>
                    <input  wire:model.lazy="name"   type="text" id="" name="name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                    <div class="h-4">
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email*:</label>
                    <input wire:model.lazy="email"  type="email" id="" name="email" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                    <div class="h-4">
                        @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 ">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Teléfono:</label>
                    <input wire:model.lazy="phone"  type="text" id="" name="phone" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                    <div class="h-4">
                        @error('phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="document" class="block text-gray-700 font-bold mb-2">Identificación:</label>
                    <input wire:model.lazy="document"  type="text" id="document" name="document" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('document') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="countries" class="block text-gray-700 font-bold mb-2">Rol*:</label>
                    <select wire:model.lazy="roleId" id="" name="roleId" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccionar rol</option>
                        @foreach( $roles_list as $value)
                            <option value="{{ $value->id }}">{{$value->name}}</option>
                        @endforeach

                    </select>
                    <div class="h-4">
                        @error('roleId') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($action == 'create')
                    <div x-data="{ showpassword: true }" class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-5 ">
                        <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña*:</label>
                        <input wire:model.lazy="password" :type=" showpassword ? 'password' : 'text'" id="" name="password" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">

                        <div class="mt-2">
                            <input type="checkbox" @click="showpassword = !showpassword" name="" id="">
                            <span class="text-gray-400 text-sm">Mostrr contraseña</span>
                        </div>
                        <div class="h-4">
                            @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

            </div>
            <div  class="flex flex-col text-gray-400 ">
                <span>* Campo obligatorio.</span>
                <span>El número de teléfono debe tener entre 7 y 10 dígitos.</span>
                <span class="@if($action !== 'create') hidden @endif">La contraseña debe contener al menos 8 caracteres, una letra mayúscula, una letra minúscula y un número</span>
            </div>
            <div class="md:flex md:items-center mb-4">

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0 mt-6">
                    <x-buttons.save content="Guardar"></x-buttons.save>
                    <div class="h-4"> <div wire:loading  > <span class="text-gray-400">Guardando...</span> </div></div>
                </div>
            </div>

        </form>

    </div>


</div>


