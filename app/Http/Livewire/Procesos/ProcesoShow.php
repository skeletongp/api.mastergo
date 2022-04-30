<?php

namespace App\Http\Livewire\Procesos;

use App\Models\Proceso;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;
use DNS1D;

class ProcesoShow extends Component
{
    use WithPagination;
    public Proceso $proceso;
    public $productos = [];


    public function mount()
    {
        $this->proceso = $this->proceso->load('products');
    }

    public function render()
    {
        $procesos = auth()->user()->place->procesos()->paginate(7);
        return view('livewire.procesos.proceso-show', compact('procesos'));
    }
    public function setProcess($proceso)
    {
        return redirect()->route('procesos.show', $proceso);
    }
    public function setObtained($product)
    {
        $produ = $this->proceso->products()->with('units')
            ->wherePivot('id', $product)
            ->first();
        $pivot = $produ->pivot;
        $cant = $this->productos[$pivot->product_id];
        $pivot->obtained = $cant + $pivot->obtained;
        $pivot->eficiency = ($pivot->obtained / $pivot->due) * 100;
        $this->sumProduct($produ, $pivot->unit_id, $cant);
        $pivot->save();
        $this->reset('productos');
        $this->finishProceso();
        return $this->createLabel($produ, $pivot->unit_id, $cant);
        $this->emit('showAlert', 'Monto actualizado correctamente', 'success');
        $this->render();
    }
    public function sumProduct($product, $unit_id, $cant)
    {
        $pivot = $product->units()->where('units.id', $unit_id)->first()->pivot;
        $pivot->stock = $pivot->stock + $cant;
        $pivot->save();
    }
    public function finishProceso()
    {
        $proceso = $this->proceso;
        if ($proceso->eficiency >= 100) {
            $proceso->status = 'Procesado';
            $proceso->save();
        } elseif ($proceso->eficiency >= 1) {
            $proceso->status = 'En Proceso';
            $proceso->save();
        }
    } 
    public function createLabel($product, $unit_id, $cant)
    {
        $proceso = $this->proceso;
        $product = $product;
        $cant = $cant;
        $unit = $product->units()->where('unit_id', $unit_id)->first();
        $day = date('d/m/Y');
        $data = '%' . $product->id . '.' . $unit->pivot->id . '.' . $cant . '.' . $unit->pivot->cost;
        $barcode = '<img style="width:75mm" src="data:image/png;base64,' . DNS1D::getBarcodePNG($data, 'C39') . '" alt="barcode"   />';

        $pdf = App::make('dompdf.wrapper');
        $pdfContent = $pdf->loadView('pages.procesos.product-label', get_defined_vars())->output();
        return response()->streamDownload(
            fn () => print($pdfContent),
            'Label de '.$product->name.' '.$proceso->id.'.pdf'
        );
    }
}
