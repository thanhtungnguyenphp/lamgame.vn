<?php

use App\Http\Controllers\Api\DashboardManageController;
use App\Http\Controllers\Api\ProductManageController;
use App\Http\Controllers\Api\OrderManageController;
use App\Http\Controllers\Api\SellerManageController;
use App\Http\Controllers\Api\EarningManageController;
use App\Http\Controllers\Api\WithdrawalManageController;
use App\Http\Controllers\Api\CustomerManageController;
use App\Http\Controllers\Api\InvoiceManageController;
use App\Http\Controllers\Api\RefundManageController;
use App\Http\Controllers\Api\ShipmentManageController;
use App\Http\Controllers\Api\TransactionManageController;
use App\Http\Controllers\Api\ReportingManageController;
use App\Http\Controllers\Api\CategoryManageController;

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

    // === Categories ===
    Route::get('categories', [ProductManageController::class, 'categoryList'])->name('categories.list');

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

        // Downloadable Links (source files)
        Route::get('/{id}/downloadable-links', [ProductManageController::class, 'listDownloadableLinks'])->name('downloadable-links.list')->where('id', '[0-9]+');
        Route::post('/{id}/downloadable-links', [ProductManageController::class, 'uploadDownloadableLink'])->name('downloadable-links.upload')->where('id', '[0-9]+')->middleware('throttle:5,1');
        Route::put('/{id}/downloadable-links/{linkId}', [ProductManageController::class, 'updateDownloadableLink'])->name('downloadable-links.update')->where(['id' => '[0-9]+', 'linkId' => '[0-9]+'])->middleware('throttle:5,1');
        Route::delete('/{id}/downloadable-links/{linkId}', [ProductManageController::class, 'deleteDownloadableLink'])->name('downloadable-links.delete')->where(['id' => '[0-9]+', 'linkId' => '[0-9]+'])->middleware('throttle:5,1');

        // Downloadable Samples (preview files)
        Route::post('/{id}/downloadable-samples', [ProductManageController::class, 'uploadDownloadableSample'])->name('downloadable-samples.upload')->where('id', '[0-9]+')->middleware('throttle:5,1');
        Route::delete('/{id}/downloadable-samples/{sampleId}', [ProductManageController::class, 'deleteDownloadableSample'])->name('downloadable-samples.delete')->where(['id' => '[0-9]+', 'sampleId' => '[0-9]+'])->middleware('throttle:5,1');
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

    // === Invoices ===
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceManageController::class, 'list'])->name('list');
        Route::get('/statistics', [InvoiceManageController::class, 'statistics'])->name('statistics');
        Route::get('/{id}', [InvoiceManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/create/{orderId}', [InvoiceManageController::class, 'store'])->name('store')->where('orderId', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Refunds ===
    Route::prefix('refunds')->name('refunds.')->group(function () {
        Route::get('/', [RefundManageController::class, 'list'])->name('list');
        Route::get('/statistics', [RefundManageController::class, 'statistics'])->name('statistics');
        Route::get('/{id}', [RefundManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/create/{orderId}', [RefundManageController::class, 'store'])->name('store')->where('orderId', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Shipments ===
    Route::prefix('shipments')->name('shipments.')->group(function () {
        Route::get('/', [ShipmentManageController::class, 'list'])->name('list');
        Route::get('/{id}', [ShipmentManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/create/{orderId}', [ShipmentManageController::class, 'store'])->name('store')->where('orderId', '[0-9]+')->middleware('throttle:10,1');
    });

    // === Transactions ===
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionManageController::class, 'list'])->name('list');
        Route::get('/{id}', [TransactionManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
    });

    // === Reporting ===
    Route::prefix('reporting')->name('reporting.')->group(function () {
        Route::get('/sales', [ReportingManageController::class, 'sales'])->name('sales');
        Route::get('/customers', [ReportingManageController::class, 'customers'])->name('customers');
        Route::get('/products', [ReportingManageController::class, 'products'])->name('products');
    });

    // === Categories (full CRUD) ===
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryManageController::class, 'list'])->name('list');
        Route::get('/tree', [CategoryManageController::class, 'tree'])->name('tree');
        Route::get('/{id}', [CategoryManageController::class, 'detail'])->name('detail')->where('id', '[0-9]+');
        Route::post('/', [CategoryManageController::class, 'store'])->name('store')->middleware('throttle:10,1');
        Route::put('/{id}', [CategoryManageController::class, 'update'])->name('update')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::delete('/{id}', [CategoryManageController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+')->middleware('throttle:10,1');
        Route::post('/mass-delete', [CategoryManageController::class, 'massDestroy'])->name('mass-delete')->middleware('throttle:10,1');
        Route::post('/mass-update', [CategoryManageController::class, 'massUpdate'])->name('mass-update')->middleware('throttle:10,1');
    });
});
