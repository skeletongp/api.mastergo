<div>
    <x-modal>
        <x-slot name="title">
            <span> Editar registro</span>
        </x-slot>
        <x-slot name="button">
            <span class="fas fa-pen-to-square text-green-600"></span>
        </x-slot>
        <form wire:submit.prevent="updateUser">
            <div class="  pb-6 flex items-center space-x-3">
                <div class="w-full">
                    <x-input label="Primer nombre" id="name{{ $user->id }}" wire:model.defer="user.name" />
                    <x-input-error for="user.name"></x-input-error>
                </div>
                <div class="w-full">
                    <x-input label="Apellidos" id="lastname{{ $user->id }}" wire:model.defer="user.lastname" />
                    <x-input-error for="user.lastname"></x-input-error>
                </div>
            </div>
            <div class="  pb-6 flex items-end space-x-3">
                <div class="w-2/3">
                    <x-input label="Correo Electrónico" id="email{{ $user->id }}" type="email"
                        wire:model.defer="user.email" />
                    <x-input-error for="user.email"></x-input-error>
                </div>
                <div class="w-1/3">
                    <x-input label="No. Teléfono" id="phone{{ $user->id }}" wire:model.defer="user.phone" />
                    <x-input-error for="user.phone"></x-input-error>
                </div>
            </div>
            <div class="  pb-6 ">
                <div class="w-full">
                    <label for="avatar{{ $user->id }}" class="flex items-center space-x-4 pb-2 cursor-pointer">
                        <span class="fas fa-image text-xl"></span>
                        <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen/Avatar</span>
                        @if ($avatar)
                            <span class="shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño del archivo:
                                {{ Universal::formatNumber($avatar->getSize() / 1024) }} KB</span>
                        @endif
                        <input wire:model="avatar" type="file" class="hidden" name="avatar"
                            id="avatar{{ $user->id }}" accept="image/png, image/gif, image/jpeg">
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
                        <x-button class="space-x-2 z-50 text-sm flex items-center" wire:target="avatar" wire:loading>
                            <div class="animate-spin">
                                <span class="fa fa-spinner ">
                                </span>
                            </div>
                            <h1>Procesando</h1>
                        </x-button>
                    </div>
                </div>
                <x-button wire:loading.attr="disabled" wire:loading.class="text-red-100">Guardar</x-button>
            </div>
        </form>
    </x-modal>

</div>
