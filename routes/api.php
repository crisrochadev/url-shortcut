<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenerController;


Route::post('/links', [ShortenerController::class, 'store']);
Route::put('/links/reactivate/{id}', [ShortenerController::class, 'reactivate']);
Route::delete('/links/disable/{id}', [ShortenerController::class, 'disable']);
Route::get('/links', [ShortenerController::class, 'show']);
Route::get('/links/disabled', [ShortenerController::class, 'showDisabled'],);
Route::get('/links/slug/{slug}', [ShortenerController::class, 'index']);
