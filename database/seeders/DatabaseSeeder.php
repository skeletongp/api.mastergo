<?php

namespace Database\Seeders;

use App\Http\Livewire\Store\CreateStore;
use App\Models\Invoice;
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
        /* $store = Store::create([
            'name' => 'AtrionTech Soluciones Digitales',
            'address' => 'Calle Respaldo A, No. 8E, D. N.',
            'lema' => '¡Soluciones simples a problemas complejos!',
            'email' => 'info@atriontechsd.com',
            'phone' => '(809) 508-6221',
            'rnc' => '132487524',
            'expires_at' => Carbon::now()->addMonths(3)
        ]);
        $store->image()->create([
            'path' => 'https://res.cloudinary.com/dboafhu31/image/upload/v1638122890/bfguvm5lp8qfoydcsinz.png',
        ]); */

        $store=Store::find(1);
        $unit = $store->units()->create([
            'name' => 'Servicio',
            'symbol' => 'Serv.'
        ]);

        $tax = $store->taxes()->create([
            'name' => 'ITBIS',
            'rate' => 0.18
        ]);

        $products = [
            [
                'name' => 'Desarrollo de Software',
                'description' => 'Creación y desarrollo de sistemas',
                'type' => 'Servicio'
            ],
            [
                'name' => 'Instalación de Software',
                'description' => 'Instalación de sistemas con licencia',
                'type' => 'Servicio'
            ],
            [
                'name' => 'Desarrollo Web',
                'description' => 'Creación de páginas web para negocios',
                'type' => 'Servicio'
            ],
            [
                'name' => 'Asesoría Digital',
                'description' => 'Asesoría para gestión de tecnologías',
                'type' => 'Servicio'
            ],
        ];

        $data = [
            'name' => $store->name . ' | Oficina',
            'phone' => $store->phone,

        ];

        $place = $store->places()->create($data);
        $place->preference()->create([
            'comprobante_type' => 'B00',
            'unit_id' => $unit->id,
            'tax_id' => $tax->id,
        ]);
        $provider = $store->providers()->create([
            'name' => 'Prov.',
            'lastname' => 'Genérico ',
            'email' => 'generic@provider.com',
            'address' => 'Sin Dirección',
            'phone' => '(000) 000-0000)',
            'rnc' => '000-00000-0',
            'limit' => 0,
        ]);
        foreach ($products as $prod) {
            $product = $store->products()->create($prod);
                $product->taxes()->sync($tax);
            $price_mayor = rand(75, 125);
            $price_menor = $price_mayor * 1.15;
            $cost = rand(39, 75);
            $stock = 0;
            $product->units()->attach(
                $unit,
                [
                    'place_id' => 1,
                    'cost' => $cost,
                    'price_mayor' => $price_mayor,
                    'price_menor' => $price_menor,
                    'min' => $stock * 0.25,
                    'margin' => ($price_menor / $cost) - 1,
                    'stock' => $stock

                ]
            );
            $product->providers()->attach($provider);
        };
       /*  $user = $store->users()->create([
            'name' => 'Ismael',
            'lastname' => 'Contreras ',
            'email' => 'contrerasismael0@gmail.com',
            'username' => 'mastergo',
            'password' => 'mastergo',
            'phone' => '(829) 804-1907)',
            'loggeable' => 'yes',
            'place_id' => $store->places()->first()->id,
            'store_id' => $store->id,
        ]); */
        $user=User::find(1);
        $store->users()->attach($user);
        $client = $store->clients()->create([
            'name' => 'Clte.',
            'lastname' => 'Genérico ',
            'email' => 'generico@ahumadosh.com',
            'address' => 'Sin Dirección',
            'phone' => '(000) 000-0000',
            'RNC' => '000-00000-0',
            'limit' => 5000,
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
        $bank = $store->banks()->create([
            'bank_name' => 'Banco Popular Dominicano',
            'bank_number' => '803579804',
            'titular_id' => $user->id
        ]);
        setContable($client, '101', 'debit', $client->fullname, $place->id);
        setContable($tax, '203', 'credit', 'ITBIS por Pagar', $place->id);
        setContable($tax, '103', 'debit', $tax->name . ' por Cobrar', $place->id);
        $user->assignRole('Super Admin');
        $user->assignRole('Administrador');
        $store->roles()->save(Role::find(1));
        $store->roles()->save(Role::find(2));
        $store->roles()->save(Role::find(3));
        setContable($place, '100', 'debit', 'Efectivo en Caja General', $place->id);
        setContable($place, '100', 'debit', 'Efectivo en Caja Chica', $place->id);
        setContable($place, '100', 'debit', 'Efectivo en Cheques', $place->id);
        setContable($place, '100', 'debit', 'Otros Efectivos', $place->id);
        setContable($bank, '100', 'debit', $bank->bank_name,  $place->id,);
        setContable($place, '104', 'debit', 'Inventario general',  $place->id,);
        setContable($place, '400', 'credit', 'Ingresos por Ventas', $place->id);
        setContable($place, '401', 'debit', 'Devoluciones en Ventas', $place->id);
        setContable($place, '401', 'debit', 'Otras notas de crédito', $place->id);
        setContable($place, '401', 'debit', 'Descuentos en Ventas', $place->id);
        setContable($place, '402', 'credit', 'Otros Ingresos', $place->id);
        setContable($place, '500', 'debit', 'Compra de mercancías', $place->id);
        setContable($place, '501', 'debit', 'Devoluciones en compras', $place->id);
        setContable($place, '501', 'debit', 'Descuentos en compras', $place->id);
        setContable($place, '300', 'credit', 'Capital Sucrito y Pagado', $place->id);
        $roles = ['Administrador', 'Super Admin', 'Generico'];
    }
}
