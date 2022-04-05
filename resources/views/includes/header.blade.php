<nav
    class="bg-white border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-800 flex flex-row justify-between items-center ">
    <div class="flex space-x-2 uppercase items-center">
        <a href="{{ route('home') }}">
            <div class="h-12 w-12 bg-cover bg-center rounded-full"
                style="background-image: url({{ auth()->user()->store->logo }})"></div>
        </a>
        @can('Crear Negocios')
            <livewire:general.toggle-store :label="false" :title="true" />
        @else
            <a href="{{ route('home') }}">
                <span
                    class="self-center text-xl xl:text-2xl font-bold whitespace-nowrap dark:text-white">{{ auth()->user()->store->name }}
                    | {{ env('APP_NAME') }}</span>
            </a>
        @endcan

    </div>
    <div class="container flex flex-wrap justify-end items-end mx-auto ">

        <div class="hidden md:flex md:order-2 ml-4">
            <div class=" hidden 2xl:block  mr-3 md:mr-0 w-56">
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
                    <div>
                        <a href="{{ route('products.create') }}"
                            class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                            <span class="far w-6 text-center fa-plus"></span>
                            <span> Producto</span>
                        </a>
                    </div>
                @endcan
                @can('Crear Facturas')
                    <div>
                        <a href="{{ route('products.create') }}"
                            class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-2.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                            <span class="far w-6 text-center fa-plus"></span>
                            <span> Factura</span>
                        </a>
                    </div>
                @endcan
            </x-slot>

        </x-actions>
    </div>
</nav>
