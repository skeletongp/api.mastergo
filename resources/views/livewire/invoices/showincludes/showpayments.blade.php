<div class="pt-12">
    @can('Cobrar Facturas')
        @if ($invoice->rest > 0)

            <form action="" wire:submit.prevent="storePayment">

                <div class="py-4  flex space-x-4 items-start">
                    <div>
                        <x-base-input {{-- s --}} onfocus="clrInput(event)"
                            onblur="restoreInput(event)" class="text-xl font-bold" type="number"
                            wire:model.lazy="payment.efectivo" label="Efectivo" id="payment.efectivo">
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
                            <x-base-input status="{{ $cobrable ? '' : 'disabled' }}" onfocus="clrInput(event)"
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
    @include('livewire.invoices.includes.invoice-js')
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
                alert('Presione aceptar...');
                printP(payment);
            })

            function align(conector, dir) {
                switch (dir) {
                    case 'right':
                        conector.establecerJustificacion(ConectorPlugin.Constantes.AlineacionDerecha);
                        break;
                    case 'center':
                        conector.establecerJustificacion(ConectorPlugin.Constantes.AlineacionCentro);
                        break;
                    case 'left':
                        conector.establecerJustificacion(ConectorPlugin.Constantes.AlineacionIzquierda);
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


            function printP(payment) {
                obj = payment;
                if (!obj.place.preference.printer) {
                    Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                    return false;
                }
                conector = new ConectorPlugin();
                conector.cortar();
                /* Encabezado Negocio */
                align(conector, 'center');
                conector.establecerEnfatizado(1);
                conector.establecerTamanioFuente(1.3, 2)
                conector.textoConAcentos(obj.payable.store.name.toUpperCase() + "\n");
                conector.establecerEnfatizado(0);
                conector.establecerTamanioFuente(1, 1)
                conector.texto('RNC: ')
                conector.texto(obj.payable.store.rnc + "\n");
                conector.texto(obj.payable.store.phone + "\n");
                conector.texto(obj.payable.store.address + "\n");
                conector.texto('--------------------------------------');
                conector.feed(1);
                /* Fin Encabezado */

                /* Sección Título */
                conector.establecerEnfatizado(1);
                conector.establecerTamanioFuente(1.3, 2);
                conector.texto('RECIBO DE PAGO');
                conector.establecerEnfatizado(0);
                conector.establecerTamanioFuente(1, 1);
                conector.feed(2)
                /* Fin Sección */

                /* Detalle Factura */
                align(conector, 'left');
                conector.establecerEnfatizado(1);
                conector.texto("CONDICIÓN: ");
                align(conector, 'right');
                conector.establecerEnfatizado(0);
                conector.texto(obj.payable.condition.toUpperCase())
                conector.feed(1);

                console.log(obj.payable)
                conector.establecerEnfatizado(1);
                conector.texto('NCF: ')
                conector.establecerEnfatizado(0);
                conector.texto(obj.payable.payment.ncf ?obj.payable.payment.ncf: " 0000000000");
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('FECHA: ')
                conector.establecerEnfatizado(0);
                conector.texto(obj.day);
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('FACT. NO.: ')
                conector.establecerEnfatizado(0);
                conector.texto(obj.payable.number);
                conector.feed(1);

                align(conector, 'center');
                conector.texto('--------------------------------------');
                conector.feed(1);
                /* Fin detalle */


                /* Datos del cliente */
                align(conector, 'left');
                conector.establecerEnfatizado(1);
                conector.texto('CLIENTE: ')
                conector.establecerEnfatizado(0);
                conector.texto(obj.payable.name ? obj.payable.name.toUpperCase() : (obj.payer.name? obj.payer.name.toUpperCase() : obj.payer.fullname.toUpperCase()));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('RNC: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.payer.rnc ? obj.payer.rnc : '0000000000')
                conector.texto(' / ');

                conector.establecerEnfatizado(1);
                conector.texto('TEL: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.payer.phone);
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('DIR: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.payer.address ? obj.payer.address : 'N/D');
                conector.feed(1);
                align(conector, 'center');
                conector.texto('--------------------------------------');
                conector.feed(1);
                /* Fin Cliente */

                /* Encabezado de pago */
                conector.establecerEnfatizado(1);
                align(conector, 'center');
                conector.establecerTamanioFuente(1.3, 1.6);
                conector.texto('DETALLES DEL PAGO')
                conector.establecerTamanioFuente(1, 1);
                conector.feed(1)
                conector.establecerEnfatizado(0);
                /* Fin encabezados */

                /* Detalles del pago */
                align(conector, 'left');
                conector.establecerEnfatizado(1);
                conector.texto('SALDO ANTERIOR: ')
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.total));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('EFECTIVO: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.efectivo));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('TRANSFERENCIA: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.transferencia));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('OTROS: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.tarjeta));
                conector.feed(2);

                conector.establecerEnfatizado(1);
                conector.texto('TOTAL PAGADO: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(parseFloat(obj.efectivo) + parseFloat(obj.tarjeta) + parseFloat(obj
                    .transferencia)));
                conector.feed(1);


                conector.establecerEnfatizado(1);
                conector.texto('SALDO RESTANTE: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.rest));
                conector.feed(1);

                conector.establecerEnfatizado(1);
                conector.texto('CAMBIO: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.cambio));
                conector.feed(1);

                align(conector, 'center');
                conector.texto('--------------------------------------');
                conector.feed(1);
                /* Fin Detalles */
                /* Sección personas */

                conector.establecerEnfatizado(1);
                conector.texto('CAJERO: ');
                conector.establecerEnfatizado(0);
                conector.texto(obj.contable.fullname);
                conector.feed(2);
                /* Fin sección */

                /* Pie */
                conector.texto('-------- GRACIAS POR PREFERIRNOS --------\n');
                conector.feed(2);
                /* Fin pie */

                conector.feed(3);
                conector.cortar();
                conector.imprimirEn(obj.place.preference.printer)
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
