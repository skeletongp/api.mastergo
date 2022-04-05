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
       $products=auth()->user()->place->products()->whereNull('deleted_at');
       return $products;
    }

    public function columns()
    {
        return [
            NumberColumn::name('id')->defaultSort('asc')->linkTo('products'),
            Column::name('name')->label('Nombre')->searchable(),
            Column::name('description')->callback(['description'], function ($description) {
                return "<div class='h-12 max-w-sm w-full pr-3  overflow-hidden overflow-ellipsis whitespace-nowrap '>$description</div>";
            })->label('Detalles')->searchable(),
            Column::name('units.price')->callback(['id'], function ($id) {
                $units=Product::find($id)->units->pluck('price', 'name');
                $arr= http_build_query($units->toArray(),'','<br>');
                return urldecode($arr);
            })->label('Precios por medida'),
            
            Column::name('units.stock')->callback(['id','description'], function ($id) {
                $units=Product::find($id)->stock;
                $arr= http_build_query($units->toArray(),'','<br>');
                return urldecode($arr);
            })->label('Stock'),

            Column::name('taxes.rate')->callback(['id', 'name'], function ($id) {
                $taxes=Product::find($id)->rate;
                $units=Product::find($id)->units->pluck('plainPrice', 'name');
                foreach ($units as $key => $value) {
                  $units[$key]=' $'.Universal::formatNumber(floatval($value)*(1+floatval($taxes)));
                }
                $arr= http_build_query($units->toArray(),'','<br>');
                return urldecode($arr);
            })->label('+Impuestos'),
            Column::delete()->label('Eliminar')
        ];
    }
    public function delete($id)
    {
        $this->authorize('Borrar Productos');
        $product=Product::find($id);
        $product->delete();
        $this->emit('refreshLivewireDatatable');
        return ;

    }
    
  
}