<?php

namespace Tests\Feature\Users;

use App\Http\Livewire\Users\CreateUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDebeCrearUnUsuario()
    {
        $user=User::find(1);
        $user2=[
            'name'=>'Juan',
            'lastname'=>'Perez',
            'email'=>'perezjuan@gmail.com',
            'password'=>'123456',
            'phone'=>'(123) 456-7890',
            'username'=>'juanperez',
            'place_id'=>1,
            
        ];
        Livewire::actingAs($user)->test(CreateUser::class)
        ->set('form',$user2)
        ->set('role','Genérico')
        ->call('createUser')
        ->assertEmitted('showAlert','Usuario registrado exitosamente','success');
        /* $user2=User::where('email',$user2['email'])->firstOrFail();
        dd($user2);
        $this->assertDatabaseHas('moso_master.users', Arr::except($user2->toArray(),['created_at','updated_at'])); */
    }
}
