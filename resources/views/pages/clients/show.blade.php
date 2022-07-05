<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('clients.show', $client) }}
    @endslot
    <div class="w-full mx-auto my-5 p-4">
        <div class="flex space-x-4">
            <!-- Left Side -->
            <div class="w-full ">
                <!-- Profile Card -->
                <div class="bg-white p-3 border-t-4 border-green-400">
                    <div class="image overflow-hidden">
                        <img class="h-auto w-1/3 mx-auto" src="{{ $client->avatar }}" alt="">
                    </div>
                    <h1 class=" font-bold text-xl leading-8 my-2 uppercase">
                        {{ $client->name ?: $client->contact->fullname }}</h1>
                    <h3 class=" text-lg text-bold leading-6">{{ $client->name ? $client->contact->fullname : '' }}</h3>
                    <ul class="bg-gray-100  hover: hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
                            @livewire('clients.client-transactions', ['client' => $client], key(uniqid()))
                    </ul>
                </div>
            </div>
            <!-- Right Side -->
            <div class="w-full">
                <!-- Profile tab -->
                <!-- About Section -->
                <div class="bg-white p-3 shadow-sm rounded-sm">
                    <div class="flex items-center space-x-2 font-semibold  leading-8">
                        <span clas="text-green-500">
                            
                        </span>
                        <span class="tracking-wide">Contacto y Detalles</span>
                    </div>
                    <div class="">
                        <div class="grid grid-cols-2 text-sm">
                            <div class="grid grid-cols-2">
                                <div class="px-4 py-2 font-semibold">Nombre</div>
                                <div class="px-4 py-2">{{$client->contact->name}}</div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="px-4 py-2 font-semibold">Apellidos</div>
                                <div class="px-4 py-2">{{$client->contact->lastname}}</div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="px-4 py-2 font-semibold">RNC/Cédula</div>
                                <div class="px-4 py-2">{{$client->rnc}}</div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="px-4 py-2 font-semibold">Tel. Ppal,</div>
                                <div class="px-4 py-2">{{$client->phone}}</div>
                            </div>
                            <div class="grid grid-cols-4 col-span-2">
                                <div class="px-4 py-2 font-semibold col-span-1">Dirección</div>
                                <div class="px-4 py-2 col-span-3">{{$client->address}}</div>
                            </div>
                            
                            <div class="grid grid-cols-4 col-span-2">
                                <div class="px-4 py-2 font-semibold col-span-1">Correo</div>
                                <div class="px-4 py-2 col-span-3 text-blue-600">
                                    {{$client->email}}
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="px-4 py-2 font-semibold">Balance</div>
                                <div class="px-4 py-2">${{formatNumber($client->balance)}}</div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="px-4 py-2 font-semibold">Deuda</div>
                                <div class="px-4 py-2">${{formatNumber($client->debt)}}</div>
                            </div>
                        </div>
                    </div>
                  
                </div>
                <!-- End of about section -->

                <div class="my-4"></div>

                <!-- Experience and education -->
                <div class="bg-white p-3 shadow-sm rounded-sm">

                    <div class=" py-2 w-max mx-auto min-h-max h-full relative ">
                        @livewire('clients.client-invoice', ['client' => $client], key(uniqid()))
                    </div>
                    <!-- End of Experience and education grid -->
                </div>
                <!-- End of profile tab -->
            </div>
        </div>
    </div>
</x-app-layout>
