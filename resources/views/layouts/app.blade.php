<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>
        @if (isset($title))
            {{ $title }}
        @else
            {{ env('APP_NAME') . ' | ' . auth()->user()->store->name }}
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
    @stack('css')
</head>

<body class=" antialised ">
    <div class="flex relative max-w-7xl mx-auto ">

        <div class="w-full  min-h-[25rem] lg:min-h-[50rem] ">
            <div class="sticky left-0 top-0  z-50 px-2" style="z-index: 80">
                @include('includes.menubar')
            </div>
            {{-- Navbar --}}
            <header class="sticky top-[4.5rem] z-50 w-full mx-auto py-2 bg-white">
                @include('includes.header')
                <div class=" w-full bg-gray-50 py-1 px-4 flex justify-between items-center">
                    @if (isset($bread))
                        {{ $bread }}
                    @endif
                    <div class="z-50">
                        @if (isset($rightButton))
                            {{ $rightButton }}
                        @endif
                    </div>
                </div>
            </header>
            {{-- Content --}}
            <main class="pl-0  flex">
                <div class="hidden" id="generalLoad">
                    <x-loading></x-loading>
                </div>
                <section class=" w-full mx-auto max-w-7xl bg-white ">
                    {{ $slot }}
                </section>
            </main>
            {{-- Foot --}}
            <footer>

            </footer>
        </div>
    </div>
  {{--   <div class="landscape:hidden">
        <h1>Por favor, gire su pantalla para ustilizar el sistema</h1>
    </div>
 --}}
    {{-- <div class="flex justify-center items-center sm:hidden w-screen h-screen">
        <h1 class=" font-bold text-3xl uppercase text-center max-w-lg leading-12">Este tamaño de pantalla no es
            compatible. Utilice un monitor más
            grande o
            aplique zoom out al sistema</h1>
    </div> --}}
    @livewireScripts

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/mobius1-selectr@latest/dist/selectr.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/formatPhoneNumber.js') }}"></script>
    <script src="{{ asset('js/printer-script.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    @stack('js')
    <x-livewire-alert::scripts />
    <script>
        colors = {
            "success": {
                "text": 'text-green-700',
                "bg": 'bg-green-100'
            },
            'error': {
                "text": 'text-red-700',
                "bg": 'bg-red-100'
            },
            'warning': {
                "text": 'text-yellow-700',
                "bg": 'bg-yellow-100'
            },
            'info': {
                text: 'text-blue-700',
                "bg": 'bg-blue-100'
            }
        };

        $("input[type=number]").bind({
            keydown: function(e) {
                if (e.shiftKey === true) {
                    if (e.which == 9 || e.which == 13) {
                        return true;
                    }
                    return false;
                }
                if (e.which == 13 ) {
                    return true;
                }
                if (e.which == 110 || e.which==190) {
                    return true;
                }
                if (e.which > 95 && e.which<106) {
                    return true;
                }
                if (e.which > 57) {
                    return false;
                }
                if (e.which == 32) {
                    return false;
                }
                return true;
            }
        });

        Livewire.on('showAlert', (alert, type, timer=2000) => {
            icons = ['success', 'error', 'info', 'warning'];

            if (!icons.includes(type)) {
                type = 'info';
            }
            Swal.fire({
                title: `<div class="p-4 mb-4 text-lg uppercase ${colors[type]['text']} ${colors[type]['bg']} 
                rounded-lg font-bold role="alert"> ${alert} </div>`,
                icon: type,
                showConfirmButton: false,
                timer: timer,
                position: 'top-end',
            });
        });

        Livewire.onError(statusCode => {
            switch (statusCode) {
                case 403:
                    msg = 'No tienes permiso para realizar esta acción';
                    break;
                case 419:
                    msg = 'Su sesión ha expirado';
                    break;
                default:
                    msg = 'Ha ocurrido un error con tu solicitud '
                    break;
            }
            Swal.fire({
                title: `<div class="p-4 mb-4 text-lg uppercase text-red-700 bg-red-100 
                rounded-lg font-bold role="alert"> ${msg} </div>`,
                icon: 'error',
                showConfirmButton: true,
                timer: 2000,
                position: 'top-end',
            });
            if (statusCode !== 500) {
                return false;
            }
        })
        $('.load').on('click', function() {
            $('#generalLoad').removeClass('hidden');
        })
        id = {{ auth()->user()->place->id }}
        var channel = Echo.private(`invoices.${id}`);
        channel.listen("NewInvoice", function(data) {
            Livewire.emit('showAlert', 'Nuevo pedido pendiente', 'success')

        });
        $(document).ready(function() {
            $('input[type=tel]').each(function() {
                $(this).formatPhoneNumber({
                    format: '(###) ###-####'
                })
            })
        })
    </script>
    <style>
        .swal2-container {
            z-index: 2000;
        }
    </style>
    <div class="flex p-4 fixed bottom-0">
        <x-button type="button" onclick="history.back();" id="hBack">
            <small class="flex items-center space-x-1">
                <span class="fas fa-angle-left text-xl"></span>
                <span id="sVolver" class="hidden font-bold text-base">Volver</span>
            </small>
        </x-button>
    </div>
    <script>
        $('#hBack').mouseenter(function() {
            $('#sVolver').toggle('', false);
        })
        $('#hBack').mouseleave(function() {
            $('#sVolver').toggle('', false);
        })
        if (window.location.pathname == '/') {
            $('#hBack').addClass('hidden');
        } else {

        }
    </script>
</body>

</html>
