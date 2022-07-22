<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditClient extends Component
{
    public  $client;
    public  $avatar, $photo_path;
    use WithFileUploads;
    public function mount(){
        $this->client['special']=0;
    }
    public function render()
    {
        return view('livewire.clients.edit-client');
    }
    function rules()
    {
        return  [
            'client' => 'required',
            'client.address' => 'required|string|max:100',
            'client.email' => 'required|string|max:100|unique:clients,email,' . $this->client['id'],
            'client.limit' => 'required|numeric',
            'client.special' => 'required|numeric',
            'client.phone' => 'required|string|max:25',
            'client.store_id' => 'required|numeric|exists:moso_master.stores,id',
        ];
    }
    public function updateClient()
    {
        $this->validate();
        $client=Client::find($this->client['id']);
        if ($this->photo_path) {
            $client->image()->updateOrCreate(['imageable_id'=>$client->id],[
                'path' => $this->photo_path
            ]);
        }
        $client->update($this->client);
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert', 'Cliente Actualizado Exitosamente', 'success');
    }

    public function updatedAvatar()
    {
        
        $path = cloudinary()->upload($this->avatar->getRealPath(),
        [
            'folder' => 'carnibores/avatars',
            'transformation' => [
                      'width' => 250,
             ]
        ])->getSecurePath();
        $this->photo_path = $path;
    }
    
}
