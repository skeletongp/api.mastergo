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
   $withoutComma=preg_replace("/[^0-9.-]/", "", $number );
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
function getNextDate(string $recurrency, $date){
    $date=Carbon::parse($date);
    switch ($recurrency) {
        case 'Quincenal':
            $date->addDays(15);
            break;
        case 'Mensual':
            $date->addMonth();
            break;
        case 'Bimestral':
            $date->addMonth(2);
            break;
        case 'Trimestral':
            $date->addMonth(3);
            break;
        case 'Cuatrimestral':
            $date->addMonth(4);
            break;
        case 'Semestral':
            $date->addMonth(6);
            break;
        case 'Anual':
            $date->addYear();
            break;
        default:
            break;
    }
    return $date;
}
function operate( $a, $op, $b)
{
    $a=floatval(str_replace(',', '', $a));
    $b=floatval(str_replace(',', '', $b));
    switch ($op) {
        case '+':
            return $a + $b;
        case '-':
            return $a - $b;
        case '*':
            return $a * $b;
        case '/':
            return $a / $b;
        default:
            return null;
    }
}
function removeAccent($cadena){

    //Codificamos la cadena en formato utf8 en caso de que nos de errores
  //  $cadena = utf8_encode($cadena);

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

   

    return $cadena;
}