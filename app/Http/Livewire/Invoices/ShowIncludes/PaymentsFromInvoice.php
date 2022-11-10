<?php

namespace App\Http\Livewire\Invoices\ShowIncludes;

use Mediconesystems\LivewireDatatables\Action;
use App\Http\Classes\Column;
use App\Http\Classes\NumberColumn;
use App\Models\Payment;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PaymentsFromInvoice extends LivewireDatatable
{
    public $invoice;
    public $hideable = "select";
    public $headTitle = 'Historial de pagos';
    public function builder()
    {
        $payments = Payment::where('payable_id', $this->invoice->id)->where('payable_type', 'App\Models\Invoice')
            ->join('moso_master.users', 'users.id', '=', 'payments.contable_id')
            ->select('payments.*', 'users.fullname as cajero')
            ->orderBy('payments.id', 'desc');

        return $payments;
    }

    public function columns()
    {

        return [
            DateColumn::name('updated_at')->label('Fecha')->hide()->format('d/m/Y H:i A'),
            Column::name('total')->callback(['total'], function ($total) {
                return '$' . formatNumber($total);
            })->label('Monto'),
            Column::name('efectivo')->callback(['efectivo'], function ($efectivo) {
                return '$' . formatNumber($efectivo);
            })->label('Efectivo'),
            Column::name('transferencia')->callback(['transferencia'], function ($transferencia) {
                return '$' . formatNumber($transferencia);
            })->label('Transf.'),
            Column::name('tarjeta')->callback(['tarjeta'], function ($tarjeta) {
                return '$' . formatNumber($tarjeta);
            })->label('Otros')->hide(),

            Column::name('payed')->callback(['payed'], function ($payed) {
                return "<b>$" . formatNumber($payed) . "</b>";
            })->label('Pagado'),
            NumberColumn::name('payments.rest')->label('Resta')->formatear('money', 'text-red-400')->hide(),
            NumberColumn::name('payments.cambio')->label('Cambio')->formatear('money', 'text-blue-600 font-bold'),

            Column::callback(['payer_id', 'id'], function ($payer, $id) {
                return  "<span class='far fa-print cursor-pointer' wire:click='print($id)'> </span>";
            })->label('Print')->contentAlignCenter(),
            Column::delete()->label('Del.')
        ];
    }
    public function print($id)
    {
        $payments = $this->builder()->get()->toArray();
        $result = arrayFind($payments, 'id', $id);
        $this->emit('printPayment', $result);
    }
    public function delete($id)
    {
        $user = auth()->user();
        if ($user->hasRole(['Super Admin', 'Admin'])) {
            $place = getPlace();
            $payment = Payment::with('payable.client', 'payer')->whereId($id)->first();
            $firstPayment = Payment::where('payable_id', $payment->payable_id)->where('payable_type', $payment->payable_type)->orderBy('id', 'asc')->first();
            if ($firstPayment->id == $payment->id) {
                $this->emit('showAlert', 'No se puede eliminar el primer pago de la factura','error');
                return;
            }
            $this->backToInvoice($payment->payable, $payment->payed - $payment->cambio);
            setTransaction('Reversión pago de ' . $payment->payer->name, $payment->payable->number, $payment->payed - $payment->cambio, $payment->payer->contable, $place->cash());
            $payment->delete();
            $this->emit('showAlert', 'El pago ha sido eliminado','success');
        } else {
            $this->emit('showAlert', 'No puede eliminar pagos', 'error');
        }
        
    }
    public function backToInvoice($invoice, $payed)
    {
        $invoice->update([
            'rest' => $invoice->rest + $payed,
        ]);
        $invoice->client->update([
            'debt' => $invoice->client->invoices->sum('rest'),
            'limit' => $invoice->client->limit + $payed
        ]);
    }
}
