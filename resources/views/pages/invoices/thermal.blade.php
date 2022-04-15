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
                Nombre del Negocio
            </h1>
            <hr>
            <h2 class="biz-rnc subtitle ">
                132-48752-4
            </h2>
            <h2 class="biz-phone subtitle   ">
                8095086221
            </h2>
            <h2 class="biz-addr subtitle    ">
                {{ substr('Calle Respaldo A, No. 8E, Ensanche La Paz, Distrito Nacional, República Dominicana', 0, 65) . '...' }}
            </h2>
        </div>
        {{-- Invoice Data --}}
        <table>
            <tr class="invoice-data">
                <td class="data-left" colspan="2">
                    <h2 class="sale-type subtitle">
                        Venta a Crédito
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>FECHA</b>: 12-04-2022
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>Vence</b>: 12-04-2023
                    </h2>
                </td>
                <td class="data-right" colspan="2">
                    <h2 class="pay-type subtitle">
                        Pago por tarjeta
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>NCF</b>: B0100000007
                    </h2>
                    <h2 class="data-detail subtitle">
                        <b>Fct. Nº</b>: {{ $invoice->number }}
                    </h2>
                </td>
            </tr>
        </table>

        {{-- Cliente Data --}}
        <table>
            <tr class="invoice-data">
                <td class="data-left" colspan="2">
                    <h2 class="client-title subtitle">
                        CLIENTE
                    </h2>
                    <h2 class="data-title subtitle">
                        <b>NCF/CED</b>:
                    </h2>
                    <h2 class="data-title subtitle">
                        <b>Dirección</b>:
                    </h2>
                    <h2 class="data-title subtitle">
                        <b>Teléfono</b>:
                    </h2>
                </td>
                <td class="data-right" colspan="2">
                    <h2 class="subtitle client-name">
                        {{ strlen('Alexandra Altagracia Santos Rodríguez') > 25? substr('Alexandra Altagracia Santos Rodríguez', 0, 25) . '...': 'Alexandra Altagracia Santos Rodríguez' }}
                    </h2>
                    <h2 class="data-detail ">
                        {{ $invoice->seller->email ?: 'No Disponible' }}
                    </h2>
                    <h2 class="data-detail ">
                        {{ $invoice->seller->rnc ?: 'No Disponible' }}
                    </h2>
                    <h2 class="data-detail subtitle">
                        {{ $invoice->seller->phone }}
                    </h2>
                </td>
            </tr>
        </table>
        <div>
            <h1 class="invoice-type">
                Factura de Consumo Final
            </h1>
        </div>
        <div class="invoice-details">
            <table class="table-details">
                <thead class="head-details">
                    <tr>
                        <th class="th-details">Cant</th>
                        <th class="th-details">Detalle</th>
                        <th class="th-details">$$$</th>
                        <th class="th-details">Subt.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->details as $detail)
                        <tr class="tr-detail">
                            <td class=" td-details text-center">{{ Universal::formatNumber($detail->cant) }}</td>
                            <td class="td-details text-center">
                                {{ $detail->product->name }}
                            </td>
                            <td class=" td-details text-center">${{ Universal::formatNumber($detail->price) }}</td>
                            <td class=" td-details text-center">${{ Universal::formatNumber($detail->subtotal) }}
                            </td>
                        </tr>
                    @endforeach

                    <tr>
                        <td class="td-total text-right" colspan="3">
                            <b>SUBTOTAL</b>
                        </td>
                        <td class="td-total text-right">
                            ${{ Universal::formatNumber($invoice->details->sum('subtotal')) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="td-total text-right" colspan="3">
                            <b>IMPUESTOS</b>
                        </td>
                        <td class="td-total text-right">
                            ${{ Universal::formatNumber($invoice->details->sum('total')-$invoice->details->sum('subtotal')) }}
                        </td>
                    </tr>
                    <tr  class="tr-final">
                        <td class="td-total text-right" colspan="3">
                            <b>TOTAL</b>
                        </td>
                        <td class="td-total text-right">
                           <b> ${{ Universal::formatNumber($invoice->details->sum('total')) }}</b>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>

</body>
<style>
    @page {
        size: 80mm 297mm portrait;
        margin: 5px;
        font-family: 'Times New Roman', Times, serif;
    }

    hr {
        color: #999;
        border: .5px;
    }

    table {
        min-width: 100%;
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
        font-size: small;
        text-transform: uppercase;
        text-align: center;
    }

    .biz-rnc {
        font-size: x-small;
        text-transform: uppercase;
        text-align: center;
        line-height: 4px;
    }

    .biz-phone {
        font-size: x-small;
        text-transform: uppercase;
        text-align: center;
        line-height: 3px;
    }

    .biz-addr {
        font-size: x-small;
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
        font-size: x-small;
        line-height: 6px;
        font-weight: bold;
    }

    .data-title {
        font-size: x-small;
        line-height: 6px;
    }

    .client-name {
        font-size: x-small;
        line-height: 6px;
    }

    .invoice-type {
        font-size: small;
        line-height: 8px;
        text-transform: uppercase;
        text-align: center;
    }

    .table-details {
        border-collapse: collapse
    }

    .invoice-details {}

    .head-details {
        font-size: xx-small;
        text-transform: uppercase;
        border-bottom: solid .2px #ddd;

    }

    .th-details {
        padding: 2px 5px;
    }

    .tr-detail {
        font-size: xx-small;
        text-transform: uppercase;
        border-bottom: solid .2px #ddd;

    }

    .td-details {
        padding: 5px;
    }

    .td-total {
        font-size: xx-small;
        padding: 3px 5px 0px 12px;
    }
    .tr-final{
        border-top: solid .2px #ddd
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

</style>

</html>
