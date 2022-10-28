<div class="w-full flex  items-start" x-data="{showOptions: false}" x-cloak>
   
    <div class="  sticky top-24 pt-8 " >
        <div class=" py-2 px-4" @click="showOptions= ! showOptions">
            <span class="fas fa-bars right-0">
            </span>
        </div>
        <div :class="showOptions?'translate-x-0':'-translate-x-[10rem] lg:translate-x-0'"
            class=" lg:w-[16rem] max-w-[16rem] text-xs lg:text-lg font-medium text-gray-900  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-300 ease-linear ">
            <div aria-current="true"
                class="block w-full px-4 py-2 pb-3 text-gray-800 bg-gray-100  rounded-tl-lg cursor-pointer dark:bg-gray-800 dark:border-gray- text-sm lg:text-xl uppercase text-center font-bold ">
                Configuración
            </div>
            <div class="w-full py-4">
                <hr>
            </div>
            <div wire:click="changeView('settings.setting-user')" id="divAccount"
                class="flex flex-row items-center space-x-2 relative w-full px-2 lg:px-4 my-2 py-1 lg:py-3 overflow-hidden overflow-ellipsis whitespace-nowrap cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'settings.setting-user' ? 'bg-blue-100' : '' }}">
                <span class="far fa-user-cog text-xs lg:text-xl w-4 lg:w-8 text-center"></span>
                <span class=" text-xs lg:text-lg">Ajustes de la cuenta</span>
                <hr>
            </div>
            @can('Ver Negocios')
                <div wire:click="changeView('settings.setting-store')" id="divStore"
                    class="flex flex-row items-center space-x-2 relative w-full px-2 lg:px-4 my-2 py-1 lg:py-3 overflow-hidden overflow-ellipsis whitespace-nowrap cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'settings.setting-store' ? 'bg-blue-100' : '' }}">
                    <span class="far fa-building text-xs lg:text-xl w-4 lg:w-8 text-center"></span>
                    <span class=" text-xs lg:text-lg">Datos de la empresa</span>
                </div>
                <hr>
            @endcan
            @can('Crear Roles')
                <div wire:click="changeView('settings.setting-role')" id="divRole"
                    class="flex flex-row items-center space-x-2 relative w-full px-2 lg:px-4 my-2 py-1 lg:py-3 overflow-hidden overflow-ellipsis whitespace-nowrap cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'settings.setting-role' ? 'bg-blue-100' : '' }}">
                    <span class="far fa-user-shield text-xs lg:text-xl w-4 lg:w-8 text-center"></span>
                    <span class=" text-xs lg:text-lg">Roles y Permisos</span>
                </div>
                <hr>
            @endcan
            @can('Crear Sucursales')
                <div wire:click="changeView('settings.setting-place')" id="divPlace"
                    class="flex flex-row items-center space-x-2 relative w-full px-2 lg:px-4 my-2 py-1 lg:py-3 overflow-hidden overflow-ellipsis whitespace-nowrap cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'settings.setting-place' ? 'bg-blue-100' : '' }}">
                    <span class="far fa-store-alt text-xs lg:text-xl w-4 lg:w-8 text-center"></span>
                    <span class=" text-xs lg:text-lg">Sucursales</span>
                </div>
                <hr>
            @endcan
            @can('Crear Unidades')
                <div wire:click="changeView('settings.setting-measure')" id="divUnit"
                    class="flex flex-row items-center space-x-2 relative w-full px-2 lg:px-4 my-2 py-1 lg:py-3 overflow-hidden overflow-ellipsis whitespace-nowrap cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'settings.setting-measure' ? 'bg-blue-100' : '' }}">
                    <span class="far fa-weight text-xs lg:text-xl w-4 lg:w-8 text-center"></span>
                    <span class=" text-xs lg:text-lg">Unidades y Medidas</span>
                </div>
                <hr>
            @endcan
            @can('Crear Comprobantes')
                <div wire:click="changeView('settings.setting-comprobante')" id="divComprobante"
                    class="flex flex-row items-center space-x-2 relative w-full px-2 lg:px-4 my-2 py-1 lg:py-3 overflow-hidden overflow-ellipsis whitespace-nowrap cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'settings.setting-comprobante' ? 'bg-blue-100' : '' }}">
                    <span class="far fa-file-invoice-dollar text-xs lg:text-xl w-4 lg:w-8 text-center"></span>
                    <span class=" text-xs lg:text-lg">Comprobantes</span>
                </div>
                <hr>
            @endcan

            @can('Crear Bancos')
                <div wire:click="changeView('settings.setting-preference')" id="divPreference"
                    class="flex flex-row items-center space-x-2 relative w-full px-2 lg:px-4 my-2 py-1 lg:py-3 overflow-hidden overflow-ellipsis whitespace-nowrap cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $componentName == 'settings.setting-preference' ? 'bg-blue-100' : '' }}">
                    <span class="far fa-sliders-h text-xs lg:text-xl w-4 lg:w-8 text-center"></span>
                    <span class=" text-xs lg:text-lg">Preferencias</span>
                </div>
                <hr>
            @endcan
        </div>
    </div>
    <div class="w-full h-full relative  p-4" x-data="{ open: true }">
        <button
            class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
            wire:loading>
            <x-loading></x-loading>
        </button>
        @switch($componentName)
            @case('settings.setting-user')
                @livewire($componentName)
            @break

            @case('settings.setting-store')
                @livewire($componentName)
            @break

            @case('settings.setting-role')
                @livewire($componentName)
            @break

            @case('settings.setting-place')
                @livewire($componentName)
            @break

            @case('settings.setting-measure')
                @livewire($componentName)
            @break

            @case('settings.setting-comprobante')
                @livewire($componentName)
            @break

            @case('settings.setting-preference')
                @livewire($componentName)
            @break

            @default
        @endswitch
    </div>
    @push('js')
        <script>
            function searchPrinter() {
                Impresora.getImpresoras()
                    .then(listaDeImpresoras => {
                        Livewire.emit('setPrinters', listaDeImpresoras)

                    });

            }
        </script>
    @endpush
</div>
