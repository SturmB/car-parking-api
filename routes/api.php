<?php

use App\Http\Controllers\Api\V1\Auth;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ZoneController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', Auth\RegisterController::class);
Route::post('auth/login', Auth\LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [Auth\ProfileController::class, 'show']);
    Route::put('profile', [Auth\ProfileController::class, 'update']);
    Route::put('password', Auth\PasswordUpdateController::class);
    Route::post('auth/logout', Auth\LogoutController::class);

    Route::apiResource('vehicles', VehicleController::class);

    Route::get('parkings', [ParkingController::class, 'index']);
    Route::get('parkings/history', [ParkingController::class, 'stoppedParkings']);
    Route::post('parkings/start', [ParkingController::class, 'start']);
    Route::get('parkings/{parking}', [ParkingController::class, 'show'])->whereNumber('parking');
    Route::put('parkings/{parking}', [ParkingController::class, 'stop'])->whereNumber('parking');
});

Route::get('zones', [ZoneController::class, 'index']);
