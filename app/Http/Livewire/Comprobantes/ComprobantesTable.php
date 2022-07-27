<?php

namespace App\Http\Livewire\Comprobantes;

use App\Http\Livewire\UniqueDateTrait;
use App\Models\Comprobante;
use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
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
            ->leftjoin('creditnotes', 'creditnotes.comprobante_id', '=', 'comprobantes.id')
            ->leftjoin('clients', 'clients.id', '=', 'invoices.client_id')
            ->leftjoin('payments', 'payments.payable_id', '=', 'invoices.id')
            ->leftjoin('moso_master.users', 'moso_master.users.id', '=', 'invoices.seller_id')
            ->select(
                'comprobantes.*',
                'invoices.day as day',
                'creditnotes.created_at as creditDay',
                'invoices.id as invoiceId',
                'creditnotes.id as creditId',
                'invoices.number as invoiceNumber',
                'clients.name as clientName',
                'invoices.name as name'
            )
            ->orderBy('comprobantes.status', 'asc')
            ->orderBy('comprobantes.ncf', 'asc')
            ->distinct('comprobantes.id');;

        return $comprobantes;
    }

    public function columns()
    {
        return [
            Column::callback(['invoices.day', 'creditnotes.created_at'], function ($day, $created) {
                if ($day || $created) {
                    return Carbon::parse($day ?:$created )->format('d/m/Y h:i M');
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
            Column::callback(['invoices.id', 'creditnotes.id', 'invoices.number'], function ($invoiceId, $creditId, $invoiceNumber) {
                if ($invoiceId) {
                    return '<a href="' . route('invoices.show', $invoiceId) . '">' . ltrim(substr($invoiceNumber, strpos($invoiceNumber, '-') + 1), '0') . '</a>';
                } else if ($creditId) {
                    return $creditId;
                } else {
                    return 'N/D';
                };
            })->label('Doc.')->searchable(),
            Column::callback(['invoices.name', 'clients.name'], function ($name, $client_name) {
              ellipsis( $name?:($client_name?:'N/D'), 20);
            })->label('Cliente.')->searchable(),
            /* 
            Column::callback(['type', 'id'], function ($type, $id) use ($comprobantes) {
                $result = arrayFind($comprobantes, 'id', $id);
                if ($result['invoice']) {
                    return '$' . formatNumber($result['invoice']['payment']['total']);
                } else if ($result['creditnote']) {
                    return '$' . formatNumber($result['creditnote']['invoice']['payment']['total']);
                } else {
                    return 'N/D';
                }
            })->label('Facturado')->searchable(),
            Column::callback(['number', 'id'], function ($number, $id) use ($comprobantes) {
                $result = arrayFind($comprobantes, 'id', $id);
                if ($result['invoice']) {
                    return '$' . formatNumber($result['invoice']['payment']['tax']);
                } else if ($result['creditnote']) {
                    return '$' . formatNumber($result['creditnote']['invoice']['payment']['tax']);
                } else {
                    return 'N/D';
                }
            })->label('Impuesto'),
            Column::callback(['ncf', 'id'], function ($number, $id) use ($comprobantes) {
                $result = arrayFind($comprobantes, 'id', $id);
                if ($result['invoice']) {
                    return ellipsis($result['invoice']['name'] ?: ($result['invoice']['client']['name'] ?: $result['invoice']['client']['fullname']), 25);
                } else if ($result['creditnote']) {
                    return ellipsis($result['creditnote']['invoice']['name'] ?: ($result['creditnote']['invoice']['client']['name'] ?: $result['creditnote']['invoice']['client']['fullname']), 25);
                } else {
                    return 'N/D';
                }
            })->label('Cliente'),
            Column::callback(['created_at', 'id'], function ($created, $id) use ($comprobantes) {
                $result = arrayFind($comprobantes, 'id', $id);
                if ($result['user']) {
                    return $result['user']['fullname'];
                } {
                    return 'N/D';
                };
            })->label('Cajero'), */
        ];
    }
}
