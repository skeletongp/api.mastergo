
<div>
    <x-modal :fitVerticalContainer="false">
        <x-slot name="title">
            <span> Nueva Empresa</span>
        </x-slot>
        <x-slot name="button">
                <span class="far w-6 text-center fa-plus mr-2"></span>
                <span> Negocio</span>
        </x-slot>
        <div>
            <form action="" class="p-2 w-full" wire:submit.prevent="createStore">
                <div class="w-full space-y-6  flex flex-col items-center">
                    {{-- Área de la foto de perfil --}}
                    <div class="mx-4">
                        <x-upload-progress>
                            <input wire:model="logo" type="file" class="hidden" name="logo" id="logoSet2"
                                accept="image/png, image/gif, image/jpeg">
                        </x-upload-progress>
                        <div class="flex flex-col space-y-6 items-center">
                           
            
                            {{-- Foto actual --}}
                            <label for="logoSet2" class="cursor-pointer">
                                <div class="w-24 h-24 shadow-xl shadow-gray-300 bg-center bg-cover mb-12 pb-4  mx-auto flex items-end justify-center"
                                    style="background-image: url({{ $photo_path ? $logo->temporaryUrl() : '' }})">
                                    <span class="fas fa-upload text-7xl shadow-xl shadow-cyan-300 opacity-5"></span>
                                </div>
                            </label>
                        </div>
                        <x-input-error for="logo"></x-input-error>
                    </div>
            
                 <div class="space-y-12 w-full">
                        {{-- Nombre y apellidos --}}
                    <div class="flex space-x-4 items-center">
                        <div class="w-full">
                            <x-input class="text-2xl" label="Nombre del negocio" wire:model.defer="form.name"
                                id="form.name">
                            </x-input>
                            <x-input-error for="form.name"></x-input-error>
                        </div>
                        <div class="w-full">
                            <x-input class="text-2xl" label="Correo Electrónico" wire:model.defer="form.email"
                                id="form.email"></x-input>
                            <x-input-error for="form.email"></x-input-error>
                        </div>
                    </div>
            
                    {{-- Dirección y teléfono --}}
                    <div class="flex space-x-4 items-center">
                        <div class="w-full">
                            <x-input class="text-2xl" label="Dirección" wire:model.defer="form.address"
                                id="form.address"></x-input>
                            <x-input-error for="form.address"></x-input-error>
                        </div>
                       
                    </div>
                    <div class="flex space-x-4 items-center">
                        <div class="w-full">
                            <x-input type="tel" class="text-2xl " label="No. Teléfono" wire:model.defer="form.phone"
                                id="form.phone">
                            </x-input>
                            <x-input-error for="form.phone"></x-input-error>
                        </div>
                        <div class="w-full">
                            <x-input class="text-2xl" label="RNC/ID" wire:model.defer="form.RNC"
                                id="form.RNC">
                            </x-input>
                            <x-input-error for="form.RNC"></x-input-error>
                        </div>
                       
                    </div>
                    
                    {{-- Botón --}}
                    <div class="flex my-4  items-center justify-end ">
                        <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-700 text-xs"
                            wire:loading.attr="disabled">
                            <div class="animate-spin mr-2" wire:loading wire:target="createStore">
                                <span class="fa fa-spinner ">
                                </span>
                            </div>
                            Guardar
                        </x-button>
                    </div>
                 </div>
                </div>
            </form>
        </div>
    </x-modal>

</div>