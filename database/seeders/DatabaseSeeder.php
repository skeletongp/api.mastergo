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
            'rnc'=>'132487524',
            'expires_at' => Carbon::now()->addMonths(3)
        ]);
        $store->image()->create([
            'path' => 'https://pbs.twimg.com/profile_images/1706139939/image_400x400.jpg',
        ]);

        $unit = $store->units()->create([
            'name' => 'Libra',
            'symbol' => 'Lb'
        ]);
        $unit2 = $store->units()->create([
            'name' => 'Quintal',
            'symbol' => 'Qt'
        ]);
       $tax= $store->taxes()->create([
            'name' => 'ITBIS',
            'rate' => 0.18
        ]);
        $products = [
            [
                'name' => 'Chuleta ahumada',
                'description' => '<p><b>Chuleta</b> de cerdo precocida</p>'
            ],
            [
                'name' => 'Costillitas',
                'description' => '<p><b>Costillas</b> de cerdo </p>'
            ],
            [
                'name' => 'Longaniza',
                'description' => '<p><b>Longaniza</b> de cerdo con picante </p>'
            ],
            [
                'name' => 'Masita',
                'description' => '<p><b>Masita</b> de cerdo con picante </p>'
            ],
            [
                'name' => 'Tocineta',
                'description' => '<p><b>Tocineta</b> de cerdo </p>'
            ],
            [
                'name' => 'Pinguilín',
                'description' => '<p><b>Pinguilín</b> de cerdo </p>'
            ],
            [
                'name' => 'Muslo',
                'description' => '<p><b>Muslo</b> de cerdo </p>'
            ],
            [
                'name' => 'Derivados de la barrigada',
                'description' => '<p><b>Barrigada</b> de cerdo </p>'
            ],
            [
                'name' => 'Bistec de res',
                'description' => '<p>Bistec de red artesanal </p>'
            ],
            [
                'name' => 'Filete de res',
                'description' => '<p>Filete de red artesanal </p>'
            ],
            [
                'name' => 'Tocineta de res',
                'description' => '<p>Tocineta de red artesanal </p>'
            ],
        ];
        
        $data = [
            'name' => $store->name . ' | Sede Principal',
            'phone' => $store->phone,
            
        ];
        $place=$store->places()->create($data);
        $provider = $store->providers()->create([
            'name' => 'Proveedor',
            'lastname' => 'Genérico ',
            'email' => 'pgenerico@ahumadosh.com',
            'address' => 'Sin Dirección',
            'phone' => '8098765432',
            'RNC' => '00000000',
            'limit' => 0,
        ]);
        foreach($products as $prod){
            $product=$store->products()->create($prod);
           // $product->taxes()->sync($tax);
            $price_mayor=rand(75,125);
            $price_menor=$price_mayor*1.15;
            $cost=rand(39,75);
            $stock=rand(105,500);
            $product->units()->attach($unit,
            [
                'place_id'=>1,
                'cost'=>$cost,
                'price_mayor'=>$price_mayor,
                'price_menor'=>$price_menor,
                'min'=>$stock*0.25,
                'margin'=>($price_menor/$cost)-1,
                'stock'=>$stock

            ]);
            $product->units()->attach($unit2,
            [
                'place_id'=>1,
                'cost'=>$cost*1.75,
                'price_mayor'=>$price_mayor*1.175,
                'price_menor'=>$price_menor*1.75,
                'min'=>$stock*0.15,
                'margin'=>(($price_menor*1.75)/$cost)-1,
                'stock'=>$stock

            ]);
            $product->providers()->attach($provider);
        };
        $user = $store->users()->create([
            'name' => 'Ismael',
            'lastname' => 'Contreras ',
            'email' => 'mastergo@atriontechsd.com',
            'username' => 'mastergo',
            'password' => 'mastergo',
            'phone' => '8298041907',
            'loggeable'=>'yes',
            'place_id' => $store->places()->first()->id
        ]);
        $user2 = $store->users()->create([
            'name' => 'Estefany',
            'lastname' => 'Fernández ',
            'email' => 'admin@ahumadosmyl.com',
            'username' => 'estefany',
            'password' => 'estefany',
            'loggeable'=>'yes',
            'phone' => '8298041907',
            'place_id' => $store->places()->first()->id
        ]);

        $client = $store->clients()->create([
            'name' => 'Cliente',
            'lastname' => 'Genérico ',
            'email' => 'generico@ahumadosh.com',
            'address' => 'Sin Dirección',
            'phone' => '8098765432',
            'RNC' => '00000000',
            'limit' => 5750,
        ]);
       
        $user->image()->create([
            'path' => 'https://definicion.de/wp-content/uploads/2016/02/avatar.jpg',
        ]);
        $this->call(
            [
                RoleSeeder::class,
                ScopeSeeder::class,
                CountMainSeeder::class,
            ]
        );
        setContable($client, '101');
        setContable($tax, '202', 'ITBIS por Pagar');
        setContable($tax, '103', $tax->name.' por Cobrar');
        $user->assignRole('Super Admin');
        $user2->assignRole('Administrador');
        $store->roles()->save(Role::find(1));
        $store->roles()->save(Role::find(2));
        setContable($place, '100','Efectivo en Caja', $place->id);
        setContable($place, '100','Efectivo en Cheques', $place->id);
        setContable($place, '100','Otros Efectivos', $place->id);
        setContable($place, '400','Ingresos por Ventas', $place->id);
        setContable($place, '401','Descuento en Ventas', $place->id);
        setContable($place, '401','Devolución en Ventas', $place->id);
        setContable($place, '402','Otros Ingresos', $place->id);

        User::factory(400)->create()->each(function ($us) use ($store) {
            $num = rand(1, 250);
            $us->image()->create([
                'path' => "https://picsum.photos/id/{$num}/200/300",
            ]);
            $us->stores()->save($store);
            if (fmod($us->id, 33) == 0) {
                $us->assignRole('Administrador');
            }
        });
    }
}
