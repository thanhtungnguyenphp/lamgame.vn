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

    // Polls
    Route::post('polls/{id}/vote', [\App\Http\Controllers\Api\ForumPollController::class, 'vote']);
    Route::delete('polls/{id}/vote', [\App\Http\Controllers\Api\ForumPollController::class, 'retract']);

    // Private Messages
    Route::get('conversations', [\App\Http\Controllers\Api\ForumMessageController::class, 'conversations']);
    Route::post('conversations', [\App\Http\Controllers\Api\ForumMessageController::class, 'createConversation']);
    Route::get('conversations/{id}/messages', [\App\Http\Controllers\Api\ForumMessageController::class, 'messages']);
    Route::post('conversations/{id}/messages', [\App\Http\Controllers\Api\ForumMessageController::class, 'send']);
    Route::patch('conversations/{id}/read', [\App\Http\Controllers\Api\ForumMessageController::class, 'markRead']);
    Route::post('users/{id}/block', [\App\Http\Controllers\Api\ForumMessageController::class, 'block']);
    Route::delete('users/{id}/block', [\App\Http\Controllers\Api\ForumMessageController::class, 'unblock']);
});

// Polls (public)
Route::get('polls/{id}/results', [\App\Http\Controllers\Api\ForumPollController::class, 'results']);
