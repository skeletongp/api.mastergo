<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estado de G/p y Balance General</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            text-align: center;
            color: #222;
            margin-top: 11.5rem;
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

        .row {
            background-color: #dee2e6;
            text-transform: uppercase;
            color: #000000;
            border: 1px solid white;
            border-collapse: collapse;
        }


        .row-second {
            background-color: #fff;
            text-transform: uppercase;
            color: #000000;
            border: 1px solid white;
            border-collapse: collapse;
        }

        .row-third {
            background-color: #fff;
            text-transform: uppercase;
            color: #000000;
            border: 1px solid white;
            border-collapse: collapse;
        }

        .row th {
            padding-top: 10px;
            width: 60%;
            padding-bottom: 10px;
            padding-left: 15px;
            text-align: left;
        }

        .row td {
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-right: 15px;
            text-align: right;
        }

        .row-second th {
            padding-top: 5px;
            width: 60%;
            padding-bottom: 5px;
            padding-left: 30px;
            text-align: left;
            font-weight: normal;
        }

        .row-second td {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-right: 15px;
            text-align: right;
        }

        .row-third th {
            padding-top: 10px;
            width: 60%;
            padding-bottom: 10px;
            padding-left: 15px;
            text-align: left;
        }

        .row-third td {
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 10px;
            padding-right: 15px;
            text-align: right;
        }
    </style>
</head>
<header style="position: fixed;top: 0px;left: 0px;right: 0px;height: 50px;">
    <table style="width:100%">
        <thead style="display: table-header-group;">
            <tr class="item">
                <td class="title">
                    <img src="{{ auth()->user()->store->logo }}" alt="Company logo"
                        style=" max-width: 150px; max-height: 75px" />
                </td>
                <td colspan="2" style="text-align:right; line-height: 20px ">
                    <b style="text-transform: uppercase; font-size:x-large"> {!! auth()->user()->store->name !!}</b><br />
                    <i>{!! auth()->user()->store->lema !!}</i><br />
                    <b>Tel.: </b>{{ auth()->user()->store->phone }}<br />
                    <b>Rnc: </b>{{ auth()->user()->store->rnc ?: '00000000' }}<br />
                    {{ auth()->user()->store->address }}<br />
                    <br />
                </td>
            </tr>
        </thead>

    </table>
    <h3 style="text-transform: uppercase">
        Estado de resultados
    </h3>

</header>
<body>

    <table>
        <tbody class="cuerpo" style="width: 100%">
            {{-- Ingresos --}}
            <tr class="row">
                <th>
                    Ventas Totales
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['ventas']) }}
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    (-) Devoluciones en ventas
                </th>
                <td>
                    ${{ formatNumber($data['devoluciones']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    (-) Descuentos en ventas
                </th>
                <td>
                    ${{ formatNumber($data['descuentos']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    (+) Otros Ingresos
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['otros_ingresos']) }}
                </td>
            </tr>
            <tr class="row-third">
                <th>
                    Ventas Netas
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['ingresos']) }}
                </td>

            </tr>

            {{-- Costos --}}
            <tr class="row">
                <th>
                    Costos Totales
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['costos_totales']) }}
                </td>
            </tr>
            <tr class="row-second">
                <th>
                   Costos de Ventas
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['costos_ventas']) }}
                </td>
            </tr>
            <tr class="row-second">
                <th style="padding-left: 60px">
                   Devolución en compras
                </th>
                <td>
                    ${{ formatNumber($data['devolucionCompras']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-second">
                <th style="padding-left: 60px">
                   Descuentos en compras
                </th>
                <td>
                    ${{ formatNumber($data['descuentosCompras']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-second">
                <th>
                   Costos de Fletes
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['costos_flete']) }}
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    Otros Costos
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['otros_costos']) }}
                </td>
            </tr>
            <tr class="row-third">
                <th>
                    Utilidad Bruta
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['utilidad']) }}
                </td>

            </tr>
            {{-- Gastos --}}
            <tr class="row">
                <th>
                    Gastos Totales
                </th>
                <td>
                    ${{ formatNumber($data['gastos_ventas']+$data['gastos_admin']+$data['gastos_financieros']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-second">
                <th>
                   Gastos de ventas
                </th>
                <td>
                    ${{ formatNumber($data['gastos_ventas']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    Gastos Administrativos
                </th>
                <td>
                    ${{ formatNumber($data['gastos_admin']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    Gastos Financieros
                </th>
                <td>
                    ${{ formatNumber($data['gastos_financieros']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-third">
                <th>
                    Utilidad Antes de ISR (27%)
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['utilidad_antes_impuestos']) }}
                </td>

            </tr>
            <tr class="row">
                <th>
                    Utilidad Neta 
                </th>
                <td></td>
                <td style="font-size: large">
                    ${{ formatNumber($data['utilidad_neta']) }}
                </td>
            </tr>
        </tbody>
    </table>
    <hr>
    <h3 style="text-transform: uppercase">
        Balance General
    </h3>
    <table>
        <tbody class="cuerpo" style="width: 100%">
            {{-- BALANCE --}}
          
            <tr class="row-second">
                <th>
                    Activo
                </th>
                <td>
                    ${{ formatNumber($data['activo']) }}
                </td>
                <td>
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    Pasivo
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['pasivo']) }}
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    Capital
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['capital']) }}
                </td>
            </tr>
            <tr class="row-second">
                <th>
                    Resultados antes de ISR
                </th>
                <td>
                </td>
                <td>
                    ${{ formatNumber($data['utilidad_antes_impuestos']) }}
                </td>
            </tr>
            <tr class="row">
                <th>
                    BALANCE
                </th>
                <td>
                    ${{ formatNumber($data['activo']) }}
                </td>
                <td>
                    ${{ formatNumber($data['pasivo_capital']) }}
                </td>
            </tr>
        
        </tbody>
    </table>

</body>

</html>
