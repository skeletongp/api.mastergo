<div>
    <x-modal :fitV='false' maxWidth="max-w-4xl" :listenOpen="true">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Nuevo Cliente</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-user-plus mr-2"></span>
            <span> Cliente</span>
        </x-slot>
        <div class="relative pt-12">
            <div class="absolute right-2 flex justify-end space-x-4 items-center left-2 top-0 font-bold text-lg">
                <div class="w 40">
                    <x-base-select label="" id="cltSpecial" wire:model.defer="client.special">
                        <option value="0">Normal</option>
                        <option value="1">Especial</option>
                    </x-base-select>
                </div>
                <span>Cód.: {{ $client['code'] }}</span>
            </div>

            <form wire:submit.prevent="createClient" class="mb-2">
                <div class="flex space-x-4">
                    <div class="w-full overflow-hidden">
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Nombre del negocio" id="client.name"
                                    wire:model.defer="client.name" />
                                <x-input-error for="client.name" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Correo Electrónico" id="client.email" type="email"
                                    wire:model.defer="client.email" />
                                <x-input-error for="client.email" />
                            </div>
                        </div>
                        <div class="    pb-6 ">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Dirección" id="client.address"
                                    wire:model.defer="client.address" />
                                <x-input-error for="client.address" />
                            </div>
                        </div>
                    </div>
                    <div class="w-full overflow-hidden">
                        <div class=" pb-0  lg:pb-6 flex flex-col lg:flex-row lg:space-y-0 lg:space-x-3 items-start ">
                            <div class="w-full pb-6 lg:pb-0 overflow-hidden">
                                <x-base-select class="{{ $cltDocType ? 'text-black' : 'text-gray-300' }}"
                                    label="Tipo de documento" id="cltDocType" wire:model="cltDocType">
                                    <option value="" class="text-gray-300">Elija RNC o Cédula</option>
                                    <option class="text-black">RNC</option>
                                    <option class="text-black">Cédula</option>
                                </x-base-select>
                                <x-input-error for="cltDocType">Indique el tipo de documento</x-input-error>
                            </div>
                            <div class="w-full pb-6 lg:pb-0 overflow-hidden {{ $cltDocType != 'RNC' ? 'hidden' : '' }}">
                                <x-base-input label="No. Documento" placeholder="Ingrese el Nº. de RNC" id="client_RNC"
                                    type="text" wire:model.defer="client.rnc"
                                    wire:keydown.enter.prevent="loadFromRNC" />
                                <x-input-error for="client.rnc" />

                            </div>
                            <div class="w-full overflow-hidden {{ $cltDocType != 'Cédula' ? 'hidden' : '' }}">
                                <x-base-input label="No. Documento" placeholder="Ingrese el Nº. de Cédula"
                                    id="client_Cedula" type="text" wire:model.defer="client.rnc"
                                    wire:keydown.enter.prevent="loadFromRNC" />
                                <x-input-error for="client.rnc" />

                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="No. Teléfono" id="client.phone"
                                    wire:model.defer="client.phone" />
                                <x-input-error for="client.phone" />
                            </div>
                            @can('Asignar Créditos')
                                <div class="w-full overflow-hidden">
                                    <x-base-input label="Balance" placeholder="Límite de crédito" type="number"
                                        id="client.limit" wire:model.defer="client.limit" />
                                    <x-input-error for="client.limit" />
                                </div>
                            @else
                                <input type="hidden" name="client.limit" wiere.model="client.limit"
                                    x-bind:value="0.00" id="client.limit">
                            @endcan
                        </div>
                        <div class=" flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <label for="client_avatar" class="flex items-center space-x-4 pb-6 cursor-pointer">
                                    <span class="fas fa-image text-xl"></span>
                                    <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Logo/Avatar</span>
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
                        </div>

                    </div>
                </div>
                <h1 class="text-center uppercase py-4 font-bold text-xl">Persona de Contacto</h1>
                <div class=" ">
                    <div class="  pb-6 flex items-start space-x-3">
                        <div class="flex w-full flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Nombre" id="client.name" wire:model.defer="name" />
                                <x-input-error for="name" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Apellidos" id="client.lastname" wire:model.defer="lastname" />
                                <x-input-error for="lastname" />
                            </div>
                        </div>
                        <div class="flex w-full flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="Nº. Celular" id="client.cellphone"
                                    wire:model.defer="cellphone" />
                                <x-input-error for="cellphone" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input type="text" label="Cédula Personal" id="contact_cedula"
                                    wire:model.defer="cedula" />
                                <x-input-error for="cedula" />
                            </div>

                        </div>

                    </div>
                </div>
                <div class="py-3 flex justify-end items-center">
                    <x-button>Guardar</x-button>
                </div>
            </form>
        </div>



    </x-modal>
</div>

