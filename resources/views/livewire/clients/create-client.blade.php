<div>
    <x-modal  :fitV='false' maxWidth="max-w-4xl">
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
                                <x-base-input label="Primer nombre" id="client.name" wire:model.defer="form.name" />
                                <x-input-error for="form.name" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Apellidos" id="client.lastname" wire:model.defer="form.lastname" />
                                <x-input-error for="form.lastname" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Correo Electrónico" id="client.email" type="email"
                                    wire:model.defer="form.email" />
                                <x-input-error for="form.email" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Dirección" id="client.address" wire:model.defer="form.address" />
                                <x-input-error for="form.address" />
                            </div>

                        </div>
                    </div>
                    <div class="w-full overflow-hidden">

                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-select label="Tipo de documento" id="cltDocType" wire:model.defer="cltDocType">
                                    <option value=""></option>
                                    <option>RNC</option>
                                    <option>Cédula</option>
                                </x-base-select>
                                <x-input-error for="cltDocType">Indique el tipo de documento</x-input-error>
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input label="No. Documento" id="client_RNC" type="text"
                                    wire:model.defer="form.rnc" />
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
                                    <x-base-input label="Crédito" type="number" id="client.limit"
                                        wire:model.defer="form.limit" />
                                    <x-input-error for="form.limit" />
                                </div>
                            @else
                                <input type="hidden" name="form.limit" wiere.model="form.limit" x-bind:value="0.00"
                                    id="form.limit">
                            @endcan
                        </div>
                        <div class="    pb-6 ">
                            <div class="w-full overflow-hidden">
                                <label for="client_avatar" class="flex items-center space-x-4 pb-4 cursor-pointer">
                                    <span class="fas fa-image text-xl"></span>
                                    <span
                                        class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen/Avatar</span>
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
                    </div>
            </form>
</div>

</div>
</x-modal>
@push('js')
    <script>
        $('#cltDocType').on('change', function() {
            if ($(this).val() === 'RNC') {
                $('#client_RNC').formatPhoneNumber({
                    format: '###-#####-#'
                })

            } else {
                $('#client_RNC').formatPhoneNumber({
                    format: '###-#######-#'
                })
            }
            $('#client_RNC').val('');
        })
    </script>
@endpush

</div>
