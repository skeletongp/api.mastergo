<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;

class EditClient extends Component
{
    public Client $client;
    public  $avatar, $photo_path;

    public function render()
    {
        return view('livewire.clients.edit-client');
    }
    function rules() {
        return  [
            'client.name' => 'required|string|max:50',
            'client.lastname' => 'required|string|max:75',
            'client.address' => 'required|string|max:100',
            'client.email' => 'required|string|max:100|unique:clients,email,'.$this->client->id,
            'client.limit' => 'required|numeric|min:0',
            'client.phone' => 'required|string|max:25',
            'client.store_id' => 'required|numeric|exists:stores,id',
        ];
    }
    public function updateClient()
    {
        $this->validate();
        if ($this->photo_path) {
            $this->client->image()->update([
                'path'=>$this->photo_path
            ]);
        }
        $this->client->save();
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert', 'Cliente Actualizado Exitosamente','success');
    }
    
    public function updatedAvatar()
    {
        $ext = pathinfo($this->avatar->getFileName(), PATHINFO_EXTENSION);
        $photo = $this->avatar->storeAs('/avatars', date('Y_m_d_H_i_s') . '.' . $ext);
        $this->photo_path = asset("storage/{$photo}");
    }
}
