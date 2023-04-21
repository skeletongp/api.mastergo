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
            <x-datalist type="search" inputId="outCountCode" label="Cuenta afectada" listName="countList"
                wire:model.lazy="code_name">
                @foreach ($counts as $code => $count)
                    <option value="{{$code.' - '.ellipsis($count, 27) }}"></option>
                @endforeach
            </x-datalist>
            <x-input-error for="count_code">Campo requerido</x-input-error>
        </div>
        <div class="w-full">
            <x-base-input type="text" id="outRef" label="Referencia" placeholder="NCF u otro referencia"
                wire:model.defer="ref">
            </x-base-input>
            <x-input-error for="ref">Revise los datos</x-input-error>
        </div>
    </div>

    @if ($setCost )
        <div class="flex space-x-4 items-start mt-8">
            <div class="w-full">
                <x-base-input type="number" id="outEfectivo" label="Efectivo" placeholder="Efectivo pagado"
                    wire:model="efectivo"></x-base-input>
                <x-input-error for="efectivo">Campo requerido</x-input-error>
            </div>
            <div class="w-full">
                <x-base-input type="number" id="outTransferencia" label="Transferencia/Cheque"
                    placeholder="Transferencia realizada" wire:model="transferencia"></x-base-input>
                <x-input-error for="transferencia">Campo requerido</x-input-error>
            </div>
            <div class="w-full">
                <x-base-input type="number" id="outOtros" label="Otros" placeholder="Otras formas de pago"
                    wire:model="tarjeta"></x-base-input>
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

</div>