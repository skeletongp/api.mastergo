<?php

namespace App\Http\Livewire\Provisions;

use App\Models\Condiment;
use App\Models\Product;
use App\Models\Recurso;
use Illuminate\Support\Facades\DB;
use App\Http\Classes\Column;
use App\Http\Classes\NumberColumn;
use App\Models\Provision;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProvisionTable extends LivewireDatatable
{

    public $provisionable = [];
    public $padding = "px-2 py-0.5";
    public $headTitle = "Compras de productos y recursos";
    public function builder()
    {
        $this->provisionable = [
            Product::class => 'Productos',
            Recurso::class => 'Recursos',
            Condiment::class => 'Condimentos',
        ];
        $place = getPlace();
        $provisions = Provision::where('place_id', $place->id)
           ->leftJoin('providers','providers.id','=','provisions.provider_id')
            ->groupBy('code');
        $this->emit('printProvision', $provisions);
        return $provisions;
    }

    public function columns()
    {
        return [
            Column::callback(['code'], function ($code) {
                return view('pages.provisions.actions',
                    ['provision_code' => $code]
                );
            })->label('Cód.')->contentAlignCenter(),
            DateColumn::name('provisions.created_at')->label('Fecha')->filterable(),
            Column::name('provider.fullname')->label('Proveedor')->searchable(),
            Column::callback(['provisionable_type'], function ($provisionable)
           {
                return $this->provisionable[$provisionable];
            })->label('Tipo')->filterable([
                'App\Models\Product' => 'Productos',
                'App\Models\Recurso' => 'Recursos',
                'App\Models\Condiment' => 'Condimentos',
            ]),

            NumberColumn::raw('SUM(cant*cost) AS amount')->label('Monto')->formatear('money','font-bold'),
            Column::callback(['updated_at', 'code'], function ($cant, $code)
            {
              return view(
                   'pages.provisions.print-button',
                   ['provision_code' => $code]
               );
           })->label('Print')->contentAlignCenter(),

        ];
    }
    public function cellClasses($row, $column)
    {
        return
            'whitespace-normal  text-gray-900 px-2 py-2';
    }
    public function summarize($column)
    {

        $results=json_decode(json_encode($this->results->items()), true);
        foreach ($results as $key => $value) {
            $val=json_decode(json_encode($value), true);
            $results[$key][$column]=preg_replace("/[^0-9 .]/", '', $val[$column]);
        }
        try {

            return "<h1 class='font-bold text-right'>". '$'.formatNumber(array_sum(array_column($results, $column)))."</h1>";;
        } catch (\TypeError $e) {
            return '';
        }
    }
}
