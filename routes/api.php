<?php

use Illuminate\Http\Request;

// Setting up CORS to allow the client url access the API
header('Access-Control-Allow-Origin: ' . env('CLIENT_URLS'));

header('Access-Control-Allow-Credentials: true');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where API routes for UseWaterWisely is registered . These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::group(['middleware' => ['api']], function () {

    // Route to get location suggestions
    Route::post('/searchlocation', 'Tank\WaterlevelController@getLocationSuggestions');

    // Route to get the tank size suggestions
    Route::post('/gettanksize', 'Tank\WaterlevelController@getTankWaterSizeForLocation');

    // Route to get tank water levels for the present tank
    Route::post('/getwaterlevels','Tank\WaterlevelController@getTankWaterLevelForLocation');


});
