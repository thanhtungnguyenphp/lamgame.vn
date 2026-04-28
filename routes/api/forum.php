<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ForumApiController;

// Public
Route::get('posts', [ForumApiController::class, 'index']);
Route::get('posts/{slug}', [ForumApiController::class, 'show']);
Route::get('categories', [ForumApiController::class, 'categories']);
Route::get('tags', [ForumApiController::class, 'tags']);
Route::get('trending', [ForumApiController::class, 'trending']);
Route::get('leaderboard', [ForumApiController::class, 'leaderboard']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts', [ForumApiController::class, 'store']);
    Route::put('posts/{id}', [ForumApiController::class, 'update']);
    Route::delete('posts/{id}', [ForumApiController::class, 'destroy']);
    Route::post('posts/{id}/comments', [ForumApiController::class, 'storeComment']);
    Route::post('posts/{id}/bookmark', [ForumApiController::class, 'bookmark']);
    Route::post('vote', [ForumApiController::class, 'vote']);
    Route::get('notifications', [ForumApiController::class, 'notifications']);
    Route::post('notifications/read', [ForumApiController::class, 'markNotificationRead']);
});
