<?php

namespace App\Http\Livewire\Comprobantes;

use Carbon\Carbon;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class ComprobantesTable extends LivewireDatatable
{
    public $headTitle = "Tabla de comprobantes";
    public $padding = "px-2";
    public function builder()
    {
        $store = auth()->user()->store;
        return $store->comprobantes()
            ->with(
                'invoice.payment',
                'invoice.client',
                'creditnote.invoice.payment',
                'creditnote.invoice.client',
                'user'
            );
    }

    public function columns()
    {
        $comprobantes = $this->builder()->get()->toArray();
        return [
            Column::callback(['updated_at', 'id'], function ($number, $id) use ($comprobantes) {
                $result = arrayFind($comprobantes, 'id', $id);
                if ($result['invoice']) {
                    return Carbon::parse($result['invoice']['day'])->format('d/m/Y');
                } else if ($result['creditnote']) {
                    return Carbon::parse( $result['creditnote']['created_at'])->format('d/m/Y');
                } else {
                    return 'N/D';
                }
            })->label('Fecha'),
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
            Column::callback('id', function ($id) use ($comprobantes) {
                $result = arrayFind($comprobantes, 'id', $id);
                if ($result['invoice']) {
                    return '<a href="' . route('invoices.show', $result['invoice']['id']) . '">' . $result['invoice']['number'] . '</a>';
                     
                } else if ($result['creditnote']) {
                    return $result['creditnote']['invoice']['number'];
                } else {
                    return 'N/D';
                }
            })->label('Factura')->searchable(),
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
            })->label('Cajero'),
        ];
    }
}
