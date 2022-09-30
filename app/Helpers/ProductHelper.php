<?php

use Illuminate\Support\Facades\Cache;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

function sendWSCatalogue($phone, $path){
    $user=auth()->user();
    $phone='1'.preg_replace('/[^0-9]/', '', $phone);
    $phone2='1'.preg_replace('/[^0-9]/', '', $user->phone);
    $whatsapp_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => env('WHATSAPP_NUMBER_ID'),
        'access_token' => env('WHATSAPP_TOKEN'),
    ]);
    $document_name = basename($path);
    $document_caption =  'Catálogo de productos';
    $user=auth()->user();
    $document_link = $path;
    $link_id = new LinkID($document_link);
    $whatsapp_cloud_api->sendDocument($phone, $link_id, $document_name, $document_caption);
    $whatsapp_cloud_api->sendDocument($phone2, $link_id, $document_name, $document_caption);
    $whatsapp_cloud_api->sendTextMessage($phone, 'Verifica nuestro '.$document_caption);
    $whatsapp_cloud_api->sendTextMessage($phone2, 'Verifica nuestro '.$document_caption);
}