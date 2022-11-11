<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Comprobantes 607</title>
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

        .cuerpo tr:nth-child(2n) {
            background: #DDD
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

        @page {
            size: a2 portrait;
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
        Comprobantes para el 607 ({{ \Carbon\Carbon::parse($start_at)->format('Ym') }})
    </h2>

    <table>
        <thead>
            <tr>
                <th style="text-align: left">RNC/Cédula</th>
                <th style="text-align: left">Tipo ID</th>
                <th style="text-align: left">NCF Asign.</th>
                <th style="text-align: left">NCF Mod.</th>
                <th style="text-align: left">Tipo Ingreso</th>
                <th style="text-align: left">F. Ret.</th>
                <th style="text-align: left">F. Mod.</th>
                <th style="text-align: left">Monto</th>
                <th style="text-align: left">ITBIS</th>
                <th style="text-align: left">Efectivo</th>
                <th style="text-align: left">Transf/Cheque</th>
                <th style="text-align: left">Tarjeta</th>
                <th style="text-align: left">Crédito</th>
            </tr>
        </thead>
        <tbody class="cuerpo">
            {{-- <tr>
                <td colspan="9">
                    <hr>
                    <div
                        style="width: 100%; text-align:center; font-size:large; font-weight:bold; text-transform:uppercase; padding-top:10px; padding-bottom:10px">
                        INGRESOS POR VENTAS Y PENDIENTES COBRADOS
                    </div>
                </td>
            </tr> --}}
            @forelse ($comprobantes as $ind=>  $comprobante)
                @php
                    $docId = $comprobante->invRnc ?: $comprobante->rnc;
                    $docId = str_replace('-', '', $docId);
                    $docId!='00000000000' ?$docId=$docId: $docId = '131459331';
                @endphp
                <tr style="font-size: normal; {{ fmod($ind + 1, 2) == 0 ? 'background-color:#EEE' : '' }}">
                    <td style=" text-align: left">
                        {{ $docId }}
                    </td>

                    <td style="width:3%;  text-align: left">
                        {{ strlen($docId) == 9 ? 1 : 2 }}
                    </td>
                    <td style="  text-align: left">
                        {{ $comprobante->ncf }}
                    </td>

                    <td style=" text-align: left">
                        {{ '-' }}
                    </td>
                    <td style="width:5%;  text-align: left">
                        1
                    </td>
                    <td style=" text-align: left">
                        {{ \Carbon\Carbon::parse($comprobante->day)->format('Ymd') }}
                    </td>
                    <td style=" text-align: left">
                        - - -
                    </td>
                    <td style=" text-align: left;  font-weight:bold">
                        ${{ formatNumber($comprobante->amount) }}
                    </td>
                    <td style=" text-align: left">
                        ${{ formatNumber($comprobante->tax) }}
                    </td>
                    <td style=" text-align:left; ">
                        ${{ formatNumber($comprobante->efectivo > 0 ? $comprobante->efectivo : 0) }}
                    </td>
                    <td style=" text-align:left;">
                        ${{ formatNumber($comprobante->transferencia) }}
                    </td>
                    <td style=" text-align:left; ">
                        ${{ formatNumber(0) }}
                    </td>
                    <td style=" text-align:left;">
                        ${{ formatNumber($comprobante->rest) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">

                    </td>
                </tr>
            @endforelse

            @forelse ($creditnotes as $indi=>  $creditnote)
                @php
                    $docId = $creditnote->invRnc ?: $creditnote->rnc;
                    $docId = str_replace('-', '', $docId);
                @endphp
                <tr style="font-size: normal; {{ fmod($indi + 1, 2) == 0 ? 'background-color:#EEE' : '' }}">
                    <td style=" text-align: left">
                        {{ $docId }}
                    </td>

                    <td style="width:3%;  text-align: left">
                        {{ strlen($docId) == 9 ? 1 : 2 }}
                    </td>
                    <td style="  text-align: left">
                        {{ $creditnote->invNcf }}
                    </td>

                    <td style=" text-align: left">
                        {{ $creditnote->ncf }}
                    </td>
                    <td style="width:5%;  text-align: left">
                        1
                    </td>
                    <td style=" text-align: left">
                        {{ \Carbon\Carbon::parse($creditnote->invDay)->format('Ymd') }}
                    </td>
                    <td style=" text-align: left">
                        {{ \Carbon\Carbon::parse($creditnote->day)->format('Ymd') }}
                    </td>
                    <td style=" text-align: left;  font-weight:bold">
                        ${{ formatNumber($creditnote->amount) }}
                    </td>
                    <td style=" text-align: left">
                        ${{ formatNumber($creditnote->tax) }}
                    </td>
                    <td style=" text-align:center; ">
                        {{ '-' }}
                    </td>
                    <td style=" text-align:center; ">
                        {{ '-' }}
                    </td>
                    <td style=" text-align:center; ">
                        {{ '-' }}
                    </td>
                    <td style=" text-align:center; ">
                        {{ '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">

                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <hr>
    <script type="text/php">if ( isset($pdf) ) {
                                            $font = $fontMetrics->get_font("helvarialetica", "bold");
                                            $pdf->page_text(18, 18, auth()->user()->store->name.". Página: {PAGE_NUM} de {PAGE_COUNT}  ", $font, 12, array(0,0,0));
                                            $pdf->page_text(18, 32, date('d/m/Y'), $font, 12, array(0,0,0));
                                            $pdf->page_text(18, 1640, "Reporte de comprobantes para el 607", $font, 12, array(0,0,0));
                                        }</script>
    @if ($comprobantes->count() > (18 * $comprobantes->count()) / 2 + 2)
        <div style="page-break-after: always"></div>
    @endif
    <table style="width: 40%; margin-top:35px;float: left; line-height:10px">
        <tr>
            <td colspan="6">
                <div
                    style="text-transform: uppercase; font-weight:bold; font-size:large; width:100%; text-align:center">
                    Resumen General de Facturas de Consumo (F.C.):
                </div>
            </td>
        </tr>

        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:25px; text-transform:uppercase">
                Cantidad NCFs Emitidos de F.C.:
            </td>
            <td colspan="2" style="padding-top:25px">
                {{ count($resumen) ?: '0' }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                Total ITBIS Facturado:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber($resumen->sum('tax')) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                Total Monto Facturado:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber($resumen->sum('amount')) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                Impuesto Selectivo al Consumo:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber(0) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                Total Otros Impuestos/Tasas:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber(0) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                Total Monto Propina Legal:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber(0) }}
            </td>
        </tr>
        <tr >
            <td colspan="6">
                <div
                style="text-transform: uppercase; font-weight:bold; font-size:large; width:100%; text-align:center; margin-top: 30px">
               DETALLES DE TRIBUTACIÓN
            </div>
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
               PROPRORCIÓN RESUMEN:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber($props['resProp']) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                OTRA PROPORCIÓN:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber($props['compProp']) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
               MONTO GRAVADO:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber($props['propTotal']) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
              MONTO NO GRAVADO:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber($props['total']-$props['propTotal']) }}
            </td>
        </tr>
    </table>
    <table style="width: 40%; margin-top:35px; float: right; line-height:10px">
        <tr>
            <td colspan="6">
                <div
                    style="text-transform: uppercase; font-weight:bold; font-size:large; width:100%; text-align:center">
                    DETALLES DE LAS VENTAS:
                </div>
            </td>
        </tr>

        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:25px; text-transform:uppercase">
                EFECTIVO:
            </td>
            <td colspan="2" style="padding-top:25px">
                ${{ formatNumber($resumen->sum('efectivo')>0?$resumen->sum('efectivo'):0) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                CHEQUE/TRANSFERENCIA/DEPOSITO:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber($resumen->sum('transferencia')) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                TARJETA DEBITO / CREDITO:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber(0) }}
            </td>
        </tr>
        <tr style="font-weight: bold">
            <td colspan="4" style="padding-top:10px; text-transform:uppercase">
                VENTA A CREDITO:
            </td>
            <td colspan="2" style="padding-top:10px">
                ${{ formatNumber($resumen->sum('rest')) }}
            </td>
        </tr>
      
    </table>
    
</body>

</html>