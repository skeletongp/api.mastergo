<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

use function PHPUnit\Framework\isNan;

class LastProducts extends LivewireDatatable
{
    public $headTitle = "Más vendidos hoy";
    public $perPage=10;
    public $padding="px-2";
    public function builder()
    {
        $details = auth()->user()->place->details()
            ->select(DB::raw('product_id, SUM(cant) as cant, id'))
            ->where('detailable_type', Invoice::class)
            ->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))
            ->orderBy('cant', 'desc')->with('product')->distinct()->groupBy('product_id')
            ;
        return $details;
    }

    public function columns()
    {
        $details = $this->builder()->get()->toArray();
        return [
            Column::index($this, 'unit_id')->label('Nº.'),
            Column::callback(['cant', 'id'], function ($cant, $id)  use ($details) {
                $det = arrayFind($details, 'id', $id);
                if(!is_array($det)){
                    return $cant;
                }
                return  formatNumber($det['cant']);
            })->label('Cant.'),
            Column::callback(['id', 'product_id'], function ($id) use ($details) {
                $det = arrayFind($details, 'id', $id);
                if(!is_array($det)){
                    return $det;
                }
                return $det['product']['name'];
            })->label('Producto')
        ];
    }
}
