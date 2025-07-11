<?php

use Illuminate\Support\Facades\Route;
use Whilesmart\LaravelAppAuthentication\Http\Controllers\AppController;

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

// App routes
Route::post('/apps', [AppController::class, 'store']);
Route::get('/apps', [AppController::class, 'index']);
Route::get('/apps/{app}', [AppController::class, 'show']);
Route::put('/apps/{app}', [AppController::class, 'update']);
Route::delete('/apps/{app}', [AppController::class, 'destroy']);

Route::get('/apps/{app}/api-keys', [AppController::class, 'getApiKeys']);
Route::post('/apps/{app}/api-keys', [AppController::class, 'generateApiKeys']);
Route::delete('/apps/{app}/api-keys/{apiKey}', [AppController::class, 'deleteApiKey']);
