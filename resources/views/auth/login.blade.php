<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | LOGIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fa/css/all.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/flowbite@1.3.4/dist/flowbite.js"></script>
</head>

<body>
        <div class="flex h-[90vh] lg:h-screen items-center justify-center w-full  mx-auto overflow-hidden bg-cyan-400 rounded-lg shadow-xl">
            <div class=" lg:flex flex-col lg:flex-row lg:max-h-[30rem] w-full mx-auto max-w-sm sm:max-w-4xl bg-white">
                <div class="hidden order-2 lg:order-1 h-full w-full lg:h-auto lg:w-1/2 bg-teal-700 text-gray-100 lg:grid grid-cols-2 gap-6 p-6">
                    <div class="flex items-center justify-between h-[3.5rem] lg:h-[6.5rem] flex-col space-y-2 font-bold text-xl uppercase">
                        <span class="fas fa-chart-bar text-4xl lg:text-7xl"></span>
                        Estadísticas
                    </div>
                    <div class="flex items-center justify-between h-[3.5rem] lg:h-[6.5rem] flex-col space-y-2 font-bold text-xl uppercase">
                        <span class="fas fa-cash-register text-4xl lg:text-7xl"></span>
                        Contabilidad
                    </div>
                    <div class="flex items-center justify-between h-[3.5rem] lg:h-[6.5rem] flex-col space-y-2 font-bold text-xl uppercase">
                        <span class="fas fa-file-invoice-dollar text-4xl lg:text-7xl"></span>
                        Facturación
                    </div>
                    <div class="flex items-center justify-between h-[3.5rem] lg:h-[6.5rem] flex-col space-y-2 font-bold text-xl uppercase">
                        <span class="fas fa-cogs text-4xl lg:text-7xl"></span>
                        Gestión
                    </div>
                    <div class="flex items-center justify-between h-[3.5rem] lg:h-[6.5rem] flex-col space-y-2 font-bold text-xl uppercase">
                        <span class="fas fa-users-cog text-4xl lg:text-7xl"></span>
                        Personas
                    </div>
                    <div class="flex items-center justify-between h-[3.5rem] lg:h-[6.5rem] flex-col space-y-2 font-bold text-xl uppercase">
                        <span class="fas fa-layer-group text-4xl lg:text-7xl"></span>
                        Inventario
                    </div>
                </div>
                <div class="order-1 lg:order-2 flex items-center justify-center p-6 sm:p-12 lg:w-1/2">
                    <form class="w-full" id="formLogin" action="{{ route('login.store') }}" method="POST">
                        @csrf

                        <div class="flex justify-center">
                            <div class="w-44 h-24 bg-contain bg-no-repeat bg-center"
                                style="background-image: url({{ getStoreLogo() }})">

                            </div>
                        </div>
                        <h1 class="mb-4 text-2xl font-bold text-center text-gray-700">
                            Ingresa a tu cuenta
                        </h1>
                        <div>
                            <label class="block">
                                Nombre de usuario
                            </label>
                            <select type="text" name="username" id="username"
                                class="w-full px-4 py-2 border rounded-md focus:border-teal-400 focus:outline-none focus:ring-1 focus:ring-teal-600"
                                placeholder="">
                                @foreach ($users as $username => $fullname)
                                    <option value="{{ $username }}" {{old('username')}}>{{ $fullname }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="username"></x-input-error>
                        </div>
                        <div class="mb-8">
                            <label class="block mt-4">
                                Contraseña
                            </label>
                            <input name="password" id="password"
                                class="w-full px-4 py-2 border rounded-md focus:border-teal-400 focus:outline-none focus:ring-1 focus:ring-teal-600"
                                autocomplete="off" placeholder="" type="password" />
                            <x-input-error for="password"></x-input-error>
                        </div>

                        <div class="flex justify-end">
                            <button
                                class="block px-4 py-2 font-bold leading-6 text-center text-white transition-colors duration-150 bg-teal-700 border border-transparent rounded-lg active:bg-teal-700 hover:bg-teal-800 focus:outline-none focus:shadow-outline-teal uppercase"
                                href="#">
                                Acceder
                            </button>
                        </div>


                        <div class="hidden" id="generalLoad">
                            <x-loading></x-loading>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
         $('button').on('click', function() {
            $('#generalLoad').removeClass('hidden');
        })
         $('#formLogin').on('submit', function() {
            $('#generalLoad').removeClass('hidden');
        })
        $('#username').on('change',function(){
            val=$(this).val();
            localStorage.setItem('username',val);
        })
      if (localStorage.getItem('username')) {
        $('#username').val(localStorage.getItem('username'));
      }
        msg = '{{ Session::get('msg') }}';
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
                text: 'text-teal-700',
                "bg": 'bg-teal-100'
            }
        };
        
        if (msg) {
            msg = msg.split('|');
            Swal.fire({
                title: `<div class="p-4 mb-4 text-lg uppercase ${colors[msg[0]]['text']} ${colors[msg[0]]['bg']} rounded-lg font-bold" role="alert"> ${msg[1]}</div>`,
                icon: msg[0],
                showConfirmButton: false,
                timer: 2000,
                position: 'top-end',
            });
        }
    </script>
</body>

</html>
