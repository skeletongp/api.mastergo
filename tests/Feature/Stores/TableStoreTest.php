<?php

namespace Tests\Feature\Stores;

use App\Http\Livewire\Store\TableStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TableStoreTest extends TestCase
{
    public function testDebeDevolverTodasLasStores()
    {
        /* Se llama al método render de la clase TableStore */
        /* Se verifica que se haya cargado la ruta correctamente */
        /* Se verifica que la ruta tenga el campo search */

       Livewire::test(TableStore::class)
       ->call('render')
       ->assertViewIs('livewire.store.table-store')
       ->assertViewHas('search');
       


    }
}
