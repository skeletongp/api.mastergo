<nav
    class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-800 flex flex-row justify-between items-center ">
    <div class="flex space-x-2 uppercase items-center">
        <a href="{{ route('home') }}">
            <div class="h-12 w-12 bg-contain bg-no-repeat bg-center rounded-full"
                style="background-image: url({{ getStoreLogo() }})"></div>
        </a>

        <a href="{{ route('home') }}">
            <span
                class="self-center text-xl  font-bold whitespace-nowrap dark:text-white">{{ ellipsis(getStore()->name, 20) }}
            </span>
        </a>

    </div>
    <div class="container flex flex-row justify-end items-end mx-auto ">
        <div class="hidden lg:flex lg:order-2 ml-4">
            <div class=" hidden lg:block  mr-3 lg:mr-0 w-56">
                <livewire:general.search-field />
            </div>
        </div>
        <x-actions>
            <x-slot name="content">

                @can('Crear Usuarios')
                    <span class="float-element">
                        <livewire:users.create-user />
                    </span>
                @endcan

                @can('Crear Negocios')
                    <span class="float-element">
                        <livewire:store.create-store />
                    </span>
                @endcan

                @can('Crear Clientes')
                    <span class="float-element">
                        <livewire:clients.create-client />
                    </span>
                @endcan


                @can('Crear Productos')
                    <span class="float-element">
                        <livewire:products.create-product />
                    </span>
                @endcan




                @can('Crear Facturas')
                    @if (request()->routeIs('invoices.create'))
                        @can('Ver Facturas')
                            <div>
                                <a href="{{ route('invoices.index') }}"
                                    class="text-gray-900 load bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                                    <span class="far w-6 text-center fa-file-invoice"></span>
                                    <span> Facturas</span>
                                </a>
                            </div>
                        @endcan
                    @else
                        <div>
                            <a href="{{ route('invoices.create') }}"
                                class="text-gray-900 load bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                                <span class="far w-6 text-center fa-file-invoice"></span>
                                <span> Facturar</span>
                            </a>
                        </div>
                    @endif
                @endcan


            </x-slot>
        </x-actions>
    </div>
</nav>
