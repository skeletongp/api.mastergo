<?php

namespace App\Http\Livewire\Clients;

use App\Models\Client;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditClient extends Component
{
    public  $client, $client_id;
    public  $avatar, $photo_path;
    use WithFileUploads;

    protected $listeners = ['modalOpened'];

    public function mount()
    {
        $this->client['special'] = 0;
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
    public function modalOpened()
    {

        $this->client = Client::find($this->client_id)->toArray();
        $this->client['special'] = $this->client['special']?:0;
    }
    public function updateClient()
    {
        $this->validate();
        $client = Client::find($this->client['id']);
        if ($this->photo_path) {
            $client->image()->updateOrCreate(['imageable_id' => $client->id], [
                'path' => $this->photo_path
            ]);
        }
        $client->update($this->client);
        Cache::forget('clientCount' . env('STORE_ID'));
        Cache::forget('clientsWithCode_' . env('STORE_ID'));
        $this->emit('refreshLivewireDatatable');
        $this->emit('showAlert', 'Cliente Actualizado Exitosamente', 'success');
    }

    public function updatedAvatar()
    {

        $path = cloudinary()->upload(
            $this->avatar->getRealPath(),
            [
                'folder' => 'carnibores/avatars',
                'transformation' => [
                    'width' => 250,
                ]
            ]
        )->getSecurePath();
        $this->photo_path = $path;
    }
}
