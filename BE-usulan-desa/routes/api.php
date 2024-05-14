<?php

use App\Http\Controllers\SuggestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::prefix('/auth')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/verified/{id}', [UserController::class, 'verified'])->middleware('auth:sanctum', 'role:admin');
});

Route::prefix('/suggestion')->middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::post('/add', [SuggestionController::class, 'store']);
    Route::get('/get', [SuggestionController::class, 'index']);
    Route::get('/{id}', [SuggestionController::class, 'getOne']);
    
    Route::delete('/delete/{id}', [SuggestionController::class, 'destroy']);
});
