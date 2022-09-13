<?php

namespace App\Http\Livewire\Clients;

use App\Models\Invoice;
use App\Models\Store;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class PrintMany extends Component
{
    public $invoice_ids, $invoices, $content;
    public function mount($invoice_ids)
    {
        $this->invoice_ids = explode(',', $invoice_ids);

        $this->invoices =
            Invoice::whereIn('invoices.id', $this->invoice_ids)
            ->join('payments', 'payments.payable_id', '=', 'invoices.id')
            ->where('payments.payable_type', '=', 'App\Models\Invoice')
            ->join('clients', 'clients.id', '=', 'invoices.client_id')
            ->leftjoin('comprobantes', 'comprobantes.id', '=', 'invoices.comprobante_id')
            ->where('invoices.status', '=', 'cerrada')
            ->groupBy('invoices.id')
            ->selectRaw('invoices.*, SUM(payments.payed-payments.cambio) as payed, SUM(payments.efectivo) as efectivo,
             SUM(payments.transferencia) as transferencia,  SUM(payments.tarjeta) as tarjeta, SUM(cambio)
             as Cambio, clients.name as client_name, clients.id as client_id, clients.address as client_address,
             clients.phone as client_phone, clients.email as client_email, clients.rnc as client_rnc, comprobantes.ncf 
             as ncf, MAX(payments.created_at) as payment_date, payments.total as amount')
             ->with('payments')
            ->get();
            $this->generatePDF();
    }
    public function render()
    {
        return view('livewire.clients.print-many');
    }
    public function generatePDF(){
        $data=[
            'invoices'=>$this->invoices,
            'client'=>$this->invoices->first()->client,
            'store'=>Store::find(env('STORE_ID')),
        ];
        $PDF = App::make('dompdf.wrapper');
        $pdf = $PDF->setOptions([
            'logOutputFile' => null,
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadView('pages.clients.printmany-pdf', $data);
        $this->content=base64_encode($pdf->output());
        return $pdf->download('facturas.pdf');
    }
}
