<?php


use Twilio\Rest\Client as TwilioClient;
function sendWSCatalogue($phone, $path){
    $phone='+1'.preg_replace('/[^0-9]/', '', $phone);
    $client= new TwilioClient(env('TWILIO_ACCOUNT_SID'), env('TWILIO_AUTH_TOKEN'));
    $client->messages->create(
        'whatsapp:'.$phone,
        [
            'from' => env('TWILIO_FROM_NUMBER'),
            'body' => '¡Hola!, explora nuestro catálogo de productos.',
            "mediaUrl" => [$path],
        ]
    );
}