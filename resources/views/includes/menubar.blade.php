<aside class=" h-full   hidden md:block " aria-label="Sidebar">
    <div class="h-full py-4 px-3 pl-4 bg-gray-50 rounded dark:bg-gray-800">
        <ul class=" h-full space-y-0 flex justify-between space-x-4">
            <div>
                <li>


                    <x-dropdown alignmentClasses="left">
                        <x-slot name="trigger">
                            <div
                                class="uppercase flex items-center p-2 text-base font-bold cursor-pointer  rounded-lg  hover:bg-gray-200  ">
                                <div class="h-8 w-8 rounded-full shadow-sm bg-cover bg-center "
                                    style="background-image: url({{ auth()->user()->avatar }}); min-width:2rem; min-height:2rem">
                                </div>
                                <span
                                    class="ml-3 px-4 w-full overflow-hidden overflow-ellipsis whitespace-nowrap">{{ auth()->user()->fullname }}</span>
                            </div>
                        </x-slot>
                        <x-slot name="content">
                            <x-side-link routeName='settings.index' icon='fas w-10 text-center fa-cogs' text='Ajustes'
                                activeRoute="settings.*" />
                            @if (auth()->user()->places->count() > 1)
                                <div class="py-4 px-4">
                                    <livewire:general.toggle-place />
                                </div>
                            @endif
                            <x-side-link routeName='prueba' icon='far w-10 text-center fa-user-tie fa-lg' text='Prueba'
                            activeRoute="prueba.*" scope="" />
                            <form action="{{ route('auth.logout') }}" method="POST">
                                @csrf
                                <x-button class=" bg-transparent text-black flex space-x-3 items-center">
                                    <span class="far fa-sign-out-alt  text-xl"></span>
                                    <span> Cerrar sesión</span>
                                </x-button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </li>

            </div>

            <ul class="flex space-x-4">
                <x-dropitem text="Contactos" icon="far fa-users" :routes="['users.*', 'clients.*', 'providers.*']">
                    <x-side-link routeName='users.index' icon='far w-10 text-center fa-user-tie fa-lg' text='Usuarios'
                        activeRoute="users.*" scope="Usuarios" />
                    <x-side-link routeName='clients.index' icon='far w-10 text-center fa-users fa-lg' text='Clientes'
                        activeRoute="clients.*" scope="Clientes" />
                    <x-side-link routeName='users.index' icon='far w-10 text-center fa-user-tag fa-lg'
                        text='Proveedores' activeRoute="home.*" scope="Proveedores" />
                </x-dropitem>

                <x-dropitem text="Facturación" icon="far fa-copy" :routes="['invoices.*', 'orders']">
                    <x-side-link routeName='invoices.index' icon='far w-10 text-center fa-file-invoice-dollar fa-lg'
                        text='Facturas' activeRoute="invoices.*" scope="Facturas" />
                    <x-side-link routeName='users.index' icon='far w-10 text-center fa-file-exclamation fa-lg'
                        text='Cotizaciones' activeRoute="home.*" scope="Cotizaciones" />
                    <x-side-link routeName='orders' icon='far w-10 text-center fa-copy fa-lg' text='Pedidos'
                        activeRoute="orders" scope="Pedidos" />
                </x-dropitem>

                <x-dropitem text="Inventario" icon=" far fa-cabinet-filing" :routes="['products.*', 'recursos.*', 'Procesos.*']">
                    <x-side-link routeName='products.index' icon='far w-10 text-center fa-layer-group fa-lg'
                        text='Productos' activeRoute="products.*" scope="Productos" />
                    <x-side-link routeName='recursos.index' icon='far w-10 text-center fa-warehouse-alt fa-lg'
                        text='Recursos' activeRoute="recursos.*" scope="Recursos" />
                    <x-side-link routeName='procesos.index' icon='far w-10 text-center fa-copy fa-lg' text='Procesos'
                        activeRoute="procesos.*" scope="Procesos" />
                </x-dropitem>

                <x-dropitem text="Informes" icon="far fa-file-alt">
                    <x-side-link routeName='invoices.index' icon='far w-10 text-center fa-file-chart-pie fa-lg'
                        text='Estadísticas' activeRoute="invoices.*" scope="Estadísticas" />
                    <x-side-link routeName='users.index' icon='far w-10 text-center fa-file-chart-line fa-lg'
                        text='Reportes' activeRoute="home.*" scope="Reportes" />
                    <x-side-link routeName='users.index' icon='far w-10 text-center fa-download fa-lg'
                        text='Exportaciones' activeRoute="home.*" scope="Reportes" />
                 
                </x-dropitem>

                <x-dropitem text="Finanzas" icon="far fa-wallet">
                    <x-side-link routeName='invoices.index' icon='far w-10 text-center fa-chart-line fa-lg'
                        text='Ingresos' activeRoute="invoices.*" scope="Ingresos" />
                    <x-side-link routeName='users.index' icon='far w-10 text-center fa-chart-line-down fa-lg'
                        text='Gastos' activeRoute="home.*" scope="Gastos" />
                        <x-side-link routeName='reports.general_daily' icon='far w-10 text-center fa-calendar-day fa-lg'
                        text='Diario General' activeRoute="home.*" scope="Reportes" />
                    <x-side-link routeName='reports.general_mayor' icon='far w-10 text-center fa-calendar-alt fa-lg'
                        text='Balance General' activeRoute="home.*" scope="Reportes" />
                </x-dropitem>
                @can('Crear Permisos-')
                    <x-side-link routeName='telescope' icon='far w-10 text-center fa-chart-line-down fa-lg' text='Telescope'
                        activeRoute="home.*" />
                @endcan
                
            </ul>
        </ul>
    </div>
</aside>
