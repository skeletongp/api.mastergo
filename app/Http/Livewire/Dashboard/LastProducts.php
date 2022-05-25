<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class LastProducts extends LivewireDatatable
{
    public $headTitle = "Productos más vendidos";
    public function builder()
    {
        $details = auth()->user()->place->details()
            ->select(DB::raw('product_id, SUM(cant) as cant, id'))
            ->where('detailable_type', Invoice::class)
            ->orderBy('cant', 'desc')->with('product')->distinct()->groupBy('product_id')
            ->offset(0)->limit(10);
        return $details;
    }

    public function columns()
    {
        $details = $this->builder()->get()->toArray();
        return [
            Column::index($this, 'unit_id')->label('Nº.'),
            Column::callback(['cant', 'id'], function ($cant, $id)  use ($details) {
                $det = arrayFind($details, 'id', $id);
                return  formatNumber($det['cant']);
            })->label('Cant.'),
            Column::callback(['id', 'product_id'], function ($id) use ($details) {
                $det = arrayFind($details, 'id', $id);
                return $det['product']['name'];
            })->label('Producto')
        ];
    }
}
