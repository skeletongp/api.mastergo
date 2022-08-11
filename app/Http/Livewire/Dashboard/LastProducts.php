<?php

namespace App\Http\Livewire\Dashboard;

use App\Http\Classes\NumberColumn as ClassesNumberColumn;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

use function PHPUnit\Framework\isNan;

class LastProducts extends LivewireDatatable
{
    public $headTitle = "Más vendidos esta semana";
    public $perPage = 10;
    public $padding = "px-2";
    public function builder()
    {
        $details = auth()->user()->place->details()
            ->leftjoin('products', 'products.id', '=', 'details.product_id')
            ->where('detailable_type', Invoice::class)
            ->whereDate('details.created_at', '>=', Carbon::now()->subWeek())
            ->orderBy(DB::raw('sum(details.cant)'), 'desc')->distinct()->groupBy('product_id');
        return $details;
    }

    public function columns()
    {
        return [
            Column::index($this, 'unit_id')->label('Nº.'),
            ClassesNumberColumn::raw('SUM(details.cant) AS cant')->label('Cant.')->formatear('number'),
            Column::callback(['products.name'], function ($prod) {
               
                return $prod;
            })->label('Producto')->searchable()
        ];
    }
}
