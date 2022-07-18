<div class="w-max">
    <div class="flex space-x-4 items-center">
        @livewire('invoices.order-confirm', ['invoice' => $invoice, 'banks' => $banks], key(uniqid()))
        <x-modal title="Detalle de la orden → {{$invoice['name']?:($invoice['client']['name']?:$invoice['client']['fullname'])}}"  maxWidth="max-w-3xl">
            <x-slot name="button">
                Ver
            </x-slot>
            <div>
               @livewire('invoices.order-preview', ['invoice' => $invoice], key(uniqid().rand(1,100)))
            </div>
        </x-modal>
    </div>
</div>
