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
    Route::get('/index', [UserController::class, 'indexUsers'])->middleware('auth:sanctum', 'role:admin');
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::delete('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/verify/{id}', [UserController::class, 'verified'])->middleware('auth:sanctum', 'role:admin');
    Route::get('/reject/{id}', [UserController::class, 'rejected'])->middleware('auth:sanctum', 'role:admin');
});

Route::prefix('/suggestion')->middleware(['auth:sanctum'])->group(function () {
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
        Route::post('/approve/{id}', [SuggestionController::class, 'approveSuggestion']);
        Route::get('/reject/{id}', [SuggestionController::class, 'rejected']);
        Route::get('/index', [SuggestionController::class, 'indexAdmin']);
    });
});

Route::prefix('/program')->group(function () {
    Route::post('/add', [ProgramController::class, 'store'])->middleware('auth:sanctum', 'role:admin');
    Route::get('/index', [ProgramController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{id}', [ProgramController::class, 'detailedProgram'])->middleware('auth:sanctum', 'role:admin');
});

Route::prefix('/progress')->group(function () {
    Route::post('/add', [ProgressController::class, 'store'])->middleware('auth:sanctum', 'role:admin');
    Route::get('/index/{programId}', [ProgressController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{progressID}', [ProgressController::class, 'getOne'])->middleware('auth:sanctum');
});