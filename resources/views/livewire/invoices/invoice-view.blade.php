    <div class="w-full flex  items-start">
        <div class=" relative">
            <div
                class="w-full text-lg font-medium text-gray-900 bg-white  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <livewire:invoices.invoice-list />
            </div>
        </div>
       
        <div class="w-full h-full  pl-0" x-data="{ open: true }">
            @if ($invoices->count())
                <div class="mx-auto ">
                    <div
                        class="flex justify-end relative items-center pb-2 pt-1 space-x-4 bg-gray-100 pr-4 rounded-tr-lg">
                        <div class="absolute left-2 overflow-hidden overflow-ellipsis whitespace-nowrap  w-72 ">
                            <span class="font-bold uppercase text-xl "> {{ $currentInvoice->client->fullname }}</span>
                        </div>

                        <x-tooltip id="seeOrders">Ver pedidos</x-tooltip>
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300" href="{{ route('orders') }}"
                            data-tooltip-target="seeOrders" data-tooltip-style="light">
                            <span class="far fa-copy"></span>
                        </a>
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                            href="{{ route('invoices.show', $currentInvoice) }}">
                            <span class="far fa-eye"></span>
                        </a>
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                            href="{{ route('invoices.show', [$currentInvoice, 'includeName' => 'showpayments', 'includeTitle' => 'Pagos']) }}">
                            <span class="fas fa-hand-holding-usd"></span>
                        </a>

                        @can('Imprimir Facturas')
                            <x-tooltip id="printAction">Imprimir</x-tooltip>
                            <button class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                                data-tooltip-target="printAction" data-tooltip-style="light"
                                wire:click="$emit('changeInvoice',{{ $invoice, true }})">
                                <span class="far fa-print"></span>
                            </button>
                        @endcan
                    </div>
                    <div class=" mx-auto relative " style=" height:70vh; width:80mm">
                        @include('livewire.invoices.includes.show')
                    </div>
                </div>
            @else
                <div class="h-full w-full flex flex-col items-center justify-center">
                    <h1 class="text-3xl leading-8 max-w-sm text-center uppercase font-bold">Seleccione una factura para
                        obtener una vista previa</h1>
                </div>
            @endif

        </div>
        @include('livewire.invoices.includes.invoice-js')
    </div>
