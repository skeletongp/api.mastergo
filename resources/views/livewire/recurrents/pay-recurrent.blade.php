<div>
    <x-modal title="Registrar pago" maxWidth="max-w-2xl">
        <x-slot name="button">
            <span class="far fa-hand-holding-usd text-lg"></span>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="payRecurrent" class="space-y-4">
                <div class="flex space-x-4 items-start ">
                    <div class="w-full">
                        <x-base-input type="text" disabled id="recurName" label="Obligación"
                            placeholder="Efectivo pagado" wire:model.defer="recurrent.name"></x-base-input>
                    </div>
                    <div class="w-full">
                        <x-base-input id="recuNextDate" label="Nueva Fecha de Pago" type="text" disabled
                            wire:model.lazy="next_date"></x-base-input>
                    </div>

                </div>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-base-input type="number" id="outEfectivo" label="Efectivo" placeholder="Efectivo pagado"
                            wire:model.defer="efectivo"></x-base-input>
                        <x-input-error for="efectivo">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="number" id="outTransferencia" label="Transferencia/Cheque"
                            placeholder="Transferencia realizada" wire:model.lazy="transferencia"></x-base-input>
                        <x-input-error for="transferencia">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="text" id="outBankRef" label="Referencia"
                            placeholder="Referencia de la transferencia" wire:model.defer="ref_bank">
                        </x-base-input>
                        <x-input-error for="ref_bank">Campo requerido</x-input-error>
                    </div>
                </div>

                <div class="flex space-x-4 items-start">
                    @if ($transferencia > 0)
                        <div class="w-full">
                            <x-base-select label="Banco" wire:model="bank_id" id="outBankId">
                                <option value=""></option>
                                @foreach ($banks as $ide => $bank)
                                    <option value="{{ $ide }}">{{ $bank }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="bank_id">Campo requerido</x-input-error>
                        </div>
                    @endif
                    <div class="w-full">
                        <x-base-input type="text" id="outTax" label="Impuestos pagados"
                            placeholder="Impuestos pagados" wire:model.defer="tax"></x-base-input>
                        <x-input-error for="tax">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="number" id="outOtros" label="Otros" placeholder="Otras formas de pago"
                            wire:model.defer="tarjeta"></x-base-input>
                        <x-input-error for="tarjeta">Campo requerido</x-input-error>
                    </div>
                </div>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-base-input type="text" id="outRNC" label="RNC Registrado"
                            placeholder="RNC Registrado" wire:model.defer="rnc"></x-base-input>
                    </div>
                    <div class="w-full">
                        <x-base-input type="text" id="outNCF" label="NCF Generado" placeholder="NCF Generado"
                            wire:model.defer="ncf"></x-base-input>
                    </div>
                </div>
                <div class="flex space-x-4 justify-end items-start">
                    <x-button>Pagar</x-button>
                </div>

            </form>
        </div>
    </x-modal>
</div>
