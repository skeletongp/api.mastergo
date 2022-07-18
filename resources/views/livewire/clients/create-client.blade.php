<div>
    <x-modal :fitV='false' maxWidth="max-w-4xl">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Nuevo Cliente</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-user-plus mr-2"></span>
            <span> Cliente</span>
        </x-slot>
        <div class="relative pt-8">
            <div class="absolute right-2 top-0 font-bold text-lg">
                <span>Cód.: {{ $form['code'] }}</span>
            </div>
            <form wire:submit.prevent="createClient">
                <div class="flex space-x-4">
                    <div class="w-full overflow-hidden">

                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Nombre del negocio" id="form.name" wire:model.defer="form.name" />
                                <x-input-error for="form.name" />
                            </div>

                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Correo Electrónico" id="client.email" type="email"
                                    wire:model.defer="form.email" />
                                <x-input-error for="form.email" />
                            </div>
                        </div>
                        <div class="    pb-6 ">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Dirección" id="client.address" wire:model.defer="form.address" />
                                <x-input-error for="form.address" />
                            </div>
                        </div>
                    </div>
                    <div class="w-full overflow-hidden">

                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-select class="{{ $cltDocType ? 'text-black' : 'text-gray-300' }}"
                                    label="Tipo de documento" id="cltDocType" wire:model="cltDocType">
                                    <option value="" class="text-gray-300">Elija RNC o Cédula</option>
                                    <option class="text-black">RNC</option>
                                    <option class="text-black">Cédula</option>
                                </x-base-select>
                                <x-input-error for="cltDocType">Indique el tipo de documento</x-input-error>
                            </div>
                            <div class="w-full overflow-hidden {{ $cltDocType != 'RNC' ? 'hidden' : '' }}">
                                <x-base-input label="No. Documento" placeholder="Ingrese el Nº. de RNC" id="client_RNC"
                                    type="text" wire:model.defer="form.rnc"
                                    wire:keydown.enter.prevent="loadFromRNC" />
                                <x-input-error for="form.rnc" />

                            </div>
                            <div class="w-full overflow-hidden {{ $cltDocType != 'Cédula' ? 'hidden' : '' }}">
                                <x-base-input label="No. Documento" placeholder="Ingrese el Nº. de Cédula"
                                    id="client_Cedula" type="text" wire:model.defer="form.rnc"
                                    wire:keydown.enter.prevent="loadFromRNC" />
                                <x-input-error for="form.rnc" />

                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="No. Teléfono" id="client.phone"
                                    wire:model.defer="form.phone" />
                                <x-input-error for="form.phone" />
                            </div>
                            @can('Asignar Créditos')
                                <div class="w-full overflow-hidden">
                                    <x-base-input label="Balance" placeholder="Límite de crédito" type="number"
                                        id="client.limit" wire:model.defer="form.limit" />
                                    <x-input-error for="form.limit" />
                                </div>
                            @else
                                <input type="hidden" name="form.limit" wiere.model="form.limit"
                                    x-bind:value="0.00" id="form.limit">
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
                <div>
                    <div class="  pb-6 flex items-start space-x-3">
                        <div class="w-full overflow-hidden">
                            <x-base-input label="Nombre" id="client.name" wire:model.defer="name" />
                            <x-input-error for="name" />
                        </div>
                        <div class="w-full overflow-hidden">
                            <x-base-input label="Apellidos" id="client.lastname" wire:model.defer="lastname" />
                            <x-input-error for="lastname" />
                        </div>

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
                <div class="py-3 flex justify-end items-center">
                    <x-button>Guardar</x-button>
                </div>
            </form>
        </div>

    </x-modal>
</div>
@push('js')
    <script>
        $('#cltDocType').on('change', function () {
            $('#client_RNC').val(' ');
        });
        $('#client_RNC').formatPhoneNumber({
            format: '###-#####-#'
        })
        $('#client_Cedula').formatPhoneNumber({
            format: '###-#######-#'
        })
        $('#contact_cedula').formatPhoneNumber({
            format: '###-#######-#'
        })
    </script>
@endpush
