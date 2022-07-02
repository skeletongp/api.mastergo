@push('js')
    <script>
        function align(conect, dir) {
            switch (dir) {
                case 'right':
                    conect.establecerJustificacion(ConectorPlugin.Constantes.AlineacionDerecha);
                    break;
                case 'center':
                    conect.establecerJustificacion(ConectorPlugin.Constantes.AlineacionCentro);
                    break;
                case 'left':
                    conect.establecerJustificacion(ConectorPlugin.Constantes.AlineacionIzquierda);
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

            const conect = new ConectorPlugin();
            conect.cortar();
            /* Encabezado Negocio */
            align(conect, 'center');
            if (order.store.image) {
                conect.imagenDesdeUrl(order.store.image.path);
                conect.feed(1)
            }
            conect.establecerEnfatizado(1);
            conect.establecerTamanioFuente(1.3, 2)
            conect.texto(order.store.name.toUpperCase() + "\n");
            conect.establecerEnfatizado(0);
            conect.establecerTamanioFuente(1, 1)
            conect.texto('RNC: ')
            conect.texto(order.store.rnc + "\n");
            conect.texto(order.store.phone + "\n");
            conect.texto(order.store.address + "\n");
            align(conect, 'center');
            conect.texto('--------------------------------------');
            conect.feed(1);
            /* Fin Encabezado */


            /* Detalle Factura */
            align(conect, 'left');
            conect.establecerEnfatizado(1);
            conect.texto("CONDICIÓN: ");
            align(conect, 'right');
            conect.establecerEnfatizado(0);
            conect.texto(order.condition.toUpperCase())
            conect.feed(1);

            conect.establecerEnfatizado(1);
            conect.texto('NCF: ')
            conect.establecerEnfatizado(0);
            conect.texto(order.comprobante ? order.comprobante.ncf : " 0000000000");
            conect.feed(1);

            align(conect, 'center');
            conect.texto('--------------------------------------');
            conect.feed(1);
            /* Fin detalle */


            /* Datos del cliente */
            align(conect, 'left');
            conect.establecerEnfatizado(1);
            conect.texto('CLIENTE: ')
            conect.establecerEnfatizado(0);
            conect.texto(order.name ? order.name.toUpperCase() : order.client.name.toUpperCase());
            conect.feed(1);

            conect.establecerEnfatizado(1);
            conect.texto('RNC: ');
            conect.establecerEnfatizado(0);
            if (order.rnc) {
                conect.texto(order.rnc);
            } else {
                conect.texto(order.client.rnc ? order.client.rnc : '0000000000')
            }

            conect.texto(' / ');

            conect.establecerEnfatizado(1);
            conect.texto('TEL: ');
            conect.establecerEnfatizado(0);
            conect.texto(order.client.phone);
            conect.feed(1);

            conect.establecerEnfatizado(1);
            conect.texto('DIR: ');
            conect.establecerEnfatizado(0);
            conect.texto(order.client.address ? order.client.address : 'N/D');
            conect.feed(1);

            align(conect, 'center');
            conect.texto('--------------------------------------');
            conect.feed(1);
            /* Fin Cliente */


            /* Título de la Orden */
            conect.establecerEnfatizado(1);
            conect.establecerTamanioFuente(1.3, 2)
            align(conect, 'center');
            conect.texto('ORDEN DE COBRO Nº. ' + order.number)
            conect.establecerTamanioFuente(1, 1)
            conect.feed(2);
            /* Fin Título */



            /* Sección totales */
            align(conect, 'right')
            conect.establecerEnfatizado(1);
            conect.texto('SUBTOTAL: ');
            conect.establecerEnfatizado(0);
            conect.texto(formatter.format(order.payment.amount));
            conect.feed(1);

            if (order.payment.discount > 0) {
                conect.establecerEnfatizado(1);
                conect.texto('DESCUENTO: ');
                conect.establecerEnfatizado(0);
                conect.texto(formatter.format(order.payment.discount));
                conect.feed(1);
            }

            if (order.payment.tax > 0) {
                conect.establecerEnfatizado(1);
                conect.texto('IMPUESTOS: ');
                conect.establecerEnfatizado(0);
                conect.texto(formatter.format(order.payment.tax));
                conect.feed(1);
            }
            conect.feed(1);
            conect.establecerTamanioFuente(1.3, 2)
            conect.establecerEnfatizado(1);
            conect.texto('TOTAL: ');
            conect.texto(formatter.format(order.payment.total));
            conect.feed(1);


            conect.texto('--------------------------------------');
            conect.feed(1);
            /* Fin Sección */

            /* Sección personas */
            conect.establecerTamanioFuente(1, 1)
            conect.establecerEnfatizado(1);
            conect.texto('VENDEDOR: ');
            conect.establecerEnfatizado(0);
            conect.texto(order.seller.fullname);
            conect.feed(1);

            /* Fin sección */



            conect.feed(3);
            conect.cortar();
            conect.imprimirEn(order.place.preference.printer)
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
