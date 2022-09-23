<?php

namespace App\Http\Livewire\Provisions;

use App\Models\Condiment;
use App\Models\Product;
use App\Models\Recurso;
use Illuminate\Support\Facades\DB;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ProvisionTable extends LivewireDatatable
{
    public $provisionable = [];
    public $padding = "px-2";
    public $headTitle = "Compras de productos y recursos";
    public function builder()
    {
        $this->provisionable = [
            Product::class => 'Productos',
            Recurso::class => 'Recursos',
            Condiment::class => 'Condimentos',
        ];
        $place = auth()->user()->place;
        $provisions = $place->provisions()
            ->select(DB::raw('id, SUM(cant*cost) as totalGroup, provisionable_type, provisionable_id, 
                                atribuible_type, atribuible_id,provider_id,prov_name, prov_rnc'))
            ->orderBy('created_at', 'desc')
            ->with('provider', 'provisionable', 'atribuible', 'place.store')
            ->groupBy('code');
        $this->emit('printProvision', $provisions);
        return $provisions;
    }

    public function columns()
    {
        $provisions = $this->builder()->get()->toArray();
        return [
            Column::name('code')->label('Cod.'),
            DateColumn::name('created_at')->format('d/m/Y')->label('Fecha'),
            Column::callback(['provider_id', 'id'], function ($provider, $id)
            use ($provisions) {
                $result = arrayFind($provisions, 'id', $id);
                if (array_key_exists('prov_name',$result) && $result['prov_name']) {
                    return ellipsis($result['prov_name'], 20);
                }
                return $result['provider']['fullname'];
            })->label('Proveedor'),
            Column::callback(['provisionable_id', 'id'], function ($provisionable, $id)
            use ($provisions) {
                $result = arrayFind($provisions, 'id', $id);
                return $this->provisionable[$result['provisionable_type']];
            })->label('Tipo'),
            Column::callback(['cant', 'id'], function ($cant, $id)
            use ($provisions) {
                $result = arrayFind($provisions, 'id', $id);
                return '$' . formatNumber($result['totalGroup']);
            })->label('Monto'),
            Column::callback(['updated_at', 'code'], function ($cant, $code)
            use ($provisions) {
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
}
