<?php

namespace App\Http\Livewire\Transactions;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Count;
use App\Models\CountMain;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateTransaction extends Component
{
    use Confirm;
    public $countMains, $cMainDebit_id, $cDetailDebit_id, $cMainCredit_id, $cDetailCredit_id, $countsDebit = [], $countsCredit = [];
    public $concept, $ref, $amount;
    protected $listeners = ['validateAuthorization', 'createTransaction'];
    protected $rules = [
        'cMainDebit_id' => 'required',
        'cDetailDebit_id' => 'required',
        'cMainCredit_id' => 'required',
        'cDetailCredit_id' => 'required',
        'concept' => 'required|string',
        'ref' => 'required|string',
        'amount' => 'required|numeric|min:1',
    ];
    public function mount()
    {
        $this->countMains = CountMain::select(
            DB::raw('CONCAT(code," - ",name) AS name'),
            'id'
        )->orderBy('code')->pluck('name', 'id')->toArray();
    }

    public function render()
    {
        return view('livewire.transactions.create-transaction');
    }
    public function updatedCMainDebitID()
    {
        if ($this->cMainDebit_id) {
            $countMain = CountMain::whereId($this->cMainDebit_id)->with('counts')->first();
            $this->countsDebit = $countMain->counts()->select(
                DB::raw('CONCAT(code," - ",name) AS name'),
                'id'
            )->pluck('name', 'id');
        }
    }
    public function updatedCMainCreditID()
    {
        if ($this->cMainCredit_id) {
            $countMain = CountMain::whereId($this->cMainCredit_id)->with('counts')->first();
            $this->countsCredit = $countMain->counts()->select(
                DB::raw('CONCAT(code," - ",name) AS name'),
                'id'
            )->pluck('name', 'id');
        }
    }
    public function createTransaction()
    {
        $this->validate();
        $debitable = Count::whereId($this->cDetailDebit_id)->first();
        $creditable = Count::whereId($this->cDetailCredit_id)->first();
        setTransaction($this->concept, $this->ref, $this->amount, $debitable, $creditable);
        $this->reset();
        $this->mount();
        $this->emit('showAlert', 'Transacción registrada exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}
