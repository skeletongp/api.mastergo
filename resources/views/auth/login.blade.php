<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @if (isset($titlte))
            {{ $title }}
        @else
            {{ env('APP_NAME') }} | Iniciar Sesión
        @endif
    </title>


    {{-- Fonts --}}


    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa/css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">

    {{-- Scripts --}}

    <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>

    @livewireStyles
    @laravelPWA

</head>

<body class="flex">
    <main class="h-screen w-screen flex flex-col items-center justify-center">
        <div class="p-4 max-w-sm w-full shadow-lg shadow-blue-100">
            <img src="{{ asset('images/logo.png') }}" class="h-32 mx-auto p-4" alt="logo">
            <h1 class="mt-4 mb-8 text-center font-bold text-xl uppercase">
                Accede a tu cuenta
            </h1>
            @if (Session::has('msg'))
            @endif
            <form action="{{ route('login.store') }}" method="POST" class="space-y-8">
                @csrf
                <div>
                    <x-input label="Nombre de usuario" name="username" id="username" required>
                        <x-slot name="icon">
                            <span class="fas fa-user text-blue-600 text-2xl pr-6"></span>
                        </x-slot>
                    </x-input>
                    <x-input-error for="username"></x-input-error>
                </div>
                <div>
                    <x-input label="Contraseña" type="password" id="password" name="password" required>
                        <x-slot name="icon">
                            <span class="fas fa-lock text-blue-600 text-2xl pr-6"></span>
                        </x-slot>
                    </x-input>
                    <x-input-error for="password"></x-input-error>
                </div>
                <div class="flex justify-end">
                    <x-button class="uppercase text-xl font-bold text-black">Acceder</x-button>
                </div>
            </form>
            <div class="flex flex-row space-x-2 mt-4 mb-3 items-center">
                <hr class="w-full">
                <span class="whitespace-nowrap text-lg font-semibold">TAMBIÉN PUEDES</span>
                <hr class="w-full">
            </div>
            <div class="flex justify-center">
                <x-button class="flex space-x-2 items-center bg-red-600 hover:bg-red-700 hover:opacity-80  uppercase ">
                    <span class="fab fa-google text-white "></span>
                    <span class="text-white ">Acceder con google</span>
                </x-button>
            </div>
        </div>
    </main>
    @livewireScripts
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

    </style>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        @if (!Session::has('msg'))
        toastr.options.preventDuplicates = true;
        toastr.options.positionClass = 'toast-bottom-center';
            toastr.error('{{ session('msg') }}')
        @endif
    </script>

</body>

</html>
