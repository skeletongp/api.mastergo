<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\CuadreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ContableController;
use App\Http\Controllers\CotizeController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProvisionController;
use App\Http\Controllers\RecurrentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Models\Client;
use App\Models\Count;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Sawirricardo\Whatsapp\Data\TextMessageData;

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
    Route::get('auth/logout', 'logout')->name('auth.logout');
    Route::get('prueba', 'prueba')->name('prueba');
});


/* Rutas protegidas por Auth */


Route::middleware(['auth'])->group(function () {
    Route::middleware(['web'])->group(function () {
        Route::get('', [DashboardController::class, 'index'])->name('home');

        Route::controller(UserController::class)->group(function () {
            Route::get('users', 'index')->name('users.index');
            Route::get('users/set-permissions/{id}', 'setPermissions')->name('users.setPermissions');
        });

        Route::controller(InvoiceController::class)->group(function () {
            Route::get('invoices', 'index')->name('invoices.index');
            Route::get('invoices/create', 'create')->name('invoices.create');
            Route::get('invoices/orders', 'orders')->name('orders');
            Route::get('invoices/show/{invoice}', 'show')->name('invoices.show');
        });

        Route::controller(CotizeController::class)->group(function () {
            Route::get('cotizes', 'index')->name('cotizes.index');
            Route::get('cotizes/create', 'create')->name('cotizes.create');
            Route::get('cotizes/{cotize}', 'show')->name('cotizes.show');
        });

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

        Route::controller(StoreController::class)->group(function () {
            Route::get('stores', 'index')->name('stores.index');
        });

        Route::controller(ClientController::class)->group(function () {
            Route::get('clients', 'index')->name('clients.index');
            Route::get('clients/paymany/{invoices}', 'paymany')->name('clients.paymany');
            Route::get('clients/printmany/{invoices}', 'printmany')->name('clients.printmany');
            Route::get('clients/invoices/{client_id}', 'invoices')->name('clients.invoices');
            Route::get('clients/{client_id}', 'show')->name('clients.show');
        });
        Route::controller(ProviderController::class)->group(function () {
            Route::get('providers', 'index')->name('providers.index');
        });

        Route::controller(SettingController::class)->group(function () {
            Route::get('settings', 'index')->name('settings.index');
        });

        Route::controller(RecursoController::class)->group(function () {
            Route::get('recursos', 'index')->name('recursos.index');
            Route::get('recursos/sum', 'sum')->name('recursos.sum');
            Route::get('recursos/{recurso}', 'show')->name('recursos.show');
        });

        Route::controller(ProcesoController::class)->group(function () {
            Route::get('procesos', 'index')->name('procesos.index');
            Route::get('procesos/create', 'create')->name('procesos.create');
            Route::get('procesos/formula/{proceso}', 'formula')->name('procesos.formula');
            Route::get('procesos/{proceso}', 'show')->name('procesos.show');
            Route::get('productions/{production}', 'production_show')->name('productions.show');
        });
        Route::controller(ContableController::class)->group(function () {
            Route::get('general_daily', 'general_daily')->name('contables.general_daily');
            Route::get('historial_daily', 'historial_daily')->name('contables.historial_daily');
            Route::get('general_mayor', 'general_mayor')->name('contables.general_mayor');
            Route::get('catalogue', 'catalogue')->name('contables.catalogue');
            Route::get('view_catalogue', 'view_catalogue')->name('contables.view_catalogue');
            Route::get('results', 'results')->name('contables.results');
            Route::get('report_607', 'report_607')->name('contables.report_607');
            Route::get('report_606', 'report_606')->name('contables.report_606');
            Route::get('countview/{code}', 'countview')->name('contables.countview');
            Route::get('counttrans/{id}', 'counttrans')->name('contables.counttrans');
        });
        Route::controller(ComprobanteController::class)->group(function () {
            Route::get('comprobantes', 'index')->name('comprobantes.index');
        });

        Route::controller(CuadreController::class)->group(function () {
            Route::get('cuadres', 'index')->name('cuadres.index');
            Route::get('cuadres/{cuadre}', 'show')->name('cuadres.show');
        });
        Route::controller(ReportController::class)->group(function () {
            Route::get('incomes', 'incomes')->name('reports.incomes');
            Route::get('outcomes', 'outcomes')->name('reports.outcomes');
            Route::get('rep_invoices', 'invoices')->name('reports.invoices');
            Route::get('rep_invoices_por_cobrar', 'invoices_por_cobrar')->name('reports.invoices_por_cobrar');
        });
        Route::controller(ProvisionController::class)->group(function () {
            Route::get('provisions', 'index')->name('provisions.index');
        });


        Route::controller(FinanceController::class)->group(function () {
            Route::get('finances', 'index')->name('finances.index');
            Route::get('banks/{bank}/{type}', 'bank_show')->name('finances.bank_show');
        });

        Route::controller(RecurrentController::class)->group(function () {
            Route::get('recurrents', 'index')->name('recurrents.index');
        });
    });
});

Route::post('whatsapp/webhook', function () {
    dd(request()->all());
    Log::info(request()->all());
});

