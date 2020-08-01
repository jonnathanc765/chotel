<?php

use App\Http\Controllers\ReservationController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('room')->group(function ()
{

    Route::get('/availability/{roomTypeId}/{checkin}/{checkout}', 'RoomController@index');
    Route::post('/reservation', 'ReservationController@store');
    Route::put('/reservation/{reservation}', 'ReservationController@update');
    Route::delete('/reservation/{reservation}', 'ReservationController@destroy');
    Route::get('/reservation/', 'ReservationController@index');

});
