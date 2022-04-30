<div class="relative">
    <h1 class="uppercase text-xl text-center font-bold">Actualice los datos de su negocio </h1>
    
    <form action="" class="mt-4 p-2 pt-4 " wire:submit.prevent="updateStore">
        <div class="w-full space-y-6  flex items-center space-x-3">

            {{-- Área de la foto de perfil --}}
            <div class="m-4">
                <x-upload-progress>
                    <input wire:model="logo" type="file" class="hidden" name="logo" id="logoSet"
                        accept="image/png, image/gif, image/jpeg">
                </x-upload-progress>
                <div class="flex flex-col space-y-6 items-center">
                    {{-- Foto anterior --}}
                    

                    {{-- Foto actual --}}
                    <label for="logoSet" class="cursor-pointer">
                        <div class="w-44 h-44 bg-center bg-cover mb-12 pb-4  mx-auto flex items-end justify-center"
                            style="background-image: url({{ $logo ? $logo->temporaryUrl() : $store->logo }})">
                            <span class="fas fa-image text-7xl shadow-xl shadow-cyan-300 opacity-5"></span>
                        </div>
                    </label>
                </div>
                <x-input-error for="logo"></x-input-error>
            </div>

         <div class="space-y-12 w-full">
                {{-- Nombre y apellidos --}}
            <div class="flex space-x-4 items-center">
                <div class="w-full">
                    <x-input class="text-2xl" label="Nombre del negocio" wire:model.defer="store.name"
                        id="store.name">
                    </x-input>
                    <x-input-error for="store.name"></x-input-error>
                </div>
                <div class="w-full">
                    <x-input class="text-2xl" label="Correo Electrónico" wire:model.defer="store.email"
                        id="store.email"></x-input>
                    <x-input-error for="store.email"></x-input-error>
                </div>
            </div>

            {{-- Correo y teléfono --}}
            <div class="flex space-x-4 items-center">
                <div class="w-full">
                    <x-input class="text-2xl" label="Dirección" wire:model.defer="store.address"
                        id="store.address"></x-input>
                    <x-input-error for="store.address"></x-input-error>
                </div>
                
            </div>
            <div class="flex space-x-4 items-center">
                <div class="w-full">
                    <x-input type="tel" class="text-2xl" label="No. Teléfono" wire:model.defer="store.phone"
                    id="store.phone">
                </x-input>
                <x-input-error for="store.phone"></x-input-error>
            </div>
            <div class="w-full">
                <x-input class="text-2xl" label="RNC/ID" wire:model.defer="store.RNC"
                    id="store.RNC"></x-input>
                <x-input-error for="store.RNC"></x-input-error>
            </div>
            </div>
            {{-- Botón --}}
            <div class="flex my-4  items-center justify-end ">
                <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-700 text-xs"
                    wire:loading.attr="disabled">
                    <div class="animate-spin mr-2" wire:loading wire:target="updateStore">
                        <span class="fa fa-spinner ">
                        </span>
                    </div>
                    Actualizar
                </x-button>
            </div>
         </div>
        </div>
    </form>
    <div class="py-4">
        <hr>
        <livewire:settings.scopes.scope-index />
    </div>

   
</div>
