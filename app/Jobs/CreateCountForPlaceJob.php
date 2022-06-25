<?php

namespace App\Jobs;

use App\Models\Place;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class CreateCountForPlaceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $store, $place;
    public function __construct(Store  $store, Place $place)
    {
        $this->store=$store;
        $this->place=$place;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $counts=$this->store->counts()->get()->unique('code');
        Log::info($counts->count());
        
        foreach ($counts as $count) {
            $cuenta=Arr::except($count->toArray(),'id','balance');
            if ($cuenta['contable_type']==Place::class) {
                $cuenta['contable_id']=$this->place->id;
            }
            $cuenta['place_id']=$this->place->id;
            $this->store->counts()->create($cuenta);
        }
    }
}
