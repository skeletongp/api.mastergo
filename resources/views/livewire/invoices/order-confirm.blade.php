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
            <div class="col-span-3 space-y-3">
                <label for="form.client_id" class="font-medium">Cliente</label>
                <x-select wire:model.lazy="form.client_id">
                    <option value=""></option>
                    @foreach ($clients as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-select>
                <x-input-error for="form.client_id"></x-input-error>
            </div>
            {{-- Segunda Fila --}}
            <div class="col-span-2 space-y-3">
                <label for="form.type" class="font-medium">Tipo</label>
                <x-select wire:model.lazy="form.type" id="form{{ $form['id'] }}.type">
                    @foreach (App\Models\Invoice::TYPES as $ind => $type)
                        <option value="{{ $type }}">{{ $ind }}</option>
                    @endforeach
                </x-select>
                @if (!$compAvail)
                    <span class="text-red-400">Tipo de comprobante no disponible</span>
                @endif
            </div>

            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.amount"
                    label="Subtotal" id="form{{ $form['id'] }}.amount">
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
                <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                    wire:model.lazy="form.discount" label="Descuento" id="form{{ $form['id'] }}.discount"></x-dinput>
                <x-input-error for="form.discount"></x-input-error>
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

            <div class="col-span-3 space-y-3">
                <x-dinput class="text-xl font-bold" type="text" wire:model.lazy="form.note" label="Nota"
                    id="form{{ $form['id'] }}.note" placeholder="Ingrese una nota a la factura"></x-dinput>
            </div>

            <div class="col-span-5 flex justify-end">
                <x-button wire:loading.attr="disabled">
                    Cobrar
                </x-button>
            </div>
            <button
            class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
            wire:loading>
            <div class="mx-auto h-40 w-40 bg-center bg-cover"
                style="background-image: url({{ asset('images/assets/loading.gif') }})">
            </div>
        </button>
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
