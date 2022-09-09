<?php

use App\Models\Bank;
use App\Models\Invoice;
use App\Models\Place;
use App\Models\Preference;
use App\Models\Product;
use App\Models\Store;
use App\Models\Tax;
use App\Models\Unit;
use Carbon\Carbon;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

function formatNumber($number, $lenght=2)
{
    $number = floatval(str_replace(',', '', $number));
    $formatted = rtrim(rtrim(number_format($number, 4, ".", ""), '0'), '.');
    if (!strpos('.', $formatted)) {
        $formatted = number_format($formatted, $lenght);
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
    if (mb_strlen($string) > $maxLength) {
        return mb_substr($string, 0, $maxLength) . '...';
    }
    return $string;
}
function getNextDate(string $recurrency, $date){
    $fecha = Carbon::createFromDate($date);
    $recurrency=mb_strtolower($recurrency);
    switch ($recurrency) {
        case 'diario':
            $fecha->addDay();
            $fecha_db = $fecha->toDateString();
            break;
        case 'semanal':
            $fecha->addWeek();
            $fecha_db = $fecha->toDateString();
            break;
        case 'quincenal':
            $fecha->addDays(3);
            $day=$fecha->format('d');
            if ($day<15) {
              $fecha=$fecha->day(15);
            } else {
              $fecha=$fecha->lastOfMonth();
            }
            $fecha_db = $fecha->toDateString();
            break;
        case 'mensual':
            $fecha->addMonth();
            $fecha_db = $fecha;
            break;
    }
    return Carbon::parse($fecha_db);
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
function sendMessage($to, $message){
    $phone=preg_replace('/[^0-9]/', '', $to);
    if(substr($phone, 0, 1)!='1'){
        $phone='1'.$phone;
    }
    $whatsapp_cloud_api = new WhatsAppCloudApi([
        'from_phone_number_id' => env('WHATSAPP_NUMBER_ID'),
        'access_token' => env('WHATSAPP_TOKEN'),
    ]);
    
    $whatsapp_cloud_api->sendTextMessage($phone, $message);
}
function getBanks(){
    $banks=Cache::get('banks'.env('STORE_ID'));
    if (!$banks) {
        $banks=Bank::where('store_id', env('STORE_ID'))->pluck('bank_name', 'id');
        Cache::put('banks'.env('STORE_ID'), $banks);
    }
    return $banks;
}
function getPreference($place_id){
    $preference=Cache::get('preference'.$place_id);
    if (!$preference) {
        $preference=Preference::where('place_id', $place_id)->first();
        Cache::put('preference'.$place_id, $preference);
    }
    return $preference;
}
function getStoreLogo(){
    $logo=Cache::get('store_logo'.env('STORE_ID'));
    if (!$logo) {
        $logo=Store::find(env('STORE_ID'))->logo;
        Cache::put('store_logo'.env('STORE_ID'), $logo);
    }
    return $logo;
}
function getPlaceInvoices($place_id){
    $invoices=Cache::get('place_invoices'.$place_id);
    if (!$invoices) {
        $invoices=Invoice::where('place_id', $place_id)->get();
        Cache::put('place_invoices'.$place_id, $invoices);
    }
    return $invoices;
}
function getPlaceInvoicesWithTrashed($place_id){
    $invoices=Cache::get('place_invoices_with_trashed'.$place_id);
    if (!$invoices) {
        $invoices=Invoice::where('place_id', $place_id)->withTrashed()->get();
        Cache::put('place_invoices_with_trashed'.$place_id, $invoices);
    }
    return $invoices;
}
function getTaxes(){
    $taxes=Cache::get('taxes'.env('STORE_ID'));
    if (!$taxes) {
        $taxes=Tax::where('store_id', env('STORE_ID'))->pluck('name', 'id');
        Cache::put('taxes'.env('STORE_ID'), $taxes);
    }
    return $taxes;
}
function getUnits(){
    $units=Cache::get('units'.env('STORE_ID'));
    if (!$units) {
        $units=Unit::where('store_id', env('STORE_ID'))->pluck('name', 'id');
        Cache::put('units'.env('STORE_ID'), $units);
    }
    return $units;
}

function getPlaces(){
    $places=Cache::get('places'.env('STORE_ID'));
    if (!$places) {
        $places=Place::where('store_id', env('STORE_ID'))->pluck('name', 'id');
        Cache::put('places'.env('STORE_ID'), $places);
    }
    return $places;
}
function getStore(){
    $store=Cache::get('store'.env('STORE_ID'));
    if (!$store) { 
        $store=Store::find(env('STORE_ID'));
        Cache::put('store'.env('STORE_ID'), $store);
    }
    return $store;
}
function getPlace(){
    $place_id=auth()->user()->place_id;
    $place=Cache::get('place'.$place_id);
    if (!$place) { 
        $place=Place::find($place_id);
        Cache::put('place'.$place_id, $place);
    }
    return $place;
}
function getProductsWithCode(){
    $place=getPlace();
    $products=Cache::get('products'.$place->id);
    if (!$products) {
        $products=$place->products->pluck('name','code');
        Cache::put('products'.$place->id, $products);
    }
    return $products;
}