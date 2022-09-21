<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::resource('categories', CategoriesController::class);
Route::resource('products', ProductsController::class);

Route::resource('countries', \App\Http\Controllers\Api\CountriesController::class);
Route::resource('cities', \App\Http\Controllers\Api\CitiesController::class);
Route::resource('areas', \App\Http\Controllers\Api\AreasController::class);

Route::group(['prefix' => 'users'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('forgetPassword', [AuthController::class, 'forgetPass']);
    Route::post('resetPassword', [AuthController::class, 'resetPass']);

    Route::group(['middleware' => 'checkAuth'], function () {
        Route::get('profile/{profile}', 'AuthController@profile');
        Route::resource('address', \App\Http\Controllers\Api\UserAddress::class);
        Route::post('updateAddress/{id}', [\App\Http\Controllers\Api\UserAddress::class, 'update']);


    });

});
Route::group(['middleware' => 'checkAuth'], function () {
    Route::get('products/wishlist/{action}', [ProductsController::class, 'wishlist']);
    Route::resource('carts', \App\Http\Controllers\Api\CartController::class);
    Route::get('coupon/{action}', [\App\Http\Controllers\Api\CartController::class, 'coupon']);

});
