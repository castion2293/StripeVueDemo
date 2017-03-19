<?php

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
    $products = App\Product::all();

    return view('welcome', ['products' => $products]);
});
Route::post('purchases', 'PurchasesController@store');

Route::get('/subscription', function () {
    $plans = App\Plan::all();

    return view('subscription', ['plans' => $plans]);
});
Route::post('/subscription', 'SubscriptionController@store');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::post('/stripe/webhook', 'WebhooksController@handle');
