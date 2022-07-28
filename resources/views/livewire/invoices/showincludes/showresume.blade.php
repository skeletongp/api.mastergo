<div class="w-full">

    <div class="bg-white p-3 shadow-sm rounded-sm">
        <div class="">
            <div class="flex items-center space-x-2 font-bold uppercase
            leading-8">

                <span class="tracking-wide text-lg py-4">Detalles de la factura</span>
            </div>
            <div class="grid grid-cols-3 text-sm">
                <div class="grid col-span-1 grid-cols-3">
                    <div class="py-2 font-bold uppercase
                    ">Nro.</div>
                    <div class="py-2 col-span-2">{{ $invoice->number }}</div>
                </div>
                <div class="grid col-span-2 grid-cols-6">
                    <div class="py-2 font-bold uppercase
                    ">Cliente</div>
                    <div class="py-2 col-span-5">{{ $invoice->name ?: $invoice->client->name }}</div>
                </div>
                <div class="grid col-span-1 grid-cols-3">
                    <div class="py-2 font-bold uppercase
                    ">NCF</div>
                    <div class="py-2 col-span-2">{{ $invoice->payment->ncf ?: 'B0000000000' }}</div>
                </div>
                <div class="grid col-span-1 grid-cols-3">
                    <div class="py-2 font-bold uppercase
                    ">Subt.</div>
                    <div class="py-2 col-span-2">${{ formatNumber($invoice->payment->amount) }}</div>
                </div>
                <div class="grid col-span-1 grid-cols-3">
                    <div class="py-2 font-bold uppercase
                    ">Desc.</div>
                    <div class="py-2 col-span-2">${{ formatNumber($invoice->payment->discount) }}</div>
                </div>
                <div class="grid col-span-1 grid-cols-3 font-bold">
                    <div class="py-2 font-bold uppercase
                    ">Total </div>
                    <div class="py-2 col-span-2">${{ formatNumber($invoice->payment->total) }}</div>
                </div>

                <div class="grid col-span-1 grid-cols-3">
                    <div class="py-2 font-bold uppercase
                    ">Efect.</div>
                    <div class="py-2 col-span-2">${{ formatNumber($invoice->payments->sum('efectivo')) }}</div>
                </div>
                <div class="grid col-span-1 grid-cols-3">
                    <div class="py-2 font-bold uppercase
                    ">Transf.</div>
                    <div class="py-2 col-span-2">${{ formatNumber($invoice->payments->sum('transferencia')) }}</div>
                </div>
                <div class="grid col-span-1 grid-cols-3">
                    <div class="py-2 font-bold uppercase
                    ">Otros</div>
                    <div class="py-2 col-span-2">${{ formatNumber($invoice->payments->sum('tarjeta')) }}</div>
                </div>
                <div class="grid col-span-1 grid-cols-3">
                    <div class="py-2 font-bold uppercase
                    ">Pagado </div>
                    <div class="py-2 col-span-2">${{ formatNumber($invoice->payments->sum('payments')) }}</div>
                </div>
                <div class="grid col-span-1 grid-cols-3 {{ $invoice->rest > 0 ? 'text-red-400 font-bold' : '' }}">
                    <div class="py-2 font-bold uppercase
                    ">Resta </div>
                    <div class="py-2 col-span-2">${{ formatNumber($invoice->rest) }}</div>
                </div>
            </div>

          <div class="flex py-4">
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <button type="button"  wire:click="printInvoice"
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
