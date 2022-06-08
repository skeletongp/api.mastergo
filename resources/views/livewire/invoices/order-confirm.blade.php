<div class="">
    @include('includes.authorize')
    <x-modal id="modalConfirmInvoice"  maxWidth="max-w-3xl">
        <x-slot name="button">
            <span>
                Cobrar 
            </span>
        </x-slot>
        <x-slot name="title">
            Cobrar Pedido Nº. {{ $form['number'] }}
        </x-slot>
        
        <form wire:submit.prevent="tryPayInvoice" class="grid grid-cols-5 gap-4 p-3 max-w-3xl mx-auto text-left">
            {{-- Primera fila --}}
            <div class="col-span-2">
                <x-dinput class="text-xl font-bold" label="Vendedor" id="form{{ $form['id'] }}.seller" disabled
                    value="{{ $form['seller']['fullname'] }}">
                    </x-input>
            </div>



            {{-- Segunda Fila --}}
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.amount" label="Subtotal"
                    id="form{{ $form['id'] }}.amount">
                </x-dinput>
            </div>

            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.tax" label="Impuestos"
                    id="form{{ $form['id'] }}.tax"></x-dinput>
            </div>
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.discount"
                    label="Descuento" id="form{{ $form['id'] }}.discount"></x-dinput>
                <x-input-error for="form.rest"></x-input-error>
            </div>
            <div>
                <x-dinput class="text-xl font-bold text-green-600" type="number" disabled wire:model.lazy="form.total"
                    label="Total" id="form{{ $form['id'] }}.total"></x-dinput>
                <x-input-error for="form.total"></x-input-error>
            </div>

            {{-- Tercera Fila --}}
            <div>
                <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                    wire:model.lazy="form.efectivo" label="Efectivo" id="form{{ $form['id'] }}.efectivo"></x-dinput>
                <x-input-error for="form.efectivo"></x-input-error>
            </div>
            <div>
                <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                    wire:model.lazy="form.tarjeta" label="Tarjeta/Cheque" id="form{{ $form['id'] }}.tarjeta">
                </x-dinput>
                <x-input-error for="form.tarjeta"></x-input-error>
            </div>
            @if (auth()->user()->store->banks->count())
                <div>
                    <x-dinput onfocus="clrInput(event)" onblur="restoreInput(event)" class="text-xl font-bold"
                        type="number" wire:model.lazy="form.transferencia" label="Transferencia"
                        id="form{{ $form['id'] }}.transferencia"></x-dinput>
                    <x-input-error for="form.transferencia"></x-input-error>
                </div>
            @endif


            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.payed" label="Pagado"
                    id="form{{ $form['id'] }}.payed"></x-dinput>
            </div>

            {{-- Cuarta Fila --}}
            @if (auth()->user()->store->banks->count())
                <div class="col-span-2">
                    <x-base-select id="{{ $form['id'] }}bank_id" wire:model="bank_id" label="Banco"
                        class="py-3">
                        <option value=""></option>
                        @foreach ($banks as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </x-base-select>
                    <x-input-error for="bank">Seleccione un Banco</x-input-error>
                </div>
                <div>
                    <x-dinput class="text-sm py-4" type="text" wire:model.lazy="reference" label="No. Referencia"
                    id="f{{ $form['id'] }}.reference" placeholder="Nº. Ref."></x-dinput>
                    <x-input-error for="reference">Requerido</x-input-error>
                </div>
            @endif

            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.rest" label="Pendiente"
                    id="form{{ $form['id'] }}.rest"></x-dinput>
                <x-input-error for="form.rest"></x-input-error>
            </div>
            <div>
                <x-dinput class="text-xl font-bold" type="number" disabled wire:model.lazy="form.cambio"
                    label="Devuelta" id="form{{ $form['id'] }}.cambio"></x-dinput>
                <x-input-error for="form.cambio"></x-input-error>
            </div>

            <div class="{{ auth()->user()->store->banks->count()? 'col-span-5': 'col-span-4' }} space-y-3">
                <x-dinput class="text-xl font-bold" type="text" wire:model.lazy="form.note" label="Nota"
                    id="form{{ $form['id'] }}.note" placeholder="Ingrese una nota a la factura"></x-dinput>
            </div>
            @if ($form['tarjeta'] > 0)
                <div class="flex space-x-4 col-span-5">
                    <div class="w-full">
                        <label for="{{ $form['id'] }}cheque" class="flex items-center space-x-4 pb-4 cursor-pointer">
                            <span class="fas fa-image text-xl"></span>
                            <span class="shadow-sm rounded-xl hover:bg-gray-100  px-4 py-2.5">Imagen del adjunto</span>
                            @if ($photo_path)
                                <span class=" text-sm shadow-sm rounded-xl bg-blue-100  px-4 py-2.5">Tamaño:
                                    {{ formatNumber($cheque->getSize() / 1024) }} KB</span>
                            @endif
                            <input wire:model="cheque" type="file" class="hidden" name="cheque"
                                id="{{ $form['id'] }}cheque" accept="image/*" capture>
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
            <button
            class="space-x-2 z-50 text-4xl absolute bg-gray-200 bg-opacity-20 top-0 bottom-0 left-0 right-0 bg-transparent"
            wire:loading>
            <x-loading></x-loading>
        </button>
            <div class="col-span-5 flex justify-end">
                <x-button  wire:loading.attr="disabled">
                    Cobrar
                </x-button>
            </div>

        </form>
    </x-modal>
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
            
            Livewire.on('printThermal', function(url) {
                printJS({
                    printable: url,
                });
            });
        </script>
    @endpush

</div>
