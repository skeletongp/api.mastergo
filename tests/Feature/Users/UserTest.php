<?php

namespace Tests\Feature\Users;

use App\Http\Livewire\Users\CreateUser;
use App\Http\Livewire\Users\EditUser;
use App\Http\Livewire\Users\TableUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testDebeCrearUnUsuario()
    {
        $user = User::find(1);
        $user2 = [
            'name' => 'Juan',
            'lastname' => 'Perez',
            'email' => uniqid() . 'perezjuan@gmail.com',
            'password' => '12345678',
            'phone' => '(123) 456-7890',
            'username' => 'juanperez' . uniqid(),
            'place_id' => 1,

        ];
        if (User::where('email', $user2['email'])->exists()) {
            $user2['email'] = $user2['email'] . '-' . rand(1, 100);
        }
        Livewire::actingAs($user)->test(CreateUser::class)
            ->set('form', $user2)
            ->set('role', 'Genérico')
            ->call('createUser')
            ->assertHasNoErrors();
    }
    public function testDebeDevolverTodosLosUsuarios()
    {
        $user = User::find(1);
        Livewire::actingAs($user)
            ->test(TableUser::class)
            ->call('columns')
            ->assertHasNoErrors();
    }
    public function testDebeEditarUnUsuario()
    {
        $user = User::find(1);
        $user->lastname .= ' M.';
        $user->roles=$user->roles->pluck('name');
        Livewire::actingAs($user)
            ->test(EditUser::class, ['user' => $user->toArray(), 'roles' => Role::get()])
            ->set('user', $user->toArray())
            ->call('updateUser')
            ->assertHasNoErrors();
    }
}
