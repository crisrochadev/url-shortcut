<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenerController;

//Implementar sistema administrativo
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/links', [ShortenerController::class, 'store']);
Route::post('/reactivate/{id}', [ShortenerController::class, 'reactivate']);
Route::post('/disable/{id}', [ShortenerController::class, 'disable']);
Route::post('/enable/{id}', [ShortenerController::class, 'enable']);
Route::get('/links', [ShortenerController::class, 'show']);
Route::get('/{slug}', [ShortenerController::class, 'index']);
