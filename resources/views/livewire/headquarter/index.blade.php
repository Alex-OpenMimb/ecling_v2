<div class="max-h-screen overflow-y-auto">
    <div class="bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10">
        <div class="flex flex-col">
            <h2 class="text-lg font-bold">Sedes </h2>
            <h3 class="text-base font-semibold" > Cliente : {{$client_name}} </h3>
        </div>
        <div class="flex gap-2">
            <a  @if($status) href="{{route('admin.create.headquarters',['client'=> $client_slug])}}" @else  @click="$dispatch('inactive_client')" @endif class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md hover:bg-blue-500 hover:text-white transition duration-300">
                Crear
            </a>

            <x-buttons.back route="admin.clients" content="Volver a clientes"></x-buttons.back>
        </div>
    </div>
    <div class="mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
        <livewire:headquarter.datatable-headquarters :client="$client_slug"/>
    </div>

</div>
