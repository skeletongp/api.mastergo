    <div class="w-full flex  items-start">
        <div class=" relative" style="width: 34rem; max-width: 34rem">
            @if (1)
                <div
                    class="w-full text-lg font-medium text-gray-900 bg-white  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <div aria-current="true"
                        class="block w-full px-4 py-2 pb-3 text-gray-800 bg-gray-100  rounded-tl-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600 text-xl uppercase text-center font-bold">
                        Facturas
                    </div>
                    @foreach ($invoices as $invoice)
                        <div wire:click="setPDF('{{ $invoice->pdf }}')" id="divInvoice"
                            class="flex flex-col relative w-full px-4 mb-2 py-3 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $pdfPath == $invoice->pdf ? 'bg-blue-100' : '' }}">
                            <span class=" text-lg">
                                {{ $invoice->number }}
                            </span>
                            <span class="text-base">
                                {{ 'No. de Factura' }}
                            </span>
                            <span class="text-base">
                                {{ 'Estatus' }}
                            </span>
                            <div class="absolute right-2 top-10 flex flex-col  text-right">
                                <span class=" text-2xl font-bold">
                                    RD${{ Universal::formatNumber($invoice->total) }}
                                </span>
                                <span class="text-base">
                                    {{ date_format(date_create($invoice->day), 'd-m-Y') }}
                                </span>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                    <div class="my-2">
                        {{ $invoices->onEachSide(1)->links('vendor.livewire.tailwind') }}
                    </div>

                </div>
            @else
            @endif
        </div>
        <div class="w-full h-full  pl-0" x-data="{open: true}">
            @if ($invoices->count())
                <div class="mx-auto ">
                    <div class="flex justify-end items-center pb-2 pt-1 space-x-4 bg-gray-100 pr-4 rounded-tr-lg">
                        <x-button>
                            <span class="fas fa-print" data-tooltip-target="tooltip-print"
                                data-tooltip-style="light"></span>
                            <x-tooltip id="tooltip-print">Impresión Térmica</x-tooltip>
                        </x-button>
                        <x-dropdown wClass="w-48" class="order-2">
                            <x-slot name="trigger">
                                <span class="far fa-ellipsis-h-alt">
                            </x-slot>
                            <x-slot name="content">
                                <x-button class="px-3 py-1.5 flex space-x-2 items-center">
                                    <span class="fas fa-plus"></span>
                                    <span>Prueba</span>
                                </x-button>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    <div class="w-full relative px-12 " style=" height:215.4mm">
                        <embed class="mx-auto h-full  " id="pdfObj" src="{{ $pdfPath }}#zoom=100" width="100%"
                            height="100%" type="application/pdf" />


                    </div>
                </div>
            @else
                <div class="h-full w-full flex flex-col items-center justify-center">
                    <h1 class="text-3xl leading-8 max-w-sm text-center uppercase font-bold">Seleccione una factura para
                        obtener una vista previa</h1>
                </div>
            @endif

        </div>
    </div>
