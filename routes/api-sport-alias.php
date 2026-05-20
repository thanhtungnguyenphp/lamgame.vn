<?php

use App\Http\Controllers\Api\Sport\SportController;
use App\Http\Controllers\Api\Sport\LeagueController;
use App\Http\Controllers\Api\Sport\MatchController;
use App\Http\Controllers\Api\Sport\ContentController;
use App\Http\Controllers\Api\Sport\TeamController;
use App\Http\Controllers\Api\Sport\SportUserController;
use App\Http\Controllers\Api\Sport\SearchController;
use Illuminate\Support\Facades\Route;

// Public: /api-sport/*
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/sports', [SportController::class, 'index']);
    Route::get('/leagues', [LeagueController::class, 'index']);
    Route::get('/leagues/{id}/standings', [LeagueController::class, 'standings']);
    Route::get('/leagues/{id}/top-scorers', [LeagueController::class, 'topScorers']);

    Route::get('/matches/live', [MatchController::class, 'live']);
    Route::get('/matches/schedule', [MatchController::class, 'schedule']);
    Route::get('/matches/results', [MatchController::class, 'results']);
    Route::get('/matches/{id}', [MatchController::class, 'show']);
    Route::get('/matches/{id}/events', [MatchController::class, 'events']);
    Route::get('/matches/{id}/lineups', [MatchController::class, 'lineups']);
    Route::get('/matches/{id}/h2h', [MatchController::class, 'h2h']);

    Route::get('/highlights', [ContentController::class, 'highlights']);
    Route::get('/articles', [ContentController::class, 'articles']);
    Route::get('/articles/{id}', [ContentController::class, 'articleShow']);
    Route::get('/discover', [ContentController::class, 'discover']);

    Route::get('/teams/{id}', [TeamController::class, 'show']);
    Route::get('/teams/{id}/matches', [TeamController::class, 'matches']);

    Route::get('/search', [SearchController::class, 'index']);

    // Broadcasting info for mobile
    Route::get('/ws-config', function () {
        return response()->json([
            'driver' => 'pusher',
            'key' => config('broadcasting.connections.pusher.key'),
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            'ws_host' => request()->getHost(),
            'ws_port' => 6001,
            'wss_port' => 6001,
            'force_tls' => false,
            'channels' => [
                'live_score' => 'sport.match.{matchId}',
                'events' => ['score.updated', 'match.status'],
            ],
            'note' => 'Public channels — no auth needed. Use Pusher client SDK with custom host.',
        ]);
    });
});

// User: /api-sport/user/* (Firebase Auth)
Route::prefix('user')->middleware(['throttle:30,1', 'firebase.auth'])->group(function () {
    Route::get('/profile', [SportUserController::class, 'profile']);
    Route::put('/favorites', [SportUserController::class, 'updateFavorites']);
    Route::put('/notification-settings', [SportUserController::class, 'updateNotificationSettings']);
    Route::post('/reminders', [SportUserController::class, 'storeReminder']);
    Route::delete('/reminders/{matchId}', [SportUserController::class, 'destroyReminder']);
    Route::get('/reminders', [SportUserController::class, 'reminders']);
    Route::post('/fcm-token', [SportUserController::class, 'registerFcmToken']);
    Route::delete('/account', [SportUserController::class, 'deleteAccount']);
});
