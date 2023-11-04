<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('outcomes') }}
    @endslot
    @slot('rightButton')
        @can('Crear Gastos')
          <div class="flex items-center space-x-3">
            @livewire('reports.create-outcome', key(uniqid()))
            @livewire('outcomes.newcreditoutcome', key(uniqid()))

          </div>
        @endcan
    @endslot
    <div class=" w-full ">
        <div class=" mx-auto py-2 w-max min-h-max h-full  sm:px-6 lg:px-8">

            <livewire:reports.outcome-table />
        </div>
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
                texto(conector, obj.store.name.toUpperCase() + "\n");
                conector.setEmphasize(0);
                conector.setFontSize(1, 1)
                texto(conector, 'RNC: ')
                texto(conector, obj.store.rnc + "\n");
                texto(conector, obj.store.phone + "\n");
                texto(conector, obj.store.address + "\n");
                texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin Encabezado */

                /* Sección Título */
                conector.setEmphasize(1);
                conector.setFontSize(1, 2);
                texto(conector, 'RECIBO DE PAGO DE GASTO');
                conector.setEmphasize(0);
                conector.setFontSize(1, 1);
                conector.feed(2)
                /* Fin Sección */



                conector.setEmphasize(1);
                texto(conector, 'NCF: ')
                conector.setEmphasize(0);
                texto(conector, obj.payable.ncf ? obj.payable.ncf : " ND");
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'FECHA: ')
                conector.setEmphasize(0);
                texto(conector, obj.day);
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'CÓD.: ')
                conector.setEmphasize(0);
                texto(conector, obj.payable.ref);
                conector.feed(1);

                align(conector, 'center');
                texto(conector, '--------------------------------------');
                conector.feed(1);
                /* Fin detalle */


                /* Datos del cliente */
                align(conector, 'left');
                conector.setEmphasize(1);
                texto(conector, 'SUPLIDOR: ')
                conector.setEmphasize(0);
                texto(conector, obj.payer.fullname);
                conector.feed(1);

                conector.setEmphasize(1);
                texto(conector, 'RNC: ');
                conector.setEmphasize(0);
                texto(conector, obj.payer.rnc)
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
</x-app-layout>
