<?php

use App\Http\Controllers\API\V1\RequestTravelController;
use App\Http\Controllers\API\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', action: [UserController::class, 'login']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('request-travels', RequestTravelController::class);
    Route::patch('request-travels/{requestTravel}/aprove', [RequestTravelController::class, 'aprovarViagem']);
    Route::patch('request-travels/{requestTravel}/reject', [RequestTravelController::class, 'reprovarViagem']);
});

