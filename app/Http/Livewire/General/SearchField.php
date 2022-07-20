<?php

namespace App\Http\Livewire\General;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Proceso;
use App\Models\Product;
use App\Models\Recurso;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Searchable\Search;
use Spatie\Searchable\ModelSearchAspect ;

class SearchField extends Component
{
    public $search , $models=[];
    public $searchResults;
    use WithPagination;
    protected $queryString=['search'];
    public function mount()
    {
        $this->models=[
            'users'=>'Usuarios',
            'clients'=>'Clientes',
            'products'=>'Productos',
            'procesos'=>'Procesos',
            'recursos'=>'Recursos',
            'invoices'=>'Facturas',
        ];
    }
    public function render()
    {

      return view('livewire.general.search-field');
    }
    public function updatedSearch()
    {
      if ($this->search) {
        $store=auth()->user()->store;
        $place=auth()->user()->place;
        $this->searchResults = (new Search())
        ->registerModel(User::class, function(ModelSearchAspect $modelSearchAspect) use ($store){
          $users=$store->users()->pluck('users.id')->toArray();
          $modelSearchAspect
          ->addSearchableAttribute('fullname')
          ->whereIn('id', $users);
        })
        ->registerModel(Product::class, function(ModelSearchAspect $modelSearchAspect) use ($store){
          $products=$store->products()->pluck('products.id')->toArray();
          $modelSearchAspect
          ->addSearchableAttribute('name')
          ->whereIn('id', $products);
        })
        ->registerModel(Client::class, function(ModelSearchAspect $modelSearchAspect) use ($store){
          $clients=$store->clients()->pluck('clients.id')->toArray();
          $modelSearchAspect
          ->addSearchableAttribute('clients.name')
          
          ->whereIn('clients.id', $clients);
        })
        ->registerModel(Proceso::class, function(ModelSearchAspect $modelSearchAspect) use ($place){
          $procesos=$place->procesos()->pluck('procesos.id')->toArray();
          $modelSearchAspect
          ->addSearchableAttribute('name')
          ->whereIn('id', $procesos);
        })
        ->registerModel(Recurso::class, function(ModelSearchAspect $modelSearchAspect) use ($place){
          $recursos=$place->recursos()->pluck('recursos.id')->toArray();
          $modelSearchAspect
          ->addSearchableAttribute('name')
          ->whereIn('id', $recursos);
        })
        ->registerModel(Invoice::class, function(ModelSearchAspect $modelSearchAspect) use ($place){
          $invoices=$place->invoices()->pluck('invoices.id')->toArray();
          $modelSearchAspect
          ->addSearchableAttribute('number')
          ->whereIn('id', $invoices);
        })
        ->search($this->search);
      } else {
        $this->searchResults=null;
      }
    }
}
