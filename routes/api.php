<?php

use Illuminate\Http\Request;
header('Access-Control-Allow-Origin: ' . env('CLIENT_URLS'));

header('Access-Control-Allow-Credentials: true');
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

Route::group(['middleware' => ['api']], function () {

    Route::post('/searchlocation', 'Tank\WaterlevelController@getLocationSuggestions');

    Route::post('/getrunoffamount', 'Tank\WaterlevelController@getTankWaterLevelForLocation');


});
