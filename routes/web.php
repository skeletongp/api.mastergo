<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\CuadreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RecursoController;
use App\Http\Controllers\ContableController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\RecurrentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

