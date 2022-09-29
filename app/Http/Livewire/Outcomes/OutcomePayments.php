<?php

namespace App\Http\Livewire\Outcomes;

use App\Models\Outcome;
use App\Models\Payment;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class OutcomePayments extends LivewireDatatable
{
    public $outcome_id;
    public $headTitle='Historial de pagos al gasto';
    public $padding="px-2";
    public $hideable='select';
    public function builder()
    {
        $place=getPlace();
        $payments=
        Payment::where('payments.place_id', $place->id)
        ->where('payable_id', $this->outcome_id)
        ->where('payable_type', Outcome::class);
        return $payments;
    }

    public function columns()
    {
        return [
            DateColumn::name('updated_at')->label('Fecha')->format('d/m/Y H:i A'),
            Column::name('total')->callback(['total'], function ($total) {
                return '$' . formatNumber($total);
            })->label('Monto'),
            Column::name('efectivo')->callback(['efectivo'], function ($efectivo) {
                return '$' . formatNumber($efectivo);
            })->label('Efectivo')->hide(),
            Column::name('transferencia')->callback(['transferencia'], function ($transferencia) {
                return '$' . formatNumber($transferencia);
            })->label('Transf.')->hide(),
            Column::name('tarjeta')->callback(['tarjeta'], function ($tarjeta) {
                return '$' . formatNumber($tarjeta);
            })->label('Otros')->hide(),
            Column::name('payed')->callback(['payed'], function ($payed) {
                return "<b>$". formatNumber($payed)."</b>";
            })->label('Pagado'),
            Column::callback(['rest'], function ($rest) {
                return  "<span class='text-red-400 font-bold'>$". formatNumber($rest)."</span>";
            })->label('Resta'),
            Column::callback(['cambio'], function ($cambio) {
                return  "<span class='text-blue-600 font-bold'>$". formatNumber($cambio)."</span>";
            })->label('Cambio'),
            Column::callback(['payer_id', 'id'], function ($payer, $id)  {
                return  "<span class='far fa-print cursor-pointer' wire:click='print($id)'> </span>";
            })->label('Print')->contentAlignCenter(),
        ];
    }
    public function print($id)
    {
       
        $result=Payment::whereId($id)->with('place','payable','payer','contable')->first();
        $result->place->preference=getPreference(getPlace()->id);
        $result->store=getStore();
        $this->emit('printPayment', $result);
    }
}