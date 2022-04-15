<div class="">
    <x-modal id="modalConfirmInvoice" fitVerticalContainer='true' maxWidth="max-w-3xl">
        <x-slot name="button">
            <span>
                Cobrar
            </span>
        </x-slot>
        <x-slot name="title">
            Cobrar Pedido Nº. {{$invoice['number']}}
        </x-slot>
        <form wire:submit.prevent="createPDF" class="grid grid-cols-5 gap-2 p-3 max-w-3xl mx-auto text-left">
            {{-- Primera fila --}}
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.defer="invoice.amount"
                    label="Subtotal" id="invoice.amount">
                </x-dinput>
            </div>
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.defer="invoice.payed"
                    label="Pagado" id="invoice.payed"></x-dinput>
            </div>
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.defer="invoice.tax"
                    label="Impuestos" id="invoice.tax"></x-dinput>
            </div>
            <div class="col-span-2">
                <x-dinput class="text-xl font-bold" label="Vendedor" id="invoice.seller" disabled
                    value="{{ $invoice['seller']['fullname'] }}">
                    </x-input>
            </div>
            {{-- Segunda Fila --}}
            <div>
                <x-dinput class="text-xl font-bold" type="number" wire:model.defer="invoice.efectivo" label="Efectivo"
                    id="invoice.efectivo"></x-dinput>
            </div>
            <div>
                <x-dinput class="text-xl font-bold" type="number" wire:model.defer="invoice.tarjeta" label="Tarjeta"
                    id="invoice.tarjeta"></x-dinput>
            </div>
            <div>
                <x-dinput class="text-xl font-bold" type="number" wire:model.defer="invoice.transferencia"
                    label="Transferencia" id="invoice.transferencia"></x-dinput>
            </div>
            <div class="col-span-2 space-y-3">
                <label for="invoice.type" class="font-medium">Tipo</label>
                <x-select wire:model="invoice.type" id="invoice.type">
                    @foreach (App\Models\Invoice::TYPES as $ind => $type)
                        <option value="{{ $type }}">{{ $ind }}</option>
                    @endforeach
                </x-select>
            </div>
            {{-- Tercera Fila --}}
            <div class="col-span-2 space-y-3">
                <label for="invoice.client_id" class="font-medium">Cliente</label>
                <x-select>
                    @foreach ($clients as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div class="col-span-3 space-y-3">
                <x-dinput class="text-xl font-bold" type="text" wire:model.defer="invoice.note" label="Nota"
                    id="invoice.note"></x-dinput>
            </div>
        </form>
    </x-modal>

</div>
