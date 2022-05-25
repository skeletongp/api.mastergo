<div class="pt-12">
    @can('Cobrar Facturas')
        @if ($invoice->rest > 0)
            <form action="" wire:submit.prevent="storePayment">
                <div class="py-4  flex space-x-4 items-start">
                    <div>
                        <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold"
                            type="number" wire:model.lazy="payment.efectivo" label="Efectivo" id="payment.efectivo">
                        </x-dinput>
                        <x-input-error for="payment.efectivo">Verifique el campo</x-input-error>
                    </div>
                    <div>
                        <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold"
                            type="number" wire:model.lazy="payment.tarjeta" label="Tarjeta/Cheque" id="payment.tarjeta">
                        </x-dinput>
                        <x-input-error for="payment.tarjeta">Verifique el campo</x-input-error>
                    </div>
                    @if (auth()->user()->store->banks->count())
                        <div>
                            <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold"
                                type="number" wire:model.lazy="payment.transferencia" label="Transferencia"
                                id="payment.transferencia"></x-dinput>
                            <x-input-error for="payment.transferencia">Verifique el campo</x-input-error>
                        </div>
                    @endif
                    <div class=" pt-8 bottom-0">
                        <x-button>Cobrar</x-button>
                    </div>
                </div>
                @if (!empty($payment['transferencia']) && $payment['transferencia'] > 0)
                    <div class="flex space-x-4 items-start ">
                        <div class="w-full">
                            <x-base-select id="bank_id" wire:model="bank_id" label="Cuenta de Banco" class="py-3">
                                <option value=""></option>
                                @foreach ($banks as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="bank">Seleccione un Banco</x-input-error>
                        </div>
                        <div class="w-full">
                            <x-dinput class="text-sm py-4" type="text" wire:model.lazy="reference" label="No. Referencia"
                                id="payment.reference" placeholder="Nº. Ref."></x-dinput>
                            <x-input-error for="reference">Verifique el campo</x-input-error>
                        </div>

                    </div>
                @endif
                @if (!empty($payment['tarjeta']) && $payment['tarjeta'] > 0)
                    <div class="flex space-x-4 col-span-5 mt-4">
                        <div class="w-full">
                            <label for="cheque" class="flex items-center space-x-4 pb-4 cursor-pointer">
                                <span class="fas fa-image text-xl"></span>
                                <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen del adjunto</span>
                                @if ($photo_path)
                                    <span class=" text-sm shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño:
                                        {{ formatNumber($cheque->getSize() / 1024) }} KB</span>
                                @endif
                                <input wire:model="cheque" type="file" class="hidden" name="cheque" id="cheque"
                                    accept="image/*" capture>
                            </label>
                            <hr>
                            <x-input-error for="cheque" />
                        </div>
                        <div class="w-96 h-[3rem]  bg-center bg-cover"
                            style="background-image: url({{ $photo_path ? $cheque->temporaryUrl() : '' }})">
                        </div>
                        <div class="">
                            <x-button class="space-x-2 z-50 text-sm flex items-center" wire:target="cheque" wire:loading>
                                <div class="animate-spin">
                                    <span class="fa fa-spinner ">
                                    </span>
                                </div>
                                <h1>Procesando</h1>
                            </x-button>
                        </div>
                    </div>
                @endif
            </form>
        @endif
    @endcan
    <div class="">
        <livewire:invoices.show-includes.payments-from-invoice :invoice="$invoice->load('payments')" :key="$invoice->id" />
    </div>
    @push('js')
        <script>
            var prevVal = 0;

            function clrInput(event) {
                input = event.target;
                prevVal = input.value;
                input.value = '';
            }

            function restoreInput(event) {
                input = event.target;
                input.value = prevVal;
            }
            Livewire.on('printInvoice', function(url) {

                printJS({
                    printable: url,
                    showModal: true,

                    modalMessage: 'Cargando documento'
                });
            });
        </script>
    @endpush
</div>
