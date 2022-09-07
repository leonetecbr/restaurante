<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::controller(Controllers\UserController::class)->group(function (){
    Route::get('/', 'get')->name('login');

    Route::post('/login', 'post')->name('auth');

    Route::get('/logout', 'logout')->name('logout') ->middleware('auth');
});

Route::middleware('auth')->name('dashboard')->group(function (){
    Route::get('/dashboard', function (){
        return to_route('dashboard.'.Auth::user()->type.'.home');
    });

    Route::name('.admin.')->middleware('isAdmin')->group(function () {
        Route::get('/dashboard/admin', [Controllers\AdminController::class, 'get'])->name('home');
    });

    Route::name('.garcom.')->middleware('isGarcom')->group(function () {
        Route::get('/dashboard/garcom', [Controllers\GarcomController::class, 'get'])->name('home');
    });
});
