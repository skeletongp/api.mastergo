<div class="">

    <x-modal id="modalConfirmInvoice" fitVerticalContainer='true' maxWidth="max-w-3xl">
        <x-slot name="button">
            <span>
                Cobrar
            </span>
        </x-slot>
        <x-slot name="title">
            Cobrar Pedido Nº. {{ $form['number'] }}
        </x-slot>
        <form wire:submit.prevent="payInvoice" class="grid grid-cols-5 gap-4 p-3 max-w-3xl mx-auto text-left">
            {{-- Primera fila --}}
            <div class="col-span-2">
                <x-dinput class="text-xl font-bold" label="Vendedor" id="form{{ $form['id'] }}.seller" disabled
                    value="{{ $form['seller']['fullname'] }}">
                    </x-input>
            </div>

            

            {{-- Segunda Fila --}}
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.amount" label="Subtotal"
                    id="form{{ $form['id'] }}.amount">
                </x-dinput>
            </div>

            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.tax" label="Impuestos"
                    id="form{{ $form['id'] }}.tax"></x-dinput>
            </div>
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.total" label="Total"
                    id="form{{ $form['id'] }}.total"></x-dinput>
                <x-input-error for="form.total"></x-input-error>
            </div>

            {{-- Tercera Fila --}}
            <div>
                <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                    wire:model.lazy="form.efectivo" label="Efectivo" id="form{{ $form['id'] }}.efectivo"></x-dinput>
                <x-input-error for="form.efectivo"></x-input-error>
            </div>
            <div>
                <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                    wire:model.lazy="form.tarjeta" label="Tarjeta" id="form{{ $form['id'] }}.tarjeta"></x-dinput>
                <x-input-error for="form.tarjeta"></x-input-error>
            </div>
            <div>
                <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                    wire:model.lazy="form.transferencia" label="Transferencia"
                    id="form{{ $form['id'] }}.transferencia"></x-dinput>
                <x-input-error for="form.transferencia"></x-input-error>
            </div>

           
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.payed" label="Pagado"
                    id="form{{ $form['id'] }}.payed"></x-dinput>
            </div>

            {{-- Cuarta Fila --}}
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.rest" label="Pendiente"
                    id="form{{ $form['id'] }}.rest"></x-dinput>
                <x-input-error for="form.rest"></x-input-error>
            </div>
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.cambio"
                    label="Devuelta" id="form{{ $form['id'] }}.cambio"></x-dinput>
                <x-input-error for="form.cambio"></x-input-error>
            </div>

            <div class="col-span-4 space-y-3">
                <x-dinput class="text-xl font-bold" type="text" wire:model.lazy="form.note" label="Nota"
                    id="form{{ $form['id'] }}.note" placeholder="Ingrese una nota a la factura"></x-dinput>
            </div>

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
