<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $invoice->number }}</title>
</head>

<body>
    <div class="container">
        <div class="biz">
            <div class="logo">
            </div>
            <h1 class="biz-name   ">
                {{ $invoice->store->name }}
            </h1>
            <hr>
            <h2 class="biz-rnc subtitle ">
                132-48752-4
            </h2>
            <h2 class="biz-phone subtitle   ">
                8095086221
            </h2>
            <h2 class="biz-addr subtitle    ">
                {{ substr($invoice->store->address, 0, 50) }}{{ strlen($invoice->store->address) > 50 ? '...' : '' }}
            </h2>
        </div>
        {{-- Invoice Data --}}
        <table>
            <tr class="invoice-data">
                <td class="data-left" colspan="2">
                    <h2 class="sale-type subtitle">
                        <b>Condición</b>: {{ $invoice->condition }}
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>EMITIDA</b>: 12-04-2022
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>Vence</b>: 12-04-2023
                    </h2>
                </td>
                <td class="data-right" colspan="2">
                    <h2 class="pay-type subtitle">
                        {{ $invoice->payway }}
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>NCF</b>: {{ $invoice->comprobante ? $invoice->comprobante->number : '0000000000' }}
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>Fct. Nº</b>: {{ $invoice->number }}
                    </h2>

                </td>
            </tr>
            <tr class="invoice-data">
                <td colspan="4">
                    <h2 class="data-detail subtitle">
                        <b>Vendedor</b>: {{ $invoice->seller->fullname }}
                    </h2>

                    <h2 class="data-detail subtitle">
                        <b>Cajero</b>: {{ $invoice->contable->fullname }}
                    </h2>
                </td>
            </tr>
        </table>

        {{-- Cliente Data --}}
        <table>
            <tr class="invoice-data">
                <td colspan="4" style="margin-bottom: -40px">
                    <h2 class="client-name uppercase">Cliente</h2>
                    <h2 class=" client-name" style="width: 100%; padding:0px; margin-bottom:0px">
                        {{ strlen($invoice->client->fullname) > 28 ? substr($invoice->client->fullname, 0, 28) : $invoice->client->fullname }}{{ strlen($invoice->client->fullname) > 28 ? '...' : '' }}
                    </h2>
                </td>
            </tr>
            <tr class="invoice-data">

                <td class="data-left" colspan="4">
                    <h2 class="data-detail ">
                        <b>RNC/CÉD: </b>{{ $invoice->client->rnc ?: 'No Disponible' }}
                    </h2>
                    <h2 class="data-detail ">
                        <b>DIRECCIÓN.:</b>
                        {{ strlen($invoice->client->address) > 30 ? substr($invoice->client->address, 0, 30) : $invoice->client->address }}{{ strlen($invoice->client->address) > 30 ? '...' : '' }}
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>Tel.:</b> {{ $invoice->client->phone }}
                    </h2>
                </td>
            </tr>
        </table>
        <div>
            <h1 class="invoice-type">
                {{ array_search($invoice->type, App\Models\Invoice::TYPES) }}
            </h1>
        </div>
        <div class="invoice-details">
            <table class="table-details">
                <thead class="head-details">
                    <tr>
                        <th class="th-details">Detalle</th>
                        <th class="th-details">Prec.</th>
                        <th class="th-details">Desc</th>
                        <th class="th-details">Imp</th>
                        <th class="th-details text-right">Subt.</th>
                    </tr>
                </thead>
                <tbody>
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
                                {{ formatNumber($detail->discount_rate * 100) }}%</td>
                            <td class=" border-b text-right">${{ formatNumber($detail->totalTax) }} </td>
                            <td class=" border-b text-right">${{ formatNumber($detail->total) }} </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="td-total text-right" style="padding-top:15px" colspan="4">
                            <b>SUBTOTAL</b>
                        </td>
                        <td class="td-total text-right" style="padding-top:15px">
                            ${{ formatNumber($payment->amount) }}
                        </td>
                    </tr>

                    @if ($invoice->comprobante)

                        @foreach ($invoice->taxes as $tax)
                            <tr>
                                <td class="td-total text-right" style="" colspan="4">
                                    <div style="margin-top:-1px; margin-bottom:-1px">{{ $tax->name }}</div>
                                </td>
                                <td class="td-total text-right" style="">
                                    <div style="margin-top:-1px; margin-bottom:-1px">
                                        ${{ formatNumber($tax->pivot->amount) }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td class="td-total text-right" style="padding-top:10px" colspan="4">
                            <b>DESCUENTO</b>
                        </td>
                        <td class="td-total text-right" style="padding-top:10px">
                            (${{ formatNumber($payment->discount) }})
                        </td>
                    </tr>
                    <tr class="tr-final">
                        <td class="td-total text-right" colspan="4">
                            <b>TOTAL</b>
                        </td>
                        <td class="td-total text-right">
                            <b> ${{ formatNumber($payment->total) }}</b>
                        </td>
                    </tr>
                    @if ($payment->efectivo > 0)
                        <tr class="">
                            <td class="td-total text-right" style="padding-top:15px" colspan="4">
                                <b>Efectivo</b>
                            </td>
                            <td class="td-total text-right" style="padding-top:15px">
                                <b> ${{ formatNumber($payment->efectivo) }}</b>
                            </td>
                        </tr>
                    @endif
                    @if ($payment->tarjeta > 0)
                        <tr class="">
                            <td class="td-total text-right" colspan="4">
                                <b>Tarjeta</b>
                            </td>
                            <td class="td-total text-right">
                                <b> ${{ formatNumber($payment->tarjeta) }}</b>
                            </td>
                        </tr>
                    @endif
                    @if ($payment->transferencia > 0)
                        <tr class="">
                            <td class="td-total text-right" colspan="4">
                                <b>Transferencia</b>
                            </td>
                            <td class="td-total text-right">
                                <b> ${{ formatNumber($payment->transferencia) }}</b>
                            </td>
                        </tr>
                    @endif
                    @if ($payment->rest > 0)
                        <tr class="">
                            <td class="td-total text-right" style="padding-top:15px" colspan="4">
                                <b>PENDIENTE</b>
                            </td>
                            <td class="td-total text-right" style="padding-top:15px">
                                <b> ${{ formatNumber($payment->rest) }}</b>
                            </td>
                        </tr>
                    @else
                        <tr class="">
                            <td class="td-total text-right" style="padding-top:15px" colspan="4">
                                <b>CAMBIO</b>
                            </td>
                            <td class="td-total text-right" style="padding-top:15px">
                                <b> ${{ formatNumber($payment->cambio) }}</b>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <h6 class="text-xs uppercase text-center font-normal">
                <div class="text-left w-full">EMITIDA POR SISTEMA</div>
                <hr>
                ***¡Gracias por preferirnos!*** <br>
                <div style="width: 100%; padding-top:5px"></div>
                Favor revisar la mercancía al momento de recibir. No se aceptan devoluciones <br>
                <hr>
            </h6>
        </div>
    </div>

</body>
<style>
    @page {
        size: 80mm 297mm portrait;
        margin: 5px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: large;
    }

    hr {
        color: #999;
        border: .5px;
    }

    table {
        min-width: 90%;
        max-width: 90vw !important
    }

    .logo {
        width: 20mm;
        height: 20mm;
        margin: auto;
        border-radius: 50%;
        background-image: url({{ $invoice->store->logo }});
        background-attachment: fixed;
        background-position: center;
        background-size: cover;
    }

    .subtitle {
        font-weight: normal;
        text-transform: uppercase;
    }

    .container {
        width: 99%;
        height: 258mm;
    }

    .biz-name {
        font-size: medium;
        text-transform: uppercase;
        text-align: center;
    }

    .biz-rnc {
        font-size: small;
        text-transform: uppercase;
        text-align: center;
        line-height: 4px;
    }

    .biz-phone {
        font-size: small;
        text-transform: uppercase;
        text-align: center;
        line-height: 3px;
    }

    .biz-addr {
        font-size: small;
        width: 80%;
        margin: auto;
        text-align: center;
        margin-top: -8px;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .sale-type,
    .pay-type {
        font-size: x-small;
    }

    .data-detail {
        font-size: x-small;
        line-height: 6px;
        font-weight: normal;

    }

    .data-right {
        text-align: right;
    }

    .client-title {
        font-size: small;
        line-height: 6px;
        font-weight: bold;
    }

    .data-title {
        font-size: small;
        line-height: 6px;
    }

    .client-name {
        font-size: small;
        line-height: 6px;
    }

    .invoice-type {
        font-size: small;
        line-height: 8px;
        padding-top: 5px;
        padding-bottom: 5px;
        text-transform: uppercase;
        text-align: center;
    }

    .table-details {
        border-collapse: collapse
    }

    .invoice-details {}

    .head-details {
        font-size: x-small;
        text-transform: uppercase;
        border-bottom: solid .2px #ddd;

    }

    .th-details {
        padding: 2px 5px;
    }

    .td-details {
        padding: 5px;
        padding-bottom: 0;
    }

    .td-details2 {
        padding: -5px -5px;
    }

    .tr-detail {
        font-size: x-small;
        text-transform: uppercase;

    }

    .border-b {
        border-bottom: solid .2px #ddd;

    }



    .td-total {
        font-size: x-small;
        padding: 3px 5px 0px 12px;
    }

    .tr-final,
    .tr-pagado {
        border-top: solid .2px #ddd
    }

    .tr-pagado>td {
        padding-top: 10px;
        border-top: solid .2px #eee
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .uppercase {
        text-transform: uppercase;
    }

    .text-sm {
        font-size: small;
    }

    .text-md {
        font-size: medium;
    }

    .text-xs {
        font-size: x-small;
    }

    .font-normal {
        font-weight: normal;
    }

    .font-bold {
        font-weight: bold;
    }

    .w-full {
        width: 100%;
    }

</style>

</html>
