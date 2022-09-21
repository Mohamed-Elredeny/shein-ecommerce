<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PaymobController;
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


Auth::routes();


Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('view/login',[LoginController::class,'showLoginForm'])->name('auth.login');
Route::post('login/post', [LoginController::class,'UserLogin'])->name('auth.login.post');

Route::get('paymob', [PaymobController::class,'processToken']);
