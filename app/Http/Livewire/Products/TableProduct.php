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
    public function builder()
    {
        $products = auth()->user()->place->products()->orderBy('code')->whereNull('deleted_at');
        return $products;
    }

    public function columns()
    {
        return [
            Column::callback(['id', 'uid'], function ($id) {
                return view('datatables::link', [
                    'href' => route('products.show', $id),
                    'slot' => " <span class='far fa-eye text-xl '></span>",
                    'bg' => "hover:bg-green-500",
                ]);
                return view('components.view', ['url' => route('products.show', $id)]);
            })->label('Ver')->unsortable(),
            Column::name('code')->label('Cód.')->searchable()->defaultSort(true),
            Column::name('name')->label('Nombre')->searchable(),
            Column::name('description')->callback(['description'], function ($description) {
                return "<div class='max-w-sm w-full pr-3  overflow-hidden overflow-ellipsis whitespace-nowrap '>$description</div>";
            })->label('Detalles')->searchable(),
            Column::name('created_at')->callback(['id', 'code'], function ($id) {
                return view('datatables::link', [
                    'href' => route('products.edit', $id),
                    'slot' => "<span class='far fa-pen-square  text-xl'></span>",
                    'bg' => "hover:bg-yellow-500",
                ]);
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
