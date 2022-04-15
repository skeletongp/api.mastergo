<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Helper\Universal;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\Scope;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
            $users = auth()->user()->store->users()->with('image')->paginate(8);
            return view('welcome', compact('users'));
        })->name('home');

        Route::controller(UserController::class)->group(function () {

            Route::get('users', 'index')->name('users.index');
        });

        Route::controller(InvoiceController::class)->group(function () {
            Route::get('/invoices', 'index')->name('invoices.index');
            Route::get('/invoices/create', 'create')->name('invoices.create');
            Route::get('/invoices/orders', 'orders')->name('orders');
            
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

        Route::controller(RecursoController::class)->group(function () {
            Route::get('recursos', 'index')->name('recursos.index');
            Route::get('recursos/{recurso}', 'show')->name('recursos.show');
        });

        Route::controller(ProcesoController::class)->group(function () {
            Route::get('procesos', 'index')->name('procesos.index');
            Route::get('procesos/create', 'create')->name('procesos.create');
            Route::get('procesos/{proceso}', 'show')->name('procesos.show');
        });
    });
});
Route::get('uid', function () {
    $invoice = Invoice::first();
    $data = [
        'invoice' => $invoice,
    ];
    $pdf = PDF::loadView('pages.invoices.thermal', $data)->setPaper('80mm', 'portrait');
   return  $pdf->stream('invoice.pdf');
});
