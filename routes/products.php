<?php

use App\Http\Controllers\Products\CategoryController;
use App\Http\Controllers\Products\PesadaController;
use App\Http\Controllers\Products\ProcesoController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\ProvisionController;
use App\Http\Controllers\Products\RecursoController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)->group(function () {
    Route::get('products', 'index')->name('products.index');
    Route::get('products/report', 'report')->name('products.report');
    Route::get('products/catalogue', 'catalogue')->name('products.catalogue');
    Route::get('products/create', 'create')->name('products.create');
    Route::get('products/sum', 'sum')->name('products.sum');
    Route::get('products/edit/{product}', 'edit')->name('products.edit');
    Route::get('products/{product}', 'show')->name('products.show');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('categories', 'index')->name('categories.index');
    Route::get('categories/create', 'create')->name('categories.create');
    Route::get('categories/edit/{category}', 'edit')->name('categories.edit');
    Route::get('categories/{category}', 'show')->name('categories.show');
});

Route::controller(ProcesoController::class)->group(function () {
    Route::get('procesos', 'index')->name('procesos.index');
    Route::get('procesos/create', 'create')->name('procesos.create');
    Route::get('procesos/formula/{proceso}', 'formula')->name('procesos.formula');
    Route::get('procesos/{proceso}', 'show')->name('procesos.show');
    Route::get('productions/{production}', 'production_show')->name('productions.show');
});

Route::controller(ProvisionController::class)->group(function () {
    Route::get('provisions', 'index')->name('provisions.index');
});
Route::controller(RecursoController::class)->group(function () {
    Route::get('recursos', 'index')->name('recursos.index');
    Route::get('recursos/sum', 'sum')->name('recursos.sum');
    Route::get('recursos/{recurso}', 'show')->name('recursos.show');
});

Route::controller(PesadaController::class)->group(function () {
    Route::get('pesadas', 'index')->name('pesadas.index');
    Route::get('pesadas/create', 'create')->name('pesadas.create');
    Route::get('pesadas/{pesada}', 'show')->name('pesadas.show');
});