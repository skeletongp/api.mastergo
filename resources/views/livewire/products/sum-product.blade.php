<div class="max-w-7xl shadow-xl p-4">
    @if ($open)
        <livewire:reports.create-outcome :open="true" :efectivo="$efectivo" :tarjeta="$tarjeta"
            :transferencia="$transferencia" :amount="$total" :payAll="true" :count_code="$count_code" :code_name="$code_name" :concept="'Compra de mercancía ' . date('d/m/y')"
            :provider_id="$provider_id" />
    @endif
    <div class="flex flex-row space-x-4  items-start relative">
        <div class="w-full min-w-[40rem]">
            <form action="" wire:submit.prevent="addProduct">
                <div class="flex space-x-2 items-start pt-12 relative">
                    <div class="w-full max-w-[12rem] ">
                        <x-datalist label="Nombre de producto" model="form.product_id" inputId="producto_id"
                            listName="productIdList">
                            <option value=""></option>
                            @foreach ($products as $id => $product)
                                <option data-value="{{ $id }}" value="{{ $product }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="form.product_id"></x-input-error>
                    </div>
                    @if (array_key_exists('product_id', $form))
                        <div class=" w-2/6 px-2">
                            <x-base-select wire:model="form.unit" label="Medida" id="medida_id">
                                <option value=""></option>
                                @foreach ($units as $id => $unit)
                                    <option value="{{ $id }}">{{ $unit }}</option>
                                @endforeach
                            </x-base-select>
                            <x-input-error for="form.unit"></x-input-error>
                        </div>
                    @endif
                    @if (array_key_exists('unit', $form) && $form['unit'])
                        <div class=" w-1/6">
                            <x-base-input wire:loading.attr="disabled" wire:keydown.enter="addProduct" type="number"
                                label="Costo" id="form.cost" wire:model.defer="form.cost" />
                            <x-input-error for="form.cost"></x-input-error>
                        </div>
                        <div class=" w-1/6">
                            <x-base-input wire:keydown.enter="addProduct" type="number" label="Cantidad" id="form.cant"
                                wire:model.defer="form.cant" />
                            <x-input-error for="form.cant"></x-input-error>
                        </div>
                    @endif

                </div>
            </form>

            <div class="mt-4">
                @if (count($productAdded))
                    <div class="pr-4 " x-data="{ open: false }" x-cloak>
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-lg text-gray-600 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">
                                            Producto
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Medida
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Cost.
                                        </th>
                                        <th scope="col" class="px-4 py-3">
                                            Cant.
                                        </th>
                                        <th scope="col" class="px-4 py-3 text-center">
                                            <span class="sr-only">
                                                Acciones
                                            </span>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="text-base">
                                    @foreach ($productAdded as $added)
                                        <tr
                                            class="border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700 ">
                                            <th scope="row"
                                                class="px-4 py-2 font-medium text-gray-900 dark:text-white whitespace-nowrap  cursor-pointer">
                                                {{ $added['product_name'] }}
                                            </th>
                                            <td class="px-4 py-2  cursor-pointer">
                                                {{ $added['unit_name'] }}
                                            </td>
                                            <td class="px-4 py-2  cursor-pointer">
                                                {{ formatNumber($added['cost']) }}
                                            </td>
                                            <td class="px-4 py-2  cursor-pointer">
                                                {{ formatNumber($added['cant']) }}
                                            </td>

                                            <td class="px-4 py-2">
                                                <div class="flex space-x-4 w-max mx-auto cursor-pointer ">
                                                    <span class="far fa-trash-alt"
                                                        wire:click="remove({{ $added['id'] }})"></span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <b>Total:</b> {{ formatNumber($total) }}
                            @can('Registrar Asientos')
                                <div class="mr-6">
                                    <x-toggle label="Registrar gasto" id="setCost" wire:model="setCost"></x-toggle>
                                </div>
                            @endcan

                        </div>

                    </div>
                @endif
                <div class="flex justify-end py-4 bottom-0 right-2">
                    <x-button class="bg-gray-800 font-bold text-white uppercase disabled:bg-gray-200 text-xs"
                        wire:loading.attr='disabled' wire:click.prevent="sumCant">Guardar</x-button>
                </div>
            </div>

        </div>
        @if (count($productAdded))
            <div class="w-full">
                <div class="flex space-x-4 items-start pt-12">
                    <div class="w-full">
                        <x-base-select id="outProvider" label="Proveedor" wire:model="provider_id">
                            <option class="text-gray-300"> Elija un proveedor</option>
<<<<<<< HEAD
                            @foreach ($providers->sort() as $idProv => $prov)
=======
                            @foreach ($providers as $idProv => $prov)
>>>>>>> 7af05642e1478ce99e47f2e6c79e21f22c8762b2
                                <option value="{{ $idProv }}">{{ $prov }}</option>
                            @endforeach
                        </x-base-select>
                        <x-input-error for="provider_id">Campo requerido</x-input-error>
                    </div>
                    <div class="w-full">
                        <x-datalist disabled type="search" inputId="outCountCode" label="Cuenta afectada"
                            listName="countList" wire:model.lazy="code_name">
                            @foreach ($counts as $code => $count)
                                <option value="{{ $code . ' - ' . ellipsis($count, 27) }}"></option>
                            @endforeach
                        </x-datalist>
                        <x-input-error for="count_code">Campo requerido</x-input-error>
                    </div>

                </div>
            </div>
        @endif
    </div>
    <div class="opacity-0">
        @livewire('provisions.print-provision', ['provision_code' => 0], key(uniqid()))
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
</div>
