<?php

use App\Http\Controllers\Invoices\CotizeController;
use App\Http\Controllers\Invoices\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::controller(InvoiceController::class)->group(function () {
    Route::get('invoices', 'index')->name('invoices.index');
    Route::get('invoices/create', 'create')->name('invoices.create');
    Route::get('invoices/orders', 'orders')->name('orders');
    Route::get('invoices/deleteds', 'deleteds')->name('deleteds');
    Route::get('invoices/show/{invoice}', 'show')->name('invoices.show');
});

Route::controller(CotizeController::class)->group(function () {
    Route::get('cotizes', 'index')->name('cotizes.index');
    Route::get('cotizes/create', 'create')->name('cotizes.create');
    Route::get('cotizes/{cotize}', 'show')->name('cotizes.show');
});

