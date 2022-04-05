<?php

namespace App\Http\Livewire\Store;

use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateStore extends Component
{
    use WithFileUploads, AuthorizesRequests;
    public $form;
    public $logo, $photo_path, $photo_prev;
    protected $rules = [
        'form.name' => 'required|string|max:75',
        'form.address' => 'required|string|max:100',
        'form.email' => 'required|string|max:100|unique:stores,email',
        'form.phone' => 'required|string|max:15',
        'form.RNC' => 'max:12',

        'logo' => 'max:2048'
    ];

    public function render()
    {
        return view('livewire.store.create-store');
    }

    public function createStore()
    {
        $this->authorize('Crear Negocios');
        $this->validate();
        $store = Store::create($this->form);
        auth()->user()->stores()->save($store);
        if ($this->photo_path) {
            $store->image()->updateOrCreate(
                ['imageable_id' => $store->id, 'imageable_type' => 'App\Models\Store'],
                ['path' => $this->photo_path]
            );
        }
        $this->createPlace($store);
        $this->createUnit($store);
        $this->createTax($store);
        $this->createClient($store);
        $this->reset();
        $this->emit('reloadUsers');
        $this->emitUp('reloadSettingStore');
        $this->emit('showAlert', 'Negocio registrado exitosamente', 'success');
    }

    public function updatedLogo()
    {
        $ext = pathinfo($this->logo->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->logo->storeAs('logos', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
    public function createPlace(Store $store)
    {
        $data=[
            'name'=>$store->name.' | Sede Principal',
            'phone'=>$store->phone,
            'user_id'=>auth()->user()->id,
        ];
        $store->places()->create($data);
    }
    public function createUnit(Store $store)
    {
        $data=[
            'name'=>'Unidad',
            'symbol'=>'UND',
        ];
        $store->units()->create($data);
    }
    public function createTax(Store $store)
    {
        $data=[
            'name'=>'ITBIS',
            'rate'=>0.18,
        ];
        $store->taxes()->create($data);
    }
    public function createClient(Store $store)
    {
        $data=[
            'name'=>'Cliente',
            'lastname'=>'Genérico',
            'email'=>'generico@'.strtok($store->uid,' ').'.com',
            'address'=>'SIN DIRECCIÓN',
            'phone'=>'8097654321',
            'limit'=>0.0,
        ];
        $store->clients()->create($data);
    }
}
