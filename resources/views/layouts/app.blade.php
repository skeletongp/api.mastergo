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
            {{ env('APP_NAME') }}
        @endif
    </title>


    {{-- Fonts --}}


    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa/css/all.css') }}" rel="stylesheet">


    {{-- Scripts --}}

    <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>

    @livewireStyles
    @laravelPWA

</head>

<body class="flex">
    <div class="sticky left-0 top-0 z-50 h-screen bg-gray-50 p-2" style="z-index: 80">
        @include('includes.sidebar')
    </div>
    <div class="w-full ">
        {{-- Navbar --}}
        <header class="sticky top-0 z-50 w-full mx-auto py-2 bg-white">
            @include('includes.header')
            <div class=" w-full bg-gray-50 py-1 px-4">
                @if (isset($bread))
                    {{ $bread }}
                @endif
            </div>
        </header>

        {{-- Sidebar --}}


        {{-- Content --}}
        <main class=" p-4 pl-0 pt-6 bg-white flex">

            <section class="pl-6 w-full">
                {{ $slot }}
            </section>
        </main>

        {{-- Foot --}}
        <footer>

        </footer>
    </div>
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

    @stack('js')

</body>

</html>
