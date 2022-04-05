<?php

namespace Database\Seeders;

use App\Http\Livewire\Store\CreateStore;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $store = Store::create([
            'name' => 'Ahumados Smoke House',
            'address' => 'San Lorenzo de Los Minas, no. 70',
            'email' => 'ahumadoshs@gmail.com',
            'phone' => '8095086221',
            'expires_at' => Carbon::now()->addMonths(3)
        ]);
        $store->image()->create([
            'path' => 'https://pbs.twimg.com/profile_images/1706139939/image_400x400.jpg',
        ]);

        $store->units()->create([
            'name' => 'Unidad',
            'symbol' => 'UND'
        ]);
        $store->taxes()->create([
            'name' => 'ITBIS',
            'rate' => 0.18
        ]);


        $data = [
            'name' => $store->name . ' | Sede Principal',
            'phone' => $store->phone,

        ];
        $store->places()->create($data);

        $user = $store->users()->create([
            'name' => 'Ismael',
            'lastname' => 'Contreras ',
            'email' => 'mastergo@atriontechsd.com',
            'username' => 'mastergo',
            'password' => 'mastergo',
            'phone' => '8298041907',
            'place_id' => $store->places()->first()->id
        ]);


        $user->image()->create([
            'path' => 'https://definicion.de/wp-content/uploads/2016/02/avatar.jpg',
        ]);
        $this->call(
            [
                RoleSeeder::class,
                ScopeSeeder::class
            ]
        );
        $user->assignRole('Super Admin');
        $store->roles()->save(Role::find(1));
        $store->roles()->save(Role::find(2));
        User::factory(400)->create()->each(function ($us) use ($store) {
            $num = rand(1, 250);
            $us->image()->create([
                'path' => "https://picsum.photos/id/{$num}/200/300",
            ]);
            $us->stores()->save($store);
            if (fmod($us->id, 6) == 0) {
                $us->assignRole('Administrador');
            }
        });
    }
}
