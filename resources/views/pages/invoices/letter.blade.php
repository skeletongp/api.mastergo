<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $invoice->number }}</title>
    @php
        if ($invoice->store_id == 18 && $invoice->rest < 1) {
            $img = asset('/images/sello.png');
        } else {
            if ($invoice->status =='PENDIENTE') {
                $img = asset('/images/pendiente.png');
            } else {
                $img = asset('/images/pagado.png');
            }
        }
        if ($invoice->type == 'cotize') {
            $img = asset('/images/cotizacion.png');
        }
        $detItbis = false;
        if ($invoice->ncf || ($invoice->type == 'cotize' && $invoice->store->rnc)) {
            $detItbis = true;
        }
    @endphp
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            color: #777;
        }

        .sello {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: absolute;
            background-image: url('{{ $img }}');
            background-size: 30%;
            background-repeat: no-repeat;
            background-position: center;
            z-index: -5;
            opacity: 0.2;
            transform: rotate(-45deg)
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
            border: 1px solid #eee;
            border-top: 20px solid #1EB8CE;
            border-bottom: 20px solid #1EB8CE;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 14px;
            line-height: 20px;
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



<body >
    <div class="sello"></div>
    <div class="invoice-box" style="position: relative;">
        <div style="right: 15px; top: 30px; position: absolute; ">
            <div style=" overflow:hidden">
                {!! $invoice->barcode !!}
            </div>
        </div>
        <div style="position: absolute;  top:50px; text-align:center; width: 100%; ">
            <b style="text-transform: uppercase; font-size:x-large; font-weight:bold; padding-bottom:10px">
                {{ $invoice->store->name }}</b><br />
            {!! $invoice->store->rnc ? '<b>RNC:</b> ' . $invoice->store->rnc . '<br />' : '' !!}
            <b>TEL:</b> {{ $invoice->store->phone }}<br />
            {{ $invoice->store->users()->first()->email }}<br />
            {{ $invoice->store->address }}
        </div>
        <br>
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title" style="padding-top:10px">
                                <img src="{{ $invoice->store->logo }}" alt="Company logo"
                                    style=" max-width: 300px; max-height: 100px" />
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="{{ $detItbis ? '5' : '4' }}">
                    <table>
                        <tr>
                            <td colspan="2" style="border-right: .3px solid #ccc; border-bottom: .3px solid #ccc; ">
                                <div>
                                    {!! $invoice->ncf ? '<b>NCF:' . $invoice->ncf . '</b> <br />' : '' !!}
                                    {{ $invoice->num }}<br />
                                    <b>Fecha:</b> {{ date_format(date_create($invoice->day), 'd-m-Y') }}<br />
                                    <b>Vence:</b>
                                    {{ date_format(\Carbon\Carbon::create($invoice->day)->addMonth(), 'd-m-Y') }}
                                    <br />
                                    <b>Forma:</b>
                                    {{ $invoice->pay_mode }}
                                </div>
                            </td>
                            <td colspan="2" style="border-bottom: .3px solid #ccc; ">
                                <div style="text-align:right; ">
                                    <b>{{ $invoice->type == 'sale' ? 'CLIENTE' : 'COTIZADO A' }}</b> <br>
                                    {{ $invoice->client->name }}<br />
                                    {!! $invoice->client->rnc ? '<b>RNC/CED:</b> ' . $invoice->client->rnc . '<br />' : '' !!}
                                    <b>TEL:</b> {{ $invoice->client->phone }}<br />
                                    {{ $invoice->client->address ?: 'Dirección N/D' }}
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div style="width:100%; height:10px">
                </td>
            </tr>
            <tr>
                @if ($invoice->store->rnc && !$invoice->ncf)
                    <td colspan="{{$detItbis?'5':'4'}}"
                        style="text-transform:uppercase; font-weight:bold; font-size:large; text-align:center; padding-bottom: 15px">
                        CONDUCE / COTIZACIÓN
                    </td>
                @else
                    <td colspan="{{$detItbis?'5':'4'}}"
                        style="text-transform:uppercase; font-weight:bold; font-size:large; text-align:center; padding-bottom: 15px">
                        {{ $invoice->ncf ? $invoice->title : ($invoice->type == 'sale' ? 'factura' : 'Cotización') }}
                    </td>
                @endif
            </tr>

            <tr class="heading">
                <td>Cant.</td>
                <td>
                    Descripción
                </td>
                <td style="text-align:right"">Precio</td>
                <td style=" text-align:right"">Subtotal</td>
                @if ($detItbis)
                    <td style=" text-align:right">ITBIS</td>
                @endif
            </tr>

            @forelse ($invoice->details as $detail)
                <tr class="item">
                    <td style=" width:13%;">
                        <div style="padding-right: 20px">{{ \Universal::formatNumber($detail->cant) }}</div>
                    </td>
                    <td style=" width:54%;">{{ $detail->product->name }}</td>
                    <td style=" width: 25%; text-align:right;">${{ \Universal::formatNumber($detail->price) }}</td>
                    <td style=" width: 25%; text-align:right;">
                        ${{ \Universal::formatNumber($detail->cant * $detail->price) }}
                    </td>
                    @if ($detItbis)
                        <td style=" width: 20%; text-align:right">
                            {!! $detail->itbis > 0 ? '$' . \Universal::formatNumber($detail->itbis) : '<i>Exento</i>' !!}

                        </td>
                    @endif
                </tr>

            @empty
            @endforelse
            <tr class="total" style="font-weight: bold; text-transform: uppercase; ">
                <td style="text-align: left; padding-top:15px ">
                    <div>{{ Universal::formatNumber($invoice->details->sum('cant')) }}</div>
                </td>
                <td style="text-align: left; padding-top:15px  ">
                    <div>Artículos</div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div></div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div>${{ \Universal::formatNumber($invoice->subtotal)}}</div>
                </td>
                @if ($detItbis)
                    <td style=" width: 20%; text-align:right; padding-top:15px ">
                        {!! \Universal::formatNumber($invoice->itbis) !!}

                    </td>
                @endif

            </tr>
        </table>
        <br>
        <table class="totales">
            <tr class="total" style="font-weight: bold; ">
                <td colspan="2"></td>
                <td style="text-align: right;  ">
                    <div>SUBTOTAL</div>
                </td>
                <td style="text-align: right">
                    <div>${{ \Universal::formatNumber($invoice->subtotal) }}</div>
                </td>
            </tr>
            @if ($detItbis)
                )
                <tr class="total" style="font-weight: bold">
                    <td colspan="2"></td>
                    <td style="text-align: right;">
                        <div>ITBIS</div>
                    </td>
                    <td style="text-align: right">
                        <div><span style="color:blue">+</span> ${{ \Universal::formatNumber($invoice->itbis) }}</div>
                    </td>
                </tr>
            @endif
            <tr class="total" style="font-weight: bold;">
                <td colspan="2"></td>
                <td style="text-align: right; ">
                    <div>{{ $invoice->discount >= 0 ? 'DESCUENTO' : 'RECARGO' }}</div>
                </td>
                <td style="text-align: right; ">
                    <div><span>{{ $invoice->discount > 0 ? '-' : ($invoice->discount < 0 ? '+' : '') }}</span>
                        ${{ \Universal::formatNumber(abs($invoice->discount)) }}
                    </div>
                </td>
            </tr>


            <tr class="" style="font-weight: bold; font-size:medium">
                <td colspan="2"></td>
                <td style="text-align: right; border-top: 1px solid #777;">
                    <div>TOTAL</div>
                </td>
                <td style="text-align: right; border-top: 1px solid #777;">
                    <div>${{ \Universal::formatNumber($invoice->total) }}</div>
                </td>
            </tr>
            @if ($invoice->rest > 0)
                <tr class="" style="font-weight: bold; font-size:medium">
                    <td colspan="2"></td>
                    <td style="text-align: right; padding-top:15px">
                        <div>ABONADO</div>
                    </td>
                    <td style="text-align: right; padding-top:15px">
                        <div>${{ \Universal::formatNumber($invoice->payed) }}</div>
                    </td>
                </tr>
                <tr class="" style="font-weight: bold; font-size:medium; color:red">
                    <td colspan="2"></td>
                    <td style="text-align: right;">
                        <div>RESTA</div>
                    </td>
                    <td style="text-align: right;">
                        <div>${{ \Universal::formatNumber($invoice->rest) }}</div>
                    </td>
                </tr>
            @endif

        </table>

        <table>
            <tr>
                <td style="padding-top: 60px; ">
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:max-content; text-align:center; margin-right: 20px">
                        ENTREGADO POR</div>
                </td>
                <td style="padding-top: 60px;">
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:max-content; text-align:center; margin-left: 10px">
                        RECIBIDO POR</div>
                </td>
            </tr>
        </table>

        @if ($invoice->type == 'cotize')
            <table>
                <tr>
                    <td colspan="4" style="padding-top: 10px; ">
                        @if ($invoice->note && $invoice->note != '***')
                            <div
                                style=" padding-top: 0px; width:max-content; text-align:center; margin-right: 20px; text-transform:uppercase">
                                {{ $invoice->note }}</div>
                        @endif
                        <div style=" padding-top: 4px; width:max-content; text-align:center; margin-right: 20px">
                            LOS PRECIOS DE ESTA COTIZACIÓN ESTÁN SUJETOS A CAMBIOS</div>
                    </td>

                </tr>
            </table>
        @else
            <table>
                <tr>
                    <td colspan="4" style="padding-top: 10px; ">
                        @if ($invoice->note && $invoice->note != '***')
                            <div
                                style=" padding-top: 0px; width:max-content; text-align:center; margin-right: 20px; text-transform:uppercase">
                                {{ $invoice->note }}</div>
                        @endif
                        <div style=" padding-top: 0px; width:max-content; text-align:center; margin-right: 20px">
                            SUJETO A POLÍTICAS DE NO DEVOLUCIÓN</div>
                        <div style=" padding-top: 0px; width:max-content; text-align:center; margin-right: 20px">
                            GRACIAS POR PREFERIRNOS</div>
                    </td>

                </tr>
            </table>
        @endif
    </div>
    <footer>
        <table style="width: 100%">
            <tr>
                <td>GENERADA POR SISTEMA</td>
                <td style="text-align: right">ORIGINAL CLIENTE</td>
            </tr>
        </table>
    </footer>
    <table style="width: 100%; page-break-after: always;">
        <tr style="{{ isset($show) ? '' : 'display:none' }}">
            <td colspan="2">
                @if ($invoice->type == 'sale')
                    <div style=""><a href="/invoices">Volver</a></div>
                @else
                    <div style=""><a href="/cotizes">Volver</a></div>
                @endif
            </td>

        </tr>
    </table>

</body>

</html>
