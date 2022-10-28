   
<table class="w-full " style="max-width:60mm; margin:auto">
    <tr>
        <td colspan="2">
           <div style="max-width:60mm">
            {!!$barcode!!}
           </div>
        </td>
    </tr>
    <tr>
        <td>
            <span class="font-bold uppercase text-sm">
                Prod.:
            </span>
        </td>
        <td class="text-right">
            {{ $product->name }}
        </td>
    </tr>
    <tr>
        <td>
            <span class="font-bold uppercase text-sm">
                Cód.:
            </span>
        </td>
        <td class="text-right">
            {{ $product->code }}
        </td>
    </tr>
    <tr>
        <td>
            <span class="font-bold uppercase text-sm">
                Cant.:
            </span>
        </td>
        <td class="text-right">
            {{ formatNumber($cant) }} <small>{{$unit->symbol}}</small>
            x ${{ formatNumber($unit->pivot->price) }}
        </td>
    </tr>
    <tr>
        <td>
            <span class="font-bold uppercase text-sm">
                Tax:
            </span>
        </td>
        <td class="text-right">
            ${{ formatNumber(($unit->pivot->price*$cant)*$product->taxes()->sum('rate')) }}
        </td>
    </tr>
    <tr>
        <td>
            <span class="font-bold uppercase text-sm">
                Total:
            </span>
        </td>
        <td class="text-right font-bold text-lg">
            ${{ formatNumber($unit->pivot->price*$cant*(1+$product->taxes()->sum('rate'))) }}
        </td>
    </tr>
    <tr>
        <td>
            <span class="font-bold uppercase text-sm">
                Fecha:
            </span>
        </td>
        <td class="text-right">
            {{Carbon\Carbon::create(now())->format('d/m/Y H:i A')}}
        </td>
    </tr>
    <tr>
        <td>
            <span class="font-bold uppercase text-sm">
                Vence:
            </span>
        </td>
        <td class="text-right">
         {{Carbon\Carbon::create(now())->addDays(35)->format('d/m/Y H:i A')}}
        </td>
    </tr>
</table>
<style>
    @page {
        size: 80mm 100mm;
        margin: 3mm;
        font-family: Arial, Helvetica, sans-serif;
    }
    td{
        padding-top: 7px;
    }
    .text-right{
        text-align: right;
    }
    .font-bold {
       font-weight: bold;
    }
    .uppercase{
        text-transform: uppercase;
    }
    .w-full{
        width: 100%;
    }
    .text-sm{
        font-size: small;
    }
    .text-lg{
        font-size: large;
    }
    .pad-medium{
        padding: .5rem;
    }
    .m-medium{
        margin: .5rem;
    }

</style>
