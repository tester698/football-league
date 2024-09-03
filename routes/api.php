<?php

use App\Http\Controllers\CurrentWeekController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\ResultsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/routes', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('results', ResultsController::class);
Route::resource('reset', ResetController::class);
Route::resource('week', CurrentWeekController::class);
Route::resource('predict', PredictionController::class);