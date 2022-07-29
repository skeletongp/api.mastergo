<div class="w-full">

    <div class="bg-white p-3 shadow-sm rounded-sm">
        <div class="">
            <div class="flex items-center space-x-2 font-bold uppercase
            leading-8">

                <span class="tracking-wide text-lg py-4">Detalles de la factura</span>
            </div>

            <div class="flex items-center bg-gray-100 dark:bg-gray-900 p-4 max-w-6xl">
                <div class="container  px-5 mx-auto my-28">
                    <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="p-5 bg-green-100 rounded shadow-sm dark:bg-gray-800">
                            <div class="text-base text-gray-400 dark:text-gray-300">Cliente</div>
                            <div class="flex items-center pt-1">
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ ellipsis($invoice->name ?: $invoice->client->name, 30) }}</div>

                            </div>
                        </div>
                        <div class="p-5 bg-blue-100 rounded shadow-sm dark:bg-gray-800">
                            <div class="text-base text-gray-400 dark:text-gray-300">Monto Total</div>
                            <div class="flex items-center pt-1">
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    ${{ formatNumber($invoice->payment->total) }}</div>
                            </div>
                        </div>

                        <div class="p-5 bg-blue-100 rounded shadow-sm dark:bg-gray-800">
                            <div class="text-base text-gray-400 dark:text-gray-300">Pagado</div>
                            <div class="flex items-center pt-1">
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    ${{ formatNumber($invoice->payments->sum('payed') - $invoice->payments->sum('cambio')) }}
                                </div>

                            </div>
                        </div>
                        <div class="p-5 bg-green-100 rounded shadow-sm dark:bg-gray-800">
                            <div class="text-base text-gray-400 dark:text-gray-300">Resta</div>
                            <div class="flex items-center pt-1">
                                <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    ${{ formatNumber($invoice->rest) }}</div>

                            </div>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="flex justify-end py-4">
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button type="button" wire:click="printInvoice"
                        class="py-2 px-4 text-sm font-medium text-gray-900 bg-white rounded-l-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                        Imprimir
                        <span class="far fa-print"></span>
                    </button>
                    <button type="button" wire:click="sendByWS"
                        class="py-2 px-4 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                        Enviar
                        <span class="fab fa-whatsapp"></span>
                    </button>

                </div>

            </div>
        </div>

    </div>

</div>
