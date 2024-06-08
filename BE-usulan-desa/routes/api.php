<?php

use App\Http\Controllers\ProgressController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SuggestionController;

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

    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('/logout', [UserController::class, 'logout']);

        Route::middleware('role:admin')->group(function () {
            Route::get('/index', [UserController::class, 'indexUsers']);
            Route::get('/verify/{id}', [UserController::class, 'verified']);
            Route::get('/reject/{id}', [UserController::class, 'rejected']);
        });
    });
});

Route::prefix('/suggestion')->middleware(['auth:sanctum'])->group(function () {
    // Routes for users with 'user' role
    Route::middleware('role:user')->group(function () {
        Route::post('/add', [SuggestionController::class, 'store']);
        Route::get('/get', [SuggestionController::class, 'index']);
        Route::get('/rank', [SuggestionController::class, 'rankSuggestion']);
        Route::post('/update/{id}', [SuggestionController::class, 'update']);
        Route::delete('/delete/{id}', [SuggestionController::class, 'destroy']);
        Route::get('/get/{id}', [SuggestionController::class, 'getOne']);
        Route::post('/{id}/comment', [SuggestionController::class, 'addComment']);
        Route::get('/{id}/upvote', [SuggestionController::class, 'Upvote']);
        Route::get('/{id}/downvote', [SuggestionController::class, 'Downvote']);
    });
    // Routes for users with 'admin' role
    Route::middleware('role:admin')->group(function () {
        Route::post('/response/{suggestionId}', [SuggestionController::class, 'responseSuggestion']);
        Route::get('/response/{suggestionId}', [SuggestionController::class, 'getResponse']);
        Route::post('/approve/{id}', [SuggestionController::class, 'approveSuggestion']);
        Route::get('/reject/{id}', [SuggestionController::class, 'rejected']);
        Route::get('/index', [SuggestionController::class, 'indexAdmin']);
        Route::get('/admin/get/{id}', [SuggestionController::class, 'DetailAdmin']);
    });
});

Route::prefix('/program')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/index', [ProgramController::class, 'index']);
    Route::get('/get/{id}', [ProgramController::class, 'detailedProgram']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [ProgramController::class, 'dashboard']);
        Route::post('/add', [ProgramController::class, 'store']);
        Route::post('/get/update/{id}', [ProgramController::class, 'update']);
    });
});

Route::prefix('/progress')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/index/{programId}', [ProgressController::class, 'index']);
    Route::get('/{progressID}', [ProgressController::class, 'getOne']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/add', [ProgressController::class, 'store']);
        Route::post('/update/{id}', [ProgressController::class, 'update']);
        Route::delete('/delete/{id}', [ProgressController::class, 'destroy']);
    });

});