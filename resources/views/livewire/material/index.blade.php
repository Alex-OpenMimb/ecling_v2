<div class="max-h-screen overflow-y-auto pb-8">

    <div class="max-w-screen-lg mx-auto mb-8">
        <div class="bg-gray-100 rounded-md p-4 flex justify-between items-center mt-10">
            <h2 class="text-lg font-bold"> Gestión de materiales</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1 -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden h-[350px]">
                <div class="p-4">
                    <div class="mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
                        <div class="mb-2">
                            <h2 class=" text-gray-500 font-bold" >Materiales</h2>
                        </div>
                        <livewire:material.material.datatable />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden h-[350px]">
                <div class="p-4">
                    <div class="mx-2  px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
                        <div class="mb-2">
                            <h2 class=" text-gray-500 font-bold" >Repuestos</h2>
                        </div>
                        <livewire:material.spare-part.datatable />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden h-[350px]">
                <div class="p-4">
                    <div class="mx-2 px-4 py-3 mb-8 overflow-x-auto bg-white rounded-lg shadow-md dark:bg-gray-800">
                        <div class="mb-2">
                            <h2 class=" text-gray-500 font-bold" >Unidades</h2>
                        </div>
                        <livewire:material.unit.datatable />
                    </div>

                </div>
            </div>


        </div>
    </div>


</div>
