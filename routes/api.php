<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortenerController;


Route::post('/links', [ShortenerController::class, 'store']);
Route::post('/links/reactivate/{id}', [ShortenerController::class, 'reactivate']);
Route::post('/links/disable/{id}', [ShortenerController::class, 'disable']);
Route::get('/links', [ShortenerController::class, 'show']);
Route::get('/links/{slug}', [ShortenerController::class, 'index']);
