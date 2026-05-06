<?php

use App\Http\Controllers\Api\DashboardManageController;
use App\Http\Controllers\Api\ProductManageController;
use App\Http\Controllers\Api\OrderManageController;
use App\Http\Controllers\Api\SellerManageController;
use App\Http\Controllers\Api\EarningManageController;
use App\Http\Controllers\Api\WithdrawalManageController;
use App\Http\Controllers\Api\CustomerManageController;

/*
|--------------------------------------------------------------------------
| E-Commerce Management API Routes (api.key auth)
|--------------------------------------------------------------------------
|
| Header: X-Api-Key: {admin_api_token}
| Prefix: /api/manage
|
*/
Route::prefix('manage')->name('api.manage.')->middleware(['api.key', 'throttle:60,1'])->group(function () {

    // === Dashboard ===
    Route::get('dashboard', [DashboardManageController::class, 'index'])->name('dashboard');

    // === Product Management ===
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductManageController::class, 'list'])->name('list');
        Route::get('/statistics', [ProductManageController::class, 'statistics'])->name('statistics');
        Route::get('/{id}', [ProductManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/', [ProductManageController::class, 'store'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [ProductManageController::class, 'update'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [ProductManageController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/status', [ProductManageController::class, 'changeStatus'])->name('change-status')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/review', [ProductManageController::class, 'review'])->name('review')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/images', [ProductManageController::class, 'uploadImages'])->name('upload-images')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}/images/{imageId}', [ProductManageController::class, 'deleteImage'])->name('delete-image')->where(['id' => '[0-9]+', 'imageId' => '[0-9]+'])->middleware('throttle:10,1');
        Route::post('/{id}/images/reorder', [ProductManageController::class, 'reorderImages'])->name('reorder-images')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Order Management ===
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderManageController::class, 'list'])->name('list');
        Route::get('/statistics', [OrderManageController::class, 'statistics'])->name('statistics');
        Route::get('/{id}', [OrderManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/{id}/status', [OrderManageController::class, 'changeStatus'])->name('change-status')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/comment', [OrderManageController::class, 'comment'])->name('comment')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Seller Management ===
    Route::prefix('sellers')->name('sellers.')->group(function () {
        Route::get('/', [SellerManageController::class, 'list'])->name('list');
        Route::get('/statistics', [SellerManageController::class, 'statistics'])->name('statistics');
        Route::get('/{id}', [SellerManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/{id}/approve', [SellerManageController::class, 'approve'])->name('approve')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/reject', [SellerManageController::class, 'reject'])->name('reject')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/suspend', [SellerManageController::class, 'suspend'])->name('suspend')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/activate', [SellerManageController::class, 'activate'])->name('activate')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::put('/{id}', [SellerManageController::class, 'update'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Earnings ===
    Route::prefix('earnings')->name('earnings.')->group(function () {
        Route::get('/', [EarningManageController::class, 'list'])->name('list');
        Route::get('/statistics', [EarningManageController::class, 'statistics'])->name('statistics');
    });

    // === Withdrawals ===
    Route::prefix('withdrawals')->name('withdrawals.')->group(function () {
        Route::get('/', [WithdrawalManageController::class, 'list'])->name('list');
        Route::get('/{id}', [WithdrawalManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/{id}/approve', [WithdrawalManageController::class, 'approve'])->name('approve')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/complete', [WithdrawalManageController::class, 'complete'])->name('complete')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/reject', [WithdrawalManageController::class, 'reject'])->name('reject')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Customer Management ===
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerManageController::class, 'list'])->name('list');
        Route::get('/statistics', [CustomerManageController::class, 'statistics'])->name('statistics');
        Route::get('/{id}', [CustomerManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/{id}/suspend', [CustomerManageController::class, 'suspend'])->name('suspend')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/{id}/activate', [CustomerManageController::class, 'activate'])->name('activate')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::put('/{id}', [CustomerManageController::class, 'update'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
    });
});
