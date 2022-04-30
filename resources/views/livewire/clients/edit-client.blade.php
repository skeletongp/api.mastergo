<div>
    <x-modal>
        <x-slot name="title">
            <span> Editar registro</span>
        </x-slot>
        <x-slot name="button">
            <span data-tooltip-target="editId{{$client->id}}"
            data-tooltip-style="light" class="far fa-pen-square fa-xl text-green-600"></span>
            <x-tooltip id="editId{{$client->id}}">Editar registro</x-tooltip>
        </x-slot>
        <form wire:submit.prevent="updateClient">
            <div class="  pb-6 flex items-end space-x-3">
                <div class="w-full">
                    <x-input label="Primer nombre" id="client.{{$client->id}}name" wire:model.defer="client.name" />
                    <x-input-error for="client.name" />
                </div>
                <div class="w-full">
                    <x-input label="Apellidos" id="client.{{$client->id}}lastname" wire:model.defer="client.lastname" />
                    <x-input-error for="client.lastname" />
                </div>
            </div>
            <div class="  pb-6 flex items-end space-x-3">
                <div class="w-full">
                    <x-input label="Correo Electrónico" id="client.{{$client->id}}email" type="email"
                        wire:model.defer="client.email" />
                    <x-input-error for="client.email" />
                </div>
            </div>
            <div class="  pb-6 flex items-end space-x-3">
                <div class="w-full">
                    <x-input label="Dirección" id="client.{{$client->id}}address" wire:model.defer="client.address" />
                    <x-input-error for="client.address" />
                </div>

            </div>
           
            <div class="  pb-6 flex items-end space-x-3">
                <div class="w-full">
                    <x-input label="RNC/Cédula" id="client.{{$client->id}}RNC" type="number" wire:model.defer="client.RNC" />
                    <x-input-error for="client.RNC" />
                </div>
                @can('Asignar Créditos')
                    <div class="w-full">
                        <x-input label="Crédito" type="number" id="client.{{$client->id}}limit" wire:model.defer="client.limit" />
                        <x-input-error for="client.limit" />
                    </div>
                @else
                    <input type="hidden" name="client.limit" wiere.model="client.limit" x-bind:value="0.00" id="client.{{$client->id}}limit">
                @endcan
            </div>
            <div class="  pb-6 flex items-end space-y-0 space-x-3">
                <div class="w-full">
                    <x-input label="No. Teléfono" id="client.{{$client->id}}phone" wire:model.defer="client.phone" />
                    <x-input-error for="client.phone" />
                </div>
                
            </div>
            <div class="    pb-6 ">
                <div class="w-full">
                    <label for="client_avatar" class="flex items-center space-x-4 pb-4 cursor-pointer">
                        <span class="fas fa-image text-xl"></span>
                        <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen/Avatar</span>
                        @if ($avatar)
                            <span class=" text-sm shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño:
                                {{ formatNumber($avatar->getSize() / 1024) }} KB</span>
                        @endif
                        <input wire:model="avatar" type="file" class="hidden" name="avatar"
                            id="client_avatar" accept="image/png, image/gif, image/jpeg">
                    </label>
                    <hr>
                    <x-input-error for="avatar" />
                </div>
                <div class="py-3 flex justify-between items-center">
                    <div>
                        @if ($avatar)
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
    </x-modal>

</div>
