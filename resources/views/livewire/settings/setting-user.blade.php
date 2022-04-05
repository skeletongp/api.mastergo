    <div class="w-full max-w-4xl mx-auto px-4 py-8 shadow-lg bg-gray-50 ">
        <h1 class="uppercase text-xl text-center font-bold">Actualice sus datos de usuario</h1>
      
        {{-- Detalles del perfil --}}

        <form action="" class="mt-4 p-2 pt-4 " wire:submit.prevent="updateUser">
            <div class="w-full space-y-6  flex items-center space-x-3">

                {{-- Área de la foto de perfil --}}
                <div class="w-full  max-w-xs">
                    <x-upload-progress>
                        <input wire:model="avatar" type="file" class="hidden" name="avatar" id="avatarSet"
                            accept="image/png, image/gif, image/jpeg">
                    </x-upload-progress>
                    <div class="flex flex-col space-y-6 items-center">
                        {{-- Foto anterior --}}
                        @if ($photo_prev)
                            <label for="avatarSet" class="cursor-pointer">
                                <div class="w-48 h-48 bg-center bg-cover mb-12 pb-4 flex items-end justify-center"
                                    style="background-image: url({{ $photo_prev }})">
                                </div>
                            </label>

                            <span class="far fa-angle-double-down text-4xl">
                            </span>
                        @endif

                        {{-- Foto actual --}}
                        <label for="avatarSet" class="cursor-pointer">
                            <div class="w-48 h-48 bg-center bg-cover mb-12 pb-4  mx-auto flex items-end justify-center"
                                style="background-image: url({{ $avatar ? $avatar->temporaryUrl() : $user->avatar }})">
                                <span class="fas fa-image text-7xl shadow-xl shadow-cyan-300 opacity-5"></span>
                            </div>
                        </label>
                    </div>
                    <x-input-error for="avatar"></x-input-error>
                </div>

             <div class="space-y-12 w-full">
                    {{-- Nombre y apellidos --}}
                <div class="flex space-x-4 items-center">
                    <div class="w-full">
                        <x-input class="text-2xl" label="Primer nombre" wire:model.defer="user.name"
                            id="user.name">
                        </x-input>
                        <x-input-error for="user.name"></x-input-error>
                    </div>
                    <div class="w-full">
                        <x-input class="text-2xl" label="Apellidos" wire:model.defer="user.lastname"
                            id="user.lastname"></x-input>
                        <x-input-error for="user.lastname"></x-input-error>
                    </div>
                </div>

                {{-- Correo y teléfono --}}
                <div class="flex space-x-4 items-center">
                    <div class="w-2/3">
                        <x-input class="text-2xl" label="Correo Electrónico" wire:model.defer="user.email"
                            id="user.email"></x-input>
                        <x-input-error for="user.email"></x-input-error>
                    </div>
                    <div class="w-1/3">
                        <x-input class="text-2xl" label="No. Teléfono" wire:model.defer="user.phone"
                            id="user.phone">
                        </x-input>
                        <x-input-error for="user.phone"></x-input-error>
                    </div>
                </div>

                {{-- Botón --}}
                <div class="flex my-4  items-center justify-end ">
                    <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 disabled:text-gray-700 text-xs"
                        wire:loading.attr="disabled">
                        <div class="animate-spin mr-2" wire:loading wire:target="updateUser">
                            <span class="fa fa-spinner ">
                            </span>
                        </div>
                        Actualizar
                    </x-button>
                </div>
             </div>
            </div>
        </form>
        <div class="py-4"></div>
        <hr>
        <div class="py-4 pb-6"></div>
        <form action="" class="max-w-lg ml-auto" wire:submit.prevent="changePassword">
            <div class="flex space-x-4 items-start">
                <div class="w-full">
                    <x-input class="text-2xl" label="Nueva Contraseña" type="password" wire:model.defer="password"
                        id="npassword"></x-input>
                    <x-input-error for="password"></x-input-error>
                </div>
                <div class="w-full">
                    <x-input class="text-2xl" label="Confirme la Contraseña" type="password" wire:model.defer="password_confirmation" id="password_confirmation">
                    </x-input>
                    <x-input-error for="password_confirmation"></x-input-error>
                </div>
            </div>
             {{-- Botón --}}
             <div class="flex my-4  items-center justify-between ">
                <div class=" w-72">
                    <x-input class="text-2xl" label="Contraseña actual" type="password" wire:model.defer="oldPassword"
                        id="oldPassword"></x-input>
                    <x-input-error for="oldPassword"></x-input-error>
                </div>
                <x-button class=" font-bold bg-gray-800 text-white uppercase disabled:bg-gray-200 disabled:text-gray-700 text-xs"
                    wire:loading.attr="disabled">
                    <div class="animate-spin mr-2" wire:loading wire:target="updateUser">
                        <span class="fa fa-spinner ">
                        </span>
                    </div>
                    Guardar
                </x-button>
            </div>
        </form>
    </div>
