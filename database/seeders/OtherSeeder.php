<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\ProductPlaceUnit;
use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $place=Place::first();
        $store=Store::find(2);
        $this->createBanks($place, $store);
        $this->createdProducts($place, $store);
        $this->createComprobantes($store);
       
    }
    public function createComprobantes($store)
    {
        for ($i=1; $i < 21; $i++) { 
            $store->comprobantes()->create([
                'type'=>'COMPROBANTE DE CONSUMIDOR FINAL',
                'prefix'=>'B02',
                'number'=> str_pad($i, 8,'0', STR_PAD_LEFT),
            ]);
        }
    }
    public function createdProducts($place, $store)
    {
        $pierna=$store->products()->create([
            'name'=>'Piernas de cerdo ahumada',
            'code'=>'001',
            'type'=>'Producto'
        ]);
        $this->assignUnit(1,$place, $pierna, 65,80,80,50);

        $chuleta=$store->products()->create([
            'name'=>'Chuleta Importada',
            'code'=>'002',
            'type'=>'Producto'
        ]);
        $this->assignUnit(1,$place, $chuleta, 55,76,40,35);

        
    }
    public function assignUnit($unit_id,$place,$product, $price_mayor, $price_menor, $min, $cost){
        ProductPlaceUnit::create([
            'product_id'=>$product->id,
            'place_id'=>$place->id,
            'unit_id'=>$unit_id,
            'price_mayor'=>$price_mayor,
            'price_menor'=>$price_menor,
            'min'=>$min,
            'cost'=>$cost,
            'margin'=>($price_menor/$cost)-1,
        ]);
    }
    public function createBanks($place, $store)
    {
        $popular=$store->banks()->create([
            'bank_name'=>'Banco Popular',
            'bank_number'=>'803579804',
            'titular_id'=>1,
        ]);
        $reservas=$store->banks()->create([
            'bank_name'=>'BanReservas',
            'bank_number'=>'3604789684',
            'titular_id'=>1,
        ]);

        setContable($popular,'100','debit', $popular->bank_name, $place->id,true);
        setContable($reservas,'100','debit', $reservas->bank_name, $place->id,true);
    }
}
