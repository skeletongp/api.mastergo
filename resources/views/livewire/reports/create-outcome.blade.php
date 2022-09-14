<div>
    <x-modal  maxWidth="max-w-xl" :fitV="false" title="Registrar nuevo gasto">
        <x-slot name="button">
            Nuevo gasto
        </x-slot>
        <div>
            <div class="flex justify-end items-end space-x-4">
                <x-toggle label="ITBIS" value="1" wire:model="tax" id="taxToggle"></x-toggle>
                @if ($tax)
                <div class="w-16 -pt-2">
                    <x-input-error for="rate">Requerido</x-input-error>
                    <x-base-input class="py-0" placeholder="Tasa" wire:model.defer="rate" label=""></x-base-input>
                </div>
                @endif
            </div>
            <div class="flex space-x-4 items-start -mb-8">
                
                <div class="w-1/3">
                    <x-base-input type="number" id="outAmount" label="Monto" placeholder="Ingrese el monto total"
                        wire:model.defer="amount" disabled>
                    </x-base-input>
                    <x-input-error for="amount">Campo requerido</x-input-error>
                </div>
                <div class="w-full">
                    <x-base-input type="text" id="outConcepto" label="Concepto" placeholder="Texto descriptivo del gasto"
                        wire:model.defer="concept">
                    </x-base-input>
                    <x-input-error for="concept">Campo requerido</x-input-error>
                </div>
            </div>
            @include('livewire.products.includes.sumproductmoney')
            <div class="flex justify-end py-4">
                <x-button wire:click.prevent="createOutcome">Guardar</x-button>
            </div>
        </div>
    </x-modal>
</div>
