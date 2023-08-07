<div class="pt-12">
    @can('Cobrar Facturas')
        @if ($invoice->rest > 0)

            <form action="" wire:submit.prevent="storePayment">

                <div class="py-4  flex space-x-4 items-start">
                    <div>
                        <x-base-input {{-- s --}} onfocus="clrInput(event)" onblur="restoreInput(event)"
                            class="text-xl font-bold" type="number" wire:model.lazy="payment.efectivo" label="Efectivo"
                            id="payment.efectivo">
                        </x-base-input>
                        <x-input-error for="payment.efectivo">Verifique el campo</x-input-error>
                    </div>
                    <div>
                        <x-base-input status="{{ $cobrable ? '' : 'disabled' }}" onfocus="clrInput(event)"
                            onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                            wire:model.lazy="payment.tarjeta" label="Tarjeta/Cheque" id="payment.tarjeta">
                        </x-base-input>
                        <x-input-error for="payment.tarjeta">Verifique el campo</x-input-error>
                    </div>
                    @if (auth()->user()->store->banks->count())
                        <div>
                            <x-base-input onfocus="clrInput(event)"
                                onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                                wire:model.lazy="payment.transferencia" label="Transferencia" id="payment.transferencia">
                            </x-base-input>
                            <x-input-error for="payment.transferencia">Verifique el campo</x-input-error>
                        </div>
                    @endif
                    <div class=" pt-10 bottom-0">
                        <x-button class="flex space-x-4" {{-- disabled="{{ !$cobrable }}" --}}>
                            <span>
                                Cobrar
                            </span>
                        </x-button>
                    </div>
                    @if (!$cobrable)
                        <div class="pr-4">
                            <x-warning idTT="noCobrable">
                                <x-slot name="msg">
                                    Esta factura no puede ser cobrada porque hay una anterior con saldo pendiente. Favor
                                    saldar, para poder cobrar esta.
                                </x-slot>
                                <span class="fas fa-info-circle">
                                </span>
                            </x-warning>
                        </div>
                    @endif
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
                            <x-base-input class="text-sm py-4" type="text" wire:model.lazy="reference"
                                label="No. Referencia" id="payment.reference" placeholder="Nº. Ref."></x-base-input>
                            <x-input-error for="reference">Verifique el campo</x-input-error>
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
            Livewire.on('printPayment', function(payment) {
                printP(payment);
            })

            function align(conector, dir) {
                switch (dir) {
                    case 'right':
                        conector.setAlign(dir);
                        break;
                    case 'center':
                        conector.setAlign(dir);
                        break;
                    case 'left':
                        conector.setAlign(dir);
                        break;
                }
            }
            var formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            });
            var toDecimal = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 1,
                maximumFractionDigits: 2,
            });
            var sumField = (obj, field) => obj
                .map(items => items[field])
                .reduce((prev, curr) => parseFloat(prev) + parseFloat(curr), 0);
            var removeAccent = function(string) {
                string = string.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                return string;
            };

            function texto(impresora, string) {
                impresora.write(removeAccent(string.toUpperCase()));

            }

            function printP(payment) {
                obj = payment;
                if (!obj.place.preference.printer) {
                    Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                    return false;
                }
                conector = new Impresora();
                conector.cut();
                /* Encabezado Negocio */
                align(conector, 'center');
                conector.setEmphasize(1);
                conector.setFontSize(1, 2)
                texto(conector, obj.payable.store.name.toUpperCase() + "\n");
                conector.setEmphasize(0);
                conector.setFontSize(1, 1)
                texto(conector, 'RNC: ')
                texto(conector, obj.payable.store.rnc + "\n");
                texto(conector, obj.payable.store.phone + "\n");
                texto(conector, obj.payable.store.address + "\n");
                texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin Encabezado */

                /* Sección Título */
                conector.setEmphasize(1);
                conector.setFontSize(1, 2);
                texto(conector, 'RECIBO DE PAGO DE INGRESO');
                conector.setEmphasize(0);
                conector.setFontSize(1, 1);
                conector.feed(2)
                /* Fin Sección */

                /* Detalle Factura */
                align(conector, 'left');
                conector.setEmphasize(1);
                texto(conector, "CONDICIÓN: ");
                align(conector, 'right');
                conector.setEmphasize(0);
                texto(conector, obj.payable.condition.toUpperCase())
                conector.feed(1);


                conector.setEmphasize(1);
                texto(conector, 'NCF: ')
                conector.setEmphasize(0);
                texto(conector, obj.payable.payment.ncf ? obj.payable.payment.ncf : " 0000000000");
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'FECHA: ')
                conector.setEmphasize(0);
                texto(conector, obj.day);
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'FACT. NO.: ')
                conector.setEmphasize(0);
                texto(conector, obj.payable.number);
                conector.feed(1);

                align(conector, 'center');
                texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin detalle */


                /* Datos del cliente */
                align(conector, 'left');
                conector.setEmphasize(1);
                texto(conector, 'CLIENTE: ')
                conector.setEmphasize(0);
                texto(conector, obj.payable.name ? obj.payable.name : (obj.payer.name ? obj.payer.name : obj.payer.fullname));
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'RNC: ');
                conector.setEmphasize(0);
                texto(conector, obj.payer.rnc ? obj.payer.rnc : '0000000000')
                texto(conector, ' / ');

                conector.setEmphasize(1);
                texto(conector, 'TEL: ');
                conector.setEmphasize(0);
                texto(conector, obj.payer.phone);
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'DIR: ');
                conector.setEmphasize(0);
                texto(conector, obj.payer.address ? obj.payer.address : 'N/D');
                conector.feed(1);
                align(conector, 'center');
                texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin Cliente */

                /* Encabezado de pago */
                conector.setEmphasize(1);
                align(conector, 'center');
                conector.setFontSize(1, 2);
                texto(conector, 'DETALLES DEL PAGO')
                conector.setFontSize(1, 1);
                conector.feed(1)
                conector.setEmphasize(0);
                /* Fin encabezados */

                /* Detalles del pago */
                align(conector, 'left');
                conector.setEmphasize(1);
                texto(conector, 'SALDO ANTERIOR: ')
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.total));
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'EFECTIVO: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.efectivo));
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'TRANSFERENCIA: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.transferencia));
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'OTROS: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.tarjeta));
                conector.feed(2);

                conector.setEmphasize(1);
                texto(conector, 'TOTAL PAGADO: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(parseFloat(obj.efectivo) + parseFloat(obj.tarjeta) + parseFloat(obj
                    .transferencia)));
                conector.feed(1);


                conector.setEmphasize(1);
                texto(conector, 'SALDO RESTANTE: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.rest));
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'CAMBIO: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.cambio));
                conector.feed(1);

                align(conector, 'center');
                texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin Detalles */
                /* Sección personas */

                conector.setEmphasize(1);
                texto(conector, 'CAJERO: ');
                conector.setEmphasize(0);
                texto(conector, obj.contable.fullname);
                conector.feed(2);
                /* Fin sección */

                /* Pie */
                texto(conector, '-------- GRACIAS POR PREFERIRNOS --------\n');
                conector.feed(2);
                /* Fin pie */

                conector.feed(3);
                conector.cut();
                conector.imprimirEnImpresora(obj.place.preference.printer)
                    .then(respuestaAlImprimir => {
                        if (respuestaAlImprimir === true) {
                            console.log("Impreso correctamente");
                        } else {
                            console.log("Error. La respuesta es: " + respuestaAlImprimir);
                        }
                    });

            }
        </script>
    @endpush
</div>
