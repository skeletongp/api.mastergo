<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('clients.show', $client) }}
    @endslot
    <div class="w-full mx-auto my-5 p-4">
        <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4">
            <!-- Left Side -->
            <div class="w-full">

                <div class="bg-white p-3 shadow-sm rounded-sm">
                    <div class="flex items-center space-x-2 font-bold uppercase
                      leading-8">

                        <span class="tracking-wide text-lg">Contacto y Detalles</span>
                    </div>
                    <div class="">
                        <div class="grid grid-cols-2 text-sm">
                            <div class="grid grid-cols-3">
                                <div class="py-2 font-bold uppercase
                                ">Nombre</div>
                                <div class="py-2 col-span-2">{{ optional($client->contact)->name }}</div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div class="py-2 font-bold uppercase
                                ">Apellidos</div>
                                <div class="py-2 col-span-2">{{ optional($client->contact)->lastname }}</div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div class="py-2 font-bold uppercase
                                ">RNC/Cédula</div>
                                <div class="py-2 col-span-2">{{ $client->rnc }}</div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div class="py-2 font-bold uppercase
                                ">Tel. Ppal.</div>
                                <div class="py-2 col-span-2">{{ $client->phone }}</div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div class="py-2 font-bold uppercase
                                 col-span-1">
                                    Dirección</div>
                                <div class="py-2 col-span-2" title="{{ $client->address }}">
                                    {{ ellipsis($client->address, 28) }}</div>
                            </div>

                            <div class="grid grid-cols-3">
                                <div class="py-2 font-bold uppercase
                                 col-span-1">Correo
                                </div>
                                <div class="py-2 col-span-2 text-blue-600">
                                    {{ ellipsis($client->email, 28) }}
                                </div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div class="py-2 font-bold uppercase
                                ">Balance</div>
                                <div class="py-2 col-span-2">${{ formatNumber($client->balance) }}</div>
                            </div>
                            <div class="grid grid-cols-3">
                                <div class="py-2 font-bold uppercase
                                ">Deuda</div>
                                <div class="py-2 col-span-2">${{ formatNumber($client->debt) }}</div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- Right Side -->
            <div class="w-full ">
                <!-- Profile Card -->
                <div class="ebg-white p-3 border-t-4 border-green-400">
                    <div class="image overflow-hidden">
                        <img class="h-auto w-1/4 mx-auto" src="{{ $client->avatar }}" alt="">
                    </div>
                    <h1 class=" font-bold text-xl leading-8 my-2 uppercase">
                        {{ $client->name ?: optional($client->contact)->fullname }}</h1>
                    <div class="flex flex-row space-x-4 items-center">
                        <h3 class=" text-base font-bold leading-6 uppercase">
                            {{ $client->name ? optional($client->contact)->fullname : '' }}</h3>
                        <h3 class=" text-base font-boldsemibold leading-6">{{ optional($client->contact)->cellphone }}
                        </h3>
                        <h3 class=" text-base font-boldsemibold leading-6">{{ optional($client->contact)->cedula }}
                        </h3>
                    </div>

                </div>
            </div>
        </div>

        <div class="flex space-x-4">
            <div class=" py-2 w-full  min-h-max h-full relative ">
                @livewire('clients.client-invoice', ['client_id' => $client->id], key(uniqid()))
            </div>
            <div class=" py-2 w-full  min-h-max h-full relative ">
                @livewire('clients.client-transactions', ['client' => $client], key(uniqid()))
            </div>
            
        </div>
        <!-- End of Experience and education grid -->
    </div>
</x-app-layout>
