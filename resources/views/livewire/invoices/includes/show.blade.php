<section class="py-4 lg:px-2 border text-xs bg-white lg:font-bold w-[80mm] mx-auto" translate="no">
    <div id="div" class="0 px-1 lg:px-3 relative bg-white">
        <div class="h-3 w-full bg-gray-500 mb-2 "></div>

        <div class="p-4 border-b text-center flex flex-col">
            <span class="leading-4 font-bold text-lg">{{ $invoice->store->name }}</span>
            <span class="leading-4 mt-2">{{ $invoice->store->rnc }}</span>
            <span class="leading-4 ">{{ $invoice->store->phone }}</span>
            <span class="mb-0 leading-4  overflow-hidden overflow-ellipsis "
                style="-webkit-line-clamp: 2; line-clamp: 2;  -webkit-box-orient: vertical; display: -webkit-box;">{{ $invoice->store->address }}</span>
            @if ($invoice->ncf)
                <span class="leading-3"><b>RCN: </b>{{ $invoice->store->rnc }}</span>
            @endif
        </div>
        <div class="flex justify-between space-x-2 items-center border-b pt-1 pb-2  divide-x">
            <div class="w-full flex flex-col space-y-1 uppercase">
                <div>
                    VENTA
                    {{ $invoice->condition }}
                </div>
                <span class="leading-3"><b>EMITIDA:
                    </b>{{ date_format(date_create($invoice->day), 'd/m/Y') }}</span>
                <span class=" leading-3"><b>HASTA:</b>
                    {{ date_format(Carbon\Carbon::create($invoice->expires_at), 'd/m/Y') }}</span>
            </div>
            <div class="w-full flex flex-col space-y-1 items-end uppercase">

                <div>
                    {{ formatDate($invoice->created_at, 'H:i:s A') }}
                </div>

                <span class=" leading-3"><b>NCF:</b> {{ $invoice->payment->ncf ?: '0000000000' }}</span>
                <span class="leading-4 "><b>FACT. Nº: </b>{{ str_replace('Nº.', '', $invoice->number) }}</span>
            </div>
        </div>

        <div class="py-3 border-b space-y-1 ">
            <div class="grid grid-cols-2 ">
                <span class="col-span-2 uppercase text-sm">{{ellipsis($invoice->name,27)?:ellipsis($invoice->client->name,27) }}</span>
                <b>RNC/CÉD.: </b> <span class="">{{$invoice->rnc?:$invoice->client->rnc }}</span>
                <b>TELÉFONO: </b><span class="">{{ $invoice->client->phone }}</span>
                <b>DIRECCIÓN: </b><span
                    class="overflow-hidden overflow-ellipsis whitespace-nowrap">{{ $invoice->client->address }}</span>
            </div>

        </div>
        <div class="py-3 ">
            {{-- Title --}}
            <h1 class="mb-3 text-center text-sm uppercase">
                <span class="mb-2 text-right font-semibold leading-4">
                    {{ array_search($invoice->type, App\Models\Invoice::TYPES) }}
            </h1>
            <table class="w-full">
                <thead>
                    <tr class="uppercase">
                        <th class="th-details">Detalle</th>
                        <th class="th-details">Prec.</th>
                        <th class="th-details">Desc</th>
                        @if ($invoice->type != 'B00' && $invoice->type != 'B14')
                            <th class="th-details">Imp</th>
                        @endif
                        <th class="th-details text-right">Subt.</th>
                    </tr>
                </thead>
                <tbody class=" ">
                    <tr class="py-2"></tr>
                    @foreach ($invoice->details as $detail)
                        <tr class="tr-detail">
                            <td colspan="5" class=" td-details text-left">{{ formatNumber($detail->cant) }}
                                <sup>{{ $detail->unit->symbol }}</sup>
                                {{ strlen($detail->product->name) > 25 ? substr($detail->product->name, 0, 25) : $detail->product->name }}{{ strlen($detail->product->name) > 25 ? '...' : '' }}
                            </td>
                        </tr>
                        <tr class="tr-detail">
                            <td class="border-b  td-details2"></td>
                            <td class=" border-b text-center td-details2">${{ formatNumber($detail->price) }}</td>
                            <td class=" border-b text-center td-details2">
                                {{ $detail->discount_rate * 100 }}%</td>
                            @if ($invoice->type != 'B00' && $invoice->type != 'B14')
                                <td class=" border-b text-right">${{ formatNumber($detail->taxtotal) }} </td>
                            @endif
                            <td class=" border-b text-right">${{ formatNumber($detail->total) }} </td>
                        </tr>
                    @endforeach
                </tbody>
                <tr class="border-b-4">
                    <td></td>
                </tr>

            </table>
            <div class="flex flex-col items-end py-3 w-full">
                <div class="flex w-2/3 justify-between">
                    <span class=" w-16 text-right">Subtotal</span>
                    <span>${{ formatNumber($invoice->payment->amount) }}</span>
                </div>
                @if (!$invoice->comprobante)
                    @foreach ($invoice->taxes as $tax)
                        <div class="flex w-2/3 justify-between border-b pb-1">
                            <span class=" w-16 text-right">{{ $tax->name }}</span>
                            <span> ${{ formatNumber($tax->pivot->amount) }}</span>

                        </div>
                    @endforeach
                @endif
                @if ($invoice->pdfLetter)
                    <div class="absolute left-0">
                        {!! \DNS2D::getBarcodeHTML($invoice->pdfLetter, 'QRCODE', 2, 2) !!}
                    </div>
                @endif
                <div class="flex w-2/3 justify-between border-b pb-1">
                    <span class=" w-16 text-right">Descuento</span>
                    <span>(${{ formatNumber($invoice->payment->discount) }})</span>
                </div>
                <div class="flex w-2/3 justify-between py-1">
                    <span class=" w-16 text-right">Total</span>
                    <span>${{ formatNumber($invoice->payment->total) }}</span>
                </div>
                @if ($invoice->payments()->sum('efectivo') > 0)
                    <div class="flex w-2/3 justify-between font-bold ">
                        <span class=" w-16 text-right">Efectivo</span>
                        <span>${{ formatNumber($invoice->payments()->sum('efectivo')) }}</span>
                    </div>
                @endif
                @if ($invoice->payments()->sum('tarjeta') > 0)
                    <div class="flex w-2/3 justify-between font-bold ">
                        <span class=" w-16 text-right">Tarjeta</span>
                        <span>${{ formatNumber($invoice->payments()->sum('tarjeta')) }}</span>
                    </div>
                @endif
              
                @if ($invoice->payments()->sum('transferencia') > 0)
                    <div class="flex w-2/3 justify-between font-bold ">
                        <span class=" w-16 text-right">Transferencia</span>
                        <span>${{ formatNumber($invoice->payments()->sum('transferencia')) }}</span>
                    </div>
                @endif
                @if ($invoice->payment->rest > 1)
                    <div class="flex w-2/3 justify-between pt-1 font-bold p-1">
                        <span class=" w-16 text-right">Resta</span>
                        <span>${{ formatNumber($invoice->rest) }}</span>
                    </div>
                @else
                    <div class="flex w-2/3 justify-between  font-bold ">
                        <span class=" w-16 text-right">Cambio</span>
                        <span>${{ formatNumber($invoice->payments()->sum('cambio')) }}</span>
                    </div>
                @endif
            </div>
            {{-- Código de barras --}}

            <div class="text-center  py-5 flex ">
                <div class="w-full overflow-hidden overflow-ellipsis whitespace-nowrap">
                    <span class="uppercase text-xs">{{ $invoice->seller->fullname }}</span> <br>
                    <span>Vendedor</span>
                </div>
                <div class="w-full overflow-hidden overflow-ellipsis whitespace-nowrap">
                    <span class="uppercase text-xs">{{ $invoice->contable->fullname }}</span> <br>
                    <span>Cajero</span>
                </div>
            </div>


            <hr>
            <hr>

            <h6 class="text-xs uppercase text-center font-normal pt-2">
                <div class="text-left w-full py-2">EMITIDA POR SISTEMA</div>
                @if ($invoice->note)
                    <div style="padding:5px; padding-bottom:10px; font-style: italic">
                        <b>Nota: </b>{{ $invoice->note }}
                    </div>
                @endif
                ***¡Gracias por preferirnos!*** <br>
                <div class="w-full py-2 ">
                    Favor revisar la mercancía al momento de recibir. No se aceptan devoluciones
                </div>
                <hr>
            </h6>
        </div>
    </div>
</section>
