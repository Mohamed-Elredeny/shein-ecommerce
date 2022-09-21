<?php

use Illuminate\Support\Facades\Auth;
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
Route::group(['prefix' => 'admin','middleware'=>'checkWebAdmin'], function () {
    Route::get('/', function () {
        return view('admin.home');
    })->name('admin.home');
    Route::resource('users', \App\Http\Controllers\Admin\UsersController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class);
    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class);
    Route::get('deleteImage/{type}/{id}/{index}', [\App\Http\Controllers\Admin\ProductsController::class, 'deleteImage'])->name('deleteImage');
    Route::resource('orders', \App\Http\Controllers\Admin\OrdersController::class);
    Route::resource('transactions', \App\Http\Controllers\Admin\TransactionsController::class);
});
