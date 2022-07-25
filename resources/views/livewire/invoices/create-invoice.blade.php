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
                    @can('Cobrar Facturas')
                        <div class="py-4">
                            <div class="flex justify-between items-center">
                                <div>
                                @livewire('invoices.order-confirm', ['invoice' => $invoice->toArray(), 'banks'=>$banks], key(uniqid()))
                                </div>
                            </div>
                        </div>
                    @endcan
                @endif
            </div>
            @if ($errors->any())
                <div class=" bg-gray-100 p-4 rounded-xl w-max">
                    <x-input-error for="client">Debe seleccionar un cliente</x-input-error>
                    <x-input-error for="name">Digite un nombre para el cliente</x-input-error>
                    <x-input-error for="form.product_id">Debe seleccionar un producto</x-input-error>
                    <x-input-error for="product">Debe seleccionar un producto</x-input-error>
                    <x-input-error for="cant">Ingrese una cantidad válida</x-input-error>
                    <x-input-error for="price">Verifique el precio</x-input-error>
                    <x-input-error for="discount">Verifique el descuento</x-input-error>
                </div>
            @endif
        </div>
        <div class=" w-full px-2">
            <div class="flex py-4 space-x-2 items-center justify-between">
                <div class="w-full">
                    <x-base-select label="" class="py-0.5" id="detailKey" wire:model="localDetail">
                        <option value="">Guardados</option>
                        @foreach ($localKeys as $item)
                            <option>{{ $item }}</option>
                        @endforeach
                    </x-base-select>
                </div>
                @if (count($details))
                    <span id="saveLocalDetails" wire:click="storageDetails" class="fas fa-save"></span>
                @endif
                @if ($localDetail)
                    <span id="deleteLocalDetails" wire:click="deleteLocal" class="fas fa-times text-red-400"></span>
                @endif
            </div>

            @include('livewire.invoices.includes.totalsection')
        </div>

    </div>
    @include('includes.authorize')
    @include('livewire.invoices.includes.invoice-js')
    @include('livewire.invoices.includes.print-order')

    @push('js')
        <script>
            $(document).ready(function() {

            })
        </script>
    @endpush
</div>
