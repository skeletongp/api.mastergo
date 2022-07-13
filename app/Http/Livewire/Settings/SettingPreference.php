<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;

class SettingPreference extends Component
{
    public $preference, $place, $printers=[];
    protected $rules=[
        'preference'=>'required',
        'preference.comprobante_type'=>'required',
        'preference.unit_id'=>'required',
        'preference.tax_id'=>'required',
        'preference.printer'=>'required',
        'preference.min_comprobante'=>'required',
        'preference.print_order'=>'required',
        'preference.copy_print'=>'required|numeric|min:1|max:3',
    ];
    protected $listeners=['setPrinters'];
    public function mount()
    {
        $place=auth()->user()->place;
        $this->place=$place;
        $this->preference=$place->preference;

    }
    public function render()
    {
        return view('livewire.settings.setting-preference');
    }
    public function updatePreference()
    {
        $this->validate();
        $this->preference->save();
        $this->emit('showAlert','Preferencias actualizadas existosamente','success');
        $this->mount();
    }
    public function setPrinters($printers){
        $this->printers=$printers;
        $this->render();
    }
}
