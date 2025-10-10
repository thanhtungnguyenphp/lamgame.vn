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

// Debug route for banner list - bypass middleware
Route::get('/debug-banner-list', function () {
    $banners = collect([
        (object) [
            'id' => 1,
            'name' => 'Test Hero Banner',
            'type' => 'image',
            'position' => 'homepage_hero',
            'device_type' => 'all',
            'status' => true,
            'title' => 'Welcome to LamGame',
            'content' => 'Learn game development with us',
            'image' => null,
            'sort_order' => 0,
            'clicks_count' => 15,
            'impressions_count' => 250,
            'created_at' => now()
        ],
        (object) [
            'id' => 2,
            'name' => 'Sidebar Promo',
            'type' => 'image',
            'position' => 'sidebar_top',
            'device_type' => 'desktop',
            'status' => false,
            'title' => 'Special Offer',
            'content' => '50% off premium courses',
            'image' => null,
            'sort_order' => 1,
            'clicks_count' => 8,
            'impressions_count' => 120,
            'created_at' => now()->subDays(2)
        ]
    ]);
    
    return view('banner::admin.banners.simple-list', compact('banners'));
})->name('debug.banner.list');

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