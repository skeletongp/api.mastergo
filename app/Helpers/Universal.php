<?php

use Carbon\Carbon;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

function formatNumber($number)
{
    $number = floatval(str_replace(',', '', $number));
    $formatted = rtrim(rtrim(number_format($number, 4, ".", ""), '0'), '.');
    if (!strpos('.', $formatted)) {
        $formatted = number_format($formatted, 2);
    }
    return $formatted;
}
function removeComma($number)
{
   $withoutComma=preg_replace("/[^0-9.]/", "", $number );
   if (is_numeric($withoutComma)) {
       return $withoutComma;
   }
    return 0;
}
function linkPhoto($link)
{

    if (str_starts_with($link, 'http')) {
        $photo = str_replace('http:', 'https:', $link);
        return asset($photo);
    }
    return $link;
}
function upPDFToCloud($path)
{
    $cloudinary = new Cloudinary(
        [
            'cloud' => [
                'cloud_name' => env('CLOUD_NAME'),
                'api_key'    => env('CLOUD_API_KEY'),
                'api_secret' => env('CLOUD_API_SECRET'),
            ],
        ]
    );

    $path = $cloudinary->uploadApi()->upload($path);
    return json_decode(json_encode($path))->url;
}

/* @params Invoices $array, Related $key, Field $value */
/* Return Field´s Value from Related */
function arrayFind(array $array, $key, $value)
{
    $result = 0;
    foreach ($array as $ind => $item) {
        if ($array[$ind][$key] == $value) {
            $result = $item;
        }
    }
    return $result;
}
function admins()
{
    $store = auth()->user()->store;
    if (!Cache::get($store->id.'admins')) {
        Cache::put($store->id.'admins',$store->users()->role('Administrador')->where('loggeable', 'yes')
        ->orderBy('lastname')->pluck('password', 'fullname'));
    }
    
    return Cache::get($store->id.'admins');
}
function formatDate($date, $format)
{
   return Carbon::parse($date)->format($format);
}
function getApi($endPoint)
{
    $url=null;
    if (strpos($endPoint,'api')) {
       $url= $endPoint;
    } else {
       $url=env('BASE_URL') . $endPoint;;
    }
    $response = Http::withHeaders([
            'Accept' => 'application/json',

        ])->withToken(env('TOKEN'))
        ->get($url);
    return $response->json();
}
function ellipsis($string, $maxLength)
{
    if (strlen($string) > $maxLength) {
        return substr($string, 0, $maxLength) . '...';
    }
    return $string;
}