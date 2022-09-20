<div>
    <x-modal :fitV='false' maxWidth="max-w-4xl" minHeight="min-h-[65vh]" listenOpen>
        <x-slot name="title">
            <div class="flex justify-between items-center">
                <span> Nuevo Proveedor</span>

            </div>
        </x-slot>
        <x-slot name="button">
            <span data-tooltip-target="editId{{ $provider_id }}" class="far fa-pen text-green-600"></span>
            <x-tooltip id="editId{{ $provider_id }}">Editar registro</x-tooltip>
        </x-slot>
        <div class="relative pt-8">

            @if (count($provider))
                <form wire:submit.prevent="updateProvider">
                    <div class="flex space-x-4">
                        <div class="w-full overflow-hidden">
                            <div class="  pb-6 flex items-start space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-input label="Nombre Completo" id="provider.{{ $provider_id }}.name"
                                        wire:model.defer="provider.fullname" />
                                    <x-input-error for="provider.fullname" />
                                </div>
                               
                            </div>
                            <div class="  pb-6 flex items-start space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-input label="Correo Electrónico" id="provider.{{ $provider_id }}.email"
                                        type="email" wire:model.defer="provider.email" />
                                    <x-input-error for="provider.email" />
                                </div>
                            </div>
                            <div class="  pb-6 flex items-start space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-input label="Dirección" id="provider.{{ $provider_id }}.address"
                                        wire:model.defer="provider.address" />
                                    <x-input-error for="provider.address" />
                                </div>

                            </div>
                        </div>
                        <div class="w-full overflow-hidden">
                            <div class="  pb-6 flex items-start space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-select label="Tipo de documento" id="provDocType{{ $provider_id }}"
                                        wire:model.defer="provDocType">
                                        <option value=""></option>
                                        <option {{ strlen($provider['rnc']) === 12 ? 'selected' : '' }}">RNC</option>
                                        <option {{ strlen($provider['rnc']) === 13 ? 'selected' : '' }}">Cédula</option>
                                    </x-base-select>
                                    <x-input-error for="provDocType">Indique el tipo de documento</x-input-error>
                                </div>
                                <div class="w-full overflow-hidden">
                                    <x-base-input label="No. Documento" id="provider_{{ $provider_id }}_rnc"
                                        type="text" wire:model.defer="provider.rnc" />
                                    <x-input-error for="provider.rnc" />
                                </div>

                            </div>
                            <div class="  pb-6 flex items-start space-y-0 space-x-3">
                                <div class="w-full overflow-hidden">
                                    <x-base-input type="tel" label="No. Teléfono"
                                        id="provider.{{ $provider_id }}.phone" wire:model.defer="provider.phone" />
                                    <x-input-error for="provider.phone" />
                                </div>
                                @can('Asignar Créditos')
                                    <div class="w-full overflow-hidden">
                                        <x-base-input label="Crédito" type="number"
                                            id="provider.{{ $provider_id }}.limit" wire:model.defer="provider.limit" />
                                        <x-input-error for="provider.limit" />
                                    </div>
                                @else
                                    <input type="hidden" name="provider.limit" wiere.model="provider.limit"
                                        x-bind:value="0.00" id="provider.{{ $provider_id }}.limit">
                                @endcan
                            </div>
                            <div class="absolute bottom-6 right-2">
                                <x-button>Guardar</x-button>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </x-modal>
</div>
@push('js')
    <script>
        $(document).ready(function() {
            $("input[type=tel]").formatPhoneNumber({
                    format: '(###) ###-####'
                })
        });
        $("#provDocType{{ $provider_id }}").on('change', function() {
            if ($(this).val() === 'RNC') {
                $("#provider_{{ $provider_id }}_rnc").formatPhoneNumber({
                    format: '###-#####-#'
                })

            } else {
                $("#provider_{{ $provider_id }}_rnc").formatPhoneNumber({
                    format: '###-#######-#'
                })
            }
            $("#provider_{{ $provider_id }}_rnc").val('');

        })
    </script>
@endpush
