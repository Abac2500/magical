<?php

use App\Http\Controllers\Api\AnimalRegistrationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ChatMessageController;
use App\Http\Controllers\Api\FriendshipController;
use App\Http\Controllers\Api\MyAnimalController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\SpeciesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/species', [SpeciesController::class, 'index']);

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        Route::post('/species', [SpeciesController::class, 'store']);

        Route::post('/animals/register', [AnimalRegistrationController::class, 'store']);
        Route::get('/animals/me', [MyAnimalController::class, 'show']);
        Route::get('/animals/me/friends', [FriendshipController::class, 'index']);
        Route::post('/friendships', [FriendshipController::class, 'store']);
        Route::get('/animals/me/recommendations', [RecommendationController::class, 'index']);

        Route::post('/chats', [ChatController::class, 'store']);
        Route::get('/chats/{chat}/messages', [ChatMessageController::class, 'index']);
        Route::post('/chats/{chat}/messages', [ChatMessageController::class, 'store']);
    });
});
