<?php

namespace App\Http\Livewire\Settings\Places;

use App\Jobs\CreateCountForPlaceJob;
use App\Models\Store;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePlace extends Component
{
    public $form;
    use AuthorizesRequests;
    function rules()
    {
        return [
            'form.name' => 'required|string|max:100|unique:places,name,NULL,id,deleted_at,NULL,store_id,' . auth()->user()->store->id,
            'form.phone' => 'required|string|max:20|unique:places,phone,NULL,id,deleted_at,NULL,store_id,' . auth()->user()->store->id,
            'form.user_id' => 'required|exists:moso_master.users,id',
        ];
    }
    public function render()
    {
        $superAdmins = auth()->user()->store->users()
            ->role('Super Admin')
            ->select(DB::raw("CONCAT(name,' ',lastname) AS name"), 'users.id')
            ->orderBy('name')->pluck('name', 'users.id');
        $admins = auth()->user()->store->users()
            ->role('Administrador')
            ->select(DB::raw("CONCAT(name,' ',lastname) AS name"), 'users.id')
            ->orderBy('name')->pluck('name', 'users.id');
        $users = $superAdmins->union($admins);
        return view('livewire.settings.places.create-place', ['users' => $users]);
    }
    public function createPlace($store)
    {
        $this->authorize('Crear Sucursales');
        $store = Store::where('id', $store)->first();
        $this->validate();
        $this->form['name'] = $store->name . ' | ' . $this->form['name'];
        $place=$store->places()->create($this->form);
        CreateCountForPlaceJob::dispatch($store, $place)->onConnection('sync');
        $this->emit('showAlert', 'Sucursal creada exitosamente', 'success');
        $this->reset();
        $this->emit('reloadPlaces');
    }
}
