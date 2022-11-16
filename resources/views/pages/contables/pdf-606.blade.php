<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Comprobantes 606</title>
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
            .outcome-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .outcome-box table tr.information table td {
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
        @page { size: a2 portrait; } 
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
        Comprobantes para el 606 ({{ \Carbon\Carbon::parse($start_at)->format('Ym') }})
    </h2>

    <table>
        <thead>
            <tr>
                <th style="text-align: left">RNC</th>
                <th style="text-align: left">ID</th>
                <th style="text-align: left">Tipo Gasto</th>
                <th style="text-align: left">NCF</th>
                <th style="text-align: left">NCF Mod.</th>
                <th style="text-align: left">F. Compr.</th>
                <th style="text-align: left">F. Pago</th>
                <th style="text-align: left">Servicios</th>
                <th style="text-align: left">Bienes</th>
                <th style="text-align: left">Monto</th>
                <th style="text-align: left">ITBIS</th>
                <th style="text-align: left">Selectivo</th>
                <th style="text-align: left">ITBIS Ret.</th>
                <th style="text-align: left">Otros</th>
                <th style="text-align: left">Propina</th>
                <th style="text-align: left">Forma Pago</th>
               
            </tr>
        </thead>
        <tbody class="cuerpo">
           {{--  <tr>
                <td colspan="9">
                    <hr>
                    <div
                        style="width: 100%; text-align:center; font-size:large; font-weight:bold; text-transform:uppercase; padding-top:10px; padding-bottom:10px">
                        INGRESOS POR VENTAS Y PENDIENTES COBRADOS
                    </div>
                </td>
            </tr> --}}
            @forelse ($outcomes as $ind=>  $outcome)
                @php
                    $docId = $outcome->rnc ;
                    $docId=str_replace('-', '',$docId);
                    $docId=='0000000000'?$docId=ellipsis($outcome->provider,15):$docId=$docId;
                @endphp
                <tr style="font-size: normal; {{ fmod($ind+1, 2) == 0 ? 'background-color:#EEE' : '' }}">
                    <td style=" text-align: left">
                        {{ $docId }} 
                    </td>

                    <td style="width:5%;  text-align: left">
                        {{ strlen($docId) == 9 ? 1 : 2 }}
                    </td>
                    <td style="width:8%;  text-align: left">
                        {{$outcome->type}}
                    </td>
                    <td style="  text-align: left">
                        {{ $outcome->ncf }}
                    </td>

                    <td style=" text-align: left">
                        {{ '-' }}
                    </td>
                    
                    <td style=" text-align: left">
                        {{ \Carbon\Carbon::parse($outcome->created_at)->format('Ymd') }}
                    </td>
                    <td style=" text-align: left">
                        {{ \Carbon\Carbon::parse($outcome->day)->format('Ymd') }}
                    </td>
                    <td style=" text-align: left;  font-weight:bold">
                        ${{ formatNumber($outcome->services) }}
                    </td>
                    <td style=" text-align: left;  font-weight:bold">
                        ${{ formatNumber($outcome->products) }}
                    </td>
                    <td style=" text-align: left;  font-weight:bold">
                        ${{ formatNumber($outcome->services+$outcome->products) }}
                    </td>
                    <td style=" text-align: left">
                        ${{ formatNumber($outcome->itbis) }}
                    </td>
                    <td style=" text-align: left">
                        ${{ formatNumber($outcome->selectivo) }}
                    </td>
                    <td style=" text-align: left">
                        ${{ formatNumber($outcome->retenido) }}
                    </td>
                    <td style=" text-align: left;  font-weight:bold">
                        ${{ formatNumber($outcome->other) }}
                    </td>
                    <td style=" text-align: left;  font-weight:bold">
                        ${{ formatNumber($outcome->propina) }}
                    </td>
                    <td style=" text-align:left; ">
                        @if ($outcome->efectivo>0 && $outcome->transferencia>0)
                            Mixto
                        @elseif($outcome->rest>0 && ($outcome->efectivo>0 || $outcome->transferencia>0))
                            Mixto
                        @elseif($outcome->efectivo>0)
                            Efectivo
                        @elseif($outcome->transferencia>0)
                            Transferencia
                        @elseif($outcome->rest>0)
                            Crédito

                        @endif
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
                                            $pdf->page_text(18, 1640, "Reporte de comprobantes para el 606", $font, 12, array(0,0,0));
                                        }</script>
    @if ($outcomes->count() > (18 * $outcomes->count()) / 2 + 2)
        <div style="page-break-after: always"></div>
    @endif
   
</body>

</html>
