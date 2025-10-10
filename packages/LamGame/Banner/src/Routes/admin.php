<?php

use Illuminate\Support\Facades\Route;
use LamGame\Banner\Http\Controllers\Admin\BannerController;

/*
|--------------------------------------------------------------------------
| Admin Banner Routes
|--------------------------------------------------------------------------
|
| Routes for Banner administration panel
|
*/

// Test route without admin middleware
Route::get('/test-banner-admin', function () {
    return 'Banner Admin Controller is working! API is ready.';
})->name('test.banner.admin');

Route::group([
    'middleware' => ['web', 'admin'],
    'prefix' => config('app.admin_url', 'admin'),
], function () {
    
    // Banner management routes
    Route::prefix('banners')->name('admin.banners.')->group(function () {
        
        // Utility routes (must come before parameterized routes)
        Route::get('/analytics', [BannerController::class, 'analytics'])->name('analytics');
        Route::post('/clear-cache', [BannerController::class, 'clearCache'])->name('clear-cache');
        Route::post('/mass-destroy', [BannerController::class, 'massDestroy'])->name('mass-destroy');
        Route::post('/mass-update', [BannerController::class, 'massUpdate'])->name('mass-update');
        
        // Main CRUD routes
        Route::get('/', [BannerController::class, 'index'])->name('index');
        Route::get('/create', [BannerController::class, 'create'])->name('create');
        Route::post('/', [BannerController::class, 'store'])->name('store');
        Route::get('/{id}', [BannerController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', [BannerController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        Route::put('/{id}', [BannerController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [BannerController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
    });
});