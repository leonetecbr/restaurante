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

    Route::redirect('/', '/login');
    Route::get('/login', 'get')->name('login');
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
                Route::post('/edit/{table:id}', 'edit')->name('.edit')->whereNumber('table');
                Route::get('/delete/{table:id}', 'delete')->name('.delete')->whereNumber('table');
                Route::get('/delete/{table:id}/{product}', 'deleteProduct')->name('.delete.product')->whereNumber(['table', 'product']);
        });

        Route::get('/orders', [Controllers\OrdersController::class, 'get'])->name('.orders');

        Route::get('/payments', [Controllers\PaymentsController::class, 'get'])->name('.payments');
    });

    Route::get('/orders/{order:id}/products', [Controllers\OrdersController::class, 'api'])->name('orders.api')
        ->whereNumber('order');

    Route::get('/tables/{table:id}/products', [Controllers\TablesController::class ,'api'])->name('tables.api')
        ->whereNumber('table');

    Route::name('garcom')->middleware('isGarcom')
        ->prefix('garcom')->group(function () {

            Route::get('/', [Controllers\DashboardController::class, 'garcom']);

            Route::controller(Controllers\TablesController::class)->prefix('tables')
                    ->name('.tables')->group(function () {

                    Route::get('/busy/{table:id}','busy')->name('.busy')->whereNumber('table');
                    Route::post('/add/{table:id}','add')->name('.add')->whereNumber('table');
                    Route::post('/pay/{table:id}','pay')->name('.pay')->whereNumber('table');
            });
        });
});
