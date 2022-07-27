<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateComprobanteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public $form;
    public function __construct($form)
    {
        $this->form=$form;
    }
   

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $store=Store::find(env('STORE_ID'));
        for ($i=$this->form['inicial']; $i <=$this->form['final'] ; $i++) { 
           $store->comprobantes()->create([
                'type'=>$this->form['type'],
                'prefix'=>Invoice::TYPES[$this->form['type']],
                'number'=>str_pad($i, 8,'0', STR_PAD_LEFT),
            ]);
        }
    }
}
