<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | LOGIN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="flex items-center min-h-screen bg-gray-50">
        <div class="flex-1 h-full max-h-[30rem] max-w-4xl mx-auto bg-white rounded-lg shadow-xl">
            <div class="flex flex-col md:flex-row">
                <div class=" md:h-auto md:w-1/2">
                    <img class=" w-full h-[30rem]" src="https://source.unsplash.com/user/erondu/1600x900" alt="img" />
                </div>
                <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
                    <form class="w-full" action="{{ route('login.store') }}" method="POST">
                        @csrf
                        <div class="flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                        </div>
                        <h1 class="mb-4 text-2xl font-bold text-center text-gray-700">
                            Ingresa a tu cuenta
                        </h1>
                        <div>
                            <label class="block">
                                Nombre de usuario
                            </label>
                            <select type="text" name="username" id="username"
                                class="w-full px-4 py-2 border rounded-md focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-600"
                                placeholder="">
                                @foreach ($users as $username => $fullname)
                                    <option value="{{ $username }}">{{ $fullname }}</option>
                                @endforeach
                            </select>
                            <x-input-error for="username"></x-input-error>
                        </div>
                        <div class="mb-8">
                            <label class="block mt-4">
                                Contraseña
                            </label>
                            <input name="password" id="password"
                                class="w-full px-4 py-2 border rounded-md focus:border-blue-400 focus:outline-none focus:ring-1 focus:ring-blue-600" autocomplete="off"
                                placeholder="" type="password" />
                            <x-input-error for="password"></x-input-error>
                        </div>

                        <button
                            class="block w-full px-4 py-2 mt-4 font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue"
                            href="#">
                            Acceder
                        </button>


                        <hr class="my-8" />


                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
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
                text: 'text-blue-700',
                "bg": 'bg-blue-100'
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
