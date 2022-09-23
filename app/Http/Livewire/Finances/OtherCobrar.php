<?php

namespace App\Http\Livewire\Finances;

use App\Http\Traits\Livewire\Confirm;
use App\Models\Count;
use App\Models\CountMain;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OtherCobrar extends Component
{
    use Confirm;
    public $countMains=[], $cobrables=[], $cMainDebit_id, $cDetailDebit_id, $cMainCredit_id, $cDetailCredit_id, $countsDebit = [], $countsCredit = [];
    public $cMainDebitName, $cDetailDebitName, $cMainCreditName, $cDetailCreditName, $creditable_code;
    public $concept, $ref, $amount;
    protected $listeners = ['validateAuthorization', 'createTransaction', 'modalOpened'];
    protected $rules = [
        'cMainDebit_id' => 'required',
        'cDetailDebit_id' => 'required',
        'cMainCredit_id' => 'required',
        'cDetailCredit_id' => 'required',
        'concept' => 'required|string',
        'ref' => 'required|string',
        'amount' => 'required|numeric|min:1',
    ];
   

    public function render()
    {
        return view('livewire.finances.other-cobrar');
    }
    public function modalOpened(){
        $this->countMains = CountMain::select(
            DB::raw('CONCAT(code," - ",name) AS name'),
            'id'
        )->orderBy('code')->pluck('name', 'id')->toArray();
        $this->cobrables = CountMain::select(
            DB::raw('CONCAT(code," - ",name) AS name'),
            'id'
        )->where('code','100')->orderBy('code');
        $this->cMainDebit_id=$this->cobrables->first()->id;
        $this->cobrables=$this->cobrables->pluck('name', 'id')->toArray();
        $this->cMainDebitName = $this->cobrables[$this->cMainDebit_id];
        $this->cMainCreditName= $this->countMains[$this->cMainCredit_id];
        $countMain = CountMain::whereId($this->cMainDebit_id)->with('counts')->first();
        $this->countsDebit = $countMain->counts()->select(
            DB::raw('CONCAT(code," - ",name) AS name'),
            'id'
            )->pluck('name', 'id');

        $countMain = CountMain::whereId($this->cMainCredit_id)->with('counts')->first();
        $this->countsCredit = $countMain->counts()->select(
            DB::raw('CONCAT(code," - ",name) AS name'),
            'id'
        )->pluck('name', 'id');
        $this->cDetailCreditName=$this->countsCredit[$this->cDetailCredit_id];
        $this->concept="Abono a cuenta ".$this->creditable_code;
        $this->render();
    }
    
   
    public function createTransaction()
    {
        $this->validate();
        $debitable = Count::whereId($this->cDetailDebit_id)->first();
        $creditable = Count::whereId($this->cDetailCredit_id)->first();
        $trans=setTransaction($this->concept, $this->ref, $this->amount, $debitable, $creditable);
        if($trans){
            $trans->update(['deleteable'=>"1"]);
        }
        $this->reset();
        $this->mount();
        $this->emit('showAlert', 'Transacción registrada exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
}
