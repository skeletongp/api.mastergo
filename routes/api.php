<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/webhook', function (Request $request) {
    $data=$request->all();
    Log::info($data);
    return ;
   $body=$data['entry'][0]['changes'][0]['value']['messages'][0];
   Log::info($body);
   $to='18493153337';
   $message='Mensaje de *'.$body['from']."*\n".$body['text']['body'];
  sendMessage($to,$message);

})->name('webhook_post');
Route::get('/webhook', function(Request $request){
    return $request->hub_challenge;
});