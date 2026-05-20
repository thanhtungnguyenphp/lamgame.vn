<?php

use Illuminate\Support\Facades\Route;
use Webkul\JobManagement\Http\Controllers\Admin\JobController;
use Webkul\JobManagement\Http\Controllers\Admin\CompanyController;

Route::group([
    'prefix' => config('app.admin_url'),
    'middleware' => ['web', 'admin']
], function () {
    
    // Test route
    Route::get('/test-companies-route', function() {
        return view('job_management::admin.companies.index', ['companies' => collect()]);
    })->name('admin.test.companies');
    
    Route::prefix('jobs')->name('admin.jobs.')->group(function () {
        Route::get('/', [JobController::class, 'index'])->name('index');
        Route::get('/create', [JobController::class, 'create'])->name('create');
        Route::post('/', [JobController::class, 'store'])->name('store');
        Route::get('/{id}', [JobController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [JobController::class, 'edit'])->name('edit');
        Route::put('/{id}', [JobController::class, 'update'])->name('update');
        Route::delete('/{id}', [JobController::class, 'destroy'])->name('destroy');
        
        // Bulk actions
        Route::post('/mass-update', [JobController::class, 'massUpdate'])->name('mass-update');
        Route::post('/mass-delete', [JobController::class, 'massDelete'])->name('mass-delete');
        
        // Status actions
        Route::post('/{id}/publish', [JobController::class, 'publish'])->name('publish');
        Route::post('/{id}/unpublish', [JobController::class, 'unpublish'])->name('unpublish');
    });
    
    // Companies routes are registered in routes/admin.php via Route::resource
    // Removed duplicate to avoid route name collision with admin.companies.edit
    
});
