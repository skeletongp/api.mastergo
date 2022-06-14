<div>
    <x-modal :fitV='false' maxWidth="max-w-4xl" minHeight="min-h-[65vh]">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Nuevo Proveedor</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span data-tooltip-target="editId{{$provider['id']}}"
                data-tooltip-style="light" class="far fa-pen text-green-600"></span>
                <x-tooltip id="editId{{$provider['id']}}">Editar registro</x-tooltip>
        </x-slot>
        <div class="relative pt-8">
           
            <form wire:submit.prevent="updateProvider">
                <div class="flex space-x-4">
                    <div class="w-full overflow-hidden">
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Primer nombre" id="provider.{{$provider['id']}}.name" wire:model.defer="provider.name" />
                                <x-input-error for="provider.name" />
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Apellidos" id="provider.{{$provider['id']}}.lastname"
                                    wire:model.defer="provider.lastname" />
                                <x-input-error for="provider.lastname" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Correo Electrónico" id="provider.{{$provider['id']}}.email" type="email"
                                    wire:model.defer="provider.email" />
                                <x-input-error for="provider.email" />
                            </div>
                        </div>
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input label="Dirección" id="provider.{{$provider['id']}}.address" wire:model.defer="provider.address" />
                                <x-input-error for="provider.address" />
                            </div>

                        </div>
                    </div>
                    <div class="w-full overflow-hidden">
                        <div class="  pb-6 flex items-start space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-select label="Tipo de documento" id="provDocType{{$provider['id']}}"
                                    wire:model.defer="provDocType">
                                    <option value=""></option>
                                    <option {{strlen($provider['rnc'])===12?'selected':''}}">RNC</option>
                                    <option {{strlen($provider['rnc'])===13?'selected':''}}">Cédula</option>
                                </x-base-select>
                                <x-input-error for="provDocType">Indique el tipo de documento</x-input-error>
                            </div>
                            <div class="w-full overflow-hidden">
                                <x-base-input label="No. Documento" id="provider_{{$provider['id']}}_rnc" type="text"
                                    wire:model.defer="provider.rnc" />
                                <x-input-error for="provider.rnc" />
                            </div>

                        </div>
                        <div class="  pb-6 flex items-start space-y-0 space-x-3">
                            <div class="w-full overflow-hidden">
                                <x-base-input type="tel" label="No. Teléfono" id="provider.{{$provider['id']}}.phone"
                                    wire:model.defer="provider.phone" />
                                <x-input-error for="provider.phone" />
                            </div>
                            @can('Asignar Créditos')
                                <div class="w-full overflow-hidden">
                                    <x-base-input label="Crédito" type="number" id="provider.{{$provider['id']}}.limit"
                                        wire:model.defer="provider.limit" />
                                    <x-input-error for="provider.limit" />
                                </div>
                            @else
                                <input type="hidden" name="provider.limit" wiere.model="provider.limit" x-bind:value="0.00"
                                    id="provider.{{$provider['id']}}.limit">
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
        $("#provDocType{{$provider['id']}}").on('change', function() {
            if ($(this).val() === 'RNC') {
                $("#provider_{{$provider['id']}}_rnc").formatPhoneNumber({
                    format: '###-#####-#'
                })

            } else {
                $("#provider_{{$provider['id']}}_rnc").formatPhoneNumber({
                    format: '###-#######-#'
                })
            }
            $("#provider_{{$provider['id']}}_rnc").val('');
        })
    </script>
@endpush

