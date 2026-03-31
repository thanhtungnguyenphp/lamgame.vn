<?php

use App\Http\Controllers\Api\Sport\SportController;
use App\Http\Controllers\Api\Sport\LeagueController;
use App\Http\Controllers\Api\Sport\MatchController;
use App\Http\Controllers\Api\Sport\ContentController;
use App\Http\Controllers\Api\Sport\TeamController;
use App\Http\Controllers\Api\Sport\SportUserController;
use App\Http\Controllers\Api\Sport\SearchController;
use Illuminate\Support\Facades\Route;

// Public endpoints
Route::prefix('v1/sport')->name('api.sport.')->middleware('throttle:60,1')->group(function () {
    // Sports & Leagues
    Route::get('/sports', [SportController::class, 'index'])->name('sports');
    Route::get('/leagues', [LeagueController::class, 'index'])->name('leagues');
    Route::get('/leagues/{id}/standings', [LeagueController::class, 'standings'])->name('leagues.standings');
    Route::get('/leagues/{id}/top-scorers', [LeagueController::class, 'topScorers'])->name('leagues.top-scorers');

    // Matches
    Route::get('/matches/live', [MatchController::class, 'live'])->name('matches.live');
    Route::get('/matches/schedule', [MatchController::class, 'schedule'])->name('matches.schedule');
    Route::get('/matches/results', [MatchController::class, 'results'])->name('matches.results');
    Route::get('/matches/{id}', [MatchController::class, 'show'])->name('matches.show');
    Route::get('/matches/{id}/events', [MatchController::class, 'events'])->name('matches.events');
    Route::get('/matches/{id}/lineups', [MatchController::class, 'lineups'])->name('matches.lineups');
    Route::get('/matches/{id}/h2h', [MatchController::class, 'h2h'])->name('matches.h2h');

    // Content
    Route::get('/highlights', [ContentController::class, 'highlights'])->name('highlights');
    Route::get('/articles', [ContentController::class, 'articles'])->name('articles');
    Route::get('/articles/{id}', [ContentController::class, 'articleShow'])->name('articles.show');
    Route::get('/discover', [ContentController::class, 'discover'])->name('discover');

    // Teams
    Route::get('/teams/{id}', [TeamController::class, 'show'])->name('teams.show');
    Route::get('/teams/{id}/matches', [TeamController::class, 'matches'])->name('teams.matches');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
});

// User endpoints (Firebase Auth required)
Route::prefix('v1/sport/user')->name('api.sport.user.')->middleware(['throttle:30,1', 'firebase.auth'])->group(function () {
    Route::get('/profile', [SportUserController::class, 'profile'])->name('profile');
    Route::put('/favorites', [SportUserController::class, 'updateFavorites'])->name('favorites');
    Route::put('/notification-settings', [SportUserController::class, 'updateNotificationSettings'])->name('notification-settings');
    Route::post('/reminders', [SportUserController::class, 'storeReminder'])->name('reminders.store');
    Route::delete('/reminders/{matchId}', [SportUserController::class, 'destroyReminder'])->name('reminders.destroy');
    Route::get('/reminders', [SportUserController::class, 'reminders'])->name('reminders.index');
    Route::post('/fcm-token', [SportUserController::class, 'registerFcmToken'])->name('fcm-token');
    Route::delete('/account', [SportUserController::class, 'deleteAccount'])->name('account.delete');
});
