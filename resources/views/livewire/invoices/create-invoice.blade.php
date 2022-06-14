<div>
    <div class="flex space-x-8 divide-x divide-red-100">
        <div class="w-max flex flex-col max-w-6xl space-y-4">
            <div class="flex space-x-8 items-start">
                {{-- Client Section --}}
                <div>
                    @include('livewire.invoices.includes.clientsection')
                </div>
                <div>
                    @include('livewire.invoices.includes.invoicedata')
                </div>
            </div>
            <div class="float-right">
                @include('livewire.invoices.includes.productsection')
                @if ($invoice)
                <div class="py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            @livewire('invoices.order-confirm', ['invoice' => $invoice->toArray()], key(uniqid()))
                        </div>
                        <x-button class="bg-gray-200 text-black">Imprimir ticket</x-button>
                    </div>
                </div>
                @endif
            </div>
            @if ($errors->any())
                <div class=" bg-gray-100 p-4 rounded-xl w-max">
                    <x-input-error for="client">Debe seleccionar un cliente</x-input-error>
                    <x-input-error for="name">Digite un nombre para el cliente</x-input-error>
                    <x-input-error for="form.product_id">Debe seleccionar un producto</x-input-error>
                    <x-input-error for="cant">Ingrese una cantidad válida</x-input-error>
                    <x-input-error for="price">Verifique el precio</x-input-error>
                    <x-input-error for="discount">Verifique el descuento</x-input-error>
                </div>
            @endif
        </div>
        <div class=" w-full px-2">
            @include('livewire.invoices.includes.totalsection')
        </div>

    </div>
    @include('includes.authorize')
    @include('livewire.invoices.includes.orderdata')
    @include('livewire.invoices.includes.invoice-js')

</div>
