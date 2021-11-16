<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticController;

Route::post('signin', [AuthController::class, 'signin']);
Route::post('signup', [AuthController::class, 'signup']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('statistic', [StatisticController::class, 'index']);
    Route::get('summary', [StatisticController::class, 'summary']);
});


