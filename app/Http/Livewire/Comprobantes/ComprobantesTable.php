<?php

namespace App\Http\Livewire\Comprobantes;

use App\Http\Livewire\UniqueDateTrait;
use App\Models\Comprobante;
use Carbon\Carbon;
use App\Http\Classes\Column;use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ComprobantesTable extends LivewireDatatable
{
    use UniqueDateTrait;
    public $headTitle = "Tabla de comprobantes";
    public $padding = "px-2";
    public function builder()
    {
        $store = auth()->user()->store;
        $comprobantes = Comprobante::where('comprobantes.store_id', $store->id)
            ->leftjoin('invoices', 'invoices.comprobante_id', '=', 'comprobantes.id')
            ->leftjoin('clients', 'clients.id', '=', 'comprobantes.client_id')
            ->orderBy('comprobantes.ncf','desc')
            ->groupBy('comprobantes.id')
            ;
            

        return $comprobantes;
    }

    public function columns()
    {
        return [
            Column::callback(['status','updated_at'], function ($status, $day) {
                if ($status=='usado' ) {
                    return Carbon::parse($day  )->format('d/m/Y');
                } else {
                    return 'N/D';
                }
            })->label('Fecha de Uso')->searchable(),
            Column::callback(['prefix', 'ncf'], function ($prefix, $ncf) {
                return $ncf;
            })->label('NCF')->searchable()->filterable([
                'B01' => 'Cr. Fiscal',
                'B02' => 'Cons. Final',
                'B14' => 'Reg. Especial',
                'B15' => 'Gubernamental',
                'B03' => 'Nota de Débito',
                'B04' => 'Nota de Crédito',
            ]),
            Column::callback('status', function ($status) {
                return ucwords($status);
            })->label('Estado')->filterable(['Usado', 'Disponible']),
             Column::callback(['invoices.id', 'invoices.number'], function ($invoiceId, $invoiceNumber) {
                if ($invoiceId) {
                    return '<a href="' . route('invoices.show', $invoiceId) . '">' . ltrim(substr($invoiceNumber, strpos($invoiceNumber, '-') + 1), '0') . '</a>';
                
                } else {
                    return 'N/D';
                };
            })->label('Doc.')->searchable(),
            
            Column::callback(['invoices.name','clients.name'], function ($name, $client_name) {
              return ellipsis( $name?:($client_name?:'N/D'), 20);
            })->label('Cliente.')->searchable(),
            
        ];
    }
}
