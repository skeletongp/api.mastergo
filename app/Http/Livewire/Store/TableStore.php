<?php

namespace App\Http\Livewire\Store;

use App\Http\Traits\Livewire\WithSorting;
use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

class TableStore extends Component
{
    use WithPagination, WithSorting;

    public $sortField = 'id';
    public $sortAsc = true;
    public $perPage = 10;
    public $search = '';
    private $stores;

    protected $listeners = ['reloadStores'];
    public function render()
    {
        $this->stores = Store::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->search($this->search)
            ->paginate($this->perPage ?: 10);
        if ($this->perPage > $this->stores->total()) {
            $this->perPage = $this->stores->total();
        }
        $stores = [
            'stores' =>  $this->stores
        ];
        return view('livewire.store.table-store', $stores);
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

    public function reloadStores($search = "")
    {
        $this->search = $search;
        $this->resetPage();
        $this->render();
    }
}
