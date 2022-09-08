<?php

use App\Http\Controllers;
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

Route::controller(Controllers\UserController::class)->group(function () {

    Route::get('/', 'get')->name('login');
    Route::post('/login', 'post')->name('auth');
    Route::get('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::prefix('admin')->name('admin')->middleware('isAdmin')->group(function () {

        Route::get('/', [Controllers\DashboardController::class, 'admin']);

        Route::controller(Controllers\ProductsController::class)->prefix('products')
            ->name('.products')->group(function () {

            Route::get('/', 'get');
            Route::post('/edit/{product:id}', 'edit')->name('.edit')->whereNumber('product');
        });

        Route::controller(Controllers\TablesController::class)->prefix('tables')
            ->name('.tables')->group(function () {

            Route::get('/', 'get');
            Route::post('/new', 'new')->name('.new');
            Route::get('/{table:id}', 'api')->name('.api')->whereNumber('table');
            Route::post('/edit/{table:id}', 'edit')->name('.edit')->whereNumber('table');
            Route::get('/delete/{table:id}', 'delete')->name('.delete')->whereNumber('table');
            Route::get('/delete/{table:id}/{product}', 'deleteProduct')->name('.delete.product')->whereNumber(['table', 'product']);
        });

        Route::controller(Controllers\OrdersController::class)->prefix('orders')
            ->name('.orders')->group(function () {

                Route::get('/', 'get');
                Route::get('/{order:id}', 'api')->name('.api')->whereNumber('order');
        });

        Route::get('/payments', [Controllers\PaymentsController::class, 'get'])->name('.payments');
    });

    Route::name('garcom')->middleware('isGarcom')
            ->prefix('garcom')->group(function () {

        Route::get('/', [Controllers\DashboardController::class, 'garcom']);
    });
});
