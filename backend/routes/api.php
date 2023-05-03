<?php

use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

Route::get('ping_some', function () {
  return 'success ping';
});

Route::get('all_msisdns',[MainController::class,'all_msisdns']);
Route::get('devices', [MainController::class,'index']);
Route::get('my_devices', [MainController::class, 'my_devices']);
Route::delete('devices/{id}/{msisdn}', [MainController::class,'remove_device_msisdn']);
Route::post('devices', [MainController::class,'store']);
Route::patch('devices', [MainController::class,'update_patch']);
Route::delete('devices/{id}', [MainController::class,'destroy']);
Route::post('devices/{id}/msisdn', [MainController::class,'store_msisdn']);

Route::post('/register', [UserController::class, "register"]);
Route::post('/login', [UserController::class, "login"]);

Route::post('call', [MainController::class, 'call']);

Route::get('/pages/{page}', [ContentController::class, 'show']);
Route::patch('/pages', [ContentController::class, 'update']);

Route::get('reset', [MainController::class,"reset"]);

Route::group(['middleware' => 'authenticated'], function () {
  Route::post('update_profile', [UserController::class, "update_profile"]);
});
