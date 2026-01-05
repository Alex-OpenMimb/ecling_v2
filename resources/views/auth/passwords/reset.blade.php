@extends('layouts.auth_base')

@section('content')
    <div   class="min-h-screen flex flex-col items-center justify-center bg-gray-300" style="width: 500px" >
        <div class="flex flex-col bg-white shadow-md px-4 sm:px-6 md:px-8 lg:px-10 py-8 rounded-md w-full max-w-md">
            <div class="font-medium self-center text-xl sm:text-2xl uppercase text-gray-800">Cambiar Contraseña</div>
            <div class="mt-10" >
                <form  method="POST"  action="{{route('password.update')}}"  >
                    @csrf
                    <input type="hidden" name="token" value="{{ request()->token }}">
                    <div class="flex flex-col mb-2">
                        <label for="email" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Email:</label>
                        <div class="relative">
                            <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>

                            <input id="email" type="email" name="email" value="{{ old('email') }}" class=" text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400 text-gray-800" placeholder="email" />
                        </div>
                        <div class="h-4">
                            @error('email')
                            <span class="text-sm  text-red-500" role="">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <!-- Password -->
                    <div class="flex flex-col mb-6">
                        <label for="password" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Contraseña:</label>
                        <div class="relative">
                            <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                                  <span>
                                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                      <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                  </span>
                            </div>

                            <input   type="password" id="password"  name="password" class="text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400" placeholder="Contraseña" />
                        </div>
                        <div class="h-4">
                            @error('password')
                            <span class="text-sm  text-red-500" role="">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Confirmation Password -->
                    <div class="flex flex-col mb-6">
                        <label for="password" class="mb-1 text-xs sm:text-sm tracking-wide text-gray-600">Confirmar Contraseña:</label>
                        <div class="relative">
                            <div class="inline-flex items-center justify-center absolute left-0 top-0 h-full w-10 text-gray-400">
                                  <span>
                                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                      <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                  </span>
                            </div>

                            <input   type="password" id="password_confirmation"  name="password_confirmation" class="text-sm sm:text-base placeholder-gray-500 pl-10 pr-4 rounded-lg border border-gray-400 w-full py-2 focus:outline-none focus:border-blue-400" placeholder="Contraseña" />
                        </div>
                        <div class="h-4">
                            @error('password')
                            <span class="text-sm  text-red-500" role="">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>




                    <div class="flex items-center mb-4">
                        <input    type="checkbox" id="show_new_password">
                        <label for="show_new_password" class="ml-2 text-blue-500 text-sm">Mostrar contraseña</label>
                    </div>

                    <div class="flex w-full">
                        <button type="submit" class="flex items-center justify-center focus:outline-none text-white text-sm sm:text-base bg-blue-600 hover:bg-blue-700 rounded py-2 w-full transition duration-150 ease-in">
                            <span class="mr-2 uppercase">Enviar</span>
                            <span>
                                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                </span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
        let $show_password = document.getElementById('show_new_password')
        let $password = document.getElementById('password')
        let $password_confirmation = document.getElementById('password_confirmation')
        $show_password.addEventListener('click',(e)=>{
            let checked = e.target.checked
            $password.type = checked ? 'text' : 'password';
            $password_confirmation.type = checked ? 'text' : 'password';
        })
    </script>
@endsection


