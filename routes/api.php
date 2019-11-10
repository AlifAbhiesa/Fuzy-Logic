<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/Fuzzy', 'ControllerFuzzy@index');
Route::post('/Register', 'RegisterController@store');
Route::get('/Verification', 'RegisterController@verification');
Route::post('/StoreMerchant','RegisterController@storeMerchantData');
Route::post('/Login','RegisterController@LoginData');
Route::post('/Logout','RegisterController@Logout');
Route::post('/Users/Check','RegisterController@checkAccount');
Route::post('/Merchant/getMerchatNearBy','MerchantController@getMerchatNearBy');
