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
                        <div wire:click="setPDF('{{ $invoice->pdfThermal }}', '{{ $invoice->pdfLetter }}', {{ $invoice->id }})"
                            id="divInvoice"
                            class="flex flex-col relative w-full px-4 mb-2 py-3 cursor-pointer hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:text-blue-700 leading-3 {{ $pdfLetter == $invoice->pdfLetter ? 'bg-blue-100' : '' }}">
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
                                    RD${{ formatNumber($invoice->total) }}
                                </span>
                                <span class="text-base">
                                    {{ date_format(date_create($invoice->created_at), 'd-m-Y') }}
                                </span>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                    <div class="my-2">
                        {{ $invoices->links('vendor.livewire.tailwind') }}
                    </div>

                </div>
            @else
            @endif
        </div>

        <div class="w-full h-full  pl-0" x-data="{ open: true }">

            @if ($invoices->count())
                <div class="mx-auto ">
                    <div class="flex justify-end items-center pb-2 pt-1 space-x-4 bg-gray-100 pr-4 rounded-tr-lg">
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300 z-[50]"
                            wire:click="toggleThermal" data-tooltip-target="paperSize" data-tooltip-style="light">
                            <span class="far fa-exchange-alt"></span>
                        </a>
                        <x-tooltip id="paperSize">Ver tamaño {{ $thermal ? 'Carta' : 'Térmico' }}</x-tooltip>
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                            href="{{ route('orders') }}">
                            <span class="far fa-copy"></span>
                        </a>
                        <x-tooltip id="paperSize">Detalles de la factura</x-tooltip>
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                            href="{{ route('invoices.show', $currentInvoice) }}">
                            <span class="far fa-eye"></span>
                        </a>
                    </div>
                    <div class="w-full relative px-12 " style=" height:215.4mm">
                        <embed class="mx-auto h-full  " id="pdfObj"
                            src="{{ $thermal ? $pdfThermal : $pdfLetter }}#toolbar=1&navpanes=0&scrollbar=0&zoom=100"
                            width="100%" height="100%" type="application/pdf" />


                    </div>
                </div>
            @else
                <div class="h-full w-full flex flex-col items-center justify-center">
                    <h1 class="text-3xl leading-8 max-w-sm text-center uppercase font-bold">Seleccione una factura para
                        obtener una vista previa</h1>
                </div>
            @endif

        </div>
        @push('js')
            <script>
                $(document).ready(function() {
                    try {
                        window.print()
                        console.log('prueba')
                    } catch (error) {
                        console.log(error)
                    }

                })
            </script>
        @endpush

    </div>
