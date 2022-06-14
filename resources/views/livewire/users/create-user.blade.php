
<div>
    <x-modal :fitV="false">
        <x-slot name="title">
            <span> Nuevo Usuario</span>
        </x-slot>
        <x-slot name="button">
                <span class="far w-6 text-center fa-user-plus mr-2"></span>
                <span> Usuario</span>
        </x-slot>
        <div>
            <form wire:submit.prevent="createUser">
                <div class="pb-6 flex justify-end">
                    <x-toggle label="Usuario Logueable" value="true" id="usr.loggeable" wire:model="loggeable"></x-toggle>
                </div>
                <div class="  pb-6 flex items-center space-x-3">
                    <div class="w-full">
                        <x-input label="Primer nombre" id="name" wire:model.defer="form.name" />
                        <x-input-error for="form.name" />
                    </div>
                    <div class="w-full">
                        <x-input label="Apellidos" id="lastname" wire:model.defer="form.lastname" />
                        <x-input-error for="form.lastname" />
                    </div>
                </div>
                <div class="  pb-6 flex items-center space-x-3">
                    <div class="w-full">
                        <x-input label="Correo Electrónico" id="email" type="email" wire:model.defer="form.email" />
                        <x-input-error for="form.email" />
                    </div>
                </div>
                <div class="  pb-6 flex items-center space-x-3">
                    <div class="w-1/2">
                        <x-input autocomplete="username" label="Nombre de usuario" id="username" wire:model.defer="form.username" />
                        <x-input-error for="form.username" />
                    </div>
                    <div class="w-1/2">
                        <x-input label="Contraseña" autocomplete="new-password" type="password" id="new-password"
                            wire:model.defer="form.password" />
                        <x-input-error for="form.password" />
                    </div>
                </div>
                <div class="  pb-6 flex items-end space-y-0 space-x-3">
                    <div class="w-full">
                        <x-input type="tel" label="No. Teléfono" id="phone" wire:model.defer="form.phone" />
                        <x-input-error for="form.phone" />
                    </div>
                    <div class="w-full pb-0 select2-div">
                        <label for="role">Rol de usuario</label>
                        <x-select id="frole" wire:model.defer="role" class=" select2">
                            <option value=""></option>
                            @foreach ($roles as $name)
                                <option value="{{ $name }}">{{ preg_replace('/[0-9]+/', '',  $name); }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="frole" />
                    </div>
                </div>
                <div class="  pb-6 flex items-end space-y-0 space-x-3">
                    <div class="w-full pb-0">
                        <label for="place_id">Sucursal Predeterminada</label>
                        <x-select id="place_id" wire:model.defer="form.place_id" class="select2">
                            @foreach ($places as $id=> $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="form.place_id" />
                    </div>
                </div>
                <input type="hidden" name="store_id" wire:model="store_id">
                <div class="    pb-6 ">
                    <div class="w-full">
                        <label for="avatar" class="flex items-center space-x-4 pb-4 cursor-pointer">
                            <span class="fas fa-image text-xl"></span>
                            <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen/Avatar</span>
                            @if ($photo_path)
                                <span class=" text-sm shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño:
                                    {{ formatNumber($avatar->getSize() / 1024) }} KB</span>
                            @endif
                            <input wire:model="avatar" type="file" class="hidden" name="avatar" id="avatar"
                                accept="image/png, image/gif, image/jpeg">
                        </label>
                        <hr>
                        <x-input-error for="avatar" />
                    </div>
                    <div class="py-3 flex justify-between items-center">
                        <div>
                            @if ($photo_path)
                                <div class="w-12 h-12 rounded-full bg-cover"
                                    style="background-image: url({{ $avatar->temporaryUrl() }})">
                                </div>
                            @endif
                            <div class="">
                                <x-button class="space-x-2 z-50 text-sm flex items-center" wire:target="avatar"
                                    wire:loading>
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
                </div>
            </form>
        </div>
    </x-modal>

</div>

