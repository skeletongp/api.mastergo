<div>
    <div class="flex space-x-8 divide-x divide-red-100">
        <div class="w-max flex flex-col max-w-6xl space-y-4">
            <div class="flex space-x-8">
                {{-- Client Section --}}
                @include('livewire.invoices.includes.clientsection')
                @include('livewire.invoices.includes.invoicedata')
            </div>
            <div class="float-right">
                @include('livewire.invoices.includes.productsection')
            </div>
            @if ($errors->any())
                <div class=" bg-gray-100 p-4 rounded-xl w-max">
                    <x-input-error for="client">Debe seleccionar un cliente</x-input-error>
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
    @if ($open)
        @include('livewire.invoices.includes.authorize')
    @endif
</div>
