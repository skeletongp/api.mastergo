<?php

use Illuminate\Support\Facades\Cache;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

function sendWSCatalogue($phone, $path){
    $phone=preg_replace('/[^0-9]/', '', $phone);
   
    $whatsapp_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => env('WHATSAPP_NUMBER_ID'),
        'access_token' => env('WHATSAPP_TOKEN'),
    ]);
    $document_name = basename($path);
    $document_caption = 'Catálogo de productos';

    $document_link = $path;
    $link_id = new LinkID($document_link);
    $whatsapp_cloud_api->sendDocument('1'.$phone, $link_id, $document_name, $document_caption);
    $whatsapp_cloud_api->sendTextMessage('1'.$phone, 'Verifica nuestro '.$document_caption);
}