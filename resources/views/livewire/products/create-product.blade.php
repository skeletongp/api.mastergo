<div>
    <x-modal maxWidth="max-w-7xl mx-4" :fitV="false" :listenOpen="true">
        <x-slot name="title">
            <div class="flex justify-between items-center w-full">
                <span> Nuevo Producto {{array_key_exists('name',$form)?$form['name']:''}}</span>
            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-plus mr-2"></span>
            <span> Producto</span>
        </x-slot>
        <div class="relative">
            <div class="p-2 px-4 pb-4 shadow-lg ">
                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 ">
                    <ul class="flex justify-center flex-wrap -mb-px">
                        <li class="mr-2">
                            <span wire:click="$set('activeTab','infoproduct')"
                                class="cursor-pointer text-base inline-block p-4  rounded-t-lg border-b-2 {{ $activeTab == 'infoproduct' ? 'text-blue-600  border-blue-600 active' : 'border-transparent' }} ">Datos
                                Generales</span>
                        </li>
                        <li class="mr-2">
                            <span wire:click="$set('activeTab','unitsproduct')"
                                class="cursor-pointer text-base inline-block p-4  rounded-t-lg border-b-2 {{ $activeTab == 'unitsproduct' ? 'text-blue-600  border-blue-600 active' : 'border-transparent' }} ">Medidas
                                y Precios</span>
                        </li>
                    </ul>
                </div>
                <div class="pt-2 h-full min-h-[26rem] relative ">
                    @include("livewire.products.includes.$activeTab")
                </div>
            </div>
        </div>
    </x-modal>

</div>
