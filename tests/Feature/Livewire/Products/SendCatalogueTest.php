<?php

namespace Tests\Feature\Livewire\Products;

use App\Http\Livewire\Products\SendCatalogue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class SendCatalogueTest extends TestCase
{
    /** @test */
    public function DebeEnviarCatalogoAClientes()
    {
        $component = Livewire::test(SendCatalogue::class)
        ->set('selected',[
            ['code'=>'0001','name'=>'1'],
        ])
        ->call('sendCatalogue')
        ->assertHasNoErrors();

        $component->assertStatus(200);
    }
}
