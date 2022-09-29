<x-app-layout>
    @slot('bread')
        {{ Breadcrumbs::render('provisions') }}
    @endslot
    @slot('rightButton')
        @can('Sumar Productos')
            <div class="flex justify-end pb-2 space-x-4">
                <a id="addproduct" href="{{ route('products.sum') }}"
                    class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                    Sumar Productos
                </a>

                @scope('Recursos')
                    <a id="addresource" href="{{ route('recursos.sum') }}"
                        class="text-gray-900 bg-white hover:bg-gray-100 border border-gray-200 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center inline-flex items-center dark:focus:ring-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:bg-gray-700">
                        Sumar Recursos
                    </a>
                @endscope
            </div>
        @endcan
    @endslot
    <div class=" w-full mx-auto ">
        <div class="mx-auto py-2 w-max min-h-max h-full relative sm:px-6 lg:px-8">
            <livewire:provisions.provision-table />
        </div>
        @push('js')
            <script>
                Livewire.on('printProvision', function(provision) {
                    data = provision[0];
                    print(data, provision)
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
                var sumField = (obj, field) => obj
                    .map(items => items[field])
                    .reduce((prev, curr) => parseFloat(prev) + parseFloat(curr), 0);

                function print(obj, provisions) {
                    if (!obj.place.preference.printer) {
                        Livewire.emit('showAlert', 'No hay ninguna impresora añadida', 'warning');
                        return false;
                    }
                    const conector = new Impresora();
                    conector.cut();
                    /* Encabezado Negocio */
                    align(conector, 'center');
                    if (obj.place.store.image) {
                        conector.imagenDesdeUrl(obj.place.store.image.path);
                        conector.feed(1)
                    }
                    conector.setEmphasize(1);
                    conector.setFontSize(2, 2)
                    texto(conector, obj.place.store.name.toUpperCase() + "\n");
                    conector.setEmphasize(0);
                    conector.setFontSize(1, 1)
                    texto(conector, 'RNC: ')
                    texto(conector, obj.place.store.rnc ? obj.place.store.rnc : '000000000' + "\n");
                    texto(conector, obj.place.store.phone + "\n");
                    texto(conector, obj.place.store.address + "\n");
                    align(conector, 'center');
                    texto(conector, '--------------------------------------');
                    conector.feed(1);
                    /* Fin Encabezado */

                    /* Detalle Provisión */
                    align(conector, 'left');
                    conector.setEmphasize(1);
                    texto(conector, "TIPO: ");
                    conector.setEmphasize(0);
                    texto(conector, obj.provisionable.type ? 'Productos' : 'Recursos')
                    conector.feed(1);

                    conector.setEmphasize(1);
                    texto(conector, 'EMITIDA: ')
                    conector.setEmphasize(0);
                    date = new Date(obj.created_at)
                    texto(conector, date.toDateString());
                    conector.feed(1);

                    conector.setEmphasize(1);
                    texto(conector, 'CÓDIGO: ')
                    conector.setEmphasize(0);
                    texto(conector, obj.code);
                    conector.feed(1);

                    align(conector, 'center');
                    texto(conector, '--------------------------------------');
                    conector.feed(1);
                    /* Fin detalle */

                    /* Datos del Proveedor */
                    align(conector, 'left');
                    conector.setEmphasize(1);
                    texto(conector, 'PROVEEDOR: ')
                    conector.setEmphasize(0);
                    if (obj.prov_name) {
                        texto(conector, obj.prov_name)
                    } else {
                        texto(conector, obj.provider.fullname.toUpperCase());
                    }
                    conector.feed(1);

                    conector.setEmphasize(1);
                    texto(conector, 'RNC: ');
                    conector.setEmphasize(0);
                    if (obj.prov_rnc) {
                        texto(conector, obj.prov_rnc)
                    } else {
                        texto(conector, obj.provider.rnc ? obj.provider.rnc : '0000000000')
                    }
                    texto(conector, ' / ');

                    conector.setEmphasize(1);
                    texto(conector, 'TEL: ');
                    conector.setEmphasize(0);
                    texto(conector, obj.provider.phone);
                    conector.feed(1);

                    conector.setEmphasize(1);
                    texto(conector, 'DIR: ');
                    conector.setEmphasize(0);
                    texto(conector, obj.provider.address ? obj.provider.address : 'N/D');
                    conector.feed(1);

                    align(conector, 'center');
                    texto(conector, '--------------------------------------');
                    conector.feed(1);
                    /* Fin Proveedor */

                    /* Tipo de Recibo */
                    conector.setEmphasize(1);
                    conector.setFontSize(1, 2)
                    align(conector, 'center');
                    texto(conector, obj.provisionable.type ? 'PROVISIÓN DE PRODUCTOS' : 'PROVISIÓN DE RECURSOS')
                    conector.setFontSize(1, 1)
                    conector.feed(2);
                    /* Fin Tipo */


                    /* Encabezado de productos */
                    align(conector, 'left');
                    texto(conector, 'DETALLES DEL RECIBO ')
                    conector.feed(1)
                    /* Fin encabezados */

                    /* Productos agreads */
                    conector.setEmphasize(0);
                    provisions.forEach(prov => {
                        align(conector, 'left');
                        texto(conector, (toDecimal.format(prov.cant)) + " ");
                        texto(conector, prov.atribuible.symbol + " ");
                        if (prov.provisionable.code) {
                            texto(conector, prov.provisionable.code + " " + prov.provisionable.name + " ");
                        } else {
                            texto(conector, prov.provisionable.name + " ");
                        }
                        if (prov.provisionable.brand) {
                            texto(conector, prov.provisionable.brand.name + " ");
                        }
                        conector.feed(1);
                        align(conector, 'right');
                        texto(conector, "Cst. " + formatter.format(prov.cost) + " ");


                        texto(conector, "Subt. " + formatter.format(parseFloat(prov.cant) * parseFloat(prov.cost)));
                        conector.feed(2);
                    });
                    conector.feed(1);
                    /* Fin Productos */

                    /* Sección totales */
                    align(conector, 'right')
                    conector.setEmphasize(1);
                    texto(conector, 'MONTO: ');
                    conector.setEmphasize(0);
                    texto(conector, formatter.format(sumField(provisions, 'total')));
                    conector.feed(1);

                    align(conector, 'center');
                    texto(conector, '--------------------------------------');
                    conector.feed(1);
                    /* Fin Sección */

                    /* Sección personas */
                    conector.setEmphasize(1);
                    texto(conector, 'RESPONSABLE: ');
                    conector.setEmphasize(0);
                    texto(conector, obj.user.fullname);
                    conector.feed(1);
                    /* Fin sección */

                    /* Nota al pie */
                    conector.feed(1)
                    texto(conector, 'RECIBO DE INGRESO, NO FINANCIERO: ');
                    /* Fin Nota */

                    conector.feed(6);
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

</x-app-layout>
