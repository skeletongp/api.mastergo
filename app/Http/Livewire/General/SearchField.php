<?php

namespace App\Http\Livewire\General;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SearchField extends Component
{
    public $search = null;
    private $users, $clients;
    use WithPagination;
    public function render()
    {
        
            $store = Store::first();
            $this->users = $store->users()->search($this->search)
                ->paginate(4);

            $this->clients = DB::table('atrionstore.clients')
                ->where('name', 'like', '%' . $this->search . '%')
                ->paginate(4);
                $this->resetPage();

        $data = [
            'users' => $this->users,
            'clients' => $this->clients,
        ];
        return view('livewire.general.search-field', $data);
    }
}
