<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateClient extends Component
{
    public $form, $avatar, $photo_path, $store_id, $role;
    use WithFileUploads;

    public function render()
    {

        return view('livewire.clients.create-client');
    }
    protected $rules = [
        'form.name' => 'required|string|max:50',
        'form.lastname' => 'required|string|max:75',
        'form.email' => 'required|string|max:100|unique:clients,email',
        'form.address' => 'required|string|max:100',
        'form.limit' => 'required|numeric|min:0',
        'form.phone' => 'required|string|max:25',
        'form.RNC' => 'string|max:25',
        'form.store_id' => 'required|numeric|exists:stores,id',
    ];
    public function createClient()
    {
        if (empty($this->form['limit'])) {
            $this->form['limit'] = 0.00;
        }
        $store = auth()->user()->store;
        $this->form['store_id'] = $store->id;
        $this->validate();
        $store = auth()->user()->store;
        $client = $store->clients()->create($this->form);
        if ($this->photo_path) {
            $client->image()->create([
                'path' => $this->photo_path
            ]);
        }
        $this->reset();
        $this->emit('showAlert', 'Cliente registrado exitosamente', 'success');
        $this->emit('refreshLivewireDatatable');
    }
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->avatar->storeAs('clients', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
}
