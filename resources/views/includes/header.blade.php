<nav class="bg-white border-gray-200 px-2   sm:px-4 py-2.5 rounded  flex flex-row justify-between items-center z-50" >

        <x-actions>
            <x-slot name="content">

                @can('Crear Usuarios')
                    <span class="float-element">
                        <livewire:users.create-user />
                    </span>
                @endcan

                @can('Crear Negocios')
                    <span class="float-element ">
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
                                    class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center flex items-center  w-full">
                                    <span class="far w-6 text-center fa-file-invoice"></span>
                                    <span> Facturas</span>
                                </a>
                            </div>
                        @endcan
                    @else
                        <div>
                            <a href="{{ route('invoices.create') }}"
                                class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center flex items-center  w-full0">
                                <span class="far w-6 text-center fa-file-invoice mr-2"></span>
                                <span> Facturar</span>
                            </a>
                        </div>
                    @endif
                @endcan

                    <div class="  mr-3 lg:mr-0 w-56 z-50">
                        <livewire:general.search-field />
                    </div>
            </x-slot>
        </x-actions>




</nav>
