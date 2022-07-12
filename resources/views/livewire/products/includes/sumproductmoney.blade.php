<div class="w-full">
    <div class="flex space-x-4 items-start pt-12">
        <div class="w-full">
            <x-base-select id="outProvider" label="Proveedor" wire:model="provider_id">
                <option class="text-gray-300"> Elija un proveedor</option>
                @foreach ($providers as $idProv => $prov)
                    <option value="{{ $idProv }}">{{ $prov }}</option>
                @endforeach
            </x-base-select>
            <x-input-error for="provider_id">Campo requerido</x-input-error>
        </div>
        <div class="w-full">
            <x-base-select id="outCountCode" label="Cuenta afectada" wire:model.defer="count_code">
                <option class="text-gray-300"> Elija una cuenta</option>
                @foreach ($counts as $code => $count)
                    <option value="{{ $code }}">{{ $count }}</option>
                @endforeach
            </x-base-select>
            <x-input-error for="count_code">Campo requerido</x-input-error>
        </div>
        <div class="w-full">
            <x-base-input type="text" id="outRef" label="Referencia" placeholder="NCF u otro referencia"
                wire:model.defer="ref">
            </x-base-input>
            <x-input-error for="ref">Campo requerido</x-input-error>
        </div>
    </div>
    @if ($provider_id == 1)
        <div class="flex space-x-4 pt-4">
            <div class="w-full">
                <x-base-input label="Nombre del proveedor" wire:model.lazy="prov_name" id="provName">
                </x-base-input>

            </div>
            <div class="w-full">
                <x-base-input label="RNC del proveedor" wire:model.defer="prov_rnc" id="provRNC"
                    wire:keydown.enter.prevent="loadProvFromRNC">
                </x-base-input>
                <x-input-error for="form.type">Verifique el campo</x-input-error>
            </div>
        </div>
    @endif
    @if ($setCost)
        <div class="flex space-x-4 items-start mt-8">
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
                <x-base-input type="number" id="outOtros" label="Otros" placeholder="Otras formas de pago"
                    wire:model.defer="tarjeta"></x-base-input>
                <x-input-error for="tarjeta">Campo requerido</x-input-error>
            </div>
        </div>
    @endif
    @if ($transferencia > 0)
        <div class="flex space-x-4 items-start mt-8">
            <div class="w-full">
                <x-base-select label="Banco" wire:model="bank_id" id="outBankId">
                    <option value=""></option>
                    @foreach ($banks as $ide => $bank)
                        <option value="{{ $ide }}">{{ $bank }}</option>
                    @endforeach
                </x-base-select>
                <x-input-error for="bank_id">Campo requerido</x-input-error>
            </div>
            <div class="w-full">
                <x-base-input type="text" id="outBankRef" label="Referencia"
                    placeholder="Referencia de la transferencia" wire:model.defer="ref_bank">
                </x-base-input>
                <x-input-error for="ref_bank">Campo requerido</x-input-error>
            </div>

        </div>
    @endif
    @if ($setCost && !isset($hideTax))
        <div class="flex space-x-4 items-start">
            <div class="w-40 py-4">
                <x-base-input type="text" id="outTax" label="Impuestos pagados" placeholder="Impuestos facturados"
                    wire:model.defer="tax"></x-base-input>
                <x-input-error for="tax">Campo requerido</x-input-error>
            </div>
            <div class="w-40 py-4">
                <x-base-input type="text" id="outDiscount" label="Descuento aplicado"
                    placeholder="Descuentos aplicados" wire:model.lazy="discount"></x-base-input>
                <x-input-error for="discount">Campo requerido</x-input-error>
            </div>
        </div>

        @if (!isset($hideButton))
            <div class="flex justify-end py-4 bottom-0 right-2">
                <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                    wire:loading.attr='disabled' wire:click.prevent="sumCant">Guardar</x-button>
            </div>
        @endif
    @endif
</div>
