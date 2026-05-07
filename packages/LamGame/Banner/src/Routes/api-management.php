<?php

use Illuminate\Support\Facades\Route;
use LamGame\Banner\Http\Controllers\Api\BannerManagementController;

/*
|--------------------------------------------------------------------------
| Banner Management API Routes (api.key auth)
|--------------------------------------------------------------------------
|
| Admin/management API routes for banner CRUD operations.
| Prefix: api/manage/banners
| Auth: X-Api-Key header (same as blog, e-com, forum, job APIs)
|
*/

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('manage/banners')->name('api.manage.banners.')->middleware(['api.key', 'throttle:60,1'])->group(function () {

        // Options (positions, device types, banner types)
        Route::get('/options', [BannerManagementController::class, 'options'])->name('options');

        // Analytics
        Route::get('/analytics', [BannerManagementController::class, 'analytics'])->name('analytics');

        // Mass actions
        Route::post('/mass-destroy', [BannerManagementController::class, 'massDestroy'])->name('mass-destroy');
        Route::post('/mass-update', [BannerManagementController::class, 'massUpdate'])->name('mass-update');

        // Sort order
        Route::put('/update-order', [BannerManagementController::class, 'updateOrder'])->name('update-order');

        // CRUD
        Route::get('/', [BannerManagementController::class, 'index'])->name('index');
        Route::post('/', [BannerManagementController::class, 'store'])->name('store');
        Route::get('/{id}', [BannerManagementController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::post('/{id}', [BannerManagementController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [BannerManagementController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
    });
});
