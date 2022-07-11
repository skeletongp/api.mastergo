<?php

namespace App\Http\Livewire\Store;

use App\Models\Store;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditStore extends Component
{
    use WithFileUploads;
    public Store $store;
    public $logo, $photo_path, $photo_prev;
    function rules()
    {
        return [
            'store' => 'required',
            'store.name' => 'required|string|max:75',
            'store.address' => 'required|string|max:100',
            'store.lema' => 'string|max:75',
            'store.email' => "required|string|max:100|unique:moso_master.stores,email," . $this->store->id,
            'store.phone' => 'required|string|max:15',
            'store.rnc' => 'max:15',
            'logo' => 'max:2048'
        ];
    }

    public function mount()
    {
        $this->store = auth()->user()->store;
    }
    public function render()
    {
        return view('livewire.store.edit-store');
    }

    public function updateStore()
    {
        $this->validate();
        if ($this->photo_path) {
            $this->store->image()->updateOrCreate(
                ['imageable_id' => $this->store->id, 'imageable_type' => 'App\Models\Store'],
                ['path' => $this->photo_path]
            );
        }
        $this->store->save();
        $this->emit('showAlert', 'Datos actualizados correctamente', 'success');
        $this->resetExcept('store');
        Cache::forget('store_' . auth()->user()->id);
        $this->store = auth()->user()->store;
        $this->emitUp('reloadSettingStore');
    }

    public function updatedLogo()
    {
        $this->reset('photo_path');
        $this->validate([
            'logo'=>'image|max:2048'
        ]);
        $path = cloudinary()->upload($this->logo->getRealPath(),
        [
            'folder' => 'carnibores/logo',
            'transformation' => [
                      'height' => 125
             ]
        ])->getSecurePath();
      
        $this->photo_prev = $this->store->logo;
        $this->photo_path = $path;
    }
    public function deletePrevPhoto()
    {
        if ($this->photo_path) {
            $file_headers = @get_headers($this->photo_prev);
            $exists = $file_headers[0] === 'HTTP/1.1 200 OK';
            if ($exists && ($this->photo_path != $this->photo_prev)) {
                unlink('storage/logos/' . basename($this->photo_prev));
            }
        }
    }
}
