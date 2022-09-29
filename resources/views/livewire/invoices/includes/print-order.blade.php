@push('js')
    <script>
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
        var removeAccent = function(string) {
            string = string.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            return string;
        };
        function texto(impresora, string) {
            impresora.write(removeAccent(string.toUpperCase()));
        }
        var formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        });
        var toDecimal = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 1,
            maximumFractionDigits: 2,
        });
        var sumField = (order, field) => order
            .map(items => items[field])
            .reduce((prev, curr) => parseFloat(prev) + parseFloat(curr), 0);

        Livewire.on('printOrder', function(ord) {
            order = ord;
            printOrder(order);
        })

        function printOrder(order) {
            if (!order.place.preference.printer) {
                Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                return false;
            }
            if (order.place.preference.print_order == 'No') {
                Livewire.emit('showAlert', 'No se imprime la orden', 'warning');
                return false;
            }

            const conect = new Impresora();
            conect.cut();
            /* Encabezado Negocio */
            align(conect, 'center');
            
            conect.setEmphasize(1);
            conect.setFontSize(2, 2)
            texto(conect, order.store.name.toUpperCase() + "\n");
            conect.setEmphasize(0);
            conect.setFontSize(1, 1)
            texto(conect, 'RNC: ')
            texto(conect, order.store.rnc + "\n");
            texto(conect, order.store.phone + "\n");
            texto(conect, order.store.address + "\n");
            align(conect, 'center');
            texto(conect, '--------------------------------------');
            conect.feed(1);
            /* Fin Encabezado */


            /* Detalle Factura */
            align(conect, 'left');
            conect.setEmphasize(1);
            texto(conect, "CONDICIÓN: ");
            align(conect, 'right');
            conect.setEmphasize(0);
            texto(conect, order.condition.toUpperCase())
            conect.feed(1);

            conect.setEmphasize(1);
            texto(conect, 'NCF: ')
            conect.setEmphasize(0);
            texto(conect, order.comprobante ? order.comprobante.ncf : " 0000000000");
            conect.feed(1);

            align(conect, 'center');
            texto(conect, '--------------------------------------');
            conect.feed(1);
            /* Fin detalle */


            /* Datos del cliente */
            align(conect, 'left');
            conect.setEmphasize(1);
            texto(conect, 'CLIENTE: ')
            conect.setEmphasize(0);
            texto(conect, order.name ? order.name.toUpperCase() : order.client.name.toUpperCase());
            conect.feed(1);

            conect.setEmphasize(1);
            texto(conect, 'RNC: ');
            conect.setEmphasize(0);
            if (order.rnc) {
                texto(conect, order.rnc);
            } else {
                texto(conect, order.client.rnc ? order.client.rnc : '0000000000')
            }

            texto(conect, ' / ');

            conect.setEmphasize(1);
            texto(conect, 'TEL: ');
            conect.setEmphasize(0);
            texto(conect, order.client.phone);
            conect.feed(1);

            conect.setEmphasize(1);
            texto(conect, 'DIR: ');
            conect.setEmphasize(0);
            texto(conect, order.client.address ? order.client.address : 'N/D');
            conect.feed(1);

            align(conect, 'center');
            texto(conect, '--------------------------------------');
            conect.feed(1);
            /* Fin Cliente */


            /* Título de la Orden */
            conect.setEmphasize(1);
            conect.setFontSize(1, 2)
            align(conect, 'center');
            texto(conect, 'ORDEN DE COBRO No. ' + order.number)
            conect.setFontSize(1, 1)
            conect.feed(2);
            /* Fin Título */



            /* Sección totales */
            align(conect, 'right')
            conect.setEmphasize(1);
            texto(conect, 'SUBTOTAL: ');
            conect.setEmphasize(0);
            texto(conect, formatter.format(order.payment.amount));
            conect.feed(1);

            if (order.payment.discount > 0) {
                conect.setEmphasize(1);
                texto(conect, 'DESCUENTO: ');
                conect.setEmphasize(0);
                texto(conect, formatter.format(order.payment.discount));
                conect.feed(1);
            }

            if (order.payment.tax > 0) {
                conect.setEmphasize(1);
                texto(conect, 'IMPUESTOS: ');
                conect.setEmphasize(0);
                texto(conect, formatter.format(order.payment.tax));
                conect.feed(1);
            }
            conect.feed(1);
            conect.setFontSize(1, 2)
            conect.setEmphasize(1);
            texto(conect, 'TOTAL: ');
            texto(conect, formatter.format(order.payment.total));
            conect.feed(1);


            texto(conect, '--------------------------------------');
            conect.feed(1);
            /* Fin Sección */

            /* Sección personas */
            conect.setFontSize(1, 1)
            conect.setEmphasize(1);
            texto(conect, 'VENDEDOR: ');
            conect.setEmphasize(0);
            texto(conect, order.seller.fullname);
            conect.feed(1);

            /* Fin sección */



            conect.feed(3);
            conect.cut();
            conect.imprimirEnImpresora(order.place.preference.printer)
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
