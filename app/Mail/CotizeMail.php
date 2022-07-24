<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CotizeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cotize;
    public function __construct($cotize)
    {
        $this->cotize=$cotize;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('pages.mails.cotize', ['cotize'=>$this->cotize])
        ->subject('Cotización #'.$this->cotize->id.' - '.$this->cotize->client->name)
        ->attach($this->cotize->pdf->pathLetter);
    }
}
