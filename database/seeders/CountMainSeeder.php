<?php

namespace Database\Seeders;

use App\Models\CountMain;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountMainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = file_get_contents(public_path("/countmain.json"));
        $newCounts = json_decode($data, true);
        CountMain::insert($newCounts);
    }
}
