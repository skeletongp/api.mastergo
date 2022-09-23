<?php

namespace App\Http\Livewire\Finances;

use App\Http\Classes\NumberColumn;
use App\Models\Count;
use App\Models\CountMain;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OtherPorCobrar extends LivewireDatatable
{
    public $padding = 'px-2';
    public $headTitle = 'Otras cuentas por cobrar';

    public function builder()
    {
        $counts = Count::leftjoin('count_mains', 'counts.count_main_id', '=', 'count_mains.id')
            ->where('count_mains.code', '!=', '101')
            ->where('count_mains.name', 'like', '%POR COBRAR');

        return $counts;
    }

    public function columns()
    {
        return [
            Column::callback(['count_mains.code','count_mains.id'], function($code, $id) {
                return "<a class='text-blue-700 hover:text-blue-400 hover:underline' href=".route('contables.countview',$code).">".$code."</a>";
            })->label('Cta.'),
            Column::name('count_mains.name')->label('Cuenta Control'),
            Column::callback(['counts.code','counts.id'], function($code, $id) {
                return "<a class='text-blue-700 hover:text-blue-400 hover:underline' href=".route('contables.counttrans',$id).">".$code."</a>";
            })->label('Sub.'),
            Column::name('counts.name')->label('Cuenta Detalle')->searchable(),
            NumberColumn::name('counts.balance')->label('Balance')->formatear('money'),
            Column::callback(['count_mains.id', 'counts.id', 'counts.name', 'counts.code'], function ($cMainId, $id, $name, $code) {
                return view(
                    'pages.finances.other-por-cobrar-action',
                    [
                        'creditable_code' => $code,
                        'creditable_name' => $name,
                        'cMainId' => $cMainId,
                        'countId' => $id
                    ]
                );
            })->label('Acciones'),
        ];
    }
}
