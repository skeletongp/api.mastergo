@push('js')
    <script>
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
            .reduce((prev, curr) => parseFloat(prev) + parseFloat( curr), 0);

        Livewire.on('changeInvoice', function(invoice, letPrint = true) {
            obj = invoice;
            if (letPrint) {
                for (let index = 0; index < obj.place.preference.copy_print; index++) {
                print();
                }
            }
        })

        function print() {
            if (!obj.place.preference.printer) {
                Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                return false;
            }
            const conector = new ConectorPlugin();
            conector.cortar();
            /* Encabezado Negocio */
            align(conector, 'center');
            if (obj.store.image) {
                conector.imagenDesdeUrl(obj.store.image.path);
                conector.feed(1)
            }
            conector.establecerEnfatizado(1);
            conector.establecerTamanioFuente(1.3, 2)
            conector.texto(obj.store.name.toUpperCase() + "\n");
            conector.establecerEnfatizado(0);
            conector.establecerTamanioFuente(1, 1)
            conector.texto('RNC: ')
            conector.texto(obj.store.rnc + "\n");
            conector.texto(obj.store.phone + "\n");
            conector.texto(obj.store.address + "\n");
            align(conector, 'center');
            conector.texto('--------------------------------------');
            conector.feed(1);
            /* Fin Encabezado */


            /* Detalle Factura */
            align(conector, 'left');
            conector.establecerEnfatizado(1);
            conector.texto("CONDICIÓN: ");
            align(conector, 'right');
            conector.establecerEnfatizado(0);
            conector.texto(obj.condition.toUpperCase())
            conector.feed(1);

            conector.establecerEnfatizado(1);
            conector.texto('NCF: ')
            conector.establecerEnfatizado(0);
            conector.texto(obj.comprobante ? obj.comprobante.ncf : " 0000000000");
            conector.feed(1);

            conector.establecerEnfatizado(1);
            conector.texto('EMITIDA: ')
            conector.establecerEnfatizado(0);
            conector.texto(obj.day);
            conector.feed(1);

            conector.establecerEnfatizado(1);
            conector.texto('VENCE: ')
            conector.establecerEnfatizado(0);
            conector.texto(obj.expires_at);
            conector.feed(1);

            conector.establecerEnfatizado(1);
            conector.texto('ORDEN NO.: ')
            conector.establecerEnfatizado(0);
            conector.texto(obj.number);
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
            conector.texto(obj.name ? obj.name.toUpperCase() : obj.client.name.toUpperCase());
            conector.feed(1);

            conector.establecerEnfatizado(1);
            conector.texto('RNC: ');
            conector.establecerEnfatizado(0);
            if (obj.rnc) {
                conector.texto(obj.rnc);
            } else {
                conector.texto(obj.client.rnc ? obj.client.rnc : '0000000000')
            }
                
            conector.texto(' / ');

            conector.establecerEnfatizado(1);
            conector.texto('TEL: ');
            conector.establecerEnfatizado(0);
            conector.texto(obj.client.phone);
            conector.feed(1);

            conector.establecerEnfatizado(1);
            conector.texto('DIR: ');
            conector.establecerEnfatizado(0);
            conector.texto(obj.client.address ? obj.client.address : 'N/D');
            conector.feed(1);

            align(conector, 'center');
            conector.texto('--------------------------------------');
            conector.feed(1);
            /* Fin Cliente */


            /* Tipo de Factura */
            conector.establecerEnfatizado(1);
            conector.establecerTamanioFuente(1.2, 1.5)
            align(conector, 'center');
            conector.texto(obj.comprobante ? obj.comprobante.type : 'DOCUMENTO CONDUCE')
            conector.establecerTamanioFuente(1, 1)
            conector.feed(2);
            /* Fin Tipo */

            /* Encabezado de productos */
            align(conector, 'left');
            conector.texto('DETALLES DE LA FACTURA ')
            conector.feed(1)
            /* Fin encabezados */

            /* Productos facturados */
            conector.establecerEnfatizado(0);
            obj.details.forEach(det => {
                align(conector, 'left');
                conector.texto((toDecimal.format(det.cant)) + " ");
                conector.texto(det.unit.symbol + " ");
                conector.texto(det.product.code+" "+det.product.name + " ");
                conector.feed(1);
                align(conector, 'right');
                conector.texto("Pr. " + formatter.format(det.price) + " ");
                if (det.discount_rate > 0 && obj.type !== 'B00' && obj.type !== 'B14') {
                    conector.texto("Desc. " + toDecimal.format(det.discount_rate * 100) + "% ");
                }
                if (det.taxtotal > 0 && obj.type !== 'B14') {
                    conector.texto("Imp. " + formatter.format(det.taxtotal) + " ");
                }
                conector.texto("Subt. " + formatter.format(det.total));
                conector.feed(2);
            });
            conector.feed(1);
            /* Fin Productos */


            /* Sección totales */

            align(conector, 'right')
            conector.establecerEnfatizado(1);
            conector.texto('SUBTOTAL: ');
            conector.establecerEnfatizado(0);
            conector.texto(formatter.format(obj.payment.amount));
            conector.feed(1);

            if (obj.payment.discount > 0) {
                conector.establecerEnfatizado(1);
                conector.texto('DESCUENTO: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.payment.discount));
                conector.feed(1);
            }

            if (obj.payment.tax > 0) {
                conector.establecerEnfatizado(1);
                conector.texto('IMPUESTOS: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.payment.tax));
                conector.feed(1);
            }
            conector.feed(1);
            conector.establecerEnfatizado(1);
            conector.texto('TOTAL: ');
            conector.texto(formatter.format(obj.payment.total));
            conector.feed(1);
            console.log(obj);
            if (sumField(obj.payments, 'efectivo') > 0) {
                conector.establecerEnfatizado(1);
                conector.texto('EFECTIVO: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(sumField(obj.payments, 'efectivo')));
                conector.feed(1);
            }
            if (sumField(obj.payments, 'transferencia') > 0) {
                conector.establecerEnfatizado(1);
                conector.texto('TRANSFERENCIA: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(sumField(obj.payments, 'transferencia')));
                conector.feed(1);
            }
            if (sumField(obj.payments, 'tarjeta') > 0) {
                conector.establecerEnfatizado(1);
                conector.texto('OTRO: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(sumField(obj.payments, 'tarjeta')));
                conector.feed(1);
            }
            conector.feed(1);
            conector.establecerEnfatizado(1);
            conector.texto('PAGADO: ');
            conector.establecerEnfatizado(0);
            conector.texto(formatter.format(sumField(obj.payments, 'payed')));
            conector.feed(1);
            if (obj.rest > 0) {
                conector.establecerEnfatizado(1);
                conector.texto('PENDIENTE: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(obj.rest));
                conector.feed(1);
            }
            if (sumField(obj.payments, 'cambio') > 0) {
                conector.establecerEnfatizado(1);
                conector.texto('CAMBIO: ');
                conector.establecerEnfatizado(0);
                conector.texto(formatter.format(sumField(obj.payments, 'cambio')));
                conector.feed(1);
            }
            align(conector, 'center');
            conector.texto('--------------------------------------');
            conector.feed(1);
            /* Fin Sección */
            /*  Código QR */
            conector.qr(obj.pdf.pathLetter)
            /* Fin de código */

            /* Sección personas */
            conector.establecerEnfatizado(1);
            conector.texto('VENDEDOR: ');
            conector.establecerEnfatizado(0);
            conector.texto(obj.seller.fullname);
            conector.feed(1);

            conector.establecerEnfatizado(1);
            conector.texto('CAJERO: ');
            conector.establecerEnfatizado(0);
            conector.texto(obj.contable.fullname);
            conector.feed(1);

            /* Fin sección */


            /* Sección notas */
            align(conector, 'center')
            if (obj.note) {
                conector.establecerEnfatizado(1);
                conector.establecerEnfatizado(0);
                conector.texto(obj.note.toUpperCase());
                conector.feed(2);
            } else {
                conector.feed(1);
            }
            conector.texto('FAVOR REVISAR LA MERCANCÍA AL MOMENTO DE  RECIBIR. NO SE ACEPTAN DEVOLUCIONES \n');
            conector.texto('-------- GRACIAS POR PREFERIRNOS --------\n');
            conector.feed(2);
            /* Fin sección */

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
            /* printJS({
                printable: url,
                type: 'pdf',
                targetStyle: ['*'],
            }) */
        }
    </script>
@endpush
