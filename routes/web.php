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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('products', [App\Http\Controllers\ProductController::class, 'index'])->name('products');
Route::get('buy/{product}', [App\Http\Controllers\ProductController::class, 'buy'])->name('buy');
Route::post('confirm', [App\Http\Controllers\ProductController::class, 'confirm'])->name('confirm');
Route::get('checkout', [App\Http\Controllers\ProductController::class, 'checkout'])->name('checkout');
Route::post('pay', [App\Http\Controllers\ProductController::class, 'pay'])->name('pay');
Route::view('success', 'product.success')->name('success');
Route::stripeWebhooks('stripe-webhook');
