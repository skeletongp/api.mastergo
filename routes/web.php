<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::get('auth/login', 'login')->name('login');
    Route::post('auth/login', 'store')->name('login.store');
    Route::post('auth/logout', 'logout')->name('auth.logout');
});

/* TODO */
/* Rutas protegidas por Auth */


Route::middleware(['auth'])->group(function () {
    Route::middleware(['web'])->group(function () {
        Route::get('/', function () {
            return view('welcome');
        })->name('home');

        Route::controller(UserController::class)->group(function () {

            Route::get('users', 'index')->name('users.index');
        });

        Route::controller(InvoiceController::class)->group(function () {
            Route::get('/invoices', 'index')->name('invoices.index');
        });

        Route::controller(ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('products.index');
            Route::get('/products/create', 'create')->name('products.create');
            Route::get('/products/sum', 'sum')->name('products.sum');
            Route::get('/products/edit/{product}', 'edit')->name('products.edit');
            Route::get('/products/{product}', 'show')->name('products.show');
        });

        Route::controller(StoreController::class)->group(function () {
            Route::get('/stores', 'index')->name('stores.index');
        });

        Route::controller(ClientController::class)->group(function () {
            Route::get('/clients', 'index')->name('clients.index');
        });

        Route::controller(SettingController::class)->group(function () {
            Route::get('/settings', 'index')->name('settings.index');
        });

        Route::controller(RecursoController::class)->group(function(){
            Route::get('recursos','index')->name('recursos.index');
        });

        Route::get('uid', function () {
            $users = User::get();
            foreach ($users as $key => $user) {
                $user->stores()->sync(Store::first());
            }
            return $users;
        });
    });
});
