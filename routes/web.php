<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
Route::controller(AuthController::class)->group(function(){
    Route::get('auth.login', 'login')->name('login');
    Route::post('auth/login', 'store')->name('login.store');
});

/* TODO */
/* Rutas protegidas por Auth */


Route::middleware(['auth'])->group(function () {
    Route::middleware(['web'])->group(function () {
        Route::get('/', function () {
            return view('welcome');
        })->name('home');

        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', function (Request  $request) {
            $request->merge(['createUserOpen' => true]);
            return view('pages.users.index');
        })->name('users.create');
    });
});
