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
    public $headTitle = "Más vendidos hoy";
    public $perPage = 10;
    public $padding = "px-2";
    public function builder()
    {
        $details = auth()->user()->place->details()
            ->leftjoin('products', 'products.id', '=', 'details.product_id')
            ->where('detailable_type', Invoice::class)
<<<<<<< HEAD
            ->whereDate('details.created_at', '=', Carbon::now()->format('Y-m-d'))
            ->orderBy('details.cant', 'desc')->distinct()->groupBy('product_id');
=======
            ->whereDate('details.created_at', '=', Carbon::now()->subDay()->format('Y-m-d'))
            ->orderBy(DB::raw('sum(details.cant)'), 'desc')->distinct()->groupBy('product_id');
>>>>>>> 8b7b42f4a51a464d3d5d1188895a12c81cc10bd9
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
