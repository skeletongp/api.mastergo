<aside class=" h-full    " aria-label="Sidebar">
    <div class="h-full max-w-7xl relative py-4 px-3 pl-4 bg-gray-50 rounded dark:bg-gray-800">
        <div class="absolute lg:hidden top-2 right-2">
            <x-button id="btnToggleMenu">
                <span id="spanToggleMenu" class="fas fa-bars"></span>
            </x-button>
        </div>
        <ul class=" h-full  space-y-0 flex justify-between space-x-4">
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
                            @can('Crear Permisos')
                                <x-side-link routeName='prueba' icon='far w-10 text-center fa-user-tie fa-lg' text='Prueba'
                                    activeRoute="prueba.*" scope="" />
                            @endcan
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

            <ul class=" lg:space-x-4 flex-col lg:flex-row bg-white lg:bg-transparent px-2 py-1 lg:px-0 lg:py-0 absolute lg:relative right-2 top-16 hidden lg:flex lg:top-0"
                id="ulToggleMenu">
                @scopeanny(['Usuarios', 'Clientes', 'Proveedores'])
                    @canany(['Ver Usuarios', 'Ver Clientes', 'Ver Proveedores'])
                        <x-dropitem text="Contactos" icon="far fa-users" :routes="['users.*', 'clients.*', 'providers.*']">
                            @can('Ver Usuarios')
                                <x-side-link routeName='users.index' icon='far w-10 text-center fa-user-tie fa-lg' text='Usuarios'
                                    activeRoute="users.*" scope="Usuarios" />
                            @endcan
                            @can('Ver Clientes')
                                <x-side-link routeName='clients.index' icon='far w-10 text-center fa-users fa-lg' text='Clientes'
                                    activeRoute="clients.*" scope="Clientes" />
                            @endcan
                            @can('Ver Proveedores')
                                <x-side-link routeName='providers.index' icon='far w-10 text-center fa-user-tag fa-lg'
                                    text='Proveedores' activeRoute="poviders.*" scope="Proveedores" />
                            @endcan
                        </x-dropitem>
                    @endcanany
                @endscopeanny

                @scopeanny (['Facturas','Cotizaciones','Pedidos'])
                    @canany(['Cobrar Facturas', 'Ver Facturas', 'Ver Cotizaciones'])
                        <x-dropitem text="Facturación" icon="far fa-copy" :routes="['invoices.*', 'orders']">
                            @can('Ver Facturas')
                                <x-side-link routeName='invoices.index' icon='far w-10 text-center fa-file-invoice-dollar fa-lg'
                                    text='Facturas' activeRoute="invoices.*" scope="Facturas" />
                            @endcan
                            @can('Ver Cotizaciones')
                                <x-side-link routeName='users.index' icon='far w-10 text-center fa-file-exclamation fa-lg'
                                    text='Cotizaciones' activeRoute="home.*" scope="Cotizaciones" />
                            @endcan
                            @can('Cobrar Facturas')
                                <x-side-link routeName='orders' icon='far w-10 text-center fa-copy fa-lg' text='Pedidos'
                                    activeRoute="orders" scope="Pedidos" />
                            @endcan
                        </x-dropitem>
                    @endcanany
                @endscopeanny

                @scopeanny(['Productos', 'Recursos', 'Procesos', 'Gastos'])
                    @canany(['Ver Productos', 'Ver Recursos', 'Ver Procesos', 'Ver Gastos'])
                        <x-dropitem text="Inventario" icon=" far fa-cabinet-filing" :routes="['products.*', 'recursos.*', 'Procesos.*']">
                            @can('Ver Productos')
                                <x-side-link routeName='products.index' icon='far w-10 text-center fa-layer-group fa-lg'
                                    text='Productos' activeRoute="products.*" scope="Productos" />
                            @endcan
                            @canany(['Ver Recursos', 'Crear Recursos', 'Borrar Recursos', 'Editar Recursos'])
                                <x-side-link routeName='recursos.index' icon='far w-10 text-center fa-warehouse-alt fa-lg'
                                    text='Recursos' activeRoute="recursos.*" scope="Recursos" />
                            @endcanany
                            @canany(['Ver Procesos', 'Crear Procesos'])
                                <x-side-link routeName='procesos.index' icon='far w-10 text-center fa-copy fa-lg' text='Procesos'
                                    activeRoute="procesos.*" scope="Procesos" />
                            @endcanany
                            @can('Ver Gastos')
                                <x-side-link routeName='provisions.index' icon='far w-10 text-center fa-calendar-alt fa-lg'
                                    text='Compras' activeRoute="provisions.*" scope="Gastos" />
                            @endcan
                        </x-dropitem>
                    @endcanany
                @endscopeanny

                @scopeanny(['Ingresos', 'Gasto'])
                    @canany(['Ver Cuadre', 'Ver Gastos'])
                        <x-dropitem text="Finanzas" icon="far fa-file-invoice-dollar">

                            @can('Ver Cuadre')
                                <x-side-link routeName='reports.incomes' icon='far w-10 text-center fa-chart-line fa-lg'
                                    text='Ingresos' activeRoute="reports.*" scope="Ingresos" />
                            @endcan
                            @can('Ver Gastos')
                                <x-side-link routeName='reports.outcomes' icon='far w-10 text-center fa-chart-line-down fa-lg'
                                    text='Gastos' activeRoute="reports.*" scope="Gastos" />
                            @endcan
                            @can('Ver Obligaciones')
                                <x-side-link routeName='recurrents.index' icon='far w-10 text-center fa-donate fa-lg'
                                    text='Obligaciones' activeRoute="recurrents.*" scope="Gastos" />
                            @endcan
                            @can('Ver Transacciones')
                                <x-side-link routeName='finances.index' icon='far w-10 text-center fa-hand-holding-usd fa-lg'
                                    text='Cuentas' activeRoute="finances.*" scope="Ingresos" />
                            @endcan

                        </x-dropitem>
                    @endcanany
                @endscopeanny

                @scopeanny (['Reportes','Impuestos'])
                    @canany(['Ver Transacciones', 'Ver Comprobantes', 'Ver Cuadre', 'Ver Catálogo'])
                        <x-dropitem text="Contabilidad" icon="far fa-wallet">
                            @can('Ver Transacciones')
                                <x-side-link routeName='contables.general_daily' icon='far w-10 text-center fa-calendar-day fa-lg'
                                    text='Diario General' activeRoute="home.*" scope="Reportes" />
                            @endcan
                            @can('Ver Transacciones')
                                <x-side-link routeName='contables.general_mayor' icon='far w-10 text-center fa-calendar-alt fa-lg'
                                    text='Balance General' activeRoute="home.*" scope="Reportes" />
                            @endcan
                            @can('Ver Comprobantes')
                                <x-side-link routeName='comprobantes.index' icon='far w-10 text-center fa-receipt fa-lg'
                                    text='Comprobantes' activeRoute="comprobantes.*" scope="Impuestos" />
                            @endcan
                            @can('Ver Cuadre')
                                <x-side-link routeName='cuadres.index' icon='far w-10 text-center fa-chart-bar fa-lg'
                                    text='Cuadre Diario' activeRoute="cuadres.*" scope="Impuestos" />
                            @endcan
                            @can('Ver Cuadre')
                                <x-side-link routeName='contables.results' icon='far w-10 text-center fa-chart-line fa-lg'
                                    text='Estado G/P' activeRoute="contables.*" scope="Impuestos" />
                            @endcan
                            @can('Ver Catálogo')
                                <x-side-link routeName='contables.catalogue' icon='far w-10 text-center fa-list fa-lg'
                                    text='Catalógo de Ctas.' activeRoute="contables.*" scope="Impuestos" />
                            @endcan
                            @can('Ver Catálogo')
                                <x-side-link routeName='contables.report_607' icon='far w-10 text-center fa-list fa-lg'
                                    text='Reporte 607' activeRoute="contables.*" scope="Impuestos" />
                            @endcan
                        </x-dropitem>
                    @endcanany
                @endscopeanny

            </ul>
        </ul>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                $('#btnToggleMenu').click(function() {
                    $('#ulToggleMenu').toggle('translate-x-96');
                    $('#spanToggleMenu').toggleClass('fa-times fa-bars');
                });
            });
        </script>
    @endpush
</aside>
