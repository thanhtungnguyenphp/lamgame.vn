<?php

use App\Http\Controllers\Api\JobPostingController;
use App\Http\Controllers\Api\JobPostingApplicationController;

/*
|--------------------------------------------------------------------------
| Job Posting V2 API Routes (standalone job_postings table)
|--------------------------------------------------------------------------
*/
Route::prefix('v2/jobs')->name('api.v2.jobs.')->middleware('throttle:60,1')->group(function () {

    // Public
    Route::get('/', [JobPostingController::class, 'index'])->name('index');
    Route::get('/filters', [JobPostingController::class, 'filterOptions'])->name('filters');
    Route::get('/{id}', [JobPostingController::class, 'show'])->name('show')->where('id', '[0-9]+');
    Route::get('/slug/{slug}', [JobPostingController::class, 'showBySlug'])->name('show-slug');

    // Apply (public, rate limited)
    Route::post('/{id}/apply', [JobPostingApplicationController::class, 'apply'])
        ->name('apply')->where('id', '[0-9]+')
        ->middleware('throttle:5,60');

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [JobPostingController::class, 'store'])->name('store');
        Route::put('/{id}', [JobPostingController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [JobPostingController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        Route::post('/{id}/publish', [JobPostingController::class, 'publish'])->name('publish')->where('id', '[0-9]+');
        Route::post('/{id}/unpublish', [JobPostingController::class, 'unpublish'])->name('unpublish')->where('id', '[0-9]+');
        Route::post('/{id}/duplicate', [JobPostingController::class, 'duplicate'])->name('duplicate')->where('id', '[0-9]+');
        Route::get('/statistics', [JobPostingController::class, 'statistics'])->name('statistics');

        // Bulk
        Route::delete('/bulk', [JobPostingController::class, 'bulkDelete'])->name('bulk-delete');
        Route::patch('/bulk/status', [JobPostingController::class, 'bulkUpdateStatus'])->name('bulk-status');
    });
});

// Application tracking (authenticated)
Route::prefix('v2/applications')->name('api.v2.applications.')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/', [JobPostingApplicationController::class, 'getUserApplications'])->name('index');
    Route::get('/{id}/status', [JobPostingApplicationController::class, 'getApplicationStatus'])->name('status')->where('id', '[0-9]+');
});
