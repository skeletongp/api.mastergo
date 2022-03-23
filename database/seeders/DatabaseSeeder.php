<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

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

        $user = $store->users()->create([
            'name' => 'Ismael',
            'lastname' => 'Contreras ',
            'email' => 'mastergo@atriontechsd.com',
            'username' => 'mastergo',
            'password' => Hash::make('mastergo'),
            'phone' => '8298041907',
        ]);
        $user->image()->create([
            'path' => 'https://definicion.de/wp-content/uploads/2016/02/avatar.jpg',
        ]);

        User::factory(500)->create()->each(function($us) {
            $num = rand(1, 250);
            $us->image()->create([
                'path' => "https://picsum.photos/id/{$num}/200/300",

            ]);
        });
    }
}
