<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenerController;


Route::post('/links', [ShortenerController::class, 'store']);
Route::post('/reactivate/{id}', [ShortenerController::class, 'reactivate']);
Route::post('/disable/{id}', [ShortenerController::class, 'disable']);
Route::post('/enable/{id}', [ShortenerController::class, 'enable']);
Route::get('/links', [ShortenerController::class, 'show']);
Route::get('/{slug}', [ShortenerController::class, 'index']);
