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
            <x-datalist inputId="outCountCode" label="Cuenta afectada" listName="countList"
                model="count_code">
                @foreach ($counts as $code => $count)
                    <option data-value="{{ $code }}" value="{{$code.' - '.ellipsis($count, 27) }}"></option>
                @endforeach
            </x-datalist>
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
                    wire:model="efectivo"></x-base-input>
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
    @if ($this->efectivo > 0)
        <div class="flex space-x-4 items-start mt-8">
            <div class="w-full">
                <x-base-select label="Caja a Reducir" wire:model="efectivoCode" id="efectivoCode">
                    <option value=""></option>
                    @foreach ($efectivos as $index=> $efectivo)
                        <option value="{{$index}}">{{$efectivo}}</option>
                    @endforeach
                </x-base-select>
                <x-input-error for="efectivo_code">Campo requerido</x-input-error>
            </div>
            <div class="w-full"></div>
        </div>
    @endif
    @if ($setCost && !isset($hideTax))
        <div class="flex space-x-4 items-start">

            <div class="w-40 py-4">
                <x-base-input type="text" id="outDiscount" label="Descuento aplicado"
                    placeholder="Descuentos aplicados" wire:model.lazy="discount"></x-base-input>
                <x-input-error for="discount">Campo requerido</x-input-error>
            </div>
        </div>
    @endif
</div>
