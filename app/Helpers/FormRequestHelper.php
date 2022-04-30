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
function verifyClientLimit(Client $client, $rest){
    return $client->limit>=$rest;
}