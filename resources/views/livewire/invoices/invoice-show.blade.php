<div class="grid grid-cols-6 gap-4 ">
    <div class="col-span-2">
        <h1 class="text-xl uppercase font-bold  m-4 ">Opciones</h1>
        <div class="p-4 ">
            <x-button class="w-full text-xl bg-gray-200 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                <div class="flex space-x-6 items-center text-lg">
                    <span class="w-6 text-left far fa-user"></span>
                    <span class="text-lg">Cliente</span>
                </div>
            </x-button>
            <x-button class="w-full text-xl bg-gray-100 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                <div class="flex space-x-6 items-center text-lg">
                    <span class="w-6 text-left far fa-layer-group"></span>
                    <span class="text-lg">Productos</span>
                </div>
            </x-button>
            <x-button class="w-full text-xl bg-gray-200 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                <div class="flex space-x-6 items-center text-lg">
                    <span class="w-6 text-left fas fa-user-tie"></span>
                    <span class="text-lg">Vendedor</span>
                </div>
            </x-button>
            <x-button class="w-full text-xl bg-gray-100 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                <div class="flex space-x-6 items-center text-lg">
                    <span class="w-6 text-left far fa-user-tie"></span>
                    <span class="text-lg">Cajero</span>
                </div>
            </x-button>
            <x-button class="w-full text-xl bg-gray-200 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                <div class="flex space-x-6 items-center text-lg">
                    <span class="w-6 text-left far fa-dollar-sign"></span>
                    <span class="text-lg">Pagos</span>
                </div>
            </x-button>
            <x-button class="w-full text-xl bg-gray-100 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                <div class="flex space-x-6 items-center text-lg">
                    <span class="w-6 text-left far fa-paperclip"></span>
                    <span class="text-lg">Adjunto</span>
                </div>
            </x-button>
        </div>
    </div>
    <div class="col-span-4">
        @switch($includeName)
            @case('showclient')
                @include('livewire.invoices.showincludes.'.$includeName)
            @break

        @endswitch
    </div>
</div>
