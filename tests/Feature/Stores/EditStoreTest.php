<?php

namespace Tests\Feature\Stores;

use App\Http\Livewire\Store\EditStore;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditStoreTest extends TestCase
{
   public function testDebeEditarLosDatosDeLaTienda()
   {

      /* Se requiere un array con los campos on los 4 campos: name,  address, email, phone (cada uno opcional) */
      /* Se validan los campos según las reglas de validación de la clase EditStore*/
      /* Se instancia el modelo store según su UID */
      /* Se asigna el array data al campo store con sus campos*/

      $store = Store::firstOrFail();
      $store->name = "Ahumados The Smoke House";
      
      Livewire::test(EditStore::class)
         ->set('store', $store)
         ->call('render')
         ->call('updateStore')
         ->assertViewIs('livewire.store.edit-store');
         
         $this->assertDatabaseHas('stores',  $store);

         /* V.00.01 */
         /* Se debió añadir el campo $store al array $rules de la clase para realizar la prueba */
   }
}
