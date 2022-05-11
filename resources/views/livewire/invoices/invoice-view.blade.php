    <div class="w-full flex  items-start">
        <div class=" relative" style="width: 34rem; max-width: 34rem">
            <div
                class="w-full text-lg font-medium text-gray-900 bg-white  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
               {{--  <div aria-current="true"
                    class="block w-full px-4 py-2 pb-3 text-gray-800 bg-gray-100  rounded-tl-lg cursor-pointer dark:bg-gray-800 dark:border-gray-600 text-xl uppercase text-center font-bold">
                    Facturas
                </div> --}}
                <livewire:invoices.invoice-list />

            </div>
        </div>

        <div class="w-full h-full  pl-0" x-data="{ open: true }">
            @if ($invoices->count())
                <div class="mx-auto ">
                    <div class="flex justify-end relative items-center pb-2 pt-1 space-x-4 bg-gray-100 pr-4 rounded-tr-lg">
                        <div class="absolute left-2">
                            <span class="font-bold uppercase text-xl"> {{$currentInvoice->client->fullname}}</span>
                        </div>
                        
                        <x-tooltip id="paperSize">Ver tamaño {{ $thermal ? 'Carta' : 'Térmico' }}</x-tooltip>
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300 "
                            wire:click="toggleThermal" data-tooltip-target="paperSize" data-tooltip-style="light">
                            <span class="far fa-exchange-alt"></span>
                        </a>
                        <x-tooltip id="seeOrders">Ver pedidos</x-tooltip>
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                            href="{{ route('orders') }}"  data-tooltip-target="seeOrders" data-tooltip-style="light">
                            <span class="far fa-copy"></span>
                        </a>
                        <a class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                            href="{{ route('invoices.show', $currentInvoice) }}">
                            <span class="far fa-eye"></span>
                        </a>
                        <x-tooltip id="printAction">Imprimir</x-tooltip>
                        <button class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                            data-tooltip-target="printAction" data-tooltip-style="light" onclick="print('{{ $thermal ? $pdfThermal : $pdfLetter }}')">
                            <span class="far fa-print"></span>
                        </button>
                    </div>
                    <div class="w-full relative " style=" height:70vh; width:179mm">
                        <embed class="mx-auto h-full  " id="pdfObj"
                            src="{{ $thermal ? $pdfThermal : $pdfLetter }}#toolbar=0&navpanes=0&scrollbar=0&zoom=100"
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
                
                function print(url) {
                    printJS({
                        printable: url,
                        showModal: true,
                        modalMessage: 'Cargando documento'
                    });
                }
                $(document).ready(function() {


                })
            </script>
        @endpush

    </div>
