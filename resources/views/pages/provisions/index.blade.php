<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('provisions') }}
    @endslot
    @slot('rightButton')
        @can('Sumar Productos')
            <div class="flex justify-end pb-2 space-x-4">
                <a id="addproduct"  href="{{ route('products.sum') }}"
                    class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                    Sumar Productos
                </a>

                <a id="addresource"  href="{{ route('recursos.sum') }}"
                    class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                    Sumar Recursos
                </a>
            </div>
        @endcan
    @endslot
    <div class=" w-full mx-auto ">
        <div class="mx-auto py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:provisions.provision-table />
        </div>
        @push('js')
            <script>
                console.log('Loaded')
                Livewire.on('printProvision', function(provision) {
                    data = provision[0];
                    print(data, provision)
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

                function print(obj, provisions) {
                    if (!obj.place.preference.printer) {
                        Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                        return false;
                    }
                    const conector = new ConectorPlugin();
                    conector.cortar();
                    /* Encabezado Negocio */
                    align(conector, 'center');
                    if (obj.place.store.image) {
                        conector.imagenDesdeUrl(obj.place.store.image.path);
                        conector.feed(1)
                    }
                    conector.establecerEnfatizado(1);
                    conector.establecerTamanioFuente(1.3, 2)
                    conector.texto(obj.place.store.name.toUpperCase() + "\n");
                    conector.establecerEnfatizado(0);
                    conector.establecerTamanioFuente(1, 1)
                    conector.texto('RNC: ')
                    conector.texto(obj.place.store.rnc ? obj.place.store.rnc : '000000000' + "\n");
                    conector.texto(obj.place.store.phone + "\n");
                    conector.texto(obj.place.store.address + "\n");
                    align(conector, 'center');
                    conector.texto('--------------------------------------');
                    conector.feed(1);
                    /* Fin Encabezado */

                    /* Detalle Provisión */
                    align(conector, 'left');
                    conector.establecerEnfatizado(1);
                    conector.texto("TIPO: ");
                    conector.establecerEnfatizado(0);
                    conector.texto(obj.provisionable.type ? 'Productos' : 'Recursos')
                    conector.feed(1);

                    conector.establecerEnfatizado(1);
                    conector.texto('EMITIDA: ')
                    conector.establecerEnfatizado(0);
                    date = new Date(obj.created_at)
                    conector.texto(date.toDateString());
                    conector.feed(1);

                    conector.establecerEnfatizado(1);
                    conector.texto('CÓDIGO: ')
                    conector.establecerEnfatizado(0);
                    conector.texto(obj.code);
                    conector.feed(1);

                    align(conector, 'center');
                    conector.texto('--------------------------------------');
                    conector.feed(1);
                    /* Fin detalle */

                    /* Datos del Proveedor */
                    align(conector, 'left');
                    conector.establecerEnfatizado(1);
                    conector.texto('PROVEEDOR: ')
                    conector.establecerEnfatizado(0);
                    if (obj.prov_name) {
                        conector.texto(obj.prov_name)
                    } else {
                        conector.texto(obj.provider.fullname.toUpperCase());
                    }
                    conector.feed(1);

                    conector.establecerEnfatizado(1);
                    conector.texto('RNC: ');
                    conector.establecerEnfatizado(0);
                    if (obj.prov_rnc) {
                        conector.texto(obj.prov_rnc)
                    } else {
                        conector.texto(obj.provider.rnc ? obj.provider.rnc : '0000000000')
                    }
                    conector.texto(' / ');

                    conector.establecerEnfatizado(1);
                    conector.texto('TEL: ');
                    conector.establecerEnfatizado(0);
                    conector.texto(obj.provider.phone);
                    conector.feed(1);

                    conector.establecerEnfatizado(1);
                    conector.texto('DIR: ');
                    conector.establecerEnfatizado(0);
                    conector.texto(obj.provider.address ? obj.provider.address : 'N/D');
                    conector.feed(1);

                    align(conector, 'center');
                    conector.texto('--------------------------------------');
                    conector.feed(1);
                    /* Fin Proveedor */

                    /* Tipo de Recibo */
                    conector.establecerEnfatizado(1);
                    conector.establecerTamanioFuente(1.2, 1.5)
                    align(conector, 'center');
                    conector.texto(obj.provisionable.type ? 'PROVISIÓN DE PRODUCTOS' : 'PROVISIÓN DE RECURSOS')
                    conector.establecerTamanioFuente(1, 1)
                    conector.feed(2);
                    /* Fin Tipo */


                    /* Encabezado de productos */
                    align(conector, 'left');
                    conector.texto('DETALLES DEL RECIBO ')
                    conector.feed(1)
                    /* Fin encabezados */

                    /* Productos agreads */
                    conector.establecerEnfatizado(0);
                    provisions.forEach(prov => {
                        align(conector, 'left');
                        conector.texto((toDecimal.format(prov.cant)) + " ");
                        conector.texto(prov.atribuible.symbol + " ");
                        if (prov.provisionable.code) {
                            conector.texto(prov.provisionable.code + " " + prov.provisionable.name + " ");
                        } else {
                            conector.texto(prov.provisionable.name + " ");
                        }
                        if (prov.provisionable.brand) {
                            conector.texto(prov.provisionable.brand.name + " ");
                        }
                        conector.feed(1);
                        align(conector, 'right');
                        conector.texto("Cst. " + formatter.format(prov.cost) + " ");


                        conector.texto("Subt. " + formatter.format(parseFloat(prov.cant) * parseFloat(prov.cost)));
                        conector.feed(2);
                    });
                    conector.feed(1);
                    /* Fin Productos */

                    /* Sección totales */
                    align(conector, 'right')
                    conector.establecerEnfatizado(1);
                    conector.texto('MONTO: ');
                    conector.establecerEnfatizado(0);
                    conector.texto(formatter.format(sumField(provisions, 'total')));
                    conector.feed(1);

                    align(conector, 'center');
                    conector.texto('--------------------------------------');
                    conector.feed(1);
                    /* Fin Sección */

                    /* Sección personas */
                    conector.establecerEnfatizado(1);
                    conector.texto('RESPONSABLE: ');
                    conector.establecerEnfatizado(0);
                    conector.texto(obj.user.fullname);
                    conector.feed(1);
                    /* Fin sección */

                    /* Nota al pie */
                    conector.feed(1)
                    conector.texto('RECIBO DE INGRESO, NO FINANCIERO: ');
                    /* Fin Nota */

                    conector.feed(6);
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

</x-app-layout>
