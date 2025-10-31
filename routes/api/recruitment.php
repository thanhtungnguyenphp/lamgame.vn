<?php

use App\Http\Controllers\Api\Dashboard\JobController;
use App\Http\Controllers\Api\Dashboard\ApplicationController;
use App\Http\Controllers\Api\Dashboard\TrackingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('dashboard')->group(function () {
    // Job management (user's own jobs)
    Route::apiResource('jobs', JobController::class);
    
    // Application management (user's applications)
    Route::apiResource('applications', ApplicationController::class)->except(['update', 'destroy']);
    Route::patch('applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    
    // Tracking and statistics (user-specific)
    Route::get('overview', [TrackingController::class, 'getDashboardOverview']);
    Route::get('stats', [TrackingController::class, 'getJobStats']);
    Route::get('jobs/{jobId}/applications', [TrackingController::class, 'getApplicationsByJob']);
    Route::get('my-applications', [TrackingController::class, 'getMyApplications']);
});
