<div>
    <div class="grid grid-cols-7 gap-4 ">
        <div class="col-span-2">
            <button
                class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
                wire:loading>
                <x-loading></x-loading>
            </button>
            <h1 class="text-xl uppercase font-bold  m-4 ">Detalles de la factura <span class="fas fa-angle-right"></span>
                <span class="text-green-600 fon-bold"> {{ $includeTitle }}</span>
            </h1>
            <div class="p-4 ">
                <x-button wire:click="setIncludeElement('showclient','Cliente')"
                    class="w-full text-xl bg-gray-200 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                    <div class="flex space-x-6 items-center text-lg">
                        <span class="w-6 text-left far fa-user"></span>
                        <span class="text-lg">Cliente</span>
                    </div>
                </x-button>
                <x-button wire:click="setIncludeElement('showproducts','Productos')"
                    class="w-full text-xl bg-gray-100 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                    <div class="flex space-x-6 items-center text-lg">
                        <span class="w-6 text-left far fa-layer-group"></span>
                        <span class="text-lg">Productos</span>
                    </div>
                </x-button>
                <x-button wire:click="setIncludeElement('showseller','Vendedor')"
                    class="w-full text-xl bg-gray-200 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                    <div class="flex space-x-6 items-center text-lg">
                        <span class="w-6 text-left fas fa-user-tie"></span>
                        <span class="text-lg">Vendedor</span>
                    </div>
                </x-button>
                <x-button wire:click="setIncludeElement('showcontable','Cajero')"
                    class="w-full text-xl bg-gray-100 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                    <div class="flex space-x-6 items-center text-lg">
                        <span class="w-6 text-left far fa-user-tie"></span>
                        <span class="text-lg">Cajero</span>
                    </div>
                </x-button>
                <x-button wire:click="setIncludeElement('showpayments','Pagos')"
                    class="w-full text-xl bg-gray-200 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                    <div class="flex space-x-6 items-center text-lg">
                        <span class="w-6 text-left far fa-dollar-sign"></span>
                        <span class="text-lg">Pagos</span>
                    </div>
                </x-button>
                <x-button wire:click="setIncludeElement('showattach','Adjuntos')"
                    class="w-full text-xl bg-gray-100 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                    <div class="flex space-x-6 items-center text-lg">
                        <span class="w-6 text-left far fa-paperclip"></span>
                        <span class="text-lg">Adjunto</span>
                    </div>
                </x-button>
                <x-button wire:click="setIncludeElement('showhistory','Historial')"
                    class="w-full text-xl bg-gray-100 rounded-none text-black hover:text-gray-100 hover:bg-gray-900">
                    <div class="flex space-x-6 items-center text-lg">
                        <span class="w-6 text-left far fa-history"></span>
                        <span class="text-lg">Historial</span>
                    </div>
                </x-button>
            </div>
        </div>
        <div class="col-span-5 flex flex-col justify-center">
            @switch($includeName)
                @case('showclient')
                    @include('livewire.invoices.showincludes.' . $includeName)
                @break

                @case('showproducts')
                    @include('livewire.invoices.showincludes.' . $includeName)
                @break

                @case('showseller')
                    @include('livewire.invoices.showincludes.' . $includeName)
                @break

                @case('showcontable')
                    @include('livewire.invoices.showincludes.' . $includeName)
                @break

                @case('showpayments')
                    @include('livewire.invoices.showincludes.' . $includeName)
                @break

                @case('showattach')
                    @include('livewire.invoices.showincludes.' . $includeName)
                @break

                @case('showhistory')
                    @include('livewire.invoices.showincludes.' . $includeName)
                @break
            @endswitch
        </div>
    </div>

</div>
