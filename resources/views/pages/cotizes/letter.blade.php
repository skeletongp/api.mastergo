<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cotización No. {{ $cotize->id }}</title>

    <style>
        @page {
            size: letter;
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

        .sello {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: absolute;

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

        .cotize-box {
            max-width: 800px;
            margin: auto;
            padding: 5px;
            padding-top: 0;

            font-size: 14px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .cotize-box table {
            width: 100%;
            line-height: 20px;
            text-align: left;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .cotize-box table td {
            padding-right: 5px;
            padding-left: 5px;
            vertical-align: top;
        }

        .cotize-box table tr td:nth-child(2) {
            text-align: right;
        }

        .cotize-box table tr.top table td {
            padding-bottom: 20px;
        }

        .cotize-box table tr.top table td.title {
            font-size: 45px;
            line-height: 25px;
            color: #333;
        }

        .cotize-box table tr.information table td {
            padding-bottom: 20px;
        }

        .cotize-box table tr.heading td {
            background: #ddd;
            border-bottom: 2px solid #fff;
            font-weight: bold;
            text-align: left;
        }

        .cotize-box table tr.details td {
            padding-bottom: 20px;
            text-align: left;


        }

        .cotize-box table tr.item td {
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: middle;
        }

        .cotize-box table tr.item.last td {
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
            .cotize-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .cotize-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>



<body>
    <div style="position: absolute; right:4; top: 0; color:white; z-index:50; ">
        {{ date_format($cotize->updated_at, 'H:i A') }}
    </div>
    <div class="sello"></div>
    <div class="cotize-box" id="box" style="position: relative;">
        <div style="position: absolute;  top:20px; text-align:center; width: 100%; ">
            <b style="text-transform: uppercase; font-size:x-large; font-weight:bold; padding-bottom:10px">
                {{ $cotize->store->name }}</b><br />
            {!! $cotize->store->rnc ? '<b>RNC  :</b> ' . $cotize->store->rnc . '<br />' : '' !!}
            <b>TEL:</b> {{ $cotize->store->phone }} <br>
            <b>EMAIL: </b>{{ $cotize->store->email }}<br />
            {{ $cotize->store->address }}
        </div>
        <br>
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title" style="padding-top:10px">
                                <img src="{{ $cotize->store->logo }}" alt=" "
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
                            <td colspan="2" style="border-right: .3px solid #ccc; border-bottom: .3px solid #ccc; ">
                                <div>
                                    <b>Cot. Nº. </b>{{ $cotize->id }}<br />
                                    <b>Fecha:</b> {{ date_format(date_create($cotize->day), 'd/m/Y') }}<br />

                                </div>
                            </td>
                            <td colspan="2" style="border-bottom: .3px solid #ccc; ">
                                <div style="text-align:right; ">
                                    <b>{{ 'ATENCIÓN A:' }}</b> <br>
                                    {{ $cotize->client->name ?: $cotize->client->contact->fullname }}<br />
                                    {!! $cotize->client->rnc ? '<b>RNC /CED:</b> ' . $cotize->client->rnc . '<br />' : '' !!}
                                    <b>TEL:</b> {{ $cotize->client->phone }} <br>
                                    {{ $cotize->client->address ?: 'Dirección N/D' }}
                                </div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                    <td colspan="6"
                        style="text-transform:uppercase; font-weight:bold; font-size:large; text-align:center; padding-bottom: 15px; padding-top:15px">
                        COTIZACIÓN/CONDUCE
                    </td>
            </tr>
            <tr class="heading">
                <td style="padding-left:10px">Cant.</td>
                <td style="padding-left:10px">
                    Descripción
                </td>
                <td style="padding-left:10px; text-align:right">Precio</td>
                <td style="padding-left:10px;  text-align:right">Subtotal</td>
                <td style="padding-left:10px; text-align:right">Desc</td>
                <td style="padding-left:10px; text-align:right">Total</td>

            </tr>

            @forelse ($cotize->details as $ind=> $detail)
                <tr class="item" style="{{fmod($ind,2)==0?'background-color:#eee':'background-color:#fff'}}">
                    <td style=" width:20%; text-align:left; padding-left:10px;">
                            <span>{{ formatNumber($detail->cant) }} <span style="font-size: xx-small">{{ $detail->unit->symbol }}</span></span>
                    </td>
                    <td style=" width:54%;padding-left:10px; ">{{ ellipsis($detail->product->name,30) }}</td>
                    <td style=" width: 25%;padding-left:10px;  text-align:right;">${{ formatNumber($detail->price) }}</td>
                    <td style=" width: 25%;padding-left:10px;  text-align:right;">
                        ${{ formatNumber($detail->subtotal) }}
                    </td>
                    <td style=" width: 25%;padding-left:10px;  text-align:right;">${{ formatNumber($detail->discount) }}</td>
                    <td style=" width: 25%;padding-left:10px;  text-align:right;">${{ formatNumber($detail->total) }}</td>

                </tr>
            @empty
            @endforelse
            <tr class="total" style="font-weight: bold; text-transform: uppercase; font-size:small">
                <td style="text-align: left; padding-top:15px ">
                    <div>{{ formatNumber($cotize->details->count()) }}</div>
                </td>
                <td style="text-align: left; padding-top:15px  ">
                    <div>Artículos</div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div></div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div></div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div></div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div>${{ formatNumber($cotize->total) }}</div>
                </td>

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
                    <div>${{ formatNumber($cotize->amount) }}</div>
                </td>
            </tr>

            <tr class="total" style="font-weight: bold;">
                <td colspan="2"></td>
                <td style="text-align: right; ">
                    <div>{{ $cotize->discount >= 0 ? 'DESCUENTO' : 'RECARGO' }}</div>
                </td>
                <td style="text-align: right; ">
                    (${{ formatNumber(abs($cotize->discount)) }})
                </td>
            </tr>
            <tr class="" style="font-weight: bold; font-size:medium">
                <td colspan="2"></td>
                <td style="text-align: right; border-top: 1px solid #777; padding-bottom:15px">
                    <div>TOTAL</div>
                </td>
                <td style="text-align: right; border-top: 1px solid #777; padding-bottom:15px">
                    <div>${{ formatNumber($cotize->total) }}</div>
                </td>
            </tr>


        </table>

        <table>
            <tr>
                <td style="padding-top: 30px; ">
                    <div style="width: 100%; text-align:center; text-transform:uppercase; font-weight:bold; font-size:large">
                        {{$cotize->user->fullname}}
                    </div>
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-right: 20px">
                    
                        ENTREGADO POR</div>
                </td>
                <td style="padding-top: 30px;">
                    <div style="width: 100%; text-align:center; text-transform:uppercase; font-weight:bold; font-size:large; padding-top:20px">
                       
                    </div>
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-left: 10px">
                        RECIBIDO POR</div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="4" style="padding-top: 10px; text-align:center">

                    <div style=" padding-top: 0px; width:100%; text-align:center; margin-right: 20px">
                        ESTA COTIZACIÓN NO ES UNA FACTURA FORMAL. SUJETA A MODIFICACIONES</div>
                    <div style=" padding-top: 0px; width:100%; text-align:center; margin-right: 20px">
                        GRACIAS POR PREFERIRNOS</div>
                </td>

            </tr>
        </table>

    </div>

    <footer>
        <table style="width: 100%">
            <tr>
                <td>GENERADA POR SISTEMA</td>
                <td style="text-align: right">ORIGINAL CLIENTE</td>
            </tr>
        </table>
    </footer>
  

</body>


</html>
