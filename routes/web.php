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
use Illuminate\Support\Facades\Redirect;

Route::get('/', function () {
    return Redirect::route('paywithpaypal');
});

Route::get('paywithpaypal', array('as' => 'paywithpaypal','uses' => 'PaypalController@payWithPaypal'));
Route::post('paypal', array('as' => 'paypal','uses' => 'PaypalController@postPaymentWithpaypal'));
Route::get('paypal', array('as' => 'status','uses' => 'PaypalController@getPaymentStatus'));

Route::get('checkPayment', array('as' => 'checkPayment','uses' => 'PaypalController@checkPaymentRecord'));
Route::get('check', array('as' => 'check','uses' => 'PaypalController@getPaymentRecord'));