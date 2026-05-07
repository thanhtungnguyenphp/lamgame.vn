<?php

use Illuminate\Support\Facades\Route;
use LamGame\Banner\Http\Controllers\Api\BannerManagementController;

/*
|--------------------------------------------------------------------------
| Banner Management API Routes
|--------------------------------------------------------------------------
|
| Admin/management API routes for banner CRUD operations.
| Prefix: api/admin/banners
| Middleware: api, auth:sanctum (or admin auth)
|
*/

Route::middleware(['api', 'auth:sanctum'])->prefix('api/admin/banners')->name('api.admin.banners.')->group(function () {

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
