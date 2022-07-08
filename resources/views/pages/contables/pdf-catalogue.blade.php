<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Catálogo de cuentas</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            text-align: center;
            color: #222;
            margin-top: 13.5rem;
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
<header style="position: fixed;top: 0px;left: 0px;right: 0px;height: 50px;">
    <table style="width:100%">
        <thead style="display: table-header-group;">
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
        </thead>

    </table>
    <h3 style="text-transform: uppercase">
        Catálogo de cuentas
    </h3>
</header>

<body >
    
    <table>
        <thead>
            <tr>
                <th style="text-align: left"></th>
                <th style="text-align: left; padding-left:15px">CLASIFICACIÓN</th>
                <th style="text-align: left; padding-left:15px">BAL.</th>

            </tr>
        </thead>
        <tbody class="cuerpo" style="width: 100%">
            @foreach ($ctaControls as $ctaControl)
                <tr style="background-color: #dee2e6; text-transform:uppercase; color:#000000; border: 1px solid white;
                border-collapse: collapse; ">
                    <th style="font-size:medium; text-align:left; padding-top:10px; padding-bottom:10px; padding-left:15px cell-s"
                        id="par" colspan="3" scope="colgroup">
                        {{ $ctaControl->code }} - {{ $ctaControl->name }}
                    </th>
                </tr>
                @forelse ($ctaControl->counts as $count)
                    <tr style=" border: 1px solid #dee2e6;
                    border-collapse: collapse;">
                        <td style="text-align: left; width:55%; padding-top:8px; padding-left:15px">
                            {{ $count->code }} - {{ ellipsis($count->name,40) }}
                        </td>
                        <td style="text-align: left; width:25%; padding-top:8px; padding-left:15px; text-transform:capitalize">
                            {{ substr(App\Models\Count::ORIGINS[$count->origin],0,1) }} ||
                            {{ App\Models\CountMain::CLASE[substr($count['code'], 0, 1)] }}  || {{ $count->type }}
                        </td>
                        <td style="text-align: left; text-transform:capitalize; padding-top:8px; padding-left:15px">
                            ${{ formatNumber($count->balance) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td style="text-align: left; padding-top:8px; padding-left:15px" colspan="3">
                            SIN CUENTAS REGISTRADAS
                        </td>

                    </tr>
                @endforelse
            @endforeach
        </tbody>
    </table>
    <hr>
    <script type="text/php">if ( isset($pdf) ) {
                                                    $font = $fontMetrics->get_font("helvarialetica", "bold");
                                                    $pdf->page_text(18, 18, auth()->user()->store->name.". Página: {PAGE_NUM} de {PAGE_COUNT}  ", $font, 10, array(0,0,0));
                                                    $pdf->page_text(532, 32, date('d/m/Y'), $font, 10, array(0,0,0));
                                                    $pdf->page_text(18, 740, "Catálogo de cuentas actualizado", $font, 10, array(0,0,0));
                                                }</script>
    @if ($ctaControls->count() > (18 * $ctaControls->count()) / 2 + 2)
        <div style="page-break-after: always"></div>
    @endif

</body>

</html>
