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
    public $hideable = 'select';
    public function builder()
    {
        $products = auth()->user()->place->products()->orderBy('code')->with('units')->whereNull('deleted_at');
        return $products;
    }

    public function columns()
    {
        $products = $this->builder()->get()->toArray();
        $user = auth()->user();

        return [
            Column::callback(['id', 'deleted_at'], function ($id) use ($products) {
                $result = arrayFind($products, 'id', $id);
                $photo=env('NO_IMAGE');
                if($result['image']){
                    $photo=$result['image']['path'];
                }
                return view('components.avatar', ['url' => route('products.show', $id), 'avatar' => $photo, 'ide'=> $id]);
            })->label('Ver')->unsortable(),
            Column::name('code')->label('Cód.')->searchable()->defaultSort(true),
            Column::name('name')->label('Nombre')->searchable(),
            Column::callback(['type'], function ($type) {
                return $type;
            })->label('Tipo')->filterable(['Producto', 'Servicio'])->hide(),
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
            $user->hasPermissionTo('Ver Utilidad')?$this->getCosto($products):null,
            Column::callback(['store_id', 'id'], function ($created, $id) use ($products) {
                $result = arrayFind($products, 'id', $id);
                $data = '';

                foreach ($result['units'] as $unit) {
                    $data .= $unit['symbol'] . ' => ' . '$' . formatNumber($unit['pivot']['price_menor']) . '<br>';
                }
                return $data;
            })->label('Precios'),
            Column::name('origin')->label('Origen')->searchable()->hide(),
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
    public function getCosto($products){
        return Column::callback(['description','id'], function ($desc, $id) use ($products) {
            $result = arrayFind($products, 'id', $id);
            $data = '';
            foreach ($result['units'] as $unit) {
                $data .= $unit['symbol'] . ' => ' . '$' . formatNumber($unit['pivot']['cost']) . '<br>';
            }
            return $data;
        })->label('Costo');
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
