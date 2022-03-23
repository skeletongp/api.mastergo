<?php

namespace App\Http\Livewire\Users;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Traits\Livewire\WithSorting;
use App\Models\User;

class TableUser extends Component
{
    use WithPagination, WithSorting;
    public $sortField = 'id';
    public $sortAsc = true;
    public $perPage = 10;
    public $search = '';

    protected $listeners = ['reloadUsers'];
    
    public function render()
    {
        $users = User::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->search($this->search)
            ->paginate($this->perPage ?: 10);
        if ($this->perPage > $users->total()) {
            $this->perPage = $users->total();
        }
        $users = [
            'users' =>  $users
        ];
        return view('livewire.users.table-user', $users);
    }

    public function updated()
    {
        $this->resetPage();
    }
    public function updatedSearch()
    {
       if (!$this->search) {
        $this->reset('perPage');
       }
    }

    public function reloadUsers($search="")
    {
        $this->search=$search;
        $this->resetPage();
        $this->render();
    }
}
