
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
        function texto(impresora, string){
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
        var sumField = (obj, field) => obj
            .map(items => items[field])
            .reduce((prev, curr) => parseFloat(prev) + parseFloat(curr), 0);
        cant=1;
        Livewire.on('changeCant', function (cant) {
            cant = cant;
        });
        Livewire.on('changeInvoice', function(invoice, letPrint = true, creditNote = false) {
            obj = invoice;
            if (letPrint) {
               
                console.log(cant);
              
                for (let index = 0; index < cant; index++) {
                    console.log(cant)
                    print(creditNote);
                }
            }
        })

        function print(creditNote) {
            if (!obj.place.preference.printer) {
                Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                return false;
            }
            const conector = new Impresora();
            conector.cut();
            /* Encabezado Negocio */
            align(conector, 'center');
            /* if (obj.store.image  && obj.store.id==2 ) {
                 conector.imagenDesdeUrl(obj.store.image.path);
               
             }
             */
            conector.setEmphasize(1);
            conector.setFontSize(1,1)
            texto(conector, obj.store.name.toUpperCase() + "\n");
            conector.feed(1);
            conector.setEmphasize(0);
            conector.setFontSize(1,1)
            if (obj.payment.ncf) {
                texto(conector, 'RNC: ')
                texto(conector, obj.store.rnc + "\n");
                texto(conector, obj.store.phone + "\n");
                texto(conector, obj.store.address + "\n");
            }
            align(conector, 'center');
            texto(conector, '--------------------------------------');
            conector.feed(2);;
            /* Fin Encabezado */


            /* Detalle Factura */
            align(conector, 'left');
            conector.setEmphasize(1);
            texto(conector, "CONDICIÓN: ");
            align(conector, 'right');
            conector.setEmphasize(0);
            texto(conector, obj.condition.toUpperCase())
            conector.feed(1);;

            if (creditNote) {
                conector.setEmphasize(1);
                texto(conector, 'NCF: ')
                conector.setEmphasize(0);
                texto(conector, obj.creditnote.modified_ncf);
                conector.feed(1);;
            }

            conector.setEmphasize(1);
            if (creditNote) {
                texto(conector, 'NCF MODIFICADO: ')
            } else {
                texto(conector, 'NCF: ')
            }
            conector.setEmphasize(0);
            texto(conector, obj.comprobante ? obj.comprobante.ncf : " 0000000000");
            conector.feed(1);;

            conector.setEmphasize(1);
            texto(conector, 'EMITIDA: ')
            conector.setEmphasize(0);
            texto(conector, obj.day);
            conector.feed(1);;
            if (creditNote) {
                conector.setEmphasize(1);
                texto(conector, 'MODIFICADA : ')
                conector.setEmphasize(0);
                texto(conector, obj.creditnote.modified_at);
                conector.feed(1);;
            }
            conector.setEmphasize(1);
            texto(conector, 'VENCE: ')
            conector.setEmphasize(0);
            texto(conector, obj.expires_at);
            conector.feed(1);;

            conector.setEmphasize(1);
            texto(conector, 'ORDEN NO.: ')
            conector.setEmphasize(0);
            texto(conector, obj.number);
            conector.feed(1);;

            align(conector, 'center');
            texto(conector, '--------------------------------------');
            conector.feed(1);;
            /* Fin detalle */


            /* Datos del cliente */
            align(conector, 'left');
            conector.setEmphasize(1);
            texto(conector, 'CLIENTE: ')
            conector.setEmphasize(0);
            texto(conector, obj.name ? obj.name.toUpperCase() : obj.client.name.toUpperCase());
            conector.feed(1);;

            conector.setEmphasize(1);
            texto(conector, 'RNC: ');
            conector.setEmphasize(0);
            if (obj.rnc) {
                texto(conector, obj.rnc);
            } else {
                texto(conector, obj.client.rnc ? obj.client.rnc : '0000000000')
            }

            texto(conector, ' / ');

            conector.setEmphasize(1);
            texto(conector, 'TEL: ');
            conector.setEmphasize(0);
            texto(conector, obj.client.phone);
            conector.feed(1);;

            conector.setEmphasize(1);
            texto(conector, 'DIR: ');
            conector.setEmphasize(0);
            texto(conector, obj.client.address ? obj.client.address : 'N/D');
            conector.feed(1);;

            align(conector, 'center');
            texto(conector, '--------------------------------------');
            conector.feed(1);;
            /* Fin Cliente */


            /* Tipo de Factura */
            conector.setEmphasize(1);
            conector.setFontSize(1,1)
            align(conector, 'center');
            if (creditNote) {
                texto(conector, 'NOTA DE CRÉDITO')
            } else {
                texto(conector, obj.comprobante ? obj.comprobante.type : 'DOCUMENTO CONDUCE')
            }
            conector.setFontSize(1,1)
            conector.feed(2);
            /* Fin Tipo */

            /* Encabezado de productos */
            align(conector, 'left');
            texto(conector, 'DETALLES DE LA FACTURA ')
            conector.feed(1);;
            /* Fin encabezados */

            /* Productos facturados */
            conector.setEmphasize(0);
            obj.details.forEach(det => {
                align(conector, 'left');
                texto(conector, (toDecimal.format(det.cant)) + " ");
                texto(conector, det.unit.symbol + " ");
                texto(conector, det.product.code + " " + det.product.name + " ");
                conector.feed(1);;
                align(conector, 'right');
                texto(conector, "Pr. " + formatter.format(det.price) + " ");
                if (det.discount_rate > 0) {
                    texto(conector, "Desc. " + toDecimal.format(det.discount_rate * 100) + "% ");
                }
                if (det.taxtotal > 0 && obj.type !== 'B00' && obj.type !== 'B14') {
                    texto(conector, "Imp. " + formatter.format(det.taxtotal) + " ");
                }
                texto(conector, "Subt. " + formatter.format(det.total));
                conector.feed(2);
            });
            conector.feed(1);;
            /* Fin Productos */


            /* Sección totales */

            align(conector, 'right')
            conector.setEmphasize(1);
            texto(conector, 'SUBTOTAL: ');
            conector.setEmphasize(0);
            texto(conector, formatter.format(obj.payment.amount));
            conector.feed(1);;

            if (obj.payment.discount > 0) {
                conector.setEmphasize(1);
                texto(conector, 'DESCUENTO: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.payment.discount));
                conector.feed(1);;
            }

            if (obj.payment.tax > 0) {
                conector.setEmphasize(1);
                texto(conector, 'IMPUESTOS: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.payment.tax));
                conector.feed(1);;
            }
            conector.feed(1);;
            conector.setEmphasize(1);
            texto(conector, 'TOTAL: ');
            texto(conector, formatter.format(obj.payment.total));
            conector.feed(1);;
            if (sumField(obj.payments, 'efectivo') > 0) {
                conector.setEmphasize(1);
                texto(conector, 'EFECTIVO: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(sumField(obj.payments, 'efectivo')));
                conector.feed(1);;
            }
            if (sumField(obj.payments, 'transferencia') > 0) {
                conector.setEmphasize(1);
                texto(conector, 'TRANSFERENCIA: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(sumField(obj.payments, 'transferencia')));
                conector.feed(1);;
            }
            if (sumField(obj.payments, 'tarjeta') > 0) {
                conector.setEmphasize(1);
                texto(conector, 'OTRO: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(sumField(obj.payments, 'tarjeta')));
                conector.feed(1);;
            }
            conector.feed(1);;
            conector.setEmphasize(1);
            texto(conector, 'PAGADO: ');
            conector.setEmphasize(0);
            texto(conector, formatter.format(sumField(obj.payments, 'payed')));
            conector.feed(1);;
            if (obj.rest > 0) {
                conector.setEmphasize(1);
                texto(conector, 'PENDIENTE: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(obj.rest));
                conector.feed(1);;
            }
            if (sumField(obj.payments, 'cambio') > 0) {
                conector.setEmphasize(1);
                texto(conector, 'CAMBIO: ');
                conector.setEmphasize(0);
                texto(conector, formatter.format(sumField(obj.payments, 'cambio')));
                conector.feed(1);;
            }
            align(conector, 'center');
            texto(conector, '--------------------------------------');
            conector.feed(1);;
            /* Fin Sección */
            /*  Código QR */
            //conector.qr(obj.pdf.pathLetter)
            /* Fin de código */

            /* Sección personas */
            conector.setEmphasize(1);
            texto(conector, 'VENDEDOR: ');
            conector.setEmphasize(0);
            texto(conector, obj.seller.fullname);
            conector.feed(1);;

            conector.setEmphasize(1);
            texto(conector, 'CAJERO: ');
            conector.setEmphasize(0);
            texto(conector, obj.contable.fullname);
            conector.feed(2);

            /* Fin sección */


            /* Sección notas */
            align(conector, 'center')
            if (creditNote) {
                conector.setEmphasize(1);
                conector.setEmphasize(0);
                texto(conector, obj.creditnote.comment);
                conector.feed(1);;
            } else {
                conector.feed(1);;
            }

            align(conector, 'center')
            if (obj.note) {
                conector.setEmphasize(1);
                conector.setEmphasize(0);
                texto(conector, obj.note.toUpperCase());
                conector.feed(2);
            } else {
                conector.feed(1);;
            }
            texto(conector, 'FAVOR REVISAR LA MERCANCÍA AL MOMENTO DE  RECIBIR. NO SE ACEPTAN DEVOLUCIONES \n');
            texto(conector, '-------- GRACIAS POR PREFERIRNOS --------\n');
            conector.feed(2);
            /* Fin sección */

            conector.feed(3);
            conector.cut();
            conector.cash();
            conector.imprimirEnImpresora(obj.place.preference.printer)
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
