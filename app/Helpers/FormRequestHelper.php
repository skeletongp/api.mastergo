<?php

use App\Models\Client;

function orderConfirmRules() :Array
{
    return [
        'form' => 'required',
        'form.amount' => 'required|numeric',
        'form.discount' => 'required|numeric',
        'form.total' => 'required|numeric',
        'form.tax' => 'required|numeric',
        'form.payed' => 'required|numeric',
        'form.efectivo' => 'required|numeric',
        'form.tarjeta' => 'required|numeric',
        'form.transferencia' => 'required|numeric',
        'form.contable_id' => 'required|numeric',
        'form.client_id' => 'required|numeric',
        'form.type' => 'required',
    ];
}
function invoiceCreateRules($stock){
    return  [
        'form.product_id' => 'numeric|required|exists:products,id',
        'cant' => 'numeric|min:0.001|required|max:'.$stock,
        'price' => 'numeric|min:0.01|required',
        'discount' => 'numeric|min:0|required',
        'client'=>'required',
    ];
}
function verifyClientLimit(Client $client, $rest){
    return $client->limit>=$rest;
}