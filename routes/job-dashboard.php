<?php

use App\Http\Controllers\JobDashboardController;
use App\Http\Controllers\JobApplicationController;

Route::middleware(['web', 'admin'])->prefix('admin/job-dashboard')->name('job.dashboard.')->group(function () {
    Route::get('/', [JobDashboardController::class, 'index'])->name('index');
    Route::get('/jobs', [JobDashboardController::class, 'jobs'])->name('jobs');
    Route::get('/create', [JobDashboardController::class, 'create'])->name('create');
    Route::post('/store', [JobDashboardController::class, 'store'])->name('store');
});
