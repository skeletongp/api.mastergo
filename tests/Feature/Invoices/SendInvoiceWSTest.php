<?php

namespace Tests\Feature\Invoices;

use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SendInvoiceWSTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDebeEnviarFacturaPorWhatsapp()
    {
       
       $cellphone='(829) 804-1907';
       $path='https://paratesis.com/storage/invoices/1-0000011_20220728_letter.pdf';
       sendInvoiceWS($path, $cellphone);
         $this->assertTrue(true);
    }
}
