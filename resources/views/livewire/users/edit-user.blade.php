<div>
    <x-modal maxWidth="max-w-4xl">
        <x-slot name="title">
            <span> Editar registro</span>
        </x-slot>
        <x-slot name="button">
            <span data-tooltip-target="editId{{ $user['id'] }}"
                class="far fa-pen-square fa-xl text-green-600"></span>
            <x-tooltip id="editId{{ $user['id'] }}">Editar registro</x-tooltip>
        </x-slot>
        <form wire:submit.prevent="updateUser">
            <div class="flex py-4 justify-end">
                <x-toggle id="logueable{{ $user['id'] }}" label="Logueable" value="yes" wire:model="loggeable" ></x-toggle>
            </div>
            <div class="  pb-6 flex items-center space-x-3">
                <div class="w-full">
                    <x-input label="Primer nombre" id="name{{ $user['id'] }}" wire:model.defer="user.name" />
                    <x-input-error for="user.name"></x-input-error>
                </div>
                <div class="w-full">
                    <x-input label="Apellidos" id="lastname{{ $user['id'] }}" wire:model.defer="user.lastname" />
                    <x-input-error for="user.lastname"></x-input-error>
                </div>
            </div>
            <div class="  pb-6 flex items-end space-x-3">
                <div class="w-full">
                    <x-input label="Correo Electrónico" id="email{{ $user['id'] }}" type="email"
                        wire:model.defer="user.email" />
                    <x-input-error for="user.email"></x-input-error>
                </div>
                <div class="w-full">
                    <x-input label="No. Teléfono" id="phone{{ $user['id'] }}" wire:model.defer="user.phone" />
                    <x-input-error for="user.phone"></x-input-error>
                </div>
            </div>
            <div class="   flex items-end space-x-3">
                <div class="w-full">
                    <label for="avatar{{ $user['id'] }}" class="flex items-center space-x-4 pb-2 cursor-pointer">
                        <span class="fas fa-image text-xl"></span>
                        <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen/Avatar</span>

                        <input wire:model="avatar" type="file" class="hidden" name="avatar"
                            id="avatar{{ $user['id'] }}" accept="image/png, image/gif, image/jpeg">
                    </label>
                    <x-input-error for="avatar"></x-input-error>
                </div>
                @can('Asignar Roles')
                    <div class="w-full">
                        <label for="form.id">Rol de usuario</label>
                        <div class="w-full py-2"></div>
                        <x-select id="role{{ $user['id'] }}" wire:model.defer="role" class="">
                            <option value=""></option>
                            
                            @foreach ($roles as $name)
                                <option value="{{ $name }}">{{ preg_replace('/[0-9]+/', '', $name) }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="role"></x-input-error>
                    </div>
                    <hr>
                @else
                    <input type="hidden" name="role" wire:model.defer="role">
                @endcan
            </div>



            <div class="py-3 flex justify-between items-center">
                <div>
                    @if ($photo_path)
                        <label for="avatar{{ $user['id'] }}">
                            <div for="avatar{{ $user['id'] }}" class="w-12 h-12 rounded-full bg-cover cursor-pointer"
                                style="background-image: url({{ $avatar->temporaryUrl() }})">
                            </div>
                        </label>
                    @else
                        <label for="avatar{{ $user['id'] }}">
                            <div for="avatar{{ $user['id'] }}" class="w-12 h-12 rounded-full bg-cover cursor-pointer"
                                style="background-image: url({{optional( $user['image'])['path'] }})">
                            </div>
                        </label>
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
