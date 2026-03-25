<div class="py-10 overflow-y-auto max-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <section class="bg-white border border-gray-100 rounded-2xl shadow-sm px-6 py-8 sm:px-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-blue-500 font-semibold">Panel de Control</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">Configuraciones del sistema</h1>
                    <p class="text-sm text-gray-500 mt-3 max-w-2xl">
                        Personaliza los elementos clave del proyecto desde un espacio centralizado.
                    </p>
                </div>
            </div>

            <div class="w-full">
                <article class="border border-gray-100 rounded-xl shadow-xs hover:shadow-md transition-shadow duration-200 bg-white p-6 flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="h-12 w-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class='bx bx-image text-2xl'></i>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-800 mt-5">Titulos de las fotos</h2>
                    <p class="text-sm text-gray-500 mt-3 flex-1 leading-relaxed">
                        Administra los titulos de las fotos que estarán disponibles para las evidencias que se guardan los reportes.
                    </p>

                    <a href="{{ route('admin.configurations.title-photo.index')  }}"
                       class="mt-6 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        Empezar
                        <i class="ri-arrow-right-line ml-2 text-lg"></i>
                    </a>
                </article>
            </div>

            <div class="w-full mt-10">
                <article class="border border-gray-100 rounded-xl shadow-xs hover:shadow-md transition-shadow duration-200 bg-white p-6 flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="h-12 w-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class='bx bx-receipt text-2xl'></i>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-800 mt-5">Razones de visita</h2>
                    <p class="text-sm text-gray-500 mt-3 flex-1 leading-relaxed">
                        Administra las razones que se usarán para registrar las visitas.
                    </p>

                    <a href="{{ route('admin.configurations.visit-reasons.index') }}"
                       class="mt-6 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        Empezar
                        <i class="ri-arrow-right-line ml-2 text-lg"></i>
                    </a>
                </article>
            </div>

            <div class="w-full mt-10">
                <article class="border border-gray-100 rounded-xl shadow-xs hover:shadow-md transition-shadow duration-200 bg-white p-6 flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="h-12 w-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class='bx bx-list-check text-2xl'></i>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-800 mt-5">Estados de cotización</h2>
                    <p class="text-sm text-gray-500 mt-3 flex-1 leading-relaxed">
                        Administra los estados disponibles para las cotizaciones.
                    </p>

                    <a href="{{ route('admin.configurations.quotation-status.index') }}"
                       class="mt-6 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        Empezar
                        <i class="ri-arrow-right-line ml-2 text-lg"></i>
                    </a>
                </article>
            </div>

            <div class="w-full mt-10">
                <article class="border border-gray-100 rounded-xl shadow-xs hover:shadow-md transition-shadow duration-200 bg-white p-6 flex flex-col">
                    <div class="flex items-center justify-between">
                        <div class="h-12 w-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class='bx bx-image text-2xl'></i>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-gray-800 mt-5">Clases de equipos</h2>
                    <p class="text-sm text-gray-500 mt-3 flex-1 leading-relaxed">
                        Administra clases de equipos del sistema.
                    </p>

                    <a href="{{ route('admin.configurations.equipment-class.index')  }}"
                       class="mt-6 inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        Empezar
                        <i class="ri-arrow-right-line ml-2 text-lg"></i>
                    </a>
                </article>
            </div>
        </section>
    </div>
</div>
