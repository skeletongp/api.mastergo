<div class="">
        @include('includes.authorize')
   
    <x-modal id="modalConfirmInvoice" maxWidth="max-w-3xl" :listenOpen="true">
        <x-slot name="button">
            <span>
                Cobrar
            </span>
        </x-slot>
        <x-slot name="title">
            Cobrar Pedido Nº. {{ $form['number'] }}
        </x-slot>
        
        <form wire:submit.prevent="tryPayInvoice" class="grid grid-cols-5 gap-4 p-3 max-w-3xl mx-auto text-left relative pt-16">
            {{-- Vendedor --}}
            {{-- <div class="absolute top-0 right-2">
                <x-base-input type="number" class="w-12 text-right" placeholder="Copias" label="" wire:model="copyCant"></x-base-input>
            </div> --}}
            <div class="col-span-2">
                <x-base-input class="text-xl font-bold" label="Vendedor" id="form{{ $form['id'] }}.seller" disabled
                    wire:model="form.seller.name">
                    </x-input>
            </div>
            <div class="col-span-2">
                <x-base-input class="text-xl font-bold" label="Cliente" id="form{{ $form['id'] }}.client" disabled
                    wire:model="form.name">
                    </x-input>
            </div>


            {{-- Montos --}}
            <div>
                <x-base-input class="text-xl font-bold" type="number" disabled wire:model.lazy="form.amount"
                    label="Subtotal" id="form{{ $form['id'] }}.amount">
                </x-base-input>
            </div>

            <div>
                <x-base-input class="text-xl font-bold" type="number" disabled wire:model.lazy="form.tax"
                    label="Impuestos" id="form{{ $form['id'] }}.tax"></x-base-input>
            </div>
            <div>
                <x-base-input class="text-xl font-bold" type="number" disabled wire:model.lazy="form.discount"
                    label="Descuento" id="form{{ $form['id'] }}.discount"></x-base-input>
                <x-input-error for="form.rest"></x-input-error>
            </div>
            <div>
                <x-base-input class="text-xl font-bold text-green-600" type="number" disabled
                    wire:model.lazy="form.total" label="Total" id="form{{ $form['id'] }}.total"></x-base-input>
                <x-input-error for="form.total"></x-input-error>
            </div>

            {{-- Campos de cobro --}}
            <div>
                <x-base-input class="text-xl font-bold" type="number" {{-- status="{{ !$cobrable ? 'disabled' : '' }}" --}}
                    wire:model.debounce.300ms="form.efectivo" label="Efectivo" id="form{{ $form['id'] }}.efectivo">
                </x-base-input>
                <x-input-error for="form.efectivo"></x-input-error>
            </div>
            <div>
                <x-base-input class="text-xl font-bold" type="number" {{-- status="{{ !$cobrable ? 'disabled' : '' }}" --}}
                    wire:model.debounce.300ms="form.tarjeta" label="Tarjeta/Cheque" id="form{{ $form['id'] }}.tarjeta">
                </x-base-input>
                <x-input-error for="form.tarjeta"></x-input-error>
            </div>

            @if ($banks->count())
                <div class="col-span-2">
                    <x-base-input class="text-xl font-bold" {{-- status="{{ !$cobrable ? 'disabled' : '' }}" --}} type="number"
                        wire:model.debounce.300ms="form.transferencia" label="Transferencia"
                        id="form{{ $form['id'] }}.transferencia"></x-base-input>
                    <x-input-error for="form.transferencia"></x-input-error>
                </div>
            @endif

            {{-- Cuarta Fila --}}
            @if ($banks->count())
                <div class="col-span-2">
                    <x-base-select id="{{ $form['id'] }}bank_id" wire:model="bank_id" label="Banco" class="py-3">
                        <option value=""></option>
                        @foreach ($banks as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </x-base-select>
                    <x-input-error for="bank">Seleccione un Banco</x-input-error>
                </div>
                <div>
                    <x-base-input class="text-sm py-3" type="text" wire:model.lazy="reference" label="No. Referencia"
                        id="f{{ $form['id'] }}.reference" placeholder="Nº. Ref."></x-base-input>
                    <x-input-error for="reference">Requerido</x-input-error>
                </div>
            @endif
            <div class="col-span-5 space-y-3">
                <x-base-input class="text-xl font-bold" type="text" wire:model.lazy="form.note" label="Nota"
                    id="form{{ $form['id'] }}.note" placeholder="Ingrese una nota a la factura"></x-base-input>
            </div>
            {{-- Texto Grande Pagado y cambio --}}
            <div class="flex flex-col space-y-4">
                @if (array_key_exists('payed', $form) && $form['payed'] > 0)
                    <div class="flex space-x-4 items-center">
                        <span class="text-2xl font-bold text-gray-900">PAGADO</span>
                        <span class="text-2xl font-bold text-gray-900">RD${{ formatNumber($form['payed']) }}</span>
                    </div>
                @endif
                @if (array_key_exists('rest', $form) && $form['rest'] > 0)
                    <div class="flex space-x-4 items-center">
                        <span class="text-2xl font-bold text-red-500">DEBE</span>
                        <span class="text-2xl font-bold text-red-500">RD${{ formatNumber($form['rest']) }}</span>
                    </div>
                @endif
                @if (array_key_exists('cambio', $form) && $form['cambio'] > 0)
                    <div class="flex space-x-4 items-center">
                        <span class="text-2xl font-bold text-green-500">CAMBIO</span>
                        <span class="text-2xl font-bold text-green-500">RD${{ formatNumber($form['cambio']) }}</span>
                    </div>
                @endif
            </div>
            {{-- Fin de texto grande --}}
            <button
                class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
                wire:loading wire:target="tryPayInvoice">
                <x-loading></x-loading>
            </button>
            <div class="col-span-5 flex justify-end">
                <x-button wire:loading.attr="disabled">
                    Cobrar
                </x-button>
            </div>
        </form>
    </x-modal>

    @push('js')
        <script>
            var prevVal = 0;

            function clrInput(event) {
                input = event.target;
                prevVal = input.value;
                input.value = '';
            }

            function restoreInput(event) {
                input = event.target;
                input.value = prevVal;
            }
        </script>
    @endpush

</div>
