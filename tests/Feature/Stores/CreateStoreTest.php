<?php

namespace Tests\Feature\Store;

use App\Http\Livewire\Store\CreateStore;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\SoftDeletes;
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

        $data = [
            'name' => 'AtrionTechSD EIRL',
            'address' => 'Calle Respaldo A, No. 8E',
            'email' => 'info@atriontechsd.com',
            'phone' => '8095086221',

        ];

        $existing = Store::where('email', $data['email'])->first();
        if ($existing) {
            $existing->forceDelete();
        }

        Livewire::test(CreateStore::class)
            ->set('form', $data)
            ->set('photo_path', 'https://thumbs.dreamstime.com/b/cityscape-design-corporaci%C3%B3n-de-edificios-logo-para-la-empresa-inmobiliaria-158041738.jpg')
            ->call("store");
        $this->assertDatabaseHas('stores', $data);
    }
}
