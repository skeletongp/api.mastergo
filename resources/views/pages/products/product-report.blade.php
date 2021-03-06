<!DOCTYPE html>
<html lang="es">
@php
setlocale(LC_MONETARY, 'en_IN');

@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inventario</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            color: #222;
            margin-top: 11.1rem;
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
            line-height: 20px;
            text-align: left;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        table tr td:nth-child(2) {
            text-align: right;
        }

        table tr.top table td {
            padding-bottom: 20px;
        }

        table tr.top table td.title {
            font-size: 45px;
            line-height: 25px;
            color: #333;
        }

        table tr.information table td {
            padding-bottom: 10px;
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
    </style>
</head>
<header style="position: fixed;top: 0px;left: 0px;right: 0px; height: 250rem;">
    <table style="width:100%">
        <thead style="display: table-header-group;">
            <tr class="item">
                <td class="title">
                    <img src="{{auth()->user()->store->logo}}" alt="Company logo"
                        style=" max-width: 150px; max-height: 100px" />
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
        Inventario General
    </h3>
</header>
<body>

    <table>
        <tr style="background-color:#4eF; font-weight:bold; font-size:large; " class="total">
            <td style=" width: 10%; text-align:right; background-color:#fff; " colspan="2">
                Valor:
            </td>
            <td style=" width: 25%; text-align:center">
                ${{ formatNumber($totalValor) }}
            </td>
            <td style=" width: 25%; text-align:right; background-color:#fff;">
                Salida:
            </td>
            <td style=" width: 25%; text-align:center">
                ${{ formatNumber($totalSalida) }}
            </td>
        </tr>
    </table>
    <br>
    <table>
        <thead>
            <tr class="heading">
                <th>Cod.</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Costo</th>
                <th>Precio</th>
                <th>Utilidad</th>
            </tr>
        </thead>
        <tbody class="cuerpo">

            @foreach ($products as $product)
                @php
                    $unit = $product->units->first();
                @endphp
                <tr class="item">
                    <td style=" width: 10%;">
                        <div style="padding-right: 20px">{{ $product->id }}</div>
                    </td>
                    <td style=" width: 40%;">{{ $product->name }}</td>

                    <td
                        style=" width: 25%; text-align:left;  {{ $unit->pivot->stock > 0 ? 'color:black' : 'color:red' }}">
                        {{ formatNumber($unit->pivot->stock) }}
                    </td>
                    <td style=" width: 25%; text-align:left;">
                        <span style="{{ $unit->pivot->cost > 0 ? 'color:black' : 'color:#07f' }}">
                            ${{ formatNumber($unit->pivot->cost) }}
                        </span>
                        <br>
                        <b
                            style="{{ $unit->pivot->stock > 0 ? 'color:black' : 'color:red' }}">${{ formatNumber(operate($unit->pivot->cost, '*', $unit->pivot->stock)) }}</b>
                    </td>
                    <td style=" width: 25%; text-align:left">
                        ${{ formatNumber($unit->pivot->price_menor) }}
                        <br>
                        <b style="{{ $unit->pivot->stock > 0 ? 'color:black' : 'color:red' }}">
                            ${{ formatNumber(operate($unit->pivot->price_menor, '*', $unit->pivot->stock)) }}</b>
                    </td>
                    <td style=" width: 25%; text-align:center">
                        <span>${{ formatNumber(operate($unit->pivot->price_menor, '-', $unit->pivot->cost)) }}</span>
                        <br>
                        <b style="{{ $unit->pivot->stock > 0 ? 'color:black' : 'color:red' }}">
                            ${{ formatNumber(operate($unit->pivot->price_menor, '*', $unit->pivot->stock)-operate($unit->pivot->cost, '*', $unit->pivot->stock)) }}</b>
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

</body>

</html>
