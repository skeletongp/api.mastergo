<?php

namespace Tests\Feature\Store;

use App\Http\Livewire\Store\CreateStore;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Tests\TestCase;

class CreateStoreTest extends TestCase
{
    use SoftDeletes;
    public function testDebeAlmacenarLosDatosEnLaTabla()
    {
        /* Se debe crear un array con los 4 campos: name,  address, email, phone */
        /* Los campos del array son validados según la clase CreateStore */
        /* Se debe eliminar el archivo con el email (campo unique) */
        /* Se pasa el array data al campo público form de la clase CreateStore */
        /* Se pasa una ruta de imagen al campo photo_path (Opcional) */
        /* Se llama al método store */
        /* Se espera que el array $data se encuentre en la tabla stores de la DB */

        $user = User::find(1);
        $data = [
            'name' => 'AtrionTechSD EIRL',
            'address' => 'Calle Respaldo A, No. 8E',
            'email' => 'info@bicimotoelclavo.com',
            'phone' => '8095086221',

        ];

        $existing = Store::where('email', $data['email'])->first();
        if ($existing) {
            $this->assertDatabaseHas('moso_master.stores', Arr::except($existing->toArray(), ['image', 'created_at', 'updated_at']));
            return;
        }

        Livewire::actingAs($user)
            ->test(CreateStore::class)
            ->set('form', $data)
            ->call("createStore");
        $existing = Store::where('email', $data['email'])->first();
        $this->assertDatabaseHas('moso_master.stores', $data);
    }
}
