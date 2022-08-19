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
            if ($invoice->status == 'PENDIENTE') {
                $img = asset('/images/pendiente.png');
            } else {
                $img = asset('/images/pagado.png');
            }
        }
        if ($invoice->type == 'cotize') {
            $img = asset('/images/cotizacion.png');
        }
        
    @endphp
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
        {{ date_format($invoice->updated_at, 'H:i A') }}
    </div>
    <div class="sello"></div>
    <div class="invoice-box" id="box" style="position: relative;">
        <div style="right: 15px; top: 30px; position: absolute; ">
            <div style=" overflow:hidden">
                {!! $invoice->barcode !!}
            </div>
        </div>
        <div style="position: absolute;  top:20px; text-align:center; width: 100%; ">
            <b style="text-transform: uppercase; font-size:x-large; font-weight:bold; padding-bottom:10px">
                {{ $invoice->store->name }}</b><br />
            {!! $invoice->store->rnc ? '<b>RNC  :</b> ' . $invoice->store->rnc . '<br />' : '' !!}
            <b>TEL:</b> {{ $invoice->store->phone }} <br>
            <b>EMAIL: </b>{{ $invoice->store->email }}<br />
            {{ $invoice->store->address }}
        </div>
        <br>
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title" style="padding-top:10px">
                                <img src="{{ $invoice->store->logo }}" alt=" "
                                    style=" max-width: 300px; max-height: 100px" />
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="{{ $invoice->comprobante ? '6' : '5' }}">
                    <table>
                        <tr>
                            <td colspan="2" style="border-right: .3px solid #ccc; border-bottom: .3px solid #ccc; ">
                                <div>
                                    {!! $payment->ncf ? '<b>NCF: ' . $payment->ncf . '</b> <br />' : '' !!}
                                    <b>Fact. Nº. </b>{{ $invoice->number }}<br />
                                    <b>Fecha:</b> {{ date_format(date_create($invoice->day), 'd/m/Y') }}<br />
                                    <b>Vence:</b>
                                    {{ date_format(\Carbon\Carbon::create($invoice->expires_at), 'd/m/Y') }}
                                    <br />
                                    <b>Forma de pago:</b>
                                    {{ $invoice->payway }}
                                </div>
                            </td>
                            <td colspan="2" style="border-bottom: .3px solid #ccc; ">
                                <div style="text-align:right; ">
                                    <b>{{ 'DIRIGIDA A:' }}</b> <br>
                                    {{ $invoice->name ?: ($invoice->client->name ?: $invoice->client->contact->fullname) }}<br />
                                    {!! $invoice->rnc ?: ($invoice->client->rnc ? '<b>RNC /CED:</b> ' . $invoice->client->rnc . '<br />' : '') !!}
                                    <b>TEL:</b> {{ $invoice->client->phone }} <br>
                                    {{ $invoice->client->address ?: 'Dirección N/D' }}
                                </div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
            <tr>
                @if ($invoice->store->rnc)
                    <td colspan="{{ $invoice->comprobante ? '6' : '5' }}"
                        style="text-transform:uppercase; font-weight:bold; font-size:large; text-align:center; padding: 15px">
                        {{ array_search($invoice->type, App\Models\Invoice::TYPES) }}

                    </td>
                @else
                    <td colspan="{{ $invoice->comprobante ? '6' : '5' }}"
                        style="text-transform:uppercase; font-weight:bold; font-size:large; text-align:center; padding-bottom: 15px">
                        {{ $invoice->ncf ? $invoice->title : ($invoice->type == 'sale' ? 'Factura' : 'Cotización') }}
                    </td>
                @endif
            </tr>

            <tr class="heading">
                <td>Cant.</td>
                <td>
                    Descripción
                </td>
                <td style="text-align:right"">Precio</td>
                <td style="text-align:right"">Descuento</td>
                <td style=" text-align:right"">Subtotal</td>
                @if ($invoice->comprobante)
                    <td style=" text-align:right">IMP.</td>
                @endif
            </tr>

            @forelse ($invoice->details as $ind=> $detail)
                <tr class="" style="font-size: small">
                    <td style=" width:15%; text-align:left; padding-left:10px;">
                        <span>{{ formatNumber($detail->cant) }} <span
                                style="font-size: xx-small">{{ $detail->unit->symbol }}</span></span>
                    </td>
                    <td style=" width:45%;">{{ellipsis( $detail->product->name,25) }}</td>
                    <td style=" width: 20%; text-align:right;">${{ \formatNumber($detail->price) }}</td>
                    <td style="width: 20%; text-align:right">${{ formatNumber($detail->discount) }}</td>
                    <td style=" width: 25%; text-align:right;">
                        ${{ \formatNumber($detail->total) }}
                    </td>
                    @if ($invoice->comprobante)
                        <td style=" width: 20%; text-align:right">
                            ${!! \formatNumber($detail->taxtotal) !!}

                        </td>
                    @endif
                </tr>
                {{-- @if ($ind == 14)
                    <tr>
                        <td colspan="100%">
                            <h1 style="page-break-after: always; text-align:center">
                                <h1>Sigue en otra página</h1>
                            </h1>
                        </td>
                    </tr>
                @endif --}}
            @empty
            @endforelse
            <tr class="total" style="font-weight: bold; text-transform: uppercase; font-size:small">
                <td style="text-align: left; padding-top:15px ">
                    <div>{{ formatNumber($invoice->details->count()) }}</div>
                </td>
                <td style="text-align: left; padding-top:15px  ">
                    <div>Artículos</div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div></div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div>${{ \formatNumber($payment->discount) }}</div>
                </td>
                <td style="text-align: right; padding-top:15px  ">
                    <div>${{ \formatNumber($payment->total - $payment->tax) }}</div>
                </td>

                @if ($invoice->comprobante)
                    <td style=" width: 20%; text-align:right; padding-top:15px ">
                        {!! \formatNumber($payment->tax) !!}

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
                    <div>${{ \formatNumber($payment->amount) }}</div>
                </td>
            </tr>
            @if ($invoice->comprobante)
                <tr class="total" style="line-height: 14px">
                    <td colspan="2" style="padding-top:-10px"></td>
                    <td style="text-align: right; padding-top:-10px">
                        {{ __('ITBIS') }}

                    </td>
                    <td style="text-align: right; padding-top:-10px">
                        ${{ \formatNumber($payment->tax) }}
                    </td>
                </tr>
            @endif
            <tr class="total" style="font-weight: bold;">
                <td colspan="2"></td>
                <td style="text-align: right; ">
                    <div>{{ $payment->discount >= 0 ? 'DESCUENTO' : 'RECARGO' }}</div>
                </td>
                <td style="text-align: right; ">
                    (${{ \formatNumber(abs($payment->discount)) }})
                </td>
            </tr>
            <tr class="" style="font-weight: bold; font-size:medium">
                <td colspan="2"></td>
                <td style="text-align: right; border-top: 1px solid #777; padding-bottom:15px">
                    <div>TOTAL</div>
                </td>
                <td style="text-align: right; border-top: 1px solid #777; padding-bottom:15px">
                    <div>${{ \formatNumber($payment->total) }}</div>
                </td>
            </tr>
            @if ($payment->efectivo >= 0)
                <tr class="" style="font-weight: bold; ">
                    <td colspan="2"></td>
                    <td style="text-align: right; ">
                        <div>EFECTIVO</div>
                    </td>
                    <td style="text-align: right; ">
                        <div>${{ \formatNumber($payment->efectivo) }}</div>
                    </td>
                </tr>
            @endif
            @if ($payment->tarjeta > 0)
                <tr class="total" style="font-weight: bold; ">
                    <td colspan="2"></td>
                    <td style="text-align: right; ">
                        <div>TARJETA</div>
                    </td>
                    <td style="text-align: right; x">
                        <div>${{ \formatNumber($payment->tarjeta) }}</div>
                    </td>
                </tr>
            @endif
            @if ($payment->transferencia > 0)
                <tr class="total" style="font-weight: bold; ">
                    <td colspan="2"></td>
                    <td style="text-align: right; ">
                        <div>TRANSFERENCIA</div>
                    </td>
                    <td style="text-align: right; ">
                        <div>${{ \formatNumber($payment->transferencia) }}</div>
                    </td>
                </tr>
            @endif
            @if ($payment->rest > 0)
                <tr class="total" style="font-weight: bold; color:red ">
                    <td colspan="2"></td>
                    <td class="td-total text-right" style="text-align: right; padding-top:10px">
                        <b>PENDIENTE</b>
                    </td>
                    <td class="td-total text-right" style="text-align: right; padding-top:10px">
                        <b> ${{ formatNumber($payment->rest) }}</b>
                    </td>
                </tr>
            @else
                <tr class="" style="font-weight: bold; font-size:small">
                    <td colspan="2"></td>
                    <td class="td-total text-right" style="text-align: right; padding-top:2px">
                        <b>CAMBIO</b>
                    </td>
                    <td class="td-total text-right" style="text-align: right; padding-top:2px">
                        <b> ${{ formatNumber($payment->cambio) }}</b>
                    </td>
                </tr>
            @endif
        </table>

        <table>
            <tr>
                <td style="padding-top: 30px; ">
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-right: 20px">
                        ENTREGADO POR</div>
                </td>
                <td style="padding-top: 30px;">
                    <div
                        style="border-top: solid 1px #222; padding-top: 4px; width:100%; text-align:center; margin-left: 10px">
                        RECIBIDO POR</div>
                </td>
            </tr>
        </table>
        <table>
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
        </table>

    </div>
    <div style="position: absolute; right:4; bottom: 0; color:black; z-index:50; text-align:right; font-size:x-small">
        <b> VENDEDOR:</b> {{ $invoice->seller->lastname . ', ' . substr($invoice->seller->name, 0, 1) . '.' }}
        <b> CAJERO:</b> {{ $invoice->contable->lastname . ', ' . substr($invoice->contable->name, 0, 1) . '.' }}
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
