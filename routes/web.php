<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymobController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () { //...

    Auth::routes();


    Route::get('/', function () {
        return view('index');
    })->name('index');

    /*Login*/
    Route::get('view/login', [LoginController::class, 'showLoginForm'])->name('auth.login');
    Route::post('login/post', [LoginController::class, 'UserLogin'])->name('auth.login.post');

    /*Register*/
    Route::get('view/register', [RegisterController::class, 'showRegisterForm'])->name('auth.register');
    Route::post('register/post', [RegisterController::class, 'create'])->name('auth.register.post');

    /*Profile*/
    Route::get('profile', [HomeController::class, 'profile'])->name('profile.show');
    Route::post('profile/update', [HomeController::class, 'update'])->name('profile.update');

    Route::get('paymob', [PaymobController::class, 'processToken']);
});
