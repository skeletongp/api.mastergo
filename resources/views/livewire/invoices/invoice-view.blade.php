   <div class="w-full flex flex-col lg:flex-row  items-start">
       <div class="w-full lg:w-3/5 pb-6 relative">
           <div
               class="w-full text-lg font-medium text-gray-900 bg-white  rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
               <livewire:invoices.invoice-list />
           </div>
       </div>

       <div class="w-full lg:w-3/5 h-full  pl-0" x-data="{ open: true }">
           @if ($currentInvoice)
               <div class="mx-auto ">
                   <div
                       class="flex justify-end relative items-center pb-2 pt-1 space-x-4 bg-gray-100 pr-4 rounded-tr-lg">
                       <div class="absolute hidden lg:flex left-2 overflow-hidden overflow-ellipsis whitespace-nowrap  w-[22rem] ">
                           <span class="font-bold uppercase text-xl ">
                               {{ ellipsis($currentInvoice->name ?: ($currentInvoice->client->name ?: $currentInvoice->client->contact->fullname), 17) }}
                               →{{ $currentInvoice->number }} →
                               ${{ formatNumber($currentInvoice->payment->total) }}</span>
                       </div>
                       <x-tooltip id="updatePDF">Actualizar PDF</x-tooltip>
                       <a role="button" class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                           data-tooltip-target="updatePDF" wire:click="updatePDF">
                           <span class="far fa-sync-alt"></span>
                       </a>
                       <x-tooltip id="sendInvoice">Enviar Factura</x-tooltip>
                       <a role="button" class="cursor-pointer py-1 px-3 rounded-lg shadow-lg hover:bg-gray-300"
                           wire:click="sendInvoice" data-tooltip-target="sendInvoice">
                           <span class="far fa-share-alt"></span>
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
                               data-tooltip-target="printAction" wire:click="$emit('changeInvoice',{{ $invoice, true }})">
                               <span class="far fa-print"></span>
                           </button>
                       @endcan
                   </div>
                   <div class=" mx-auto relative w-full">
                       @if ($invoice->pdf)
                           <iframe src="https://docs.google.com/gview?url={{ $invoice->pdf->pathLetter }}&embedded=true" class="w-full lg:w-[700px] h-[700px]"  frameborder="0"
                               type="application/pdf">
                           </iframe>
                           
                       @endif
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
