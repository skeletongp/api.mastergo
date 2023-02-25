<div>
    <x-modal :fitV='false' maxWidth="max-w-4xl">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Nuevo Proveedor</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span class="fas w-6 text-center fa-user-plus mr-2"></span>
            <span> Proveedor</span>
        </x-slot>
        <div class="relative pt-8">

            <form wire:submit.prevent="createProvider">
                <div class="flex space-x-4">
                    <div class="w-full overflow-hidden">
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Nombre Completo" id="provider.name" wire:model.defer="form.name" />
                                <x-input-error for="form.name" />
                            </div>

                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Correo Electrónico" id="provider.email" type="email"
                                    wire:model.defer="form.email" />
                                <x-input-error for="form.email" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Dirección" id="provider.address" wire:model.defer="form.address" />
                                <x-input-error for="form.address" />
                            </div>

                        </div>
                    </div>
                    <div class="w-full overflow-hidden">
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-select label="Tipo de documento" id="provDocType"
                                    wire:model.defer="provDocType">
                                    <option value=""></option>
                                    <option>RNC</option>
                                    <option>Cédula</option>
                                </x-base-select>
                                <x-input-error for="provDocType">Indique el tipo de documento</x-input-error>
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input label="No. Documento" id="provider_RNC" type="text"
                                    wire:model.defer="form.rnc" wire:keydown.enter.prevent="loadFromRNC"/>
                                <x-input-error for="form.rnc" />
                            </div>

                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="No. Teléfono" id="provider.phone"
                                    wire:model.defer="form.phone" />
                                <x-input-error for="form.phone" />
                            </div>
                            @can('Asignar Créditos')
                                <div class="w-full overflow-hidden">
                                    <x-base-input label="Crédito" type="number" id="provider.limit"
                                        wire:model.defer="form.limit" />
                                    <x-input-error for="form.limit" />
                                </div>
                            @else
                                <input type="hidden" name="form.limit" wiere.model="form.limit" x-bind:value="0.00"
                                    id="form.limit">
                            @endcan
                        </div>
                        <div class="absolute bottom-6 right-2">
                            <x-button>Guardar</x-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-modal>
</div>
@push('js')
    <script>
        /* $('#provider_RNC').formatPhoneNumber({
                    format: '###-#####-#'
                })
        $('#provDocType').on('change', function() {
            if ($(this).val() === 'RNC') {
                $('#provider_RNC').formatPhoneNumber({
                    format: '###-#####-#'
                })

            } else {
                $('#provider_RNC').formatPhoneNumber({
                    format: '###-#######-#'
                })
            }
            $('#provider_RNC').val(''); */
        })
    </script>
@endpush

