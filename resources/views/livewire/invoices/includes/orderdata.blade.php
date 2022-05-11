<div>
    <x-modal open="{{ false }}" title="Imprimir orden" fitVerticalContainer="true" hideButton="true">
        <x-slot name="button">
            <div id="openData"></div>
        </x-slot>
        @if ($invoice)
            <div class="h-[100mm] w-[78mm] space-y-2 p-4 pt-8 shadow-xl mx-auto" id="orderContent">
                <div class="flex items-center justify-center uppercase font-bold">
                    <h1 class="text-center text-lg">{{ $invoice->store->name }}</h1>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-bold">Orden No. </span>
                    <span>{{ $invoice->number }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-bold">Cliente </span>
                    <span>{{ $invoice->client->fullname }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-bold">Condición </span>
                    <span>{{ $invoice->condition }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-bold">Monto Total </span>
                    <span>${{ $invoice->payment->total }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-bold">Vendedor </span>
                    <span>{{ $invoice->seller->fullname }}</span>
                </div>
            </div>
            <div class="flex justify-end my-4 ">
                <x-button class="" onclick="print()">
                    Imprimir
                </x-button>
            </div>
        @endif
    </x-modal>
    @push('js')
        <script>
            Livewire.on('openData', function() {
                $('#openData').click();
            })
            function print() {
                printJS({
                    printable: 'orderContent',
                    showModal: true,
                    type: 'html',
                    css: '/css/app.css',
                    modalMessage: 'Cargando documento'
                });
            }
            
        </script>
    @endpush
</div>
