<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ItineraryController;

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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItineraryItemController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Group all routes under a middleware for auth (Sanctum in this case)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('itineraries', ItineraryController::class);
    Route::apiResource('itineraries.items', ItineraryItemController::class)
         ->except(['show', 'index']); // no need for show
});
