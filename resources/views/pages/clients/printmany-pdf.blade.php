<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $client->name }}</title>

    <style>
        @page {
            size: 215.4mm 255mm;
        }

        * {
            background-color: transparent !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            border: 1px solid #eee;
            color: #777;
            padding-top: 15px;
            border-top: 22px solid #054853;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }


        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 20px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06f;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 5px;
            padding-top: 0;

            font-size: 14px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: 20px;
            text-align: left;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .invoice-box table td {
            padding-right: 5px;
            padding-left: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 25px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            text-align: left;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
            text-align: left;


        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: middle;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
            border-spacing: 5rem;
        }

        .total {
            padding: 0px;
            font-size: x-small;
            line-height: 14px;
        }

        .total td {
            padding: -10px !important;

        }



        footer {
            height: 50px;
            margin-bottom: -50px;
            position: fixed;
            left: 0px;
            right: 0px;
            bottom: 0;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>



<body>
    <div style="position: absolute; right:4; top: 0; color:white; z-index:50; ">
        {{ date_format(now(), 'd/m/Y H:i A') }}
    </div>
    <div class="sello"></div>
    <div class="invoice-box" id="box" style="position: relative;">
        <div style="right: 15px; top: 30px; position: absolute; ">

        </div>
        <div style="position: absolute;  top:20px; text-align:center; width: 100%; ">
            <b style="text-transform: uppercase; font-size:x-large; font-weight:bold; padding-bottom:10px">
                {{ $store->name }}</b><br />
            {!! $store->rnc ? '<b>RNC  :</b> ' . $store->rnc . '<br />' : '' !!}
            <b>TEL:</b> {{ $store->phone }} <br>
            <b>EMAIL: </b>{{ $store->email }}<br />
            {{ $store->address }}
        </div>
        <br>
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title" style="padding-top:10px">
                                <img src="{{ $store->logo }}" alt=" "
                                    style=" max-width: 300px; max-height: 100px" />
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="6">
                    <table>
                        <tr>

                            <td colspan="2" style="border-bottom: .3px solid #ccc; ">
                                <div style="text-align:left; ">
                                    <b>{{ 'DETALLE CLIENTE:' }}</b> <br>
                                    {{ $invoices->first()->name ?: ($client->name ?: $client->contact->fullname) }}<br />
                                    {!! $invoices->first()->rnc
                                        ? '<b>RNC /CED:</b> ' . $invoices->first()->rnc
                                        : ($client->rnc
                                            ? '<b>RNC /CED:</b> ' . $client->rnc . '<br />'
                                            : '') !!}
                                    <b>TEL:</b> {{ $client->phone }} <br>
                                    {{ $client->address ?: 'Dirección N/D' }}
                                </div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>

            <td colspan="6"
                style="text-transform:uppercase; font-weight:bold; font-size:large; text-align:center; padding: 15px">
                DETALLES DE LAS FACTURAS

            </td>

            </tr>
            <tr class="heading">
                <td style="padding-left:10px;">Nro.</td>
                <td style="padding-left:10px;">Fecha</td>
                <td style="text-align:right"">Monto</td>
                <td style="text-align:right"">Pagado</td>
                <td style=" text-align:right"">Resta</td>
                <td style=" text-align:right">Últ. Pago</td>
            </tr>

            @forelse ($invoices as $ind=> $invoice)
                <tr class="" style="font-size: small">
                    <td style=" width:20%; text-align:left; padding-left:10px;">
                        {{ $invoice->number }}
                    </td>
                    <td style=" width:20%; text-align:left; padding-left:10px;">
                        {{ formatDate($invoice->day, 'd/m/Y') }}
                    </td>
                    <td style=" width:20%; text-align:right;">${{ formatNumber($invoice->amount) }}</td>
                    <td style=" width: 20%; text-align:right;">${{ formatNumber($invoice->payed) }}</td>
                    <td style="width: 20%; text-align:right">${{ formatNumber($invoice->rest) }}</td>

                    <td style=" width: 20%; text-align:right">
                        {!! formatDate($invoice->payment_date, 'd/m/Y') !!}

                    </td>
                </tr>

            @empty
            @endforelse
            <tr class="" style="font-size: small; font-weight:bold">
                <td style=" width:20%; text-align:right; padding-left:10px;">

                </td>
                <td style=" width:20%; text-align:right; padding-left:10px;">
                    TOTALES
                </td>
                <td style=" width:20%; text-align:right;">${{ formatNumber($invoices->sum('amount')) }}</td>
                <td style=" width: 20%; text-align:right;">${{ formatNumber($invoices->sum('payed')) }}</td>
                <td style="width: 20%; text-align:right">${{ formatNumber($invoices->sum('rest')) }}</td>

                <td style=" width: 20%; text-align:right">

                </td>
            </tr>
        </table>
        <br>



        <table>
            <tr>
                <td style="padding-top: 45px; ">
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-right: 20px">
                        ENTREGADO POR</div>
                </td>
                <td style="padding-top: 45px;">
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-left: 10px">
                        RECIBIDO POR</div>
                </td>
            </tr>
        </table>
        {{-- <table>
            <tr>
                <td colspan="4" style="padding-top: 10px; text-align:center; font-size:x-small">
                    @if ($invoice->note && $invoice->note != '***')
                        <div
                            style=" padding-top: 0px; width:100%; text-align:center; margin-right: 20px; text-transform:uppercase; font-weight:bold">
                            {{ $invoice->note }}</div>
                    @endif
                    <div style=" padding-top: 0px; width:100%; text-align:center; margin-right: 20px">
                        SUJETO A POLÍTICAS DE NO DEVOLUCIÓN</div>
                    <div style=" padding-top: 0px; width:100%; text-align:center; margin-right: 20px">
                        GRACIAS POR PREFERIRNOS</div>
                </td>

            </tr>
        </table> --}}

    </div>

    <footer>
        <table style="width: 100%; font-size:x-small">
            <tr>
                <td>GENERADA POR SISTEMA</td>
                <td style="text-align: right">ORIGINAL CLIENTE</td>
            </tr>
        </table>
    </footer>

</body>


</html>
<script type="text/javascript">
    alert('hola');
</script>
