<div>
    <x-modal title="Registrar pago" maxWidth="max-w-2xl">
        <x-slot name="button">
            <span class="far fa-hand-holding-usd text-lg"></span>
        </x-slot>
        <div>
            <form action="" wire:submit.prevent="payRecurrent" class="space-y-4">
                <div class="flex space-x-4 items-start ">
                    <div class="w-full">
                        <x-base-input type="text" disabled id="recurName{{$recurrent['id']}}" label="Obligación"
                            placeholder="Efectivo pagado" wire:model.defer="recurrent.name"></x-base-input>
                    </div>
                    <div class="w-full">
                        <x-base-input id="recuNextDate{{$recurrent['id']}}" label="Nueva Fecha de Pago" type="text" disabled
                            wire:model.lazy="next_date"></x-base-input>
                    </div>
                    <div class="w-full">
                        <x-base-input id="recuTOtal{{$recurrent['id']}}" label="Monto a Pagar" type="text" disabled
                           value="${{formatNumber($recurrent['amount'])}}"></x-base-input>
                    </div>

                </div>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-base-input type="number" id="outEfectivo{{$recurrent['id']}}" label="Efectivo" placeholder="Efectivo pagado"
                            wire:model.defer="efectivo"></x-base-input>
                        <x-input-error for="efectivo">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="number" id="outTransferencia{{$recurrent['id']}}" label="Transferencia/Cheque"
                            placeholder="Transferencia realizada" wire:model.lazy="transferencia"></x-base-input>
                        <x-input-error for="transferencia">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="text" id="outBankRef{{$recurrent['id']}}" label="Referencia"
                            placeholder="Referencia de la transferencia" wire:model.defer="ref_bank">
                        </x-base-input>
                        <x-input-error for="ref_bank">Campo requerido</x-input-error>
                    </div>
                </div>

                <div class="flex space-x-4 items-start">
                    @if ($transferencia > 0)
                        <div class="w-full">
                            <x-base-select label="Banco" wire:model="bank_id" id="outBankId{{$recurrent['id']}}">
                                <option value=""></option>
                                @foreach ($banks as $ide => $bank)
                                    <option value="{{ $ide }}">{{ $bank }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="bank_id">Campo requerido</x-input-error>
                        </div>
                    @endif
                    <div class="w-full">
                        <x-base-input type="text" id="outTax{{$recurrent['id']}}" label="Impuestos pagados"
                            placeholder="Impuestos pagados" wire:model.defer="tax"></x-base-input>
                        <x-input-error for="tax">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="number" id="outOtros{{$recurrent['id']}}" label="Otros" placeholder="Otras formas de pago"
                            wire:model.defer="tarjeta"></x-base-input>
                        <x-input-error for="tarjeta">Campo requerido</x-input-error>
                    </div>
                </div>
                <div class="flex space-x-4 items-start">
                    <div class="w-full">
                        <x-base-input type="text" id="outRNC{{$recurrent['id']}}" label="RNC Registrado"
                            placeholder="RNC Registrado" wire:model.defer="rnc"></x-base-input>
                    </div>
                    <div class="w-full">
                        <x-base-input type="text" id="outNCF{{$recurrent['id']}}" label="NCF Generado" placeholder="NCF Generado"
                            wire:model.defer="ncf"></x-base-input>
                    </div>
                </div>
                <div class="flex space-x-4 justify-end items-start">
                    <x-button>Pagar</x-button>
                </div>

            </form>
        </div>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error</strong>
                <span class="block sm:inline">
                    {{ $errors->first() }}
                </span>
            </div>
            
        @endif
    </x-modal>
</div>
