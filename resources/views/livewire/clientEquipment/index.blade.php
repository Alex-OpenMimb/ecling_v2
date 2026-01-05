<div class="max-h-screen overflow-y-auto">
    <div class="bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">Equipos</h2>
            <h3 class="text-base font-semibold" > Cliente : {{$client_name}} </h3>
            <h3 class="text-base font-semibold" > Sede : {{$headquarter_name}} </h3>
            <h3 class="text-base font-semibold" > Dirección : {{$nomenclature_main}} {{$number_main}} @if($number_second) {{$nomenclature_second}} {{$number_second}} @endif No {{$number}}, {{$city_name}} </h3>
        </div>

        <div class="flex gap-2">
            <a href="{{route('admin.clients-equipments.create',['client'=>$client->slug, 'headquarter'=>$headquarter->slug])}}"   type="button" class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">Crear</a>

            <a href="{{route('admin.headquarters',['client'=>$client->slug])}}"  title="editar" class="p-1 text-blue-600 rounded hover:bg-blue-600 hover:text-white">
                <svg class="h-8 w-8 text-blue-600 hover:text-white"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" /></svg>
            </a>
        </div>
    </div>
    <div class="mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <livewire:client-equipment.datatable-client-equipment  :client="$client" :headquarter="$headquarter" />
    </div>

</div>
