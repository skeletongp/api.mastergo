<?php

namespace App\Http\Livewire\Invoices;

use App\Http\Helper\Universal;
use App\Http\Traits\Livewire\WithPagination;
use App\Models\Detail;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Unit;
use PDF;
use Illuminate\Support\Arr;
use Livewire\Component;

class CreateInvoice extends Component
{
    use WithPagination;
    public $form, $maxCant = 0, $search, $open=false;
    public $details = [];

    public $producto;

    public  $unit;

    protected $listeners = ['selProducto', 'addItems'];

    protected $queryString = ['search'];

    function rules() {
        return  [
            'form.product_id' => 'numeric|required|exists:products,id',
            'form.cant' => 'numeric|min:0.001|required|max:'.Universal::formatNumber($this->maxCant),
            'form.price' => 'numeric|min:0.01|required',
            'form.cost' => 'numeric|min:0.01|required',
        ];
    }

   

    public function render()
    {
        $products = auth()->user()->store->products()->orderBY('name')->search($this->search)->with('units')->paginate(9);
        return view('livewire.invoices.create-invoice', compact('products'));
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function setProduct($id)
    {
        $this->form['product_id']=$id;
        $this->selProducto($id);
    }

    public function addItems()
    {
        $this->validate();
        $this->removeIfExists();
        $this->form['id'] = count($this->details);
        $this->form['utility'] = ($this->form['cant'] * $this->form['price']) - ($this->form['cant'] * $this->form['cost']);
        $this->form['user_id'] = auth()->user()->id;
        $this->form['store_id'] = auth()->user()->store->id;
        $this->form['place_id'] = auth()->user()->place->id;
        $this->form['product_name'] = $this->producto->name;
        $this->form['taxes'] = $this->producto->taxes->pluck('id')->toArray();
        $this->form['unit_name'] = $this->unit->symbol;
        $this->form['subtotal'] = $this->form['cant'] * $this->form['price'];
        $this->form['total'] = $this->form['subtotal'] * (1 + $this->producto->taxes->sum('rate'));
        array_push($this->details, $this->form);
        $this->emit('addDetailToJS', $this->details);
        $this->reset('form', 'producto');
    }

    public function removeIfExists()
    {
        $exist = array_keys(array_column($this->details, 'product_id'), $this->form['product_id']);
        if ($exist) {
            foreach ($exist as $key) {
                if ($this->details[$key]['unit_id'] == $this->form['unit_id']) {
                    unset($this->details[$key]);
                }
                $this->details = array_values($this->details);
            }
        }
    }
    public function selProducto($id)
    {
        $this->producto = Product::where('id', $id)->first();
        $this->form['unit_id'] = $this->producto->units()->first()->pivot->id;
        $this->updatedForm('', 'unit_id');
        $this->render();
    }
   
    public function removeItem($id)
    {
        unset($this->details[$id]);
    }

    public function updatedForm($val, $key)
    {
        switch ($key) {
            case 'unit_id':
                $this->unit = $this->producto->units()->wherePivot('id', $this->form['unit_id'])->first();
                $this->form['price'] = Universal::formatNumber($this->unit->pivot->price);
                $this->form['cost'] = $this->unit->pivot->cost;
                $this->maxCant = Universal::formatNumber($this->unit->pivot->stock);
                break;
            
            default:
                # code...
                break;
        }
    }
    public function sendInvoice()
    {
        //   dd(array_column($this->details, 'taxes'));
        $user = auth()->user();
        $invoice = $user->store->invoices()->create(
            [
                'amount' => array_sum(array_column($this->details, 'total')),
                'discount' => 0,
                'total' =>  array_sum(array_column($this->details, 'total')),
                'payed' => 0,
                'rest' =>  array_sum(array_column($this->details, 'total')),
                'efectivo' => 0,
                'tarjeta' => 0,
                'transferencia' => 0,
                'seller_id' => $user->id,
                'contable_id' => $user->id,
                'place_id' => $user->place->id,
                'store_id' => $user->store->id,
                'status' => 'waiting',
                'type' => Invoice::TYPES['DOCUMENTO DE CONDUCE'],
            ]
        );
        $this->createDetails($invoice);
        //$this->createPDF($invoice);

        $this->reset('form', 'details', 'producto');
        $this->emit('showAlert', 'Factura enviada exitosamente', 'success');
    }
    public function createDetails($invoice)
    {
        foreach ($this->details as $ind => $detail) {
            unset($this->details[$ind]['product_name']);
            unset($this->details[$ind]['unit_name']);
            unset($this->details[$ind]['id']);
            $detail['detailable_id'] = $invoice->id;
            $detail['detailable_type'] = Invoice::class;
            $taxes = $detail['taxes'];
            $detail = Detail::create(Arr::except($detail, 'taxes'));
            $detail->taxes()->sync($taxes);
            $detail->taxtotal = $detail->taxes->sum('rate') * $detail->total;
            $detail->save();
            $this->restStock($detail['unit_id'], $detail['cant']);
        }
    }
    public function restStock($pivotUnitId, $cant)
    {
        $user = auth()->user();
        $unit = $user->place->units()->wherePivot('id', $pivotUnitId)->first();
        $unit->pivot->stock = $unit->stock - $cant;
        $unit->pivot->save();
    }
    public function createPDF($invoice)
    {
        $data = [
            'invoice' => $invoice,
        ];
        $pdf = PDF::loadView('pages.invoices.letter', $data);
        $pdf2 = PDF::loadView('pages.invoices.thermal', $data);
        file_put_contents('storage/invoices/' . $invoice->number . '-letter.pdf', $pdf->output());
        file_put_contents('storage/invoices/' . $invoice->number . '-thermal.pdf', $pdf2->output());
        $path = asset('storage/invoices/' . $invoice->number . '-letter.pdf');
        $path2 = asset('storage/invoices/' . $invoice->number . '-thermal.pdf');
        $invoice->pdfLetter = $path;
        $invoice->pdfThermal = $path2;
        $invoice->save();
    }
}
