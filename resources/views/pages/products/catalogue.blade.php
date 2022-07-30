<!DOCTYPE html>
<html lang="es">
@php
setlocale(LC_MONETARY, 'en_IN');

@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Catálogo de productos</title>
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

        @page {
            size: 259mm 297mm;
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
                    <img src="{{ auth()->user()->store->logo }}" alt="Company logo"
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
        NUESTROS PRODUCTOS
    </h3>
</header>

<body>

    <table style="margin-left:auto; margin-right:auto; ">

        <tbody class="cuerpo">
            @php
                $index = 0;
            @endphp
            @foreach ($products as $ind => $product)
                @php
                    $unit = $product->units->first();
                @endphp
                @if ($unit->pivot->price_menor > 0)
                @php
                    $index+=1;
                @endphp
                    @if (fmod($index, 2) == 1)
                        <tr style="border-bottom: solid 1.5px #AAA">
                            <td style="padding:15px; text-align:center">
                                <img style="border-radius: 50%; width:8rem; height:8rem" src="{{ $product->photo }}"
                                    alt="logo">
                            </td>
                            <td style="padding:15px; width:30%">
                                <b
                                    style="text-transform: uppercase; font-size:medium; color: #8E1301">{{ ellipsis($product->name, 30) }}</b><br>
                                <b style="color: #555">Cód.: </b>{{ $product->code }}<br>
                                <br>
                                <b style="color: #555">Unidad:</b> {{ $unit->name }}<br>
                                <b style="color: #555">Detalle: </b> ${{ formatNumber($unit->pivot->price_menor) }}<br>
                                <b style="color: #555">Mayoreo: </b> ${{ formatNumber($unit->pivot->price_mayor) }}<br>
                                <b style="color: #555">Cant. Min.: </b> {{ formatNumber($unit->pivot->min) }}<br>
                            </td>
                        @else
                            <td style="padding:15px; width:30%">
                                <b
                                    style="text-transform: uppercase; font-size:medium; color: #8E1301">{{ ellipsis($product->name, 30) }}</b><br>
                                <b style="color: #555">Cód.: </b>{{ $product->code }}<br>
                                <br>
                                <b style="color: #555">Unidad:</b> {{ $unit->name }}<br>
                                <b style="color: #555">Detalle: </b> ${{ formatNumber($unit->pivot->price_menor) }}<br>
                                <b style="color: #555">Mayoreo: </b> ${{ formatNumber($unit->pivot->price_mayor) }}<br>
                                <b style="color: #555">Cant. Min.: </b> {{ formatNumber($unit->pivot->min) }}<br>
                            </td>
                            <td style="padding:15px; text-align:center">
                                <img style="border-radius: 50%; width:8rem; height:8rem" src="{{ $product->photo }}"
                                    alt="logo">
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        </tbody>

    </table>

</body>

</html>
