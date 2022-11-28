<?php

namespace App\Http\Livewire\Comprobantes;

use App\Http\Livewire\UniqueDateTrait;
use App\Models\Comprobante;
use Carbon\Carbon;
use App\Http\Classes\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use stdClass;

class ComprobantesTable extends LivewireDatatable
{
    use UniqueDateTrait;
    public $headTitle = "Tabla de comprobantes";
    public $padding = "px-2";
    public $hideable = 'select';
    public function builder()
    {
        $store = auth()->user()->store;
        $comprobantes = Comprobante::where('comprobantes.store_id', $store->id)
            ->leftjoin('invoices', 'invoices.comprobante_id', '=', 'comprobantes.id')
            ->leftjoin('clients', 'clients.id', '=', 'comprobantes.client_id')
            ->leftJoin('creditnotes', 'creditnotes.comprobante_id', '=', 'comprobantes.id')
            ->leftjoin('invoices as invs', 'invs.id', '=', 'creditnotes.invoice_id')
            ->orderBy('comprobantes.ncf', 'desc')
            ->groupBy('comprobantes.id');


        return $comprobantes;
    }

    public function columns()
    {
        $MOTIVOS = Comprobante::MOTIVOS;
        $motivos = [];
        foreach ($MOTIVOS as $key => $value) {
            $motivos[$key] = [
                'id' => $key,
                'name' => $value,
            ];
        }

        return [
            Column::callback(['status', 'updated_at'], function ($status, $day) {
                if ($status != 'disponible') {
                    return Carbon::parse($day)->format('d/m/Y');
                } else {
                    return 'N/D';
                }
            })->label('Fecha de Uso')->searchable(),
            Column::callback(['prefix', 'ncf'], function ($prefix, $ncf) {
                return $ncf;
            })->label('NCF')->searchable()->filterable([
                'B01' => 'Fiscal',
                'B02' => 'Cons. Final',
                'B14' => 'Consumo',
                'B15' => 'Gubernamental',
                'B04' => 'Nota de Crédito',
            ]),
            Column::callback('status', function ($status) {
                return ucwords($status);
            })->label('Estado')->filterable(['Usado', 'Disponible', 'Anulado']),
            Column::callback(['invoices.id', 'invoices.number', 'invs.id', 'invs.number', 'comprobantes.status'], function ($invoiceId, $invoiceNumber, $invsId, $invsNumber, $status) {
                if ($status == 'usado') {
                    if ($invoiceId) {
                        return '<a class="text-blue-400 hover:underline" href="' . route('invoices.show', $invoiceId) . '">' . ltrim(substr($invoiceNumber, strpos($invoiceNumber, '-') + 1), '0') . '</a>';
                    } else if ($invsId) {
                        return '<a class="text-blue-400 hover:underline" href="' . route('invoices.show', $invsId) . '">' . ltrim(substr($invsNumber, strpos($invsNumber, '-') + 1), '0') . '</a>';
                    }
                } else {
                    return 'N/D';
                }
            })->label('Doc.')->searchable(),

            Column::callback(['invoices.name', 'clients.name'], function ($name, $client_name) {
                return ellipsis($name ?: ($client_name ?: 'N/D'), 20);
            })->label('Cliente')->searchable(),
            Column::callback(['comprobantes.motivo', 'invoices.note', 'comprobantes.status'], function ($motivo, $note, $status) {
                if ($status == 'usado') {
                    return ellipsis($note, 20) ?: 'N/D';
                } else if ($status == 'anulado') {
                    return ellipsis(Comprobante::MOTIVOS[$motivo], 30);
                } else {
                    return 'N/D';
                }
            })->label('Nota/Motivo')->hide()->filterable(
                $motivos
            ),

            Column::callback(['invoices.id', 'comprobantes.id', 'comprobantes.status'], function ($invoiceId, $comprobanteId, $status) {
                if ($invoiceId && $status == 'usado') {
                    return view('pages.comprobantes.actions', ['comprobanteId' => $comprobanteId, 'invoiceId' => $invoiceId]);
                } else {
                    return 'N/D';
                }
            })->label('Acciones'),

        ];
    }
}
