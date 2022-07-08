<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene el precio del dólar en la fecha actual';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $res = Http::withHeaders([
            "Content-Type"=>"text/plain",
            "apikey"=>"ik49ck79RwOhpMmfZHQtqnSMyN7hIlvs"
        ])->get("https://api.apilayer.com/exchangerates_data/convert?to=DOP&from=USD&amount=1");
        Cache::put('currency', $res->json()['result']);
        Log::info($res->json()['result']);
        return 0;
    }
}
