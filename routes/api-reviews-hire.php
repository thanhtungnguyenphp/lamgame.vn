<?php

use App\Http\Controllers\Api\SourceGameReviewController;
use App\Http\Controllers\Api\HireRequestController;

/*
|--------------------------------------------------------------------------
| Source Game Reviews & Hire Request API Routes
|--------------------------------------------------------------------------
|
| Prefix: /api/v1
|
*/
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Source Game Reviews — Public
    Route::get('source-game/{productId}/reviews', [SourceGameReviewController::class, 'index'])->name('reviews.index');
    Route::get('source-game/{productId}/review-stats', [SourceGameReviewController::class, 'stats'])->name('reviews.stats');

    // Source Game Reviews — Auth
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('source-game/{productId}/reviews', [SourceGameReviewController::class, 'store'])
            ->name('reviews.store')->middleware('throttle:5,1');
        Route::post('reviews/{id}/helpful', [SourceGameReviewController::class, 'helpful'])
            ->name('reviews.helpful')->middleware('throttle:30,1');
    });

    // Hire Request — Public (rate limited)
    Route::post('hire-request', [HireRequestController::class, 'store'])
        ->name('hire-request.store')->middleware('throttle:3,60');
});
