<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">{{$action ? 'Crear':'Editar'}} Sucursal  </h2>
            <h2>Cliente: {{$name_client}}</h2>
            @if(!$action) <h2>Sucursal: {{$headquarter_name}}</h2>  @endif

        </div>
        <a href="{{route('admin.headquarters',['client'=> $client_slug])}}"  title="atras" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
            <svg class="h-8 w-8 text-blue-600 hover:text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" /></svg>
        </a>
    </div>
    <div class="container mx-auto mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form wire:submit.prevent="updateOrStore('{{$action}}')"  >
            <!-- block 1 -->
            <div class="md:flex md:items-center mb-4">
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="nit" class="block text-gray-700 font-bold mb-2">Nombre*:</label>
                    <input wire:model.lazy="name" @if(!$status) readonly @endif type="text" id="name" name="name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="phone_1" class="block text-gray-700 font-bold mb-2">Teléfono 1*:</label>
                    <input  type="text" wire:model.lazy="phone_1" @if(!$status) readonly @endif id="phone_1" name="phone_1" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('phone_1') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="phone_2" class="block text-gray-700 font-bold mb-2">Teléfono 2:</label>
                    <input  type="text" wire:model.lazy="phone_2" @if(!$status) readonly @endif id="phone_2" name="phone_2" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('phone_2') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  ">
                    <label for="contact" class="block text-gray-700 font-bold mb-2">Contacto*:</label>
                    <input   id="contact_name" wire:model.lazy="contact_name"  @if(!$status) readonly @endif name="contact_name" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                    <div class="h-4">
                        @error('contact_name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <!-- block 2 -->
            <div class="md:w-1/2 pr-0 md:pr-4  md:mb-0 mb-2">
                <h2 class="text-gray-700 font-bold text-[1.3rem]">Dirección</h2>
            </div>
            <div class="md:flex md:items-center mb-4 items-center">
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2" title="Nomenclatura principal">Nom. Principal:</label>
                    <select id="nomenclature_main" wire:model.lazy="nomenclature_main"  name="nomenclature_main" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccionar</option>
                        <option  @if(!$action) @if($nomenclature_main == 'Calle')selected @endif @endif  value="Calle">Calle</option>
                        <option @if(!$action) @if($nomenclature_main == 'Carrera')selected @endif @endif  value="Carrera">Carrera</option>
                        <option @if(!$action) @if($nomenclature_main == 'Transversal')selected @endif @endif value="Transversal">Transversal</option>
                        <option @if(!$action) @if($nomenclature_main == 'Avenida')selected @endif @endif  value="Avenida">Avenida</option>
                    </select>
                </div>

                <div  class="md:w-1/2 pr-0 md:pr-4 mb-4  md:mb-0  ">
                    <label for="number_main" class="block text-gray-700 font-bold mb-2">Número:</label>
                    <input   wire:model.lazy="number_main"  @if(!$status) readonly @endif id="number_main" name="number_main" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                </div>

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold  mb-2" title="Nomenclatura secundaria" >Nom. Secudaria:</label>
                    <select  id="nomenclature_second" wire:model.lazy="nomenclature_second" name="nomenclature_second" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccionar</option>
                        <option  @if(!$action) @if($nomenclature_second == 'Calle') selected @endif @endif  value="Calle">Calle</option>
                        <option @if(!$action) @if($nomenclature_second == 'Carrera') selected @endif @endif  value="Carrera">Carrera</option>
                        <option @if(!$action) @if($nomenclature_second == 'Transversal') selected @endif @endif value="Transversal">Transversal</option>
                        <option @if(!$action) @if($nomenclature_second == 'Avenida') selected @endif @endif  value="Avenida">Avenida</option>
                    </select>
                </div>

                <div  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  ">
                    <label for="number_second" class="block text-gray-700 font-bold mb-2">Número:</label>
                    <input  id="number_second"  @if(!$status) readonly @endif  wire:model.lazy="number_second" name="number_second" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                </div>

                <div  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  ">
                    <label for="password" class="block text-gray-700 font-bold mb-2">No. Establecimiento:</label>
                    <input   id="number" wire:model.lazy="number"  @if(!$status) readonly @endif name="number" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                </div>

            </div>

          <div  class="md:flex md:items-center mb-4">

              <div  class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0  ">
                  <label for="observations" class="block text-gray-700 font-bold mb-2">Observaciones:</label>
                  <textarea  wire:model.lazy="observations" @if(!$status) readonly @endif  name="observations" class="resize-none focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full  p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2"></textarea>

              </div>
              <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                  <label for="email" class="block text-gray-700 font-bold mb-2">Email*:</label>
                  <input wire:model.lazy="email"  @if(!$action) @if(!$status) readonly @endif @endif type="email" id="email" name="email" class="focus:outline-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500  mb-2">
                  <div class="h-4">
                      @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                  </div>
              </div>
          </div>


            <div class="md:flex md:items-center mb-4">
                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Departamento:</label>
                    <select  wire:model.lazy="department_id" id="department_id"   name="department_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="0" >Seleccionar</option>
                        @foreach($departments as $department)
                            <option  @if($department->id === $department_id ) selected @endif  value="{{$department->id}}">{{$department->name}}</option>
                        @endforeach
                    </select>
                    <div class="h-4">
                    </div>
                </div>

                <div class="md:w-1/2 pr-0 md:pr-4 mb-4 md:mb-0">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Ciudades:</label>
                    <select wire:model.lazy="city_id" id="city_id" name="city_id" class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="select">Seleccionar</option>
                        @foreach($cities_list as $city)
                            <option  @if($city_id === $city->id) selected @endif  value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach

                    </select>
                    <div class="h-4">
                        @error('city_id') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div  class="flex flex-col text-gray-400 ">
                <span>* Campo obligatorio</span>
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


