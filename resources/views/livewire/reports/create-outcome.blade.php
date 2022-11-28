<div>
    <x-modal :hideButton="$hideButton" :open="$open" maxWidth="max-w-2xl" :listenOpen="true" :fitV="$payAll"
        title="Registrar nuevo gasto">
        <x-slot name="button">
            Nuevo gasto
        </x-slot>
        <div>
            <div class="flex justify-between items-center space-x-4">
                <x-toggle label="¿Comprobante?" value="1" wire:model="tax" id="taxToggle"></x-toggle>
                <x-base-input class="py-0" type="date" id="outDate" label="" wire:model.lazy="date">
                </x-base-input>
            </div>
            <div class="flex space-x-4 items-start pt-4 -mb-8">

                <div class="w-1/3">
                    <x-base-input type="number" id="outProducts" label="Gasto en Bienes" placeholder="Monto Bruto"
                        wire:model.lazy="products">
                    </x-base-input>
                    <x-input-error for="products">Campo requerido</x-input-error>
                </div>
                <div class="w-1/3">
                    <x-base-input type="number" id="outServices" label="Gasto en Servicios" placeholder="Monto Bruto"
                        wire:model.lazy="services">
                    </x-base-input>
                    <x-input-error for="services">Campo requerido</x-input-error>
                </div>
                <div class="w-full">
                    <x-base-input type="text" id="outConcepto" label="Concepto"
                        placeholder="Texto descriptivo del gasto" wire:model.defer="concept">
                    </x-base-input>
                    <x-input-error for="concept">Campo requerido</x-input-error>
                </div>
            </div>
            @include('livewire.products.includes.sumproductmoney')
            @if ($tax)
                <div class="flex space-x-4 items-start mt-8">
                    <div class="w-full">
                        <x-base-input type="number" id="outITBIS" placeholder="ITBIS" wire:model.lazy="itbis">
                            <x-slot name="label">
                                <span>ITBIS</span>
                                <span>({{ $inPercent ? '%' : '$' }})</span>
                                <input type="checkbox" id="itbisInpercent" wire:model="inPercent"
                                    class="ml-2 rounded-full">
                            </x-slot>
                        </x-base-input>
                        <x-input-error for="itbis">Revise el campo</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="number" id="outSelectivo" label="Selectivo (%)" placeholder="Selectivo"
                            wire:model.lazy="selectivo"></x-base-input>
                        <x-input-error for="selectivo">Revise el campo</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="number" id="outPropina" label="Propina (%)" placeholder="Propina Legal"
                            wire:model.lazy="propina"></x-base-input>
                        <x-input-error for="propina">Revise el campo</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-input type="number" id="outOther" label="Otro Cargo (%)" placeholder="Otro Cargo"
                            wire:model.lazy="other"></x-base-input>
                        <x-input-error for="other">Revise el campo</x-input-error>
                    </div>
                </div>
                <div class="flex space-x-4 items-start mt-8">

                    <div class="w-1/3">
                        <x-base-input type="number" id="outRetenido" label="ITBIS Retenido (%)"
                            placeholder="Tasa retenida " wire:model="retenido">
                        </x-base-input>
                        <x-input-error for="retenido">Revise el campo</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-base-select label="Tipo de Gasto" id="outType" wire:model="type">
                            @foreach (App\Models\Outcome::TYPES as $number => $typ)
                                <option value="{{ $number }}">{{ $typ }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="type">Campo requerido</x-input-error>
                    </div>
                </div>
            @endif
            <div class="py-4 text-xl font-bold flex justify-end gap-4">
                Total ${{ formatNumber($total) }}
            </div>
            <div class="flex justify-end py-4">
                <x-button wire:click.prevent="createOutcome">Guardar</x-button>
            </div>
        </div>
    </x-modal>
</div>
