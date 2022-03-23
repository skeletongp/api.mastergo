
<div x-cloak x-data="{ open: false }" @click.away="open = false" class=" z-50 mx-auto absolute w-96">
    <div>
        <x-button x-on:click="open= !open">Nuevo Usuario</x-button>
    </div>
    <div x-show="open" x-trasition
        class=" overflow-y-auto overflow-x-hidden  w-full z-50 justify-center items-center md:h-full md:inset-0  mx-auto ">

        <div
            class="relative bg-white rounded-lg min-w-full shadow-sm shadow-blue-500 dark:bg-gray-700 border border-blue-500">

            <div class="flex justify-between items-center py-4 px-6 rounded-t border-b dark:border-gray-600">
                <h3 class="text-base font-semibold text-gray-900 lg:text-xl dark:text-white">
                    Crear usuario </h3>

                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    x-on:click="open=false">
                    <span class="fas fa-times text-red-600"></span>
                </button>
            </div>

            <div class="p-4">
                <form wire:submit.prevent="createUser">
                    <div class="  pb-6 flex items-center space-x-3">
                        <div class="w-full">
                            <x-input label="Primer nombre" id="name" wire:model.defer="form.name" />
                            <x-input-error for="form.name"></x-input-error>
                        </div>
                        <div class="w-full">
                            <x-input label="Apellidos" id="lastname" wire:model.defer="form.lastname" />
                            <x-input-error for="form.lastname"></x-input-error>
                        </div>
                    </div>
                    <div class="  pb-6 flex items-center space-x-3">
                        <div class="w-full">
                            <x-input label="Correo Electrónico" id="email" type="email" wire:model.defer="form.email" />
                            <x-input-error for="form.email"></x-input-error>
                        </div>
                    </div>
                    <div class="  pb-6 flex items-center space-x-3">
                        <div class="w-full">
                            <x-input label="Nombre de usuario" id="username" wire:model.defer="form.username" />
                            <x-input-error for="form.username"></x-input-error>
                        </div>
                        <div class="w-full">
                            <x-input label="Contraseña" autocomplete="current_password" type="password" id="password"
                                wire:model.defer="form.password" />
                            <x-input-error for="form.password"></x-input-error>
                        </div>
                    </div>
                    <div class="  pb-6 flex items-end space-x-3">
                        <div class="w-full">
                            <x-input label="No. Teléfono" id="phone" wire:model.defer="form.phone" />
                            <x-input-error for="form.phone"></x-input-error>
                        </div>
                    </div>
                    <div class="  pb-6 ">
                        <div class="w-full">
                            <label for="avatar" class="flex items-center space-x-4 pb-4 cursor-pointer">
                                <span class="fas fa-image text-xl"></span>
                                <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen/Avatar</span>
                                @if ($avatar)
                                <span class=" text-sm shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño: {{Universal::formatNumber($avatar->getSize()/1024)}} KB</span>
                                @endif
                                <input wire:model="avatar" type="file" class="hidden" name="avatar"
                                id="avatar" accept="image/png, image/gif, image/jpeg">
                            </label>
                        </div>
                        <hr>
                        <x-input-error for="avatar"></x-input-error>
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <div>
                            @if ($avatar)
                                <div class="w-12 h-12 rounded-full bg-cover"
                                    style="background-image: url({{ $avatar->temporaryUrl() }})">
                                </div>
                            @endif
                            <div class="">
                                <x-button class="space-x-2 z-50 text-sm flex items-center"  wire:target="avatar" wire:loading>
                                    <div class="animate-spin">
                                        <span class="fa fa-spinner ">
                                        </span>
                                    </div>
                                    <h1>Procesando</h1>
                                </x-button>
                            </div>
                        </div>
                        <x-button>Guardar</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
