<div>
    <x-modal :fitV="true" maxWidth="max-w-2xl" minHeight="min-h-[65vh]" :listenOpen="true">

        <x-slot name="title">
            <span> Editar registro</span>
        </x-slot>
        <x-slot name="button">
            <span data-tooltip-target="editId{{$client_id}}"
            class="far fa-pen text-green-600"></span>
            <x-tooltip id="editId{{$client_id}}">Editar registro</x-tooltip>
        </x-slot>
        <form wire:submit.prevent="updateClient" class="space-y-8 pt-12 relative">

            <div class="absolute top-0 right-2">
                <x-base-select label="Tipo" id="client.{{$client_id}}special" wire:model="client.special">
                    <option value="0">Normal</option>
                    <option value="1">Especial</option>
                </x-base-select>

            </div>
            <div class="   flex items-end space-x-3">
                <div class="w-full">
                    <x-input label="Nombre" id="client.{{$client_id}}name" wire:model.defer="client.name" />
                    <x-input-error for="client.name" />
                </div>
                <div class="w-full">
                    <x-input label="Correo Electrónico" id="client.{{$client_id}}email" type="email"
                        wire:model.defer="client.email" />
                    <x-input-error for="client.email" />
                </div>
            </div>
            <div class="   flex items-end space-x-3">

                <div class="w-full">
                    <x-input label="Dirección" id="client.{{$client_id}}address" wire:model.defer="client.address" />
                    <x-input-error for="client.address" />
                </div>

            </div>


            <div class="   flex items-end space-x-3">
                <div class="w-full max-w-sm">
                    <x-input label="RNC/Cédula" id="client.{{$client_id}}RNC" type="text" wire:model.defer="client.rnc" />
                    <x-input-error for="client.rnc" />
                </div>
                @can('Asignar Créditos')
                    <div class="w-full max-w-sm">
                        <x-input label="Crédito" type="number" id="client.{{$client_id}}limit" wire:model.defer="client.limit" />
                        <x-input-error for="client.limit" />
                    </div>
                @else
                    <input type="hidden" name="client.limit" wiere.model="client.limit" x-bind:value="0.00" id="client.{{$client_id}}limit">
                @endcan
                <div class="w-full max-w-sm">
                    <x-input label="No. Teléfono" type="tel" id="client.{{$client_id}}phone" wire:model.defer="client.phone" />
                    <x-input-error for="client.phone" />
                </div>
            </div>

            <div class="     ">
                <div class="w-full">
                    <label for="client_avatar{{$client_id}}" class="flex items-center space-x-4 pb-4 cursor-pointer">
                        <span class="fas fa-image text-xl"></span>
                        <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen/Avatar</span>
                        @if ($avatar)
                            <span class=" text-sm shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño:
                                {{ formatNumber($avatar->getSize() / 1024) }} KB</span>
                        @endif
                        <input wire:model="avatar" type="file" class="hidden" name="avatar"
                            id="client_avatar{{$client_id}}" accept="image/png, image/gif, image/jpeg">
                    </label>
                    <hr>
                    <x-input-error for="avatar" />
                </div>
                <div class="py-3 flex justify-between items-center">
                    <div>

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
