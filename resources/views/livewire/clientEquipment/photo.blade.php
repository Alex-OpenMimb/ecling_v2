<div class="max-h-screen overflow-y-auto ">

    <div class="bg-gray-100 rounded-md p-4  flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-base font-bold"> Cliente: {{$client_name}}  </h2>
            <h2 class="text-base font-semibold"> Sede: {{$headquarter_name}}  </h2>

        </div>

            <div class="">
                <p>{{$loader}}</p>
            </div>


        <a href="{{route('admin.clients-equipments',['client'=> $client->slug,'headquarter' =>$headquarter->slug ])}}"  title="atras" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
            <svg class="h-8 w-8 text-blue-600 hover:text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" /></svg>
        </a>

    </div>
    <div class="container mx-auto   px-4 py-6 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">

         <div class="md:flex md:items-center mb-4 justify-around">
             <div class="border border-gray-300 p-4 rounded-md shadow-sm md:w-1/3">
                 <div> <h2 class="font-semibold text-base"> Foto perimetral</h2></div>
                 <div>
                     @if( $perimeter_photo )
                         <img src="{{$perimeter_photo}}"  alt="Foto perimetral"  width="" height="" class="w-photo-300 h-photo-300 ">
                     @else
                        <span  class="font-bold text-base">Sin registro fotográfico!</span>
                     @endif

                 </div>
                 <button wire:click="download_photo('perimeter')"   type="button" class="@if(!$perimeter_photo) hidden @endif mt-2 bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Descargar</button>

             </div>

             <div class="border border-gray-300 p-4 rounded-md shadow-sm md:w-1/3">
                 <div> <h2 class="font-semibold text-base">Foto de la placa</h2></div>
                 <div>
                     @if( $plate_photo )
                         <img src="{{$plate_photo}}"  alt="Foto perimetral"  width="" height="" class="w-photo-300 h-photo-300 ">
                     @else
                         <span  class="font-bold text-base">Sin registro fotográfico!</span>
                     @endif

                 </div>

                 <button wire:click="download_photo('plate')" type="button" class="@if(!$plate_photo) hidden @endif mt-2   bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Descargar</button>
             </div>

         </div>
    </div>

</div>


