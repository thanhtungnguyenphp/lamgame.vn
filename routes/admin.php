<?php

use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\CompanyController;

Route::middleware(['web', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Jobs Management
    Route::resource('jobs', JobController::class);
    
    // Applications Management  
    Route::get('applications/{id}/download-cv', [ApplicationController::class, 'downloadCV'])->name('applications.download-cv');
    Route::resource('applications', ApplicationController::class);
    
    // Companies Management
    Route::resource('companies', CompanyController::class);
    
    // Settings
    Route::get('/settings', function() {
        return view('admin.settings.index');
    })->name('settings');
    
});
