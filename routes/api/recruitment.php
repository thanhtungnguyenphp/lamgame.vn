<?php

use App\Http\Controllers\Api\Dashboard\JobController;
use App\Http\Controllers\Api\Dashboard\ApplicationController;
use App\Http\Controllers\Api\Dashboard\TrackingController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    // Job management
    Route::apiResource('jobs', JobController::class);
    
    // Application management
    Route::apiResource('applications', ApplicationController::class)->except(['update', 'destroy']);
    Route::patch('applications/{id}/status', [ApplicationController::class, 'updateStatus']);
    
    // Tracking and statistics
    Route::get('tracking/stats', [TrackingController::class, 'getJobStats']);
    Route::get('tracking/jobs/{jobId}/applications', [TrackingController::class, 'getApplicationsByJob']);
    Route::get('tracking/users/{userId}/applications', [TrackingController::class, 'getUserApplications']);
});
