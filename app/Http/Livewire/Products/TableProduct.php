<?php

namespace App\Http\Livewire\Products;

use App\Http\Helper\Universal;
use App\Models\Product;
use App\Models\Unit;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TableProduct extends LivewireDatatable
{
    use AuthorizesRequests;
    public $padding = 'px-2 ';
    public function builder()
    {
        $products = auth()->user()->place->products()->orderBy('code')->whereNull('deleted_at');
        return $products;
    }

    public function columns()
    {
        $products = $this->builder()->get()->toArray();
        return [
            Column::callback(['id', 'uid'], function ($id) use ($products) {
                $result = arrayFind($products, 'id', $id);
                return view('components.avatar', ['url' => route('products.show', $id), 'avatar' => $result['photo']]);
            })->label('Ver')->unsortable(),
            Column::name('code')->label('Cód.')->searchable()->defaultSort(true),
            Column::name('name')->label('Nombre')->searchable(),
            Column::callback(['type'], function ($type) {
                return $type;
            })->label('Tipo')->searchable(),
            Column::callback(['created_at', 'id'], function ($created, $id) use ($products) {
                $result = arrayFind($products, 'id', $id);
                $data = '';
                if ($result['type'] == 'Producto') {
                    foreach ($result['units'] as $unit) {
                        $data .= $unit['symbol'] . ' => ' . formatNumber($unit['pivot']['stock']) . '<br>';
                    }
                } else {
                    $data = 'N/D';
                }
                return $data;
            })->label('Stock'),
            Column::callback(['id', 'code'], function ($id) {
                if (auth()->user()->hasPermissionTo('Editar Productos')) {
                    return view('datatables::link', [
                        'href' => route('products.edit', $id),
                        'slot' => "<span class='far fa-pen-square  text-xl'></span>",
                        'bg' => "hover:bg-yellow-500",
                    ]);
                    return 'N/D';
                }
            })->label('Editar'),
            Column::delete()->label('Eliminar')
        ];
    }
    public function delete($id)
    {
        $this->authorize('Borrar Productos');
        $product = $this->builder()->find($id);
        $product->delete();
        $this->emit('refreshLivewireDatatable');
        return;
    }
}
