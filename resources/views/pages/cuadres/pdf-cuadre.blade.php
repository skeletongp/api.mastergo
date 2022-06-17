<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cuadre Diario</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            text-align: center;
            color: #222;
            margin-top: 20px;
            margin-bottom: 20px;
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


        table {
            width: 100%;
            line-height: 15px;
            text-align: left;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table td {
            padding-top: 5px;
            padding-bottom: 5px;
            vertical-align: top;
        }

        table tr td:nth-child(2) {
            text-align: right;
        }

        table tr.top table td {
            padding-bottom: 20px;
        }

        table tr.top table td.title {
            font-size: 35px;
            line-height: 25px;
            color: #333;
        }

        table tr.information table td {
            padding-bottom: 20px;
        }

        table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            text-align: left;
        }

        table tr.details td {
            padding-bottom: 20px;
            text-align: left;


        }

        table tr.item td {
            border-bottom: 1px solid #eee;
            text-align: left;
            vertical-align: middle;
        }

        table tr.item.last td {
            border-bottom: none;
            border-spacing: 5rem;
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

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }
    </style>
</head>

<body>
    <table>
        <tr class="top">
            <td colspan="4">
                <table>
                    <tr class="item">
                        <td class="title">
                            <img src="{{ auth()->user()->store->logo }}" alt="Company logo"
                                style=" max-width: 250px; max-height: 165px" />
                        </td>
                        <td colspan="2" style="text-align:right; line-height: 20px ">
                            <b style="text-transform: uppercase; font-size:x-large"> {!! auth()->user()->store->name !!}</b><br />
                          <i>{!! auth()->user()->store->lema !!}</i><br />
                            <b>Tel.: </b>{{ auth()->user()->store->phone }}<br />
                            <b>Rnc: </b>{{ auth()->user()->store->rnc }}<br />

                            {{ auth()->user()->store->address }}<br />
                            <br />

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <h2 style="text-transform: uppercase">
        Cuadre Diario - Cierre de Caja ({{ date('h:i: A') }})
    </h2>

    <table>
        <thead>
            <tr>
                <th style="text-align: left">Doc.</th>
                <th style="text-align: left">ID/RNC</th>
                <th style="text-align: left">Responsable</th>
                <th style="text-align: left">Tipo</th>
                <th style="text-align: right">Monto</th>

            </tr>
        </thead>
        <tbody class="cuerpo">

            @foreach ($payments as $payment)
                <tr>
                    <td style="width: 15%; text-align: left">
                        {{ $payment->payable->number?:$payment->payable->ref }}
                    </td>
                    <td style="width: 17%; text-align: left">
                        {{ $payment->payer->rnc?:'000-00000-0' }}
                    </td>
                    <td>
                        {{ $payment->payable->name ?: $payment->payer->fullname }}
                    </td>
                    <td style="width: 12%; text-align: left">
                        {{ ucfirst($payment->forma) }}
                    </td>
                    <td style="width: 23%; text-align:right">
                        ${{ formatNumber($payment->total) }}

                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>
    <hr>
    <script type="text/php">if ( isset($pdf) ) {
                $font = $fontMetrics->get_font("helvarialetica", "bold");
                $pdf->page_text(400, 18, auth()->user()->store->name.". Página: {PAGE_NUM} de {PAGE_COUNT}  ", $font, 10, array(0,0,0));
                $pdf->page_text(532, 32, date('d/m/Y'), $font, 10, array(0,0,0));
                $pdf->page_text(18, 740, "Reporte de cierre de caja diario", $font, 10, array(0,0,0));
            }</script>
    @if ($payments->count() > 18*$payments->count()/2+2 )
        <div style="page-break-after: always"></div>
    @endif
    <table style="width: 40%; margin-top:35px;float: left; line-height:10px">
        <tr style="">
            <td colspan="2">
                + Efectivo...
            </td>
            <td colspan="2">
                ${{ formatNumber($cuadre->efectivo) }}
            </td>
        </tr>
        <tr style="">
            <td colspan="2">
                + Transferencia...
            </td>
            <td colspan="2">
                ${{ formatNumber($cuadre->transferencia) }}
            </td>
        </tr>
        <tr style="">
            <td colspan="2">
                + Otros...
            </td>
            <td colspan="2">
                ${{ formatNumber($cuadre->tarjeta) }}
            </td>
        </tr>

        <tr style="">
            <td colspan="2">
                - Devolución...
            </td>
            <td colspan="2">
                (${{ formatNumber($cuadre->devolucion) }})
            </td>
        </tr>
        <tr style="">
            <td colspan="2">
                - Egresos...
            </td>
            <td colspan="2">
                (${{ formatNumber($cuadre->egreso) }})
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <hr>
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="2" style="padding-top:25px">
                BALANCE =>
            </td>
            <td colspan="2" style="padding-top:25px">
                ${{ formatNumber($cuadre->total) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="2" style="padding-top:25px">
                RETIRADO =>
            </td>
            <td colspan="2" style="padding-top:25px">
                ${{ formatNumber($cuadre->retirado) }}
            </td>
        </tr>
    </table>
    <table style="width: 50%; margin-top:35px;float: right; line-height:10px">
        <tr style="">
            <td colspan="2">
                Venta de Contado...
            </td>
            <td colspan="2">
                ${{ formatNumber($cuadre->contado) }}
            </td>
        </tr>
        <tr style="">
            <td colspan="2">
                Venta a Crédito...
            </td>
            <td colspan="2">
                ${{ formatNumber($cuadre->credito) }}
            </td>
        </tr>
        <tr style="">
            <td colspan="2">
                Venta a Cobros...
            </td>
            <td colspan="2">
                ${{ formatNumber($cuadre->cobro) }}
            </td>
        </tr>
        <tr style="">
            <td colspan="2">
                Recibos de ingresos...
            </td>
            <td colspan="2">
                ${{ formatNumber($cuadre->recibo_ingreso) }}
            </td>
        </tr>
        <tr style="">
            <td colspan="2">
                Recibos de egresos...
            </td>
            <td colspan="2">
                ${{ formatNumber($cuadre->recibo_egreso) }}
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <hr>
            </td>
        </tr>
    </table>
</body>

</html>
