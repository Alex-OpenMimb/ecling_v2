<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   {{-- <link href="https://cdn.tailwindcss.com" rel="stylesheet"> --}}
    <link rel="icon" href="{{asset('image/logo/logo.jpeg')}}" type="image/x-icon">
</head>
<body class="h-screen overflow-hidden flex items-center justify-center" style="background: #edf2f7;">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
   {{--   <script src="https://cdn.tailwindcss.com"></script> --}}
    <title>Admin Panel</title>
    @vite(['resources/css/app.css','resources/css/admin.css', 'resources/js/app.js','resources/js/admin.js'])
    @livewireStyles
</head>
<body class="text-gray-800 font-inter " >
<!--sidenav -->
<div class="container-admin fixed left-0 top-0 w-64 h-full bg-[#ffffff] p-4 z-50 sidebar-menu transition-transform border-r border-gray-300 overflow-y-auto no-scrollbar">
    <div class="flex">
        <img class="w-12 h-12 rounded-full" src="{{asset('image/logo/logo.jpeg')}}" alt="Logo Technic Service"/>
        <h2 class="font-bold text-2xl text-gray-500">Ecling</h2>
    </div>

    <ul class="mt-4">
        <li class="mb-1 group">
            <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 bg-blue-200 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="ri-home-2-line mr-3 text-lg"></i>
                <span class="text-sm">Panel Administrativo</span>
            </a>
        </li>
        <!--
        <li class="mb-1 group">
            <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class='bx bx-list-ul mr-3 text-lg'></i>
                <span class="text-sm">Programación</span>
            </a>
        </li>
        -->
        <!-- Submenu Users-->

        <li class="mb-1 group ">
            <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                <i class='bx bx-user mr-3 text-lg'></i>
                <span class="text-sm">Usuarios</span>
                <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
            </a>
            <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                @can('admin.users')
                    <li class="mb-4">
                        <a href="{{route('admin.users')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Ver usuarios</a>
                    </li>
                 @endcan
                <li class="mb-4">
                    <a href="{{route('admin.profile.user')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Perfil de  usuario</a>
                </li>

            </ul>
        </li>


        <!-- Submenu Clients-->
        @can('admin.clients')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-group mr-3 text-lg'></i>
                    <span class="text-sm">Clientes</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.clients')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Listar clientes</a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('admin.planner')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-calendar  mr-3 text-lg'></i>
                    <span class="text-sm">Planeador</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>

                </a>

                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.planner')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Planeador general</a>
                    </li>
                </ul>

            </li>
        @endcan

        @can('admin.service-order')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-calendar  mr-3 text-lg'></i>
                    <span class="text-sm">Ordenes de Servicios</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>

                </a>

                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.service-order')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Ordenes generales</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{route('admin.pending')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Pendientes</a>
                    </li>
                </ul>

            </li>
        @endcan

      {{--  @can('admin-store')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-store  mr-3 text-lg'></i>
                    <span class="text-sm">Almacén</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    @can('admin.components')
                    <li class="mb-4">
                        <a href="{{route('admin.components')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Componentes</a>
                    </li>
                    @endcan

                    @can('admin.materials')
                    <li class="mb-4">
                        <a href="{{route('admin.materials')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Materiales</a>
                    </li>

                        @endcan
                </ul>
            </li>
        @endcan --}}

      {{--  @can('admin-general-equipments')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-cog  mr-3 text-lg'></i>
                    <span class="text-sm">Equipos</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.equipments')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Equipos Generales</a>
                    </li>
                </ul>
            </li>
        @endcan --}}

        @can('admin-preventive')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-wrench  mr-3 text-lg'></i>
                    <span class="text-sm">Rutina Preventiva</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.preventive-activity')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Actividades</a>
                    </li>
                    <li class="mb-4">
                        <a href="{{route('admin.preventive-routine')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Rutinas</a>
                    </li>
                </ul>
            </li>
        @endcan


        @can('admin.schedule')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-bar-chart  mr-3 text-lg'></i>
                    <span class="text-sm">Cronograma de mantenimientos preventivos</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.schedule')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Cronograma general</a>
                    </li>
                </ul>
            </li>
        @endcan

        <!-- Submenu correctie-->
        @can('admin.corrective-activities')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-list-ul mr-3 text-lg'></i>
                    <span class="text-sm">Actividades Correctivas</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.corrective-activities')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Actividades</a>
                    </li>

                </ul>
            </li>
        @endcan

        @can('admin.corrective-management')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-line-chart  mr-3 text-lg'></i>
                    <span class="text-sm">Gestión de mantenimiento correctivos</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.corrective-management')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Matenimientos Correctivo</a>
                    </li>

                </ul>
            </li>
        @endcan

        @can('admin.configurations')
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-500 hover:bg-blue-200 hover:text-blue-800 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-blue-200 group-[.selected]:text-blue-800 sidebar-dropdown-toggle">
                    <i class='bx bx-cog  mr-3 text-lg'></i>
                    <span class="text-sm">Configuración</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{route('admin.configurations')}}" class="text-gray-900 text-sm flex items-center hover:text-blue-600 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">General</a>
                    </li>

                </ul>
            </li>
        @endcan


    </ul>
</div>
<div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
<!-- end sidenav -->

<main class="w-full md:w-3/4 md:ml-64 bg-gray-100 min-h-screen transition-all main">
    <!-- navbar -->
    <div class=" px-6 bg-[#ffffff] flex items-center shadow-md shadow-black/5 sticky top-0 left-0 z-30">
        <button type="button" class="text-lg text-gray-900 font-semibold sidebar-toggle">
            <i class="ri-menu-line"></i>
        </button>

        <ul class="ml-auto flex items-center ">
            <li class="mr-1 dropdown hidden">
                <button type="button" class="dropdown-toggle text-gray-400 mr-4 w-8 h-8 rounded flex items-center justify-center  hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="hover:bg-gray-100 rounded-full" viewBox="0 0 24 24" style="fill: gray;transform: ;msFilter:;"><path d="M19.023 16.977a35.13 35.13 0 0 1-1.367-1.384c-.372-.378-.596-.653-.596-.653l-2.8-1.337A6.962 6.962 0 0 0 16 9c0-3.859-3.14-7-7-7S2 5.141 2 9s3.14 7 7 7c1.763 0 3.37-.66 4.603-1.739l1.337 2.8s.275.224.653.596c.387.363.896.854 1.384 1.367l1.358 1.392.604.646 2.121-2.121-.646-.604c-.379-.372-.885-.866-1.391-1.36zM9 14c-2.757 0-5-2.243-5-5s2.243-5 5-5 5 2.243 5 5-2.243 5-5 5z"></path></svg>
                </button>
                <div class="dropdown-menu shadow-md shadow-black/5 z-30 hidden max-w-xs w-full bg-white rounded-md border border-gray-100">
                    <form action="" class="p-4 border-b border-b-gray-100">
                        <div class="relative w-full">
                            <input type="text" class="py-2 pr-4 pl-10 bg-gray-50 w-full outline-none border border-gray-100 rounded-md text-sm focus:border-blue-500" placeholder="Search...">
                            <i class="ri-search-line absolute top-1/2 left-4 -translate-y-1/2 text-gray-900"></i>
                        </div>
                    </form>
                </div>
            </li>
            <li class="dropdown hidden">
                <button type="button" class="dropdown-toggle text-gray-400 mr-4 w-8 h-8 rounded flex items-center justify-center  hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="hover:bg-gray-100 rounded-full" viewBox="0 0 24 24" style="fill: gray;transform: ;msFilter:;"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"></path></svg>
                </button>
                <div class="dropdown-menu shadow-md shadow-black/5 z-30 hidden max-w-xs w-full bg-white rounded-md border border-gray-100">
                    <div class="flex items-center px-4 pt-4 border-b border-b-gray-100 notification-tab">
                        <button type="button" data-tab="notification" data-tab-page="notifications" class="text-gray-400 font-medium text-[13px] hover:text-gray-600 border-b-2 border-b-transparent mr-4 pb-1 active">Notifications</button>
                        <button type="button" data-tab="notification" data-tab-page="messages" class="text-gray-400 font-medium text-[13px] hover:text-gray-600 border-b-2 border-b-transparent mr-4 pb-1">Messages</button>
                    </div>
                    <div class="my-2">
                        <ul class="max-h-64 overflow-y-auto" data-tab-for="notification" data-page="notifications">
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">New order</div>
                                        <div class="text-[11px] text-gray-400">from a user</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">New order</div>
                                        <div class="text-[11px] text-gray-400">from a user</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">New order</div>
                                        <div class="text-[11px] text-gray-400">from a user</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">New order</div>
                                        <div class="text-[11px] text-gray-400">from a user</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">New order</div>
                                        <div class="text-[11px] text-gray-400">from a user</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <ul class="max-h-64 overflow-y-auto hidden" data-tab-for="notification" data-page="messages">
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">John Doe</div>
                                        <div class="text-[11px] text-gray-400">Hello there!</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">John Doe</div>
                                        <div class="text-[11px] text-gray-400">Hello there!</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">John Doe</div>
                                        <div class="text-[11px] text-gray-400">Hello there!</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">John Doe</div>
                                        <div class="text-[11px] text-gray-400">Hello there!</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                    <img src="https://placehold.co/32x32" alt="" class="w-8 h-8 rounded block object-cover align-middle">
                                    <div class="ml-2">
                                        <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500">John Doe</div>
                                        <div class="text-[11px] text-gray-400">Hello there!</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            <button id="fullscreen-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="hover:bg-gray-100 rounded-full" viewBox="0 0 24 24" style="fill: gray;transform: ;msFilter:;"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"></path></svg>
            </button>
            <script>
                const fullscreenButton = document.getElementById('fullscreen-button');

                fullscreenButton.addEventListener('click', toggleFullscreen);

                function toggleFullscreen() {
                    if (document.fullscreenElement) {
                        // If already in fullscreen, exit fullscreen
                        document.exitFullscreen();
                    } else {
                        // If not in fullscreen, request fullscreen
                        document.documentElement.requestFullscreen();
                    }
                }
            </script>

            <li class="dropdown ml-3">
                <button type="button" class="dropdown-toggle flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 relative">
                        <div class="p-1 bg-white rounded-full focus:outline-none focus:ring">
                            <img class="w-8 h-8 rounded-full"
                                 @if(! auth()->user()->url_image )
                                     src="{{asset('image/profile/avatar.jpg')}}"
                                   @else
                                      src="{{$global_image_url}}"
                                  @endif
                                  alt=""/>
                            <div class="top-0 left-7 absolute w-3 h-3 bg-lime-400 border-2 border-white rounded-full animate-ping"></div>
                            <div class="top-0 left-7 absolute w-3 h-3 bg-lime-500 border-2 border-white rounded-full"></div>
                        </div>
                    </div>
                    <div class="p-2 md:block text-left">
                        <h2 class="text-sm font-semibold text-gray-800">{{auth()->user()->name}}</h2>
                        <p class="text-xs text-gray-500"> {{auth()->user()->roles->first()->name}} </p>
                    </div>
                </button>
                <ul class="dropdown-menu shadow-md shadow-black/5 z-30 hidden py-1.5 rounded-md bg-white border border-gray-100 w-full max-w-[140px]">
                    <li>

                        <a href="{{route('admin.profile.user')}}" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-600 hover:bg-gray-50">Perfil</a>
                    </li>
                    <li>
                        <a href="#" class="hidden flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-600 hover:bg-gray-50">Settings</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" role="menuitem" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-600 hover:bg-gray-50 cursor-pointer">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- end navbar -->
    <div >
        <!-- Content -->
        {{ $slot }}
        <!-- End Content -->
    </div>

    @livewireScriptConfig

</main>
@livewire('wire-elements-modal')
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>
</body>
</html>
