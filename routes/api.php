<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenerController;

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

Route::post('/links', [ShortenerController::class, 'store']);
Route::post('/reactivate/{id}', [ShortenerController::class, 'reactivate']);
Route::post('/disable/{id}', [ShortenerController::class, 'disable']);
Route::post('/enable/{id}', [ShortenerController::class, 'enable']);



Route::get('/links', [ShortenerController::class, 'show']);

Route::get('/{slug}', [ShortenerController::class, 'index']);
