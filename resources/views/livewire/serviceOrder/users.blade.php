<div>
    <div >

        <form  class="m-4">
            <div class="flex justify-between items-center pb-4">
                <div class="flex flex-col">
                    <h3 class="text-xl  text-gray-900 font-bold" id="brand-modal-title"> Usuarios</h3>
                    <h3 class="text-base  text-gray-900 font-semibold" id="brand-modal-title"> Creada por: {{$creator}}</h3>

                </div>

                <button   onclick="Livewire.dispatch('closeModal', { component: 'service-order.users' })" type="button" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex flex-col md:flex-row justify-between mb-4 md:space-x-4 space-y-4 md:space-y-0">

                <div class="flex-1 p-4 border border-gray-300 rounded overflow-y-auto h-40">
                    @foreach($users  as $user)
                        <p>{{$user->name}}</p>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button onclick="Livewire.dispatch('closeModal', { component: 'service-order.users' })" type="button" class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md hover:bg-red-500 hover:text-white transition duration-300">Cerrar</button>
            </div>
        </form>
    </div>

</div>

